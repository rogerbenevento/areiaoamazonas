<?php

class ItemPedido extends AppModel {

	public $useTable = 'itens_pedidos';
	public $validate = array(
	    'pedido_id' => array('rule' => 'notEmpty', 'message' => 'Informe a qual pedido pertence'),
	    'produto_id' => array('rule' => 'notEmpty', 'message' => 'Informe o produto'),
//	    'quantidade' => array('rule' => 'notEmpty', 'message' => 'Informe a quantidade')
	);
	public $belongsTo = array('Produto', 'Pedido');
	public $hasOne = array(
	    'Vale'=>array('className'=>'Vale','foreignKey'=>'item_pedido_id')
	);
	
	public $unidade = array(
		1=>'m3',
		2=>'Ton.',
		3=>'sc',
		4=>'jg',
		5=>'m2',
		6=>'UN',
		7=>'BB',
		8=>'PAR'
		);
	
	
	
	public function valorTotal() {
		$valorTotal = 0;
		//pr($_SESSION['carrinho']);
		if (count($_SESSION['carrinho'])) {
			foreach ($_SESSION['carrinho'] as $item) {
				$valor = ($item['desconto'] != 0) ?
					   ( $item['valor'] - ( ( $item['valor'] * $item['desconto'] ) / 100 ) ) * $item['quantidade'] : $valor = ( $item['valor'] * $item['quantidade'] );
				
				if(empty($item['valor_garantia']))$item['valor_garantia']=0;
				$valorTotal += ($valor + $item['valor_garantia']);
			}//foreach
			//Retira cupom de desconto
			if (!empty($_SESSION['desconto']))
				$valorTotal -= $_SESSION['desconto']['valor_cupom'];
			//Retira valor do arredondamento
			if (isset($_SESSION['arredondamento']))
				$valorTotal -= $_SESSION['arredondamento'];
		}

		return round($valorTotal, 2);
	}

	/**
	 * baixar() - Método que fará a baixa do estoque, ele irá correr todos os itens e retirar sua quantidade do estoque
	 * caso não haja a quantidade em estoque ele retornará false
	 * @param integer $pedido - ID do pedido
	 * @return boolean - Verdadeiro se todos os itens forem baixados
	 */
	public function baixar($pedido) {
		$itens = $this->findAllByPedidoId($pedido);

		if (count($itens)) {
			foreach ($itens as $item) {
				if (!$this->Produto->alterarEstoque($item['PedidoItem']['produto_id'], $item['PedidoItem']['quantidade'], 'R'))
					return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * remover() - Remove um item do pedido
	 * @param array $item - Id do item do pedido
	 * @return boolean
	 */
	public function remover($itens) {
//		$ProdutoLoja = ClassRegistry::init('ProdutoLoja');
//		//pr($itens);
//		foreach ($itens as $item) {
//			//echo $item['produto_loja_id'].'<br>';
//			if (!$ProdutoLoja->stockChange($item['produto_loja_id'], $item['quantidade'], 'I'))
//				return false;
//
//			if (!empty($item['transferencias_id'])) {
//				//pr($item);
//				$Transferencia = ClassRegistry::init('Transferencia');
//
//				if ($Transferencia->cancelar($item['transferencias_id']) != true)
//					return false;
//			}
//		}
		//return false;
		return true;
	}

	/**
	 * montarCarrinho() - Recupera a sessão de carrinho de um determinado pedido
	 * @param integer $pedido - ID do pedido
	 * @return Sessão de Carrinho
	 */
	public function montarCarrinho($pedido = null, $limpar = true,$data_full = false) {
		if ($limpar and isset($_SESSION['carrinho'])) {
			unset($_SESSION['carrinho']);
			$_SESSION['carrinho'] = Array();
		}
		if (!empty($pedido)) {
			$itens = $this->findAllByPedidoId($pedido);
			$indice = 0;
			if(!empty($itens))
			foreach ($itens as $item) {				
				$_SESSION['carrinho'][$indice] = array(
				    'indice' => $indice,
				    'id' => $item['ItemPedido']['id'],
				    //'vale_id' => $item['Vale']['id'],
				    'produto_id' => $item['Produto']['id'],
				    'produto' => $item['Produto']['nome'],
				    'valor' => $item['ItemPedido']['valor'],
				    'valor_total' => $item['ItemPedido']['valor_total'],
				    'quantidade' => $item['ItemPedido']['quantidade'],
				    'unidade' => $item['ItemPedido']['unidade']
				);
				if($data_full){
					$_SESSION['carrinho'][$indice]['pedido_id'] = $item['ItemPedido']['pedido_id'];
					$_SESSION['carrinho'][$indice]['pago'] = $item['ItemPedido']['pago'];
					$_SESSION['carrinho'][$indice]['motivo'] = $item['ItemPedido']['motivo'];
				}
				$indice++;
			}//foreach
		
			
		}//if pedido
	}

//montarCarrinho

	/**
	 * incluir() - Inclui os itens do pedido na base de dados
	 * Efetua as transferencias necessárias e também baixa os itens do estoque
	 * @param integer $pedido - ID do pedido
	 * @param array $itens - Array dos itens do pedido
	 */
	public function incluir($itens, $pedido) {

		$Vale = ClassRegistry::init('Vale');
		
		//$pedido = $Pedido->find('first', array('conditions' => array('Pedido.id' => $pedido)));
		#pr($itens);
		foreach ($itens as $item) {
			$data= array(
			    'pedido_id' => $pedido
			    ,'produto_id' => $item['produto_id']			    
			    
				   //'valor' => $item['valor'],
			);
			if(!empty($item['quantidade']))
				$data['quantidade'] = $item['quantidade'];
			#pr($data);
			//$this->save($data);
			if ($this->save($data)) {
				$Vale->id = null;
				if($Vale->save(array('item_pedido_id' => $this->id))){
					return false;
				}
				$this->id=null;
			} else
			return false;
		}//foreach
//		return false;
		return true;
	}

//incluir

	public function limpar($pedido) {
		$conditions = array('ItemPedido.pedido_id' => $pedido);
		$remover = $this->deleteAll($conditions);
		if ($remover)
			return true;
		return false;
	}

//limpar()

	public function rankingProdutos($inicio, $fim) {
		$sql = "SELECT ItemPedido.produto_id,
				Produto.nome,
				SUM(ItemPedido.quantidade) as total
			FROM itens_pedidos ItemPedido
				INNER JOIN produtos Produto ON ItemPedido.produto_id = Produto.id
				INNER JOIN pedidos Pedido ON ItemPedido.pedido_id = Pedido.id
			WHERE Pedido.created BETWEEN '{$inicio}' AND '{$fim}'
			GROUP BY ItemPedido.produto_id
			ORDER BY total DESC
			LIMIT 0, 5";

		$rows = $this->query($sql);
		$produtos = array();
		foreach ($rows as $row) {
			$produtos[$row['Produto']['nome']] = $row[0]['total'];
		}
		return $produtos;
	}

//rankingProdutos
}