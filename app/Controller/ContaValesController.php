<?php

class ContaValesController extends AppController {

	public $model = 'ContaVale';
	public $uses = array('ContaVale', 'Conta', 'Vale', 'User', 'elements');

	public function tbvales(){}		
		
	
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
			$indice = $this->request->data['vale_id'];
			$this->Session->write('vales.' . $indice, array(
					'indice' => $indice,
					'vale_id' => $this->request->data['vale_id'],
					'nota_fiscal' => $this->request->data['nota_fiscal'],
					'nota_fiscal_emissao'=>$this->request->data['nota_fiscal_emissao'],
					'quantidade'=>$this->request->data['quantidade'],
					'valor_total'=>$this->request->data['valor_total'],
					'cliente' => $this->request->data['cliente'],
					'obra' => $this->request->data['obra'],
					'pedido' =>  $this->request->data['pedido']
							    
			));
			$ret['vales'] = $this->Session->read('vales');
			echo json_encode($ret);
		}
	}

	
	public function admin_add_all() {
		$this->autoRender = false;
		$ret['error'] = false;
		if ($this->request->is('post')) {
			$ret['post'] =	$this->request->data;
			



			$conditions= ['Vale.fornecedor_id'=>$this->request->data['fornecedor']];


			$array_conditions = [];
		
			if($this->request->data['inicio']!='--'){
				$array_conditions = ['Vale.created >=' => $this->request->data['inicio'].' 00:00:00']; 
			}

			if($this->request->data['fim']!='--'){
				$array_conditions = ['Vale.created <=' => $this->request->data['fim'].' 23:59:59']; 
			}


			$conditions = array_merge($conditions, $array_conditions);

			

			// if($_SERVER['REMOTE_ADDR']=='201.46.19.114'){
			// 	Configure::write('debug',2);

			// 	pr($conditions);
			// }

			$vales = $this->Vale->find('all',['conditions'=>$conditions]);
			
			$ret['conditions'] = $conditions;
			$ret['vales'] =	$vales;


			//$this->Session->del('vales');
			$valor = 0;
			$sessionVales = '';
		 	foreach ($vales as $key => $value) {
		 		$indice = $value['Vale']['id'];

		 		$ar = [
					'indice' => $value['Vale']['id'],
					'vale_id' => $value['Vale']['id'],
					'nota_fiscal' => $value['Vale']['nota_fiscal'],
					'nota_fiscal_emissao'=>$value['Vale']['created'],
					'quantidade'=>$value['ItemPedido']['quantidade_original'],
					'valor_total'=>$value['ItemPedido']['valor_total'],
					'pedido' =>  $value['ItemPedido']['pedido_id']
				];

				$sessionVales[] = $ar;

				$valor += $value['ItemPedido']['valor_total'];
				$this->Session->write('vales.' . $indice, $ar);
			}


		 }

		$ret['sessionvales'] = $sessionVales;
		$ret['vales'] = $this->Session->read('vales');
		$ret['valor'] = $valor;
		echo json_encode($ret);
	}


	public function admin_del() {
		$this->autoRender = false;
		if ($this->Session->check('vales.' . $this->request->data['indice'])) {
			$this->Session->delete('vales.' . $this->request->data['indice']);
			//$this->Session->read('vales');
			echo true;
		}else
			echo false;
	}
	public function admin_tbvales() {
		$this->setAction("tbvales");
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
	//
	// Vendedor
	//
	public function conta_add() {
		$this->setAction('admin_add');
	}

	public function conta_del() {
		$this->setAction('admin_del');
	}

	public function conta_tbvales() {
		$this->setAction("tbvales");
	}
	public function conta_getIndice() {
		$this->setAction("getIndice");
	}
	public function conta_add_all() {
		$this->setAction("admin_add_all");
	}


	

}//class