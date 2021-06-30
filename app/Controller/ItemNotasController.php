<?php

class ItemNotasController extends AppController {

	public $model = 'ItemNota';
	public $uses = array('ItemNota', 'Nota', 'Produto', 'User', 'elements');

	public function tbpedidos(){$this->loadModel('ItemPedido');$this->set('unidades', $this->ItemPedido->unidade);}		
	public function tbvales(){}		
		
	
	//
	// ADMIN
	//
	public function by_cliente($id) {
		$this->layout = '';
		$this->loadModel('Pedido');
		$this->Pedido->Behaviors->load('Containable');
		$this->Pedido->contain(array(
			'Cliente',
			'Obra',
			'ItemPedido.Produto'
			));
		$conditions = array('Pedido.cliente_id' => $id);

		$date_start = $_GET['date_start'];
		$date_end = $_GET['date_end'];

		if(isset($date_start) && $date_start != ''){

			$date_start =  datePhpToMysql($date_start);
			$conditions['Pedido.data_entrega >='] = $date_start;
		}

		if(isset($date_end) && $date_end != ''){

			$date_end =  datePhpToMysql($date_end);
			$conditions['Pedido.data_entrega <='] = $date_end;

		}

		// if($_SERVER['REMOTE_ADDR']=='138.94.23.196'){
		// 	Configure::write('debug',2);
		// 	pr($date_start);
		// 	pr($date_end);
		// 	pr($conditions);
		// }
		$joins=array();
		
		//if(!empty( $this->request->data['conta'] ) ){
		
			//$this->loadModel('ContaPedido');
			
			$joins=array(
				array(
					'table'=>$this->ItemNota->table,
					'alias'=>$this->ItemNota->alias,
					'type'=>'LEFT',
					'conditions'=>array("{$this->ItemNota->alias}.pedido_id = Pedido.id")
				)
			);
			$conditions[]="{$this->ItemNota->alias}.pedido_id IS NULL ";
			
		//}
		
		$pedidos = $this->Pedido->find('all', array('conditions' => $conditions,'joins'=>$joins));
		//pr($this->SqlDump());
		$this->loadModel('Produto');
		$produtos = $this->Produto->find('list');
		$this->set(compact('pedidos','produtos'));
		$this->set('unidades', $this->Pedido->ItemPedido->unidade);
		
	}
	
	/**
	 * add() - Adiciona um item ao carrinho, caso o item já esteja presente o sistema irá somar quantidade e valores
	 * O sistema irá checar se a quantidade de produtos solicitada está em estoque ou se haverá necessidade de uma OS
	 */
	public function admin_add() {
		$this->autoRender = false;
		//pr($this->request->data);
		if ($this->request->is('post')) {
			$ret['error'] = false;
//			$indice = $this->request->data['pedido_id'];
//			if(!empty($this->request->data['item_pedido_id'])){
//				$indice = $indice.'_' . $this->request->data['item_pedido_id'];
//			}
			$indice =0; 
			foreach($this->Session->read('pedidos') as $i){
				$indice = $i['indice']+1;
			}
			
			$this->Session->write('pedidos.' . $indice, array(
				'indice' => $indice,
				'pedido_id' => $this->request->data['pedido_id'],
				'cliente' => $this->request->data['cliente'],
				'obra' => $this->request->data['obra'],
				'produto_id' => $this->request->data['produto_id'],
				'data_entrega' => $this->request->data['data_entrega'],
				'material' => $this->request->data['material'],
				'quantidade' => $this->request->data['quantidade'],
				'unidade' => $this->request->data['unidade'],
				'valor_unitario' => $this->request->data['valor_unitario'],
				'situacao_tributaria' => $this->request->data['situacao_tributaria'],
				'imprimir' => 1,
							    
			));
			$ret['pedidos'] = $this->Session->read('pedidos');
			echo json_encode($ret);
		}
	}

	
	public function admin_del() {
		$this->autoRender = false;
		if ($this->Session->check('pedidos.' . $this->request->data['indice'])) {
			$this->Session->delete('pedidos.' . $this->request->data['indice']);
			//pr($_SESSION['pedidos']);
			echo true;
		}else
			echo false;
	}
	public function admin_habilitar(){
		$this->autoRender = false;
		if ($this->Session->check('pedidos.' . $this->request->data['indice'])) {
			$_SESSION['pedidos'][$this->request->data['indice']]['imprimir']=1;
			echo true;
		}else
			echo false;
	}
	public function admin_desabilitar(){
		$this->autoRender = false;
		if ($this->Session->check('pedidos.' . $this->request->data['indice'])) {
			$_SESSION['pedidos'][$this->request->data['indice']]['imprimir']=0;
			echo true;
		}else
			echo false;
	}
	
	public function admin_tbpedidos() {
		$this->setAction("tbpedidos");
	}
	public function admin_tbvales() {
		$this->setAction("tbvales");
	}
	public function admin_getIndice() {
		$this->setAction("getIndice");
	}
	public function admin_by_cliente($id){
		$this->setAction('by_cliente',$id);
	}


	public function conta_tbpedidos() {
		$this->setAction("tbpedidos");
	}
	public function conta_tbvales() {
		$this->setAction("tbvales");
	}
	public function conta_getIndice() {
		$this->setAction("getIndice");
	}
	public function conta_by_cliente($id){
		$this->setAction('by_cliente',$id);
	}

	//
	// Vendedor
	//
	public function vendedor_add() {
		$this->setAction('admin_add');
	}

	public function vendedor_del() {
		$this->setAction('admin_del');
	}

	public function vendedor_getIndice() {
		$this->setAction("getIndice");
	}

	//
	// NOTAS
	//
	public function nota_add() {
		$this->setAction('admin_add');
	}

	public function nota_del() {
		$this->setAction('admin_del');
	}
	public function nota_habilitar() {
		$this->setAction('admin_habilitar');
	}
	public function nota_desabilitar() {
		$this->setAction('admin_desabilitar');
	}

	public function nota_getIndice() {
		$this->setAction("getIndice");
	}
	public function nota_tbpedidos() {
		$this->setAction("tbpedidos");
	}
	public function nota_by_cliente($id){
		$this->setAction('by_cliente',$id);
	}
}//class