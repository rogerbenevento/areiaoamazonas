<?php

class ContaPedidosController extends AppController {

	public $model = 'ContaPedido';
	public $uses = array('ContaPedido', 'Conta', 'Pedido', 'User', 'elements');

	public function tbpedidos(){}		
		
	
	//
	// ADMIN
	//
	/**
	 * add() - Adiciona um item ao carrinho, caso o item já esteja presente o sistema irá somar quantidade e valores
	 * O sistema irá checar se a quantidade de produtos solicitada está em estoque ou se haverá necessidade de uma OS
	 */
	public function admin_add() {
		$this->autoRender = false;
		//pr($this->request->data);
		if ($this->request->is('post')) {
			$ret['error'] = false;
			$indice = $this->request->data['pedido_id'];
			$this->Session->write('pedidos.' . $indice, array(
					'indice' => $indice,
					'pedido_id' => $this->request->data['pedido_id'],
					'cliente' => $this->request->data['cliente'],
					'obra' => $this->request->data['obra']
							    
			));
			$ret['pedidos'] = $this->Session->read('pedidos');
			echo json_encode($ret);
		}
	}


	public function admin_del() {
		$this->autoRender = false;
		if ($this->Session->check('pedidos.' . $this->request->data['indice'])) {
			$this->Session->delete('pedidos.' . $this->request->data['indice']);
			echo true;
		}else
			echo false;
	}
	public function admin_tbpedidos() {
		$this->setAction("tbpedidos");
	}
	public function admin_getIndice() {
		$this->setAction("getIndice");
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


}//class