<?php

class NotasController extends AppController {

	public $model = 'Nota';
	public $uses = array('Nota', 'Endereco', 'ItemPedido', 'NFe','Empresa');
	public $filter_with_like = array('Nota.numero', 'Cliente.nome', 'Empresa.nome', 'Nota.observacao');
	public $components = ['Mail'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow( array( 'arquivo' ) );
	}
	
	public function arquivo( $id, $i ){
		if( in_array( $i, [ 1, 2] ) ){
			$this->autoRender = false;
			Configure::write('debug',2);
			$this->Nota->id = $id;
			$this->Nota->saveField("anexo{$i}_aberto", 1);
			
			$this->Nota->recursive = -1;
			$nota = $this->Nota->read();
			
			$path = 'path';
			if($i==2){
				$path.=2;
			}
			#pr( $this->SqlDump() );
			#exit();
			$this->redirect('http://52.20.178.249/areiaepedra/'.$nota['Nota'][$path]);
		}
		exit();
	}
	
	public function imprimir($id, $ajustar_impressora = false) {
		$this->layout = 'imprimir';
		$this->Nota->Behaviors->load('Containable');
		$this->Nota->contain(
				'Cliente', 'Cliente.Endereco.tipo_id=1', 'Cliente.Endereco.Cidade', 'Cliente.Endereco.Estado', 'Empresa', 'ItemNota.Produto', 'ItemNota.Pedido.ItemPedido.Produto'
		);
		$nota = $this->Nota->findById($id);
		//pr($nota);exit();
		$this->set(compact('nota', 'ajustar_impressora'));
		$this->set('unidades', $this->ItemPedido->unidade);
	}

	public function inadimplente_on($cliente_id) {
		$this->loadModel('Cliente');
		$this->Cliente->id = $cliente_id;
		$this->Cliente->save([
			'inadimplente' => 1
		]);
		$this->redirect(['action' => 'index']);
	}

	public function inadimplente_off($cliente_id) {
		$this->loadModel('Cliente');
		$this->Cliente->id = $cliente_id;
		$this->Cliente->save([
			'inadimplente' => 0
		]);
		$this->redirect(['action' => 'index']);
	}

	public function exportar($nota_id) {
		$this->layout = 'download';
		$nome = $this->NFe->ExportarTxt($nota_id);
		if (!is_numeric($nome)) {
			$this->set('nome_arquivo', str_ireplace($this->NFe->getPath(), '', $nome));
			$this->set('arquivo', $nome);
			//$this->limpar_nfe(true);
			//$this->redirect('nfe');
		} else {
			switch ($nome) {
				case 0:
					$this->Session->setFlash('Selecione ao menos um Pedido!', 'default', array('class' => 'alert alert-error'));
					break;
				case -1:
					$this->Session->setFlash('Loja nao possuiu CNPJ!<br> Por favor corrija o cadastro!', 'default', array('class' => 'alert alert-error'));
					break;
			}
			$this->redirect(array('action' => 'index'));
		}
	}

	public function admin_index() {

		// Remove o inicio da action para o Paginator funcionar adequadamente 
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		$options = array('recursive' => 1);
		$options['extra']['recursive'] = 1;
		parent::index($options);
	}

	public function admin_imprimir($id, $ajustar = false) {
		$this->setAction('imprimir', $id, $ajustar);
	}

	public function admin_print_endereco($id) {
		$this->layout = 'imprimir';
		$this->set('row', $this->Endereco->find('first', array('conditions' => array('Endereco.cliente_id' => $id, 'Endereco.tipo_id' => 1))));
	}

	public function admin_addnota($id) {
		$this->autoRender = false;
		$this->Nota->id = $id;
		if ($this->request->data['File']['image']['size'] > 0) {
			$n = $this->Nota->read();
			$path = $this->Nota->Upload(
					$this->request->data['File'], $n['Nota']['empresa_id'], 'files/notas', 'nota' . $this->Nota->id, true);
			#pr($path);
			$nota['Nota']['path'] = $path['urls'][0];
			$nota['Nota']['path2'] = @$path['urls'][1];

			if ($this->Nota->save($nota['Nota'])) {
				$this->Session->setFlash('Nota salva com sucesso!', 'default', array('class' => 'alert alert-success'));
				if ($this->request->data['disparar'] == 1) {
					$this->redirect(array('action' => 'admin_nota_email', $id));
				}
			}
		}
//		pr($this->SqlDump());
//		exit();
		$this->redirect(array('action' => 'index'));
	}

	public function admin_nota_email($id) {
		$this->autoRender = false;
		$this->Nota->id = $id;
		$nota = $this->Nota->read();
		
//		$nota['Cliente']['email']='gerson@hoomweb.com';
		$continue = false;
		$msg_erro = '';
		if (!empty($nota['Nota']['path']) || !empty($nota['Nota']['path2'])) {
			$continue = true;
		} else {
			$msg_erro = 'Não foi encontrado nenhum anexo para envio!';
		}
		if ($continue) {
			if (!empty($nota['Cliente']['email'])) {
				$continue = true;

				$destinatario = array_filter(explode(';', $nota['Cliente']['email']));
				if (!is_array($destinatario)) {
					$continue = validaEmail($destinatario);
				} else {
					$desti = array();
					foreach ($destinatario as $d) {
						$d = trim($d);
						if (validaEmail($d)) {
							$desti[] = $d;
						}
					}
					if (empty($desti)) {
						$continue = false;
					} else {
						$destinatario = $desti;
						$continue = true;
					}
				}
			} else {
				$msg_erro.='Email invalido!';
			}
		}
		if ($continue) {
			//$destinatario=$nota['Cliente']['email'];
			$a1='';
			$a2='';
			$n = explode('/', $nota['Nota']['path']);
			
			if(!empty($nota['Nota']['path'])){
//				$a1 = 'http://52.20.178.249/areiaepedra/'.$nota['Nota']['path'];
				$a1 = 'http://52.20.178.249/areiaepedra/notas/arquivo/'.$nota['Nota']['id'].'/1';
				$n = explode('/', $nota['Nota']['path']);
				end($n);         // move the internal pointer to the end of the array
				$nome = current($n);
				$a1 = "<br><a href='{$a1}'>{$nome}</a>";
			}
			if(!empty($nota['Nota']['path2'])){
//				$a2 = 'http://52.20.178.249/areiaepedra/'.$nota['Nota']['path2'];
				$a2 = 'http://52.20.178.249/areiaepedra/notas/arquivo/'.$nota['Nota']['id'].'/2';
				$n2 = explode('/',$nota['Nota']['path2']);
				end($n2);         // move the internal pointer to the end of the array
				$nome2 = current($n2);
				$a2 = "<br><a href='{$a2}'>{$nome2}</a>";
			}
			$quebra_linha = (PATH_SEPARATOR == ";") ? "\r\n" //Se for Windows
					: "\n";  //Se "não for Windows"
			$mensagem = '<table border="0">'
					. '<tr>'
//			.'<td><img src="http://vsegura.com.br/sistema/img/logo.png" width="50px"><td>'
					. '<td>Olá '
					//Mensagem de um Atendente, avisar cliente
					. $nota['Cliente']['nome'] . $quebra_linha
					. '<br>Segue abaixo os links para Nfe!'
					. @$a1
					. @$a2
					. '<br> Por favor confirme o recebimento. ';
			$mensagem.="</td></tr></table>";
			$nota['Empresa']['nome'] = utf8_decode($nota['Empresa']['nome']);
			
			
			$att = [
				$nota['Nota']['path'],
				$nota['Nota']['path2']
			];
			#print_r($nota);
			#exit();
			//$destinatario[]='gerson@hoomweb.com';
			$r = $this->Mail->send($destinatario, $nota['Empresa']['nome'] . ' - NFe', $mensagem);
			#debug($r);
//			$email->to($destinatario)
//				->emailFormat('html')
//				->subject($nota['Empresa']['nome'].' - NFe')
//				->addAttachments($nota['Nota']['path'])
//				->send($mensagem);
//			

			if ($this->Nota->saveField('email_enviado', 1)) {
				$this->Session->setFlash('Nota enviada com sucesso!', 'default', array('class' => 'alert alert-success'));
			}
			#pr($this->SqlDump());
		} else {
			$this->Session->setFlash($msg_erro, 'default', array('class' => 'alert alert-error'));
		}
		#exit();
		$this->redirect(array('action' => 'index'));
	}

	public function admin_edit($id = null) {
		$this->Nota->id = $id;
		if (!empty($this->request->data)) {
			$dataSource = $this->Nota->getDataSource();
			$dataSource->begin();
			if ($this->Nota->save($this->request->data)) {

				//Insere o campo nota_id no array
				foreach ($_SESSION['pedidos'] as &$value)
					$value['nota_id'] = $this->Nota->id;

				if (!empty($id)) {
					$this->Nota->ItemNota->deleteAll(array('ItemNota.nota_id' => $id));
				}
				if ($this->Nota->ItemNota->saveAll($this->Session->read('pedidos'))) {
					$dataSource->commit();
					$this->Session->setFlash('Nota salva com sucesso!', 'default', array('class' => 'alert alert-success'));
					$this->redirect(array('action' => 'index'));
				} else
					$this->Session->setFlash('Erro ao salvar os pedidos!', 'default', array('class' => 'alert alert-error'));
				$dataSource->rollback();
			}
		} else
			$this->data = $this->Nota->read();
		$this->Nota->ItemNota->montar($id);
		$this->set('empresas', $this->Nota->Empresa->find('list', array('fields' => array('Empresa.nome'))));
		$this->set('unidades', $this->ItemPedido->unidade);
		$this->set('procemi', $this->Nota->procemi);
		$this->set('indpres', $this->Nota->indpres);	

		$notanumber = 0;
		if(!$id){
			
			$last_nota = $this->Nota->find('list', 
				array(
					'fields'=>['Nota.numero'], 
					'conditions' => array('Nota.empresa_id' => 1), 
					'order' => array('Nota.id' => 'DESC'), 
					'limit'=>1
				));

			foreach ($last_nota as $key => $value) {
				$notanumber = $value+1;  
			}

			
		}		

		$this->set('num_nota', $notanumber);	


	}
	public function nota_ultima($id) {
		$this->layout = '';
		$this->autoRender = false;
		$data = 0;

		$last_nota = $this->Nota->find('list', 
				array(
					'fields'=>['Nota.numero'], 
					'conditions' => array('Nota.empresa_id' => $id), 
					'order' => array('Nota.id' => 'DESC'), 
					'limit'=>1
				));

		if(count($last_nota)>0){

			foreach ($last_nota as $key => $value) {
				$data = $value+1;  
			}

		}else{
			$empresa = $this->Empresa->findById($id);
			$data = $empresa['Empresa']['inicio_nota_fiscal']+1;
		}	


		return $data;
	}
	public function admin_ultima($id = null){
		$this->layout = '';
		$this->autoRender = false;
		$data = 0;

		$last_nota = $this->Nota->find('list', 
				array(
					'fields'=>['Nota.numero'], 
					'conditions' => array('Nota.empresa_id' => $id), 
					'order' => array('Nota.id' => 'DESC'), 
					'limit'=>1
				));

		if(count($last_nota)>0){

			foreach ($last_nota as $key => $value) {
				$data = $value+1;  
			}

		}else{
			$empresa = $this->Empresa->findById($id);
			$data = $empresa['Empresa']['inicio_nota_fiscal']+1;
		}	


		return $data;
	}

	public function admin_del($id) {
		$dataSource = $this->Nota->getDataSource();
		$dataSource->begin();
		if ($this->Nota->ItemNota->deleteAll(array('ItemNota.nota_id' => $id))) {
			if ($this->Nota->delete($id)) {
				$this->Session->setFlash('Registro excluido com sucesso!', 'default', array('class' => 'alert alert-success'));
				$dataSource->commit();
			} else {
				$this->Session->setFlash('Ocorreu um erro ao excluir o registro.', 'default', array('class' => 'alert alert-error'));
				$dataSource->rollback();
			}
		} else {
			$this->Session->setFlash('Ocorreu um erro ao excluir o registro.', 'default', array('class' => 'alert alert-error'));
			$dataSource->rollback();
		}
		$this->redirect(array('action' => 'index'));
	}

	public function admin_add() {
		$this->setAction('admin_edit');
	}

	public function admin_inadimplente_on($cliente_id) {
		$this->setAction('inadimplente_on', $cliente_id);
	}

	public function admin_inadimplente_off($cliente_id) {
		$this->setAction('inadimplente_off', $cliente_id);
	}

	public function admin_exportar($nota_id) {
		$this->setAction('exportar', $nota_id);
	}

	public function admin_loja($id) {
		$this->layout = '';
		$loja = $this->Loja->find('first', Array("conditions" => array("Loja.id" => $id)));
		$this->set('loja', $loja['Loja']);
	}

	//
	// DIRETOR
	//
    public function diretor_index() {
		$this->setAction('admin_index');
	}

	public function diretor_edit($id = null) {
		$this->setAction('admin_edit', $id);
	}

	public function diretor_print_endereco($id) {
		$this->setAction('admin_print_endereco', $id);
	}

	public function diretor_del($id) {
		$this->setAction('admin_del', $id);
	}

	public function diretor_loja($id) {
		$this->setAction('admin_loja', $id);
	}

	public function diretor_imprimir($id) {
		$this->setAction('imprimir', $id);
	}

	//
	// GERENTE
	//
    public function gerente_index() {
		$this->setAction('admin_index');
	}

	public function gerente_del($id) {
		$this->setAction('admin_del', $id);
	}

	public function gerente_loja($id) {
		$this->setAction('admin_loja', $id);
	}

	//
	// FINANCEIRO
	//
    public function financeiro_index() {
		$this->setAction('admin_index');
	}

	public function financeiro_del($id) {
		$this->setAction('admin_del', $id);
	}

	public function financeiro_loja($id) {
		$this->setAction('admin_loja', $id);
	}

	// public function financeiro_add() {
	// 	$this->setAction('admin_add');
	// }

	//
	// NOTA
	//
    public function nota_index() {
		$this->setAction('admin_index');
	}

	public function nota_add() {
		$this->setAction('admin_add');
	}

	public function nota_edit($id = null) {
		$this->setAction('admin_edit', $id);
	}

	public function nota_del($id) {
		$this->setAction('admin_del', $id);
	}

	public function nota_imprimir($id) {
		$this->setAction('admin_imprimir', $id);
	}

	public function nota_print_endereco($id) {
		$this->setAction('admin_print_endereco', $id);
	}

	public function nota_loja($id) {
		$this->setAction('admin_loja', $id);
	}
	
	public function nota_exportar($nota_id) {
		$this->setAction('exportar', $nota_id);
	}


	//
	//VENDEDOR
	// 
	public function vendedor_loja($id) {
		$this->setAction('admin_loja', $id);
	}

}

?>
