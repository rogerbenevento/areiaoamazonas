<?php

class PedidosController extends AppController{

	public $model = 'Pedido';
	public $uses = array('Pedido', 'ItemPedido', 'ObraProduto', 'Produto', 'Cliente', 'User','Vale','Vendedor','Pagamento');
	public $filter_with_like = Array('Cliente.nome');
	//Variavel usada para controlar a limpeza do carrinho
	private $_limpar = true;
	//Variavel usada para controlar o cadastro do pedido
	// quando vendedor nao realizar o cadastro de pagamento
	private $_cadastrar_pagamento = true;

	public function arredondar() {
		$this->autoRender = false;
		if (!$this->Session->check('arredondamento') || $this->Session->read('valorTotal') != $this->request->data['valorTotal']) {
			$this->Session->write('arredondamento', $this->request->data['valorArredondamento']);
			$this->Session->write('valorTotal', $this->request->data['valorTotal']);
		}else
			$this->request->data['valorArredondamento'] = $this->Session->read('arredondamento');

		$this->request->data['valorArredondamento'] = $this->Pedido->moedaBr($this->request->data['valorArredondamento'] * -1);
		$this->request->data['valorTotal'] = $this->Pedido->moedaBr($this->request->data['valorTotal']);
		echo json_encode($this->request->data);
	}//arredondar

	
	public function by_cliente($id) {
		$this->layout = '';
		$this->Pedido->Behaviors->load('Containable');
		$this->Pedido->contain(array(
			'Cliente',
			'Obra',
			'ItemPedido.Produto'
			));
		$conditions = array('Pedido.cliente_id' => $id);
		$joins=array();
		
		if(!empty( $this->request->data['conta'] ) ){
		
			$this->loadModel('ContaPedido');
			
			$joins=array(
				array(
					'table'=>$this->ContaPedido->table,
					'alias'=>$this->ContaPedido->alias,
					'type'=>'LEFT',
					'conditions'=>array("{$this->ContaPedido->alias}.pedido_id = Pedido.id")
				)
			);
			$conditions[]='ContaPedido.pedido_id IS NULL ';
			
		}
		
		$pedidos = $this->Pedido->find('all', array('conditions' => $conditions,'joins'=>$joins));
		
		$this->set('pedidos', $pedidos);
		$this->set('unidades', $this->Pedido->ItemPedido->unidade);
		
	}
	
	//
	// Admin
	//
	public function admin_abertos() {
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		$this->conditions['Pedido.status'] = 0;
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	public function admin_index() {
		$this->conditions['Pedido.status'] = array('0', '1', '2');
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		$this->Pedido->Behaviors->load('Containable');
		$this->Pedido->contain(
				'Cliente'
			);
		$options['extra']['contain']=array(
				'Cliente'
			);
		parent::index($options);
		$this->set('status',$this->Pedido->status);
		$motoristas = $this->Vale->Motorista->find('list', array('fields' => 'nome'));
		foreach ($motoristas as &$val)
			$val = utf8_encode($val);
		$this->set('motoristas', $motoristas);
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	public function admin_finalizados() {
		//$this->conditions['Pedido.status'] = array('1');
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		parent::index();
		$this->set('status', $this->Pedido->status);
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	public function admin_edit($id = null) {
		// return true;
		$this->Pedido->id = $id;
		// $this->log($id);
		$erro=false;
		if (!empty($this->request->data)) {
			#pr($pedido); exit();
			$imprimir_vale=false;
			if ($this->Session->check('carrinho')) {
				//Existe itens no carrinho
				$dataSource = $this->Pedido->getDataSource();
				$dataSource->begin(); // Inicia o controle de transa��o
				
				//$this->ItemPedido->id = null;
				//pr($this->request->data);
				
				$this->request->data['Pedido']['observacao'] = TextoToUp($this->request->data['Pedido']['observacao']);
				$this->request->data['Pedido']['freteiro'] = DboSource::expression('(SELECT freteiro FROM obras WHERE id='.$this->request->data['Pedido']['obra_id'].')');
				$sql_comissao="(SELECT IF(o.comissao IS NULL OR o.comissao = 0 ,"
								.'(SELECT v.comissao FROM vendedores v WHERE v.user_id='.$this->request->data['Pedido']['user_id'].')'
								.',o.comissao) as comissao FROM obras o WHERE o.id ='.$this->request->data['Pedido']['obra_id'].')';
				$this->request->data['Pedido']['comissao'] = DboSource::expression($sql_comissao);

				if($this->request->data['Pedido']['comissao']>4){
					$this->request->data['Pedido']['comissao'] = 4;
				}
				$this->request->data['Pedido']['user_log'] = $this->Session->read('Auth.User.id');
				
				//pr($sql_comissao);exit();
				if ($this->Pedido->save($this->request->data)) {
					$id = $this->Pedido->id;

					foreach ($_SESSION['carrinho'] as &$value){
						$value['pedido_id']=$id;
						$value['quantidade_original']=$value['quantidade'];
						$value['unidade_original']=$value['unidade'];
					}
					//pr($_SESSION['carrinho']);
//					$this->Pedido->getDataSource()->rollback();
//					exit();
					
					if($this->Pedido->ItemPedido->saveAll($_SESSION['carrinho'])){
						$this->Pedido->ItemPedido->recursive= 1;
						$itens=$this->Pedido->ItemPedido->find('all',array(
							'fields'=>array('ItemPedido.id','Vale.id'),
							'conditions'=>array('ItemPedido.pedido_id'=>$id)
							));
//						pr($itens);
						$c=0;
						$vales=array();
						foreach ($itens as $value){
							$vales[$c]=array('Vale'=>array('item_pedido_id'=>$value['ItemPedido']['id']));
							if(!empty($value['Vale']['id']))
								$vales[$c]['Vale']['id'] = $value['Vale']['id']; 
							$item_id=$value['ItemPedido']['id'];
							$c++;
						}
						if($this->Vale->saveAll($vales)){
//							$this->Pedido->ItemPedido->recursive= 0;
//							$itens=$this->Pedido->ItemPedido->findAllByPedidoId($id,array('fields'=>'id'));
//							$dataSource->rollback();
//							pr($itens);
//							pr($vales);
//							pr($this->Pedido->SqlDump());
							//exit();

							$this->Session->setFlash('Pedido salvo com sucesso', 'default', array('class' => 'alert alert-success'));
							#unset($_SESSION['carrinho']);
							$dataSource->commit();
							$this->Vale->Behaviors->attach('Containable');
							$this->Vale->contain(
								   'ItemPedido.Pedido.Cliente'
							);
							$_SESSION['imprimir_vale'] = $this->Vale->findByItemPedidoId($item_id);
							$this->redirect(array("action" => "index"));
							//if ($this->ItemPedido->incluir($this->Session->read('carrinho'), $id)) {
						}else{
							$message='Ocorreu um erro ao incluir os vales do pedido no sistema';
							$dataSource->rollback();							
						}
					} else {
						$message='Ocorreu um erro ao incluir os itens do pedido no sistema';
						$dataSource->rollback();
					}
				} else {
					$dataSource->rollback();
					$message='Ocorreu um erro ao '.(empty($id)?'criar':'editar').' o pedido.';
				}
			} else $message='Não há itens no pedido.';
			$erro=true;
			if(!empty($this->Pedido->validationErrors))
				if(is_array($this->Pedido->validationErrors))
					foreach ($this->Pedido->validationErrors as $rule_id=>$rule) 
						foreach ($rule as $msg)
//							if($rule_id=='comissao'){
//								$ori = array();
//								$change=array();
//								
//								if(!empty($this->request->data['Pedido']['obra_id'])){
//									$ori[]='#Obra';
//									$change[]='<a href="'.Router::url(array('controller'=>'obras','action'=>'edit/'.$this->request->data['Pedido']['cliente_id'],$this->request->data['Pedido']['obra_id'])).'" target="_blank">Obra</a>';
//								}
//								if(!empty($this->request->data['Pedido']['user_id'])){
//									$ori[]='#Vendedor';
//									$change[]='<a href="'.Router::url(array('controller'=>'vendedor','action'=>'edit',$this->request->data['Pedido']['user_id'])).'" target="_blank">Vendedor</a>';
//								}
//								
//								$message.='<br>'.str_ireplace($ori,$change,$msg);
//							}else 
								$message.='<br>'.$msg;
				
			$this->Session->setFlash($message, 'default', array('class' => 'alert alert-error'));
			
			//$this->redirect(array("action" => "edit", $id));
		}
		if( $id != null ){
                        $this->Pedido->Behaviors->attach('Containable');
                        $this->Pedido->contain(
				'Cliente',
				'Obra',
				'Cliente.Cidade.Estado',
				'User',
				'ItemPedido',
				'ItemPedido.Produto',
				'ItemPedido.Vale'
			);
			$this->data=$this->Pedido->read();
		}
//		pr($this->request->data);
//		pr($this->Pedido->validationErrors);
		if(!$erro)
			$this->ItemPedido->montarCarrinho($id,true);
		
		$periodos = $this->Pedido->Periodo->find('list');
		$produtos=$this->Produto->find('list');
		$vendedores=$this->User->find('list',array('conditions'=>array('User.nivel_id'=>2))); // Pesquisa somente vendedores
		$this->set('produtos',$produtos);
		$this->set('periodos',$periodos);
		$this->set('status',$this->Vale->status);
		
		$this->set('vendedores',$vendedores);
		$this->set('unidades',$this->ItemPedido->unidade);
		$this->set('precos',  $this->precos($id));
		
		
	}
	
	
	public function admin_edit_finalizado($id,$item_pedido_id=null){
		$this->Pedido->id = $id;
		$pedido=$this->Pedido->read();
		
		$this->ItemPedido->montarCarrinho($id,true,true);
		if(!empty($this->request->data['Pedido'])){
			parent::edit($id);
		}		
		if(!empty($item_pedido_id)){
			$this->ItemPedido->id = $item_pedido_id;
			
			if(!empty($this->request->data)){
				if(!empty($this->request->data['ItemPedido'])){
					if(!empty($this->request->data['ItemPedido']['motivo'])){
						if($this->ItemPedido->save($this->request->data)){
							$this->Session->setFlash('Item editado com sucesso!', 'default', array('class'=>'alert alert-success'));
							$this->redirect( array( 'controller'=>'pedidos','action' => 'admin_edit_finalizado',$id ) );
						}else $this->Session->setFlash('Não foi possível realizar a alteração!', 'default', array('class' => 'alert alert-error'));
					}else $this->Session->setFlash('Informe um motivo para a alteração!', 'default', array('class' => 'alert alert-error'));
				}			
			}			
			$item = $this->ItemPedido->read();			
			$item['ItemPedido']['pago']=moedaBr($item['ItemPedido']['pago']);
			$item['ItemPedido']['quantidade']= number_format(($item['ItemPedido']['quantidade']*1),2,'.','');
			$this->data = $item;			
		}
		if(empty($this->data))
			$this->data=array('Pedido'=>$pedido['Pedido']);
		$this->set(compact('pedido'));
		$this->set('unidades',$this->ItemPedido->unidade);
	}
	public function admin_add() {
		$this->setAction('admin_edit');
	}

	public function admin_alterar_data($id) {
		if (!empty($this->request->data)) {
			//pr($this->request->data);
			$this->Pedido->id = $id;
			//$this->request->data['Pedido']['data_entrega'] = dateFormatBeforeSave($this->request->data['Pedido']['data_entrega']);
			if ($this->Pedido->save($this->request->data))
				$this->Session->setFlash('Data editada com sucesso!', 'default', array('class' => 'alert alert-success'));

			else
				$this->Session->setFlash('Ocorreu um erro ao editar a data.', 'default', array('class' => 'alert alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		$pedido = $this->Pedido->find('first', array("conditions" => array('Pedido.id' => $id)));
		$pedido['Pedido']['created'] = dateMysqlToPhp($pedido['Pedido']['created']);
		$this->set('pedido', $pedido);
	}

	public function admin_copiar() {
		$this->autoRender = false;
		$this->Pedido->Behaviors->attach('Containable');
		$this->Pedido->contain(
				'ItemPedido'
			);
		$pedido = $this->Pedido->findById($this->request->data['Pedido']['id']);
		
		
		unset($pedido['Pedido']['id']);
		unset($pedido['Pedido']['created']);
		unset($pedido['Pedido']['modified']);
		$pedido['Pedido']['status']=0;
		$pedido['Pedido']['user_log']=$this->Session->read('Auth.User.id');
		
		
		
		$dataSource = $this->Pedido->getDataSource();
		$dataSource->begin();
		for ($c=0;$c< $this->request->data['Pedido']['quantidade'] ;$c++):
			$this->Pedido->id=null;
			$continue=false;
			if($this->Pedido->save($pedido['Pedido'])){
				
				$itens_pedido = $pedido['ItemPedido'];
				foreach ($itens_pedido as $key=>$value) {
					unset($pedido['ItemPedido'][$key]['id']);
					unset($pedido['ItemPedido'][$key]['created']);
					unset($pedido['ItemPedido'][$key]['modified']);
					unset($pedido['ItemPedido'][$key]['pago']);
					unset($pedido['ItemPedido'][$key]['frete']);
					$pedido['ItemPedido'][$key]['pedido_id']=$this->Pedido->id;
				}
				
				if($this->Pedido->ItemPedido->saveAll($pedido['ItemPedido'])){
					$this->Pedido->ItemPedido->recursive= -1;
					$itens=$this->Pedido->ItemPedido->findAllByPedidoId( $this->Pedido->id , array('fields'=>'id'));
					$vales=array();
					foreach ($itens as $value){
						$vales[]=array('Vale'=>array('item_pedido_id'=>$value['ItemPedido']['id']));						
					}
					if($this->Vale->saveAll($vales)){
						$continue=true;
						$this->Session->setFlash('Pedido copiada com sucesso!', 'default', array('class' => 'alert alert-success'));
					}else $this->Session->setFlash('Problema ao gerar Vales!', 'default', array('class' => 'alert alert-error'));
				} else $this->Session->setFlash('Falha ao copiar os Itens do pedido!', 'default', array('class' => 'alert alert-error'));
			}else $this->Session->setFlash('Falaha ao copiar Pedido!', 'default', array('class' => 'alert alert-error'));
			if(!$continue){
				$dataSource->rollback();
				break;
			}
		endfor;
		if($continue)
			$dataSource->commit();
		$this->redirect(array('action'=>'index'));

		
	}
	/**
	 * adminFinalizar() - Ao finalizar o pedido, os itens serão baixados do estoque de produtos
	 * @param integer $id - ID do pedido
	 */
	public function admin_finalizar($id) {
		$this->Pedido->recursive = -1;
		$veri=$this->Pedido->findById($id);
		if($veri['Pedido']['status']==1){
			$this->Session->setFlash('Pedido já esta finalizado!', 'default', array('class' => 'alert alert-warning'));
			$this->redirect(array('action'=>'index'));
		}
			
		if (!empty($this->request->data)) {
			#pr($this->request->data);exit();
			$this->request->data['Pedido']['id'] = $id;
			$this->request->data['Pedido']['status'] = 1;
			$dataSource = $this->Pedido->getDataSource();
			$dataSource->begin();			
			#exit();
			$item_pedido=false;
			if (!empty($this->request->data['ItemPedido'])){
				$item_pedido=true;
				//Caso esteja finalizando um vale junto com o pedido ajustas valores
				unset($this->request->data['ItemPedido']['valor_unitario']);
				
					
				if(!empty($this->request->data['Vale']['id'])){
					//Esta finalizando o vale junto com o pedido atualizando valores do item pedido junto
					$this->request->data['ItemPedido']['frete']=moedaBD($this->request->data['ItemPedido']['frete']);
					$this->request->data['ItemPedido']['valor_total']=moedaBD($this->request->data['ItemPedido']['valor_total']);
					$this->request->data['ItemPedido']['valor'] = DboSource::expression('('. $this->request->data['ItemPedido']['valor_total'].'/'.moedaBD($this->request->data['ItemPedido']['quantidade']) . ')');
										
					
					$this->request->data['Vale']['status'] = 1;
					$this->request->data['Vale']['motorista_tipo'] = DboSource::expression('(SELECT tipo FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');
					$this->request->data['Vale']['placa'] = DboSource::expression('(SELECT placa FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');


					$this->request->data['Vale']['nota_fiscal_emissao'] = datePhpToMysql($this->request->data['Vale']['nota_fiscal_emissao']);

					 
					// ALTERARAÇÕES DEVEM SER REFLETIDAS NO CONTROLLER VALES			
					$this->Vale->save($this->request->data['Vale']);
					//$this->ItemPedido->save($this->request->data['ItemPedido']);
				}else{
					// os vales ja estao finalizados, entao atualizar apenas o valor dos fretes
					foreach($this->request->data['ItemPedido'] as &$value) {
						$value['frete']=  moedaBD($value['frete']);
					}
				}
			}
			
			$pedido_save = $this->Pedido->save( $this->request->data['Pedido'] );
			
			if ( $pedido_save){
				//pr($this->request->data['ItemPedido']);
				$save_item_ped=$this->ItemPedido->saveAll($this->request->data['ItemPedido']);
				if($save_item_ped){
//					$dataSource->rollback();	
//					pr($this->Pedido->SqlDump());
//					exit();
					$dataSource->commit();
					
					$this->Session->setFlash('Pedido finalizado com sucesso!', 'default', array('class' => 'alert alert-success'));
					//}
				}else{
					//pr($this->Pedido->SqlDump());
					
					$dataSource->rollback();				
					$this->Session->setFlash('Erro ao finalizar itens do pedido!', 'default', array('class' => 'alert alert-error'));
				}
			}else{
				$dataSource->rollback();				
				$this->Session->setFlash('Erro ao finalizar pedido!', 'default', array('class' => 'alert alert-error'));
			}
			$this->redirect(array('action' => "index"));
		}
		$this->Pedido->Behaviors->attach('Containable');
		$this->Pedido->contain(
				'Cliente',
				'Obra',
				'Cliente.Cidade.Estado',
				'User',
				'ItemPedido',
				'ItemPedido.Produto',
				'ItemPedido.Vale'
			);
		$this->set('pedido', $this->Pedido->findById($id));
		$this->set('status',$this->Vale->status);
		$this->set('empresas',$this->Vale->Empresa->find('list'));
		$this->set('freteiros',$this->Vale->Motorista->find('list',array('conditions'=>array('Motorista.tipo=2','ativo=1'))));
		$this->set('motoristas',$this->Vale->Motorista->find('list'));
		$this->set('preco_diurno',$this->Pedido->preco_diurno);
		$this->set('preco_noturno',$this->Pedido->preco_noturno);
		$this->set('porcentagem_frete',$this->Pedido->porcentagem_freteiro);
		
		$fornecedores = $this->Vale->Fornecedor->find('list', array('fields' => 'nome'));
		foreach ($fornecedores as &$val)
			$val = utf8_encode($val);
		$this->set('fornecedores', $fornecedores);
		$this->set('unidades', $this->Vale->ItemPedido->unidade);
		$this->set('periodos', $this->Vale->Periodo->find('list'));

	

		
		$this->set('precos',  $this->precos($id));

		//$this->set('pedido', $this->Pedido->find('first', array("conditions" => array('Pedido.id' => $id))));
	}

	public function precos($id){
		//$this->autoRender = false;
	    //$this->layout = '';
		$pedido  =  $this->Pedido->findById($id);
		$obra_id = $pedido['Pedido']['obra_id'];
		
		$precos = $this->ObraProduto->find('all',array('conditions'=>array('ObraProduto.obra_id'=>$obra_id)));

		$array = [];
		foreach($precos as $k => $v){
			$array[$v['ObraProduto']['produto_id']] = $v['ObraProduto']['preco']; 
		}

		
		return $array;
	}

	public function admin_cancelar($id) {
		//$this->autoRender = false;
		if(!empty($this->request->data)){
			$pedido = $this->Pedido->find('first', array("conditions" => array('Pedido.id' => $id)));
			//echo 123;exit();
			$motivo = $this->request->data['Pedido']['motivo'];

			if ($motivo == "") {
				$this->Session->setFlash('Para cancelar é necessário informar um motivo', 'default', array('class' => 'alert alert-error'));
			} else if ($motivo == null) {
				$this->Session->setFlash('Para cancelar é necessário informar um motivo', 'default', array('class' => 'alert alert-error'));
			} else {
				$this->request->data['Pedido'] = array(
				    'motivo' => $motivo,
				    'status' => '2',
				    'data_cancelamento' => date('Y-m-d'),
				    'id' => $id);
				foreach ($this->request->data['ItemPedido'] as &$value) {
					$value['frete']=  moedaBD($value['frete']);
					$value['status']=2;
				}
				//pr($this->request->data);exit();
			
				$dataSource = $this->Pedido->getDataSource();
				$dataSource->begin();				
				if ($this->Pedido->saveAssociated($this->request->data)) {
					if ($this->Vale->updateAll(array('Vale.status'=>2),array('ItemPedido.pedido_id='.$id))) {
						if ($this->ItemPedido->remover($pedido['ItemPedido'])) {
							$this->Session->setFlash("Pedido {$id} cancelado com sucesso", 'default', array('class' => 'alert alert-success'));
							$dataSource->commit();
						} else {
							$this->Session->setFlash('Ocorreu um erro ao remover os itens do pedido', 'default', array('class' => 'alert alert-error'));
							$dataSource->rollback();
						}
					} else {
						$this->Session->setFlash('Ocorreu um erro ao cancelar os vales', 'default', array('class' => 'alert alert-error'));
						$dataSource->rollback();
					}
				} else {
					$this->Session->setFlash('Ocorreu um erro ao cancelar o pedido', 'default', array('class' => 'alert alert-error'));
					$dataSource->rollback();
				}
				
			}
			$this->redirect(array('action'=>'index'));
		}//if(empty())
		$this->Pedido->Behaviors->attach('Containable');
		$this->Pedido->contain(
				'Cliente',
				'Cliente.Cidade.Estado',
				'User',
				'ItemPedido',
				'ItemPedido.Produto',
				'ItemPedido.Vale'
			);
		$this->set('pedido', $this->Pedido->findById($id));
		$this->set('status',$this->Vale->status);
		$this->set('motoristas',$this->Vale->Motorista->find('list'));
		$this->set('preco_diurno',$this->Pedido->preco_diurno);
		$this->set('preco_noturno',$this->Pedido->preco_noturno);
		$this->set('porcentagem_frete',$this->Pedido->porcentagem_freteiro);
	}

	public function admin_detalhes($id = null, $print = false) {
		if ($print != 1)
			$this->layout = '';
		else
			$this->layout = 'imprimir';
		
//		$this->Pedido->recursive = 0;
		$this->Pedido->Behaviors->attach('Containable');
		$this->Pedido->contain(
				'Obra.Cidade.Estado',
				'Cliente',
				'Cliente.Cidade.Estado',
				'User',
			   'ItemPedido',
				'ItemPedido.Produto',
				'ItemPedido.Vale'
			);
		//pr($this->Pedido->findById($id));exit();
		$this->set('pedido', $this->Pedido->findById($id));
		$this->set('precos',  $this->precos($id));
	}

	public function admin_gerar_relacao() {
		$this->set('lojas', $this->Loja->find('list', array('fields' => array('Loja.descricao'))));
		$conditions = array('User.nivel' => array('vendedor', 'gerente'));
		$this->set('users', $this->User->find('list', array('conditions' => $conditions)));
	}

	public function admin_relacao() {
		if (!empty($this->request->data)) {
			if ((empty($this->request->data['Pedido']['inicio']) && !empty($this->request->data['Pedido']['fim']))
				   || (!empty($this->request->data['Pedido']['inicio']) && empty($this->request->data['Pedido']['fim']))
			) {
				$this->Session->setFlash('Informe datas de inicio e fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
				$this->redirect(array('action' => 'gerar_relacao'));
			}
			$this->layout = 'pdf';
			Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions = array();
			foreach ($this->conditions as $campo => $valor) {
				$conditions = array_merge($conditions, array($campo => $valor));
			}
			if ($this->request->data['Pedido']['inicio'] != '' && $this->request->data['Pedido']['fim'] != '')
				$conditions = array_merge($conditions, array('Pedido.created >=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['inicio']) . " 00:00:00",
				    'Pedido.created <=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['fim']) . " 23:59:59"
					   ));

			if ($this->request->data['Pedido']['loja'] != '')
				$conditions = array_merge($conditions, array('Pedido.loja_id' => $this->request->data['Pedido']['loja']));

			if ($this->request->data['Pedido']['users'] != '')
				$conditions = array_merge($conditions, array('Pedido.user_id' => $this->request->data['Pedido']['users']));


			$conditions = array_merge($conditions, array('Pedido.status' => 1));
			//pr($conditions);
			//$pedidos=$this->Pedido->find('all',array('conditions'=>$conditions ) );
			// pr($pedidos);
			$this->Pedido->order = 'Pedido.created';
			$this->set('pedidos', $this->Pedido->find('all', array('conditions' => $conditions)));
			$this->set('model', $this->Pedido);

			$this->render();
		}else
			$this->redirect(array('action' => 'gerar_relacao'));
	}

	public function admin_gerar_relacao_forma_pagto() {
		$this->set('lojas', $this->Loja->find('list', array('fields' => array('Loja.descricao'))));
		$conditions = array('User.nivel' => array('vendedor', 'gerente'));
		$this->set('users', $this->User->find('list', array('conditions' => $conditions)));
	}

	public function admin_relacao_forma_pagto() {
		if (!empty($this->request->data)) {
			if ((empty($this->request->data['Pedido']['inicio']) && !empty($this->request->data['Pedido']['fim']))
				   || (!empty($this->request->data['Pedido']['inicio']) && empty($this->request->data['Pedido']['fim']))
			) {
				$this->Session->setFlash('Informe datas de inicio e fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
				$this->redirect(array('action' => 'gerar_relacao_forma_pagto'));
			}
			$this->layout = 'pdf';
			//Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions = array();
			foreach ($this->conditions as $campo => $valor) {
				$conditions = array_merge($conditions, array($campo => $valor));
			}
			if ($this->request->data['Pedido']['inicio'] != '' && $this->request->data['Pedido']['fim'] != '')
//                            $conditions = array_merge( $conditions, array( 'Pedido.created >=' => $this->Pedido->dateFormatBeforeSave ($this->request->data['Pedido']['inicio'])." 00:00:00",
//                                                                            'Pedido.created <='=> $this->Pedido->dateFormatBeforeSave ($this->request->data['Pedido']['fim'])." 23:59:59"   
				$conditions = array_merge($conditions, array('Pedido.created >=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['inicio']),
				    'Pedido.created <=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['fim'])
					   ));

			if ($this->request->data['Pedido']['loja'] != '')
				$conditions = array_merge($conditions, array('Pedido.loja_id' => $this->request->data['Pedido']['loja']));

			if ($this->request->data['Pedido']['users'] != '')
				$conditions = array_merge($conditions, array('Pedido.user_id' => $this->request->data['Pedido']['users']));


			//$conditions=array_merge( $conditions, array('Pedido.status'=>1) );
			//pr($conditions);
			//$pedidos=$this->Pedido->find('all',array('conditions'=>$conditions ) );
			// pr($pedidos);
			$this->Pedido->order = 'Pedido.created';
			$this->set('pedidos', $this->Pedido->RelacaoFormaPagto($conditions));
			$this->set('model', $this->Pedido);

			$this->render();
		}else
			$this->redirect(array('action' => 'gerar_relacao_forma_pagto'));
	}

	public function admin_gerar_relacao_mensal() {
		$this->set('lojas', $this->Loja->find('list', array('fields' => array('Loja.descricao'))));
	}

	public function admin_relacao_mensal() {
		if (!empty($this->request->data)) {
			if ((empty($this->request->data['Pedido']['inicio']) && !empty($this->request->data['Pedido']['fim']))
				   || (!empty($this->request->data['Pedido']['inicio']) && empty($this->request->data['Pedido']['fim']))
			) {
				$this->Session->setFlash('Informe datas de inicio e fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
				$this->redirect(array('action' => 'gerar_relacao_mensal'));
			}
			$this->layout = 'pdf';
			Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions = array();
			foreach ($this->conditions as $campo => $valor) {
				$conditions = array_merge($conditions, array($campo => $valor));
			}
			if ($this->request->data['Pedido']['inicio'] != '' && $this->request->data['Pedido']['fim'] != '')
				$conditions = array_merge($conditions, array('Pedido.created >=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['inicio']) . " 00:00:00",
				    'Pedido.created <=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['fim']) . " 23:59:59"
					   ));

			if ($this->request->data['Pedido']['loja'] != '')
				$conditions = array_merge($conditions, array('Pedido.loja_id' => $this->request->data['Pedido']['loja']));
			//Apenas pedido Finalizados
			$conditions = array_merge($conditions, array('Pedido.status' => 1));
			//pr($conditions);
			//$pedidos=$this->Pedido->find('all',array('conditions'=>$conditions ) );
			// pr($pedidos);
			$this->Pedido->order = 'Pedido.created';
			$this->set('pedidos', $this->Pedido->relatoriomensal($conditions));
			$this->set('model', $this->Pedido);

			$this->render();
		}else
			$this->redirect(array('action' => 'gerar_relacao_mensal'));
	}

	public function admin_gerar_comissao() {
		$this->set('lojas', $this->Loja->find('list', array('fields' => array('Loja.descricao'))));
		$this->set('users', $this->User->find('list', array('conditions' => array('User.nivel' => 'vendedor'), 'fields' => array('User.nome'))));
	}

	public function admin_comissao() {
		if (!empty($this->request->data)) {
			if ((empty($this->request->data['Pedido']['inicio']) && !empty($this->request->data['Pedido']['fim']))
				   || (!empty($this->request->data['Pedido']['inicio']) && empty($this->request->data['Pedido']['fim']))
			) {
				$this->Session->setFlash('Informe datas de inicio e fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
				$this->redirect(array('action' => 'gerar_comissao'));
			}
			$this->layout = 'pdf';
			//Configure::write('debug', 0);
			//pr($this->request->data);
			$conditions = array();
			foreach ($this->conditions as $campo => $valor) {
				$conditions = array_merge($conditions, array($campo => $valor));
			}
			if ($this->request->data['Pedido']['inicio'] != '' && $this->request->data['Pedido']['fim'] != '')
				$conditions = array_merge($conditions, array('Pedido.created >=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['inicio']) . " 00:00:00",
				    'Pedido.created <=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['fim']) . " 23:59:59"
					   ));

			if ($this->request->data['Pedido']['loja_id'] != '')
				$conditions = array_merge($conditions, array('Pedido.loja_id' => $this->request->data['Pedido']['loja_id']));
			if ($this->request->data['Pedido']['user_id'] != '')
				$conditions = array_merge($conditions, array('Pedido.user_id' => $this->request->data['Pedido']['user_id']));
			//Apenas pedido Finalizados
			$conditions = array_merge($conditions, array('Pedido.status' => 1));
			//pr($conditions);
			//$pedidos=$this->Pedido->find('all',array('conditions'=>$conditions ) );
			// pr($pedidos);
			$this->Pedido->order = 'Pedido.created';
			$this->set('pedidos', $this->Pedido->comissao($conditions));
			$this->set('model', $this->Pedido);

			$this->render();
		}else
			$this->redirect(array('action' => 'gerar_comissao'));
	}

	public function admin_gerar_planilhao() {
		$this->set('lojas', $this->Loja->find('list', array('fields' => array('Loja.descricao'))));
	}

	public function admin_planilhao() {
		if (!empty($this->request->data)) {
			if ((empty($this->request->data['Pedido']['inicio']) && !empty($this->request->data['Pedido']['fim']))
				   || (!empty($this->request->data['Pedido']['inicio']) && empty($this->request->data['Pedido']['fim']))
			) {
				$this->Session->setFlash('Informe datas de inicio e fim para pesquisa!', 'default', array('class' => 'alert alert-error'));
				$this->redirect(array('action' => 'gerar_planilhao'));
			}

			$conditions = array();
			foreach ($this->conditions as $campo => $valor) {
				$conditions = array_merge($conditions, array($campo => $valor));
			}
			if ($this->request->data['Pedido']['inicio'] != '' && $this->request->data['Pedido']['fim'] != '')
				$conditions = array_merge($conditions, array('Pedido.created >=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['inicio']) . " 00:00:00",
				    'Pedido.created <=' => $this->Pedido->dateFormatBeforeSave($this->request->data['Pedido']['fim']) . " 23:59:59"
					   ));

			if ($this->request->data['Pedido']['loja_id'] != '')
				$conditions = array_merge($conditions, array('Pedido.loja_id' => $this->request->data['Pedido']['loja_id']));

			//Apenas pedido Finalizados
			//$conditions=array_merge( $conditions, array('Pedido.status'=>1) );
			//pr($conditions);
			//$pedidos=$this->Pedido->find('all',array('conditions'=>$conditions ) );
			// pr($pedidos);
			$this->Pedido->order = 'Pedido.created';
			$this->Pedido->recursive = 2;
			$this->set('rows', $this->Pedido->find('all', array('conditions' => $conditions)));
			$this->set('model', $this->Pedido);

			//$this->render();
		}else
			$this->redirect(array('action' => 'gerar_planilhao'));
	}

	public function admin_arredondar() {
		$this->setAction('arredondar');
	}
	public function admin_by_cliente($id) {
		$this->setAction('by_cliente',$id);
	}

	//
	//  GERENTE
	//
    public function gerente_abertos() {	$this->setAction('admin_abertos');}
	public function gerente_finalizados() {	$this->setAction('admin_finalizados');}
	public function gerente_finalizar($id) {$this->setAction('admin_finalizar', $id);}
	public function gerente_index() {	$this->setAction('vendedor_index');	}

	public function gerente_add() {
		$this->ItemPedido->montarCarrinho(null, true);
		$this->Pagamento->montarPagamentos(null);
		$this->Session->delete('arredondamento');
		$this->Session->delete('desconto');
		$this->setAction('vendedor_edit');
	}

	public function gerente_edit($id = null) {	$this->setAction('admin_edit', $id);}

	public function gerente_detalhes($id) {
		$this->_id = $id;
		$this->setAction('admin_detalhes');
	}

	public function gerente_limpar() {
		$this->Pedido->limpar_carrinho();
		$this->redirect($this->Auth->redirect('/vendedor/users/dashboard'));
	}

	public function gerente_arredondar() {	$this->setAction('arredondar');}

	//
	//  Vendedor
	//
    public function vendedor_index() {
		$this->conditions['Pedido.status'] = array('0', '1');
//	    $this->set('pedido',$this->Comanda->incluir(207) );
		$this->Pedido->Behaviors->load('Containable');
		$this->Pedido->contain(
				'Cliente'
			);
		$options['extra']['contain']=array(
				'Cliente'
			);
		parent::index($options);
		$this->set('status',$this->Pedido->status);
		$motoristas = $this->Vale->Motorista->find('list', array('fields' => 'nome'));
		foreach ($motoristas as &$val)
			$val = utf8_encode($val);
		$this->set('motoristas', $motoristas);
	}

	public function vendedor_add() {
		$this->ItemPedido->montarCarrinho(null, true);
		$this->Pagamento->montarPagamentos(null);
		$this->Session->delete('arredondamento');
		$this->Session->delete('desconto');
		$this->setAction('vendedor_edit');
	}

	public function vendedor_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function vendedor_detalhes($id) {	$this->setAction('admin_detalhes',$id);	}
	public function vendedor_limpar() {
		$this->Pedido->limpar_carrinho();
		$this->redirect($this->Auth->redirect('/vendedor/users/dashboard'));
	}

	public function vendedor_arredondar() {	$this->setAction('arredondar');	}

	//
	//  PEDIDO
	//
    public function pedido_abertos() {	$this->setAction('vendedor_abertos');}
	public function pedido_finalizados() {	$this->setAction('vendedor_finalizados');	}
	public function pedido_index() {$this->setAction('vendedor_index');	}
	public function pedido_add() {	$this->setAction('vendedor_add');}
	public function pedido_edit($id = null) {	$this->setAction('vendedor_edit', $id);}
	public function pedido_detalhes($id) {	$this->setAction('vendedor_detalhes', $id);}
	public function pedido_finalizar($id) {	$this->setAction('vendedor_finalizar', $id);}
	public function pedido_cancelar($id) {	$this->setAction('vendedor_cancelar', $id);}
	public function pedido_copiar() {	$this->setAction('vendedor_copiar');}
	//
	//  FINANCEIRO
	//
    public function financeiro_abertos() {	$this->setAction('admin_abertos');}
	public function financeiro_finalizados() {	$this->setAction('admin_finalizados');	}
	public function financeiro_index() {$this->setAction('admin_index');	}
	public function financeiro_add() {	$this->setAction('financeiro_edit');}
	public function financeiro_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function financeiro_detalhes($id) {	$this->setAction('admin_detalhes', $id);}
	public function financeiro_finalizar($id) {	$this->setAction('admin_finalizar', $id);}
	public function financeiro_cancelar($id) {	$this->setAction('admin_cancelar', $id);}
	public function financeiro_copiar() {	$this->setAction('admin_copiar');}
	
	//
	// PROGRAMACAO1
	// 
	public function programacao1_abertos() {	$this->setAction('admin_abertos');}
	public function programacao1_finalizados() {	$this->setAction('admin_finalizados');	}
	public function programacao1_index() {$this->setAction('admin_index');	}
	public function programacao1_add() {	$this->setAction('financeiro_edit');}
	public function programacao1_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function programacao1_detalhes($id) {	$this->setAction('admin_detalhes', $id);}
	public function programacao1_finalizar($id) {	$this->setAction('admin_finalizar', $id);}
	public function programacao1_cancelar($id) {	$this->setAction('admin_cancelar', $id);}
	public function programacao1_copiar() {	$this->setAction('admin_copiar');}
	
	//
	// PEDIDOS1
	// 
	public function pedidos1_abertos() {	$this->setAction('admin_abertos');}
	public function pedidos1_finalizados() {	$this->setAction('admin_finalizados');	}
	public function pedidos1_index() {$this->setAction('admin_index');	}
	public function pedidos1_add() {	$this->setAction('financeiro_edit');}
	public function pedidos1_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function pedidos1_detalhes($id) {	$this->setAction('admin_detalhes', $id);}
	public function pedidos1_finalizar($id) {	$this->setAction('admin_finalizar', $id);}
	public function pedidos1_cancelar($id) {	$this->setAction('admin_cancelar', $id);}
	public function pedidos1_copiar() {	$this->setAction('admin_copiar');}
	
	//
	// PEDIDOS2
	// 
	public function pedidos2_abertos() {	$this->setAction('admin_abertos');}
	public function pedidos2_finalizados() {	$this->setAction('admin_finalizados');	}
	public function pedidos2_index() {$this->setAction('admin_index');	}
	public function pedidos2_add() {	$this->setAction('financeiro_edit');}
	public function pedidos2_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function pedidos2_detalhes($id) {	$this->setAction('admin_detalhes', $id);}
	public function pedidos2_finalizar($id) {	$this->setAction('admin_finalizar', $id);}
	public function pedidos2_cancelar($id) {	$this->setAction('admin_cancelar', $id);}
	public function pedidos2_copiar() {	$this->setAction('admin_copiar');}
}//class