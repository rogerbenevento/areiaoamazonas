<?php

class ItemPedidosController extends AppController {

	public $model = 'ItemPedido';
	public $uses = array('ItemPedido', 'Pedido', 'Produto', 'User', 'elements','Vale');

	
	function tbitenspedido() {
		$this->layout = '';
		$this->set('unidades',$this->ItemPedido->unidade);
	}
	/**
	 * valorTotal() - Action que retorna o valor total do Pedido do Cliente
	 * @access public
	 */
	public function valorTotal() {
		$this->autoRender = false;
		$this->Session->check('carrinho');
		$data['valorTotal'] = moedaBr($this->ItemPedido->valorTotal());
		$data['valorArredondamento'] = moedaBr($this->Session->read('arredondamento') * -1);
		echo json_encode($data);
	}

	public function valorItemTotal($indice) {
		return $this->Session->read("carrinho.{$indice}.valor_garantia") +
			   ( $this->Session->read("carrinho.{$indice}.quantidade") *
			   $this->Session->read("carrinho.{$indice}.valor") *
			   (1 - ($this->Session->read("carrinho.{$indice}.desconto") / 100))
			   );
	}

	public function aplicarDesconto() {
		$this->autoRender = false;

		$data['autorizado'] = false;
		$data['retorno'] = 'ok';

		$data['desconto'] = $this->ItemPedido->recebeFloatBeforeSave($this->request->data['desconto']);
		$item = $this->request->data['item'];

		// Faz os calculos e aplica o desconto no produto 
		$data['produto_id'] = $this->Session->read('carrinho.' . $item . '.produto_id');
		$data['condicao'] = $this->ProdutoDesconto->getCondicaoProdDesconto($_SESSION['carrinho'][$item]['condicao']);
		//$data['valor_unitario'] = $_SESSION['carrinho'][$item]['valor'] - (($_SESSION['carrinho'][$item]['valor'] * $data['desconto']) / 100);
		$data['valor_unitario'] = number_format($this->Session->read('carrinho.' . $item . '.valor') * (1 - ($data['desconto'] / 100)), 2);
		$data['quantidade'] = $_SESSION['carrinho'][$item]['quantidade'];
		$data['valor'] = $data['valor_unitario'] * $data['quantidade'];
		$data['item'] = $item;

		$desconto = $this->ProdutoDesconto->discountByLevel($data['produto_id'], 'v');

		$descontoVendedor = $desconto[$data['condicao']];

		$desconto = $this->ProdutoDesconto->discountByLevel($data['produto_id'], 'g');
		$descontoGerente = $desconto[$data['condicao']];

		if ($data['desconto'] > $descontoGerente):
			$data['retorno'] = 'valorNaoPermitido';
		elseif ($this->Session->read('Auth.User.nivel') != 'gerente'):
			if ($data['desconto'] <= $descontoVendedor):
				$data['autorizado'] = true;
			else:
				if ($this->data['desconto_user'] == '' || $this->request->data['desconto_pass'] == ''):
					$data['retorno'] = 'requerLogin';
				else:
					if (!$this->User->checkIn($this->request->data['desconto_user'], $this->request->data['desconto_pass'], 'gerente')):
						$data['retorno'] = 'loginIncorreto';
					else: $data['autorizado'] = true;
					endif;
				endif;
			endif;
		elseif ($this->Session->read('Auth.User.nivel') == 'gerente'):
			$data['autorizado'] = true;
		endif;

		if ($data['autorizado']):
			$this->Session->delete("arredondamento");
			$this->Session->write("carrinho.{$item}.desconto", $data['desconto']);
			$data['desconto'] = number_format($data['desconto'], 2);
			$data['valor'] = moedaBR($this->valorItemTotal($item));
		endif;
		echo json_encode($data);
	}

	public function aplicar_garantia() {
		//$this->layout = '';
		$this->autoRender = false;
		$valor_garantia = number_format($this->request->data['valor'], 2);
		$data['indice'] = $this->request->data['item'];
		//$this->Session->read('carrinho');
		$this->Session->write("carrinho.{$data['indice']}.valor_garantia", $valor_garantia);
		$this->Session->write('carrinho.' . $data['indice'] . '.periodo_garantia', $this->request->data['periodo']);
		//pr($this->Session->read('carrinho'));
		//echo $this->valorItemTotal($data['indice']);
		$this->Session->delete("arredondamento");
		$data['valorgarantia'] = moedaBR($valor_garantia);
		$data['periodogarantia'] = $this->request->data['periodo'] . ' ano(s)';
		$data['valorTotal'] = moedaBR($this->valorItemTotal($data['indice']));

		echo json_encode($data);
	}

	public function mais_um() {
		$this->autoRender = false;
		$this->ProdutoLoja->recursive = -1;
		$data['indice'] = $this->request->data['indice'];
		$data['produto_loja_id'] = $this->Session->read('carrinho.' . $data['indice'] . '.produto_loja_id');
		$data['quantidade'] = ( $this->Session->read('carrinho.' . $data['indice'] . '.quantidade') + 1 );

		$pl = $this->ProdutoLoja->find('first', array("conditions" => array('ProdutoLoja.id' => $data['produto_loja_id'])));

		$this->Session->delete("arredondamento");

		if ($pl['ProdutoLoja']['quantidade'] < $data['quantidade'])
			$data['retorno'] = false;
		else {
			$this->Session->write("carrinho.{$data['indice']}.quantidade", $data['quantidade']);
			$data['retorno'] = true;
		}
		$data['valor_total'] = moedaBR($this->valorItemTotal($data['indice']));
		echo json_encode($data);
	}

	/**
	 * Subtrai uma unidade no item do pedido
	 */
	public function menos_um() {
		$this->autoRender = false;

		$indice = $this->request->data['indice'];
		//pr($this->Session->read('carrinho'));
		if ($this->Session->read('carrinho.' . $indice . '.quantidade') <= 1) {
			$_SESSION['carrinho'][$indice]['quantidade'] = 1;
		} else {
			$_SESSION['carrinho'][$indice]['quantidade'] -= 1;
		}
		//pr($_SESSION['carrinho'][$indice]);
		$data['indice'] = $indice;
		$data['quantidade'] = $_SESSION['carrinho'][$indice]['quantidade'];
		$data['valor_total'] = moedaBR($this->valorItemTotal($data['indice']));
		$this->Session->delete("arredondamento");

		echo json_encode($data);
	}

	private function getIndice() {
		$indice = 0;
		if ($this->Session->check('carrinho'))
			foreach ($this->Session->read('carrinho') as $key => $value)
				$indice = $key + 1;
		return $indice;
	}

	public function by_pedido($so_devolvidos = 0) {
		$this->layout = '';
		$conditions = array('pedido_id' => $this->request->data['pedido_id']);
		if ($so_devolvidos)
			$conditions['devolvido'] = '0';
		$itens = $this->ItemPedido->find('all', array('conditions' => $conditions));
		$this->set('itens', $itens);
		$this->set('model', $this->ItemPedido);
	}


	public function admin_by_item($id,$vale){
		$this->layout = '';
		//$conditions = array('pedido_id' => $this->request->data['pedido_id']);
		$item = $this->ItemPedido->findById($id);
		$this->set('item', $item);
		$this->set('vale', $vale);
	}

	public function admin_by_item_atualizar(){
		$this->layout = '';
		$this->autoRender = false;

		// $_REQUEST['data']['id'];
		// $_REQUEST['data']['vale'];
		// $_REQUEST['data']['quantidade'];
		// $_REQUEST['data']['valor_total'];
		// $_REQUEST['data']['motivo'];


		$this->ItemPedido->save([
			'id'=>$_REQUEST['data']['id'],
			'quantidade_original'=>$_REQUEST['data']['quantidade'],
			'valor_total'=>$_REQUEST['data']['valor_total'],
			'motivo'=>$_REQUEST['data']['motivo']
		]);


		$this->Vale->save([
			'id'=>$_REQUEST['data']['vale'],
			'motivo'=>$_REQUEST['data']['motivo']
		]);

		return true;
		// echo "<pre>";
		// print_r($_REQUEST);
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
			$indice = self::getIndice();
			@$this->Session->write('carrinho.' . $indice, array(
				'indice' => $indice,
				'produto_id' => $this->request->data['produto_id'],
				'produto' => $this->request->data['produto'],
				'quantidade' => $this->request->data['quantidade'],
				'unidade' => $this->request->data['unidade'],
				'valor'=>  moedaBD($this->request->data['valor']),
				'valor_total'=> moedaBD($this->request->data['valor']) * $this->request->data['quantidade'],
				'desconto'=>0				    
			));
			$ret['carrinho'] = $this->Session->read('carrinho');
			echo json_encode($ret);
		}
	}

	public function admin_aplicar_desconto() {
		$this->layout = '';
		//$desconto = $this->params->query['desconto'] / 100;
		$descontoReal = $this->params->query['desconto'];
		$_SESSION['desconto'] = $this->params->query['desconto'];

		if (!empty($_SESSION['carrinho'])) {
			$itens = $_SESSION['carrinho'];
			unset($_SESSION['carrinho']);


			foreach ($itens as $indice => $item) {
				$_SESSION['carrinho'][$indice] = $item;
				$_SESSION['carrinho'][$indice]['preco'] = $item['preco'];
				$_SESSION['carrinho'][$indice]['desconto'] = $descontoReal;
			}
			$this->set('itens', $_SESSION['carrinho']);
		}
	}

	public function admin_del() {
		$this->autoRender = false;
		if ($this->Session->check('carrinho.' . $this->request->data['indice'])) {
			$this->Session->delete('carrinho.' . $this->request->data['indice']);
			echo true;
		}else
			echo false;
	}

	public function admin_aplicarDesconto() {$this->setAction('aplicarDesconto');	}
	public function admin_valorTotal() {	$this->setAction('valorTotal');	}
	public function admin_menos_um() {	$this->setAction('menos_um');	}
	public function admin_mais_um() {	$this->setAction('mais_um');}
	public function admin_aplicar_garantia() {	$this->setAction('aplicar_garantia');	}
	public function admin_tbitenspedido() {	$this->setAction('tbitenspedido');	}
	public function admin_getIndice() {$this->setAction("getIndice");	}
	public function admin_by_pedido($so_devolvidos = 0) {$this->setAction('by_pedido', $so_devolvidos);	}

	//
	// Vendedor
	//
	public function vendedor_add() {$this->setAction('admin_add');	}
	public function vendedor_del() {$this->setAction('admin_del');	}
	public function vendedor_aplicarDesconto() {$this->setAction('admin_aplicarDesconto');	}
	public function vendedor_valorTotal() {$this->setAction('valorTotal');	}
	public function vendedor_menos_um() {$this->setAction('menos_um');	}
	public function vendedor_mais_um() {$this->setAction('mais_um');}
	public function vendedor_aplicar_garantia() {$this->setAction('aplicar_garantia');}
	public function vendedor_tbitenspedido() {$this->setAction('tbitenspedido');}
	public function vendedor_getIndice(){$this->setAction("getIndice");}
	public function vendedor_by_pedido($so_devolvidos = 0) {$this->setAction('by_pedido', $so_devolvidos);}

	//
	//GERENTE
	//
    public function gerente_add() {$this->setAction('admin_add');}
	public function gerente_del(){	$this->setAction('admin_del');}
	public function gerente_aplicarDesconto(){	$this->setAction('admin_aplicarDesconto');}
	public function gerente_valorTotal(){$this->setAction('valorTotal');}
	public function gerente_menos_um() {$this->setAction('menos_um');}
	public function gerente_mais_um() {	$this->setAction('mais_um');}
	public function gerente_aplicar_garantia() {$this->setAction('aplicar_garantia');}
	public function gerente_tbitenspedido() {$this->setAction('tbitenspedido');}
	public function gerente_getIndice() {$this->setAction("getIndice");}
	public function gerente_by_pedido($so_devolvidos = 0) {	$this->setAction('by_pedido', $so_devolvidos);}

	//
	//  FINANCEIRO
	//
    public function financeiro_add() {$this->setAction('admin_add');	}
	public function financeiro_del() {$this->setAction('admin_del');	}
	public function financeiro_aplicarDesconto() {$this->setAction('admin_aplicarDesconto');	}
	public function financeiro_valorTotal() {$this->setAction('valorTotal');	}
	public function financeiro_menos_um() {$this->setAction('menos_um');	}
	public function financeiro_mais_um() {$this->setAction('mais_um');}
	public function financeiro_aplicar_garantia() {$this->setAction('aplicar_garantia');}
	public function financeiro_tbitenspedido() {$this->setAction('tbitenspedido');}
	public function financeiro_getIndice(){$this->setAction("getIndice");}
	public function financeiro_by_pedido($so_devolvidos = 0) {$this->setAction('by_pedido', $so_devolvidos);}
	//
	//  FINANCEIRO
	//
    public function pedidos1_add() {$this->setAction('admin_add');	}
	public function pedidos1_del() {$this->setAction('admin_del');	}
	public function pedidos1_aplicarDesconto() {$this->setAction('admin_aplicarDesconto');	}
	public function pedidos1_valorTotal() {$this->setAction('valorTotal');	}
	public function pedidos1_menos_um() {$this->setAction('menos_um');	}
	public function pedidos1_mais_um() {$this->setAction('mais_um');}
	public function pedidos1_aplicar_garantia() {$this->setAction('aplicar_garantia');}
	public function pedidos1_tbitenspedido() {$this->setAction('tbitenspedido');}
	public function pedidos1_getIndice(){$this->setAction("getIndice");}
	public function pedidos1_by_pedido($so_devolvidos = 0) {$this->setAction('by_pedido', $so_devolvidos);}
	//
	//  FINANCEIRO
	//
    public function pedidos2_add() {$this->setAction('admin_add');	}
	public function pedidos2_del() {$this->setAction('admin_del');	}
	public function pedidos2_aplicarDesconto() {$this->setAction('admin_aplicarDesconto');	}
	public function pedidos2_valorTotal() {$this->setAction('valorTotal');	}
	public function pedidos2_menos_um() {$this->setAction('menos_um');	}
	public function pedidos2_mais_um() {$this->setAction('mais_um');}
	public function pedidos2_aplicar_garantia() {$this->setAction('aplicar_garantia');}
	public function pedidos2_tbitenspedido() {$this->setAction('tbitenspedido');}
	public function pedidos2_getIndice(){$this->setAction("getIndice");}
	public function pedidos2_by_pedido($so_devolvidos = 0) {$this->setAction('by_pedido', $so_devolvidos);}

}

//class