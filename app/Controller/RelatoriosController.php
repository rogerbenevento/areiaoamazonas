<?php
class RelatoriosController extends AppController{
	public $uses = array('Relatorio','Vale','ItemPedido','Produto','Motorista','User','Cliente','Empresa','Nota');
	
	/*
	 * FRETE
	 */
	public function gerar_vendas(){
		//$this->set('produtos', $this->Produto->find('list', array('fields' => array('Produto.nome'))));
		$vendedores=$this->User->find('list',array('conditions'=>array('User.nivel_id'=>2))); // Pesquisa somente vendedores
		$this->set('motoristas', $this->Motorista->find('list', array('fields' => array('Motorista.nome'))));
		$this->set('layouts',array(0=>'PDF',1=>'Excel'));
		$this->set(compact('vendedores'));
	}
	public function vendas() {
		if (!empty($this->request->data)) {
//			if ((empty($this->request->data['Relatorio']['inicio']) && !empty($this->request->data['Relatorio']['fim']))
//				   || (!empty($this->request->data['Relatorio']['inicio']) && empty($this->request->data['Relatorio']['fim']))
//			) {
//				$this->Session->setFlash('Informe datas de Inicio e Fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
//				$this->redirect(array('action' => 'gerar_vendas'));
//			}
			$this->layout = 'pdf';
			//Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions=array();
			if(is_array($this->conditions))
				foreach ($this->conditions as $campo => $valor) {
					$conditions = array_merge($conditions, array($campo => $valor));
				}
			if ($this->request->data['Relatorio']['inicio'] != '' && $this->request->data['Relatorio']['fim'] != '')
				$conditions = array_merge($conditions, array('Vale.data_entrega >=' => dateFormatBeforeSave($this->request->data['Relatorio']['inicio']) . " 00:00:00",
				    'Vale.data_entrega <=' => dateFormatBeforeSave($this->request->data['Relatorio']['fim']) . " 23:59:59"
					   ));
			if (!empty($this->request->data['Relatorio']['motorista_id']))
				$conditions = array_merge($conditions, array('Vale.motorista_id' => $this->request->data['Relatorio']['motorista_id']));
			if (!empty($this->request->data['Relatorio']['user_id']))
				$conditions = array_merge($conditions, array('Obra.vendedor_id' => $this->request->data['Relatorio']['user_id']));
			if (!empty($this->request->data['Relatorio']['cliente_id']))
				$conditions = array_merge($conditions, array('Pedido.cliente_id' => $this->request->data['Relatorio']['cliente_id']));
			if (!empty($this->request->data['Relatorio']['obra_id']))
				$conditions = array_merge($conditions, array('Obra.id' => $this->request->data['Relatorio']['obra_id']));


			$conditions = array_merge($conditions, array('Vale.status' => 1));
			$this->ItemPedido->Behaviors->load('Containable');
			$this->ItemPedido->contain(array(
			    'Vale.Motorista',
			    'Vale.Fornecedor',
			    'Produto',
			));
			$this->ItemPedido->order = 'Vale.created';
			
			$vales= $this->ItemPedido->find('all', array(
				
				'conditions' => $conditions,
				'joins' => array(
					array(
						'table' => $this->ItemPedido->Pedido->table,
						'alias' => $this->ItemPedido->Pedido->alias,
						'type' => 'LEFT',
						'conditions' => array(
							'Pedido.id = '.$this->ItemPedido->alias.'.pedido_id',							
						)
					),
					array(
						'fields'=>'*',
						'table' => $this->ItemPedido->Pedido->Cliente->table,
						'alias' => $this->ItemPedido->Pedido->Cliente->alias,
						'type' => 'LEFT',
						'conditions' => array(
							'Pedido.cliente_id = '.$this->ItemPedido->Pedido->Cliente->alias.'.id',							
						)
					),
					array(
						'fields'=>'*',
						'table' => $this->ItemPedido->Pedido->Obra->table,
						'alias' => $this->ItemPedido->Pedido->Obra->alias,
						'type' => 'LEFT',
						'conditions' => array(
							'Pedido.obra_id = '.$this->ItemPedido->Pedido->Obra->alias.'.id',							
						)
					)
				),
				'fields'=>array('ItemPedido.*','Vale.*','Pedido.*','Obra.*','Cliente.*','Produto.*'),
				'order'=>array('Vale.motorista_id','Vale.data_entrega')
				));
			$this->set('vales',$vales);
			//pr($vales);
			//pr($this->ItemPedido->SqlDump());exit();
			//$this->render();
			if($this->request->data['Relatorio']['layout']==1){
				$this->set('nome_arquivo','vendas'.date('YmdHi'));
				$this->render('vendas_excel','excel');
				
			}
		}else
			$this->redirect(array('action' => 'gerar_relacao'));
	}
	
	public function gerar_frete(){
		//$this->set('produtos', $this->Produto->find('list', array('fields' => array('Produto.nome'))));
		$this->set('layouts',array(0=>'PDF',1=>'Excel'));
		
		$this->set('motoristas', $this->Motorista->find('list', array('fields' => array('Motorista.nome'))));
	}
	
	public function frete() {
		if (!empty($this->request->data)) {
//			if ((empty($this->request->data['Relatorio']['inicio']) && !empty($this->request->data['Relatorio']['fim']))
//				   || (!empty($this->request->data['Relatorio']['inicio']) && empty($this->request->data['Relatorio']['fim']))
//			) {
//				$this->Session->setFlash('Informe datas de Inicio e Fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
//				$this->redirect(array('action' => 'gerar_vendas'));
//			}
			$this->layout = 'pdf';
			//Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions=array();
			if(is_array($this->conditions))
				foreach ($this->conditions as $campo => $valor) {
					$conditions = array_merge($conditions, array($campo => $valor));
				}
			if ($this->request->data['Relatorio']['inicio'] != '' && $this->request->data['Relatorio']['fim'] != '')
				$conditions = array_merge($conditions, array('Vale.data_entrega >=' => dateFormatBeforeSave($this->request->data['Relatorio']['inicio']) . " 00:00:00",
				    'Vale.data_entrega <=' => dateFormatBeforeSave($this->request->data['Relatorio']['fim']) . " 23:59:59"
					   ));
			if (!empty($this->request->data['Relatorio']['motorista_id']))
				$conditions = array_merge($conditions, array('Vale.motorista_id' => $this->request->data['Relatorio']['motorista_id']));


			$conditions = array_merge($conditions, array('Vale.status' => 1));
			$this->Vale->Behaviors->load('Containable');
			$this->Vale->contain(array(
			    'Motorista',
			    'Motorista.Abastecimento',
			    'Fornecedor',
			    'ItemPedido.Produto',
			    'ItemPedido.Pedido.Obra',
			    'ItemPedido.Pedido.Cliente'
			));
			$this->Vale->order = 'Vale.created';
			$this->set('vales', $this->Vale->find('all', array('conditions' => $conditions,'order'=>array('Vale.motorista_id','Vale.data_entrega'))));
			
			if($this->request->data['Relatorio']['layout']==1){
				$this->set('nome_arquivo','frete'.date('YmdHi'));
				$this->render('frete_excel','excel');				
			}
		}else 
			$this->redirect(array('action' => 'gerar_frete'));
	}
	
	
	
	public function gerar_notas(){
		//$this->set('produtos', $this->Produto->find('list', array('fields' => array('Produto.nome'))));
		$this->set('layouts',array(0=>'PDF',1=>'Excel'));
		
		$this->set('vendedores', $this->User->find('list',array('conditions'=>array('User.nivel_id'=>2)))); // Pesquisa somente vendedores
		$this->set('clientes', $this->Cliente->find('list', array('conditions'=>array('Cliente.ativo'=>1),'fields' => array('Cliente.nome'))));
		$this->set('empresas', $this->Empresa->find('list', array('conditions'=>array('Empresa.habilitado'=>1),'fields' => array('Empresa.nome'))));
	}
	
	public function notas() {
		if (!empty($this->request->data)) {
//			if ((empty($this->request->data['Relatorio']['inicio']) && !empty($this->request->data['Relatorio']['fim']))
//				   || (!empty($this->request->data['Relatorio']['inicio']) && empty($this->request->data['Relatorio']['fim']))
//			) {
//				$this->Session->setFlash('Informe datas de Inicio e Fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
//				$this->redirect(array('action' => 'gerar_vendas'));
//			}
			$this->layout = 'pdf';
			//Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions=array();
			if(is_array($this->conditions))
				foreach ($this->conditions as $campo => $valor) {
					$conditions = array_merge($conditions, array($campo => $valor));
				}
			if ($this->request->data['Relatorio']['inicio'] != '' && $this->request->data['Relatorio']['fim'] != '')
				$conditions = array_merge($conditions, array('Nota.emissao >=' => dateFormatBeforeSave($this->request->data['Relatorio']['inicio']) . " 00:00:00",
				    'Nota.emissao <=' => dateFormatBeforeSave($this->request->data['Relatorio']['fim']) . " 23:59:59"
					   ));
			if (!empty($this->request->data['Relatorio']['empresa_id']))
				$conditions = array_merge($conditions, array('Nota.empresa_id' => $this->request->data['Relatorio']['empresa_id']));
			if (!empty($this->request->data['Relatorio']['cliente_id']))
				$conditions = array_merge($conditions, array('Nota.cliente_id' => $this->request->data['Relatorio']['cliente_id']));
			if (!empty($this->request->data['Relatorio']['vendedor_id']))
				$conditions = array_merge($conditions, array('Pedido.user_id' => $this->request->data['Relatorio']['vendedor_id']));
			//if (!empty($this->request->data['Relatorio']['cliente_id']))
			//echo 'aaaaaaaaaa';
			//echo !empty($this->request->data['Relatorio']['cliente_id']); exit;
		//	echo json_encode($conditions); exit;
			
			
			$this->Nota->order = 'Nota.created';
			$this->Nota->Behaviors->load('Containable');
			$this->Nota->contain(array(
				'Cliente',
				'Empresa',
				'ItemNota.Produto'
			));
			
			$notas = $this->Nota->find('all', array(
				'fields'=>array('DISTINCT Nota.*','Empresa.*','Cliente.*'),
				'conditions' => $conditions,
				'order'=>array('Nota.numero'),
				'joins'=>array(
					array(
						'table'=>'itens_notas',
						'alias'=>'Item',
						'type'=>'LEFT',
						'conditions'=>array(
							'Item.nota_id=Nota.id'
						)
					),
					array(
						'table'=>'pedidos',
						'alias'=>'Pedido',
						'type'=>'LEFT',
						'conditions'=>array(
							'Pedido.id=Item.pedido_id'
						)
					)
				)
				
				));
				
				//	echo json_encode($notas); exit;
			$this->set(compact('notas'));
			#pr($this->SqlDump());
			#exit();
			if($this->request->data['Relatorio']['layout']==1){
				$this->set('nome_arquivo','notas'.date('YmdHi'));
				$this->render('nota_excel','excel');				
			}
		}else 
			$this->redirect(array('action' => 'gerar_notas'));
	}
	
	//
	// ADMIN
	//
	public function admin_gerar_vendas() {	$this->setAction('gerar_vendas');}
	public function admin_vendas() {	$this->setAction('vendas');	}
	public function admin_gerar_frete() {	$this->setAction('gerar_frete');}
	public function admin_frete() {	$this->setAction('frete');}
	public function admin_gerar_notas() {	$this->setAction('gerar_notas');}
	public function admin_notas() {	$this->setAction('notas');}
	//
	// Direator
	//
	public function diretor_gerar_vendas() {	$this->setAction('gerar_vendas');}
	public function diretor_vendas() {	$this->setAction('vendas');	}
	public function diretor_gerar_frete() {	$this->setAction('gerar_frete');}
	public function diretor_frete() {	$this->setAction('frete');}
	public function diretor_gerar_notas() {	$this->setAction('gerar_notas');}
	public function diretor_notas() {	$this->setAction('notas');}
	//
	// Direator
	//
	public function financeiro_gerar_vendas() {	$this->setAction('gerar_vendas');}
	public function financeiro_vendas() {	$this->setAction('vendas');	}
	//
	// Diretor
	//
	public function pedido_gerar_vendas() {	$this->setAction('gerar_vendas');}
	public function pedido_vendas() {	$this->setAction('vendas');	}
	public function pedido_gerar_frete() {	$this->setAction('gerar_frete');}
	public function pedido_frete() {	$this->setAction('frete');}
	//
	// Pedidos2
	//
	public function pedidos2_gerar_vendas() {	$this->setAction('gerar_vendas');}
	public function pedidos2_vendas() {	$this->setAction('vendas');	}
	
	
}