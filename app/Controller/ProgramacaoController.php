<?php

class ProgramacaoController extends AppController {

	public $model = 'Programacao';
	public $filter_with_like = Array('Funcao.nome','User.nome');
	public $uses = array('Pedido','Vale','Programacao','Cidade');
	
	public function index($data=null,$periodo=null) {
		$this->limit = 999;
		if(empty($data)){
			$data=date('Y-m-d');
		}else{
			$data=datePhpToMysql($data);
		}
		
		
		if(!empty($periodo)){
			$this->conditions['Pedido.periodo_id']=$periodo;
		}
		$this->conditions['Pedido.status !=']=2;
		$this->conditions['DATE_FORMAT(Pedido.data_entrega,"%Y-%m-%d")']=$data;
		$this->model='Vale';
		
//		$this->Vale->Behaviors->attach('Containable');
//		$this->Vale->contain(
//				'ItemPedido.Pedido',
//				'ItemPedido.Pedido.Obra.Cidade',
//				'ItemPedido.Pedido.User',
//				'ItemPedido.Pedido.Cliente',
//				'ItemPedido.Produto'
//			);
		//$options['recursive']=$options['extra']['recursive']=-1;
		$this->Vale->recursive=-1;


		$options['order']=$this->Vale->order = 'Cliente.id,Obra.id, Produto.id ASC';
		$options['fields']=array(
				'Vale.*','ItemPedido.*','Pedido.*','Produto.*','Cliente.*','Obra.*','User.*','Cidade.*'
			);
		$options['extra']['joins']=$options['joins']=array(
				array(
					'table' => $this->Vale->ItemPedido->table,
					'alias' => $this->Vale->ItemPedido->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Vale->ItemPedido->alias.'.id = Vale.item_pedido_id'
					)
				),
				array(
					'table' => $this->Vale->ItemPedido->Produto->table,
					'alias' => $this->Vale->ItemPedido->Produto->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Vale->ItemPedido->alias.'.produto_id = '.$this->Vale->ItemPedido->Produto->alias.'.id'
					)
				),
				array(
					'table' => $this->Pedido->table,
					'alias' => $this->Pedido->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->alias.'.id = '.$this->Vale->ItemPedido->alias.'.pedido_id'
					)
				),
				array(
					'table' => $this->Pedido->Cliente->table,
					'alias' => $this->Pedido->Cliente->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->Cliente->alias.'.id = '.$this->Pedido->alias.'.cliente_id'
					)
				),
				array(
					'table' => $this->Pedido->Obra->table,
					'alias' => $this->Pedido->Obra->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->Obra->alias.'.id = '.$this->Pedido->alias.'.obra_id'
					)
				),
				array(
					'table' => $this->Cidade->table,
					'alias' => $this->Cidade->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Cidade->alias.'.id = '.$this->Pedido->Obra->alias.'.cidade_id'
					)
				),
				array(
					'table' => $this->Pedido->User->table,
					'alias' => $this->Pedido->User->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->User->alias.'.id = '.$this->Pedido->alias.'.user_id'
					)
				)
			);
		//parent::gerarFiltros();
		$options['limit'] =  600;

		//  if($_SERVER['REMOTE_ADDR']=='187.22.184.31'){

		// 	pr($this->paginate);
		// // 	$this->paginate['maxLimit'] = 500;
		//  }
		$options['maxLimit'] =300;

		parent::index($options);
		/*
		$rows= $this->Vale->find('all',array(
			'fields'=>array(
				'Vale.*','ItemPedido.*','Pedido.*','Produto.*','Cliente.*','Obra.*','User.*'
			),
			'conditions'=>$this->conditions,
			'joins'=>array(
				array(
					'table' => $this->Vale->ItemPedido->table,
					'alias' => $this->Vale->ItemPedido->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Vale->ItemPedido->alias.'.id = Vale.item_pedido_id'
					)
				),
				array(
					'table' => $this->Vale->ItemPedido->Produto->table,
					'alias' => $this->Vale->ItemPedido->Produto->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Vale->ItemPedido->alias.'.produto_id = '.$this->Vale->ItemPedido->Produto->alias.'.id'
					)
				),
				array(
					'table' => $this->Pedido->table,
					'alias' => $this->Pedido->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->alias.'.id = '.$this->Vale->ItemPedido->alias.'.pedido_id'
					)
				),
				array(
					'table' => $this->Pedido->Cliente->table,
					'alias' => $this->Pedido->Cliente->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->Cliente->alias.'.id = '.$this->Pedido->alias.'.cliente_id'
					)
				),
				array(
					'table' => $this->Pedido->Obra->table,
					'alias' => $this->Pedido->Obra->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->Obra->alias.'.id = '.$this->Pedido->alias.'.obra_id'
					)
				),
				array(
					'table' => $this->Pedido->User->table,
					'alias' => $this->Pedido->User->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Pedido->User->alias.'.id = '.$this->Pedido->alias.'.user_id'
					)
				)
			)
		));
		*/
		$programacoes=$this->Programacao->find('all',array('conditions'=>array('data'=>$data)));
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') );
		$periodos = $this->Pedido->Periodo->find('list');
		$this->set(compact('rows','data','programacoes','periodo','periodos'));
		
		
	}
	public function indexOLD($data=null,$periodo=null) {
		$this->limit = 999;
		if(empty($data)){
			$data=date('Y-m-d');
		}else{
			$data=datePhpToMysql($data);
		}
		
		if(!empty($periodo)){
			$this->conditions['Pedido.periodo_id']=$periodo;
		}
		$this->conditions['Pedido.status !=']=2;
		$this->conditions['DATE_FORMAT(Pedido.data_entrega,"%Y-%m-%d")']=$data;
		$this->model='Pedido';
		
		$this->Pedido->Behaviors->attach('Containable');
		$this->Pedido->contain(
				'User',
				'Cliente',
				'Obra.Cidade',
				'ItemPedido.Produto'
			);
		$options['recursive']=$options['extra']['recursive']=1;
		$this->Pedido->order = array(
			'Cliente.id'=>'ASC',
			'Obra.id'=>'ASC'
			
		);
		parent::index($options);
	
		$programacoes=$this->Programacao->find('all',array('conditions'=>array('data'=>$data)));
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') );
		$periodos = $this->Pedido->Periodo->find('list');
		$this->set(compact('data','programacoes','periodo','periodos'));
		
		
	}
	
	public function active(){
		$this->autoRender = false;
		$programacao = $this->Programacao->find('all',array(
			'conditions'=>array(
				'Programacao.data'=>  datePhpToMysql($this->request->data['Programacao']['data']),
				'Programacao.item_pedido_id'=>  datePhpToMysql($this->request->data['Programacao']['item_pedido_id'])
			)
		));
		
		if(!empty($programacao)){
			$this->Programacao->id = $programacao[0]['Programacao']['id'] ;
			//pr($programacao);
		}
		$retorno=($this->Programacao->save( $this->request->data ))? true : false;
		
		echo json_encode($retorno);
	}
	
	public function desactive(){
		$this->autoRender = false;
		$programacao = $this->Programacao->find('all',array(
			'conditions'=>array(
				'Programacao.data' => datePhpToMysql($this->request->data['Programacao']['data']),
				'Programacao.item_pedido_id' => datePhpToMysql($this->request->data['Programacao']['item_pedido_id'])
			)
		));
		//pr($programacao);
		if(!empty($programacao)){
			$this->Programacao->id = $programacao[0]['Programacao']['id'] ;
		
			$retorno = ($this->Programacao->delete( $this->Programacao->id ))? true : false;
		}else $retorno=false;
		echo json_encode($retorno);
	}
	
	//
	//ADMIN
	//
	public function admin_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function admin_edit($id = null) {
		parent::edit($id);
	}

	public function admin_del($id) {
		parent::del($id);
	}
	public function admin_active(){	$this->setAction('active');}
	public function admin_desactive(){	$this->setAction('desactive');}
	//
	//RH
	//
	public function rh_index() {$this->setAction('admin_index');}
	public function rh_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function rh_del($id) {	$this->setAction('admin_del', $id);}
	public function rh_add() {	$this->setAction('rh_edit');}
	//
	//Programacao
	//
	public function programacao_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function programacao_edit($id = null) {
		parent::edit($id);
	}
	public function programacao_del($id) {
		parent::del($id);
	}
	public function programacao_active(){	$this->setAction('active');}
	public function programacao_desactive(){	$this->setAction('desactive');}
	//
	//Programacao
	//
	public function programacao1_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function programacao1_edit($id = null) {
		parent::edit($id);
	}
	public function programacao1_del($id) {
		parent::del($id);
	}
	public function programacao1_active(){	$this->setAction('active');}
	public function programacao1_desactive(){	$this->setAction('desactive');}
	//
	//Programacao
	//
	public function programacao2_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function programacao2_edit($id = null) {
		parent::edit($id);
	}
	public function programacao2_del($id) {
		parent::del($id);
	}
	public function programacao2_active(){	$this->setAction('active');}
	public function programacao2_desactive(){	$this->setAction('desactive');}
	//
	//Vendedor
	//
	public function vendedor_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function vendedor_edit($id = null) {
		parent::edit($id);
	}
	public function vendedor_del($id) {
		parent::del($id);
	}
	public function vendedor_active(){	$this->setAction('active');}
	public function vendedor_desactive(){	$this->setAction('desactive');}
	
	
	//
	//pedidos2
	//
	public function pedidos2_index($data = null,$periodo=null){$this->setAction('index',$data,$periodo); }
	public function pedidos2_edit($id = null) {
		parent::edit($id);
	}

	public function pedidos2_del($id) {
		parent::del($id);
	}
	public function pedidos2_active(){	$this->setAction('active');}
	public function pedidos2_desactive(){	$this->setAction('desactive');}

}

?>
