<?php

class ItemNota extends AppModel {

	public $useTable = 'itens_notas';
	public $belongsTo = array('Nota', 'Pedido','Produto');

	/**
	 * montarCarrinho() - Recupera a sessão de carrinho de um determinada nota
	 * @param integer $pedido - ID do pedido
	 * @return Sessão de Carrinho
	 */
	public function montar($nota = null, $limpar = true) {
		if ($limpar and isset($_SESSION['pedidos'])) {
			unset($_SESSION['pedidos']);
			$_SESSION['pedidos'] = Array();
		}
		if (!empty($nota)) {
			$this->Behaviors->load('Containable');
			$this->contain(array(
				'Produto',
				'Nota',
				'Pedido.Obra',
				'Pedido.Cliente',
				'Pedido.ItemPedido.Produto'
			));
			$itens = $this->findAllByNotaId($nota);
			//pr($itens);
			//exit();
			$indice = 0;
			if(!empty($itens))
			foreach ($itens as $item){				
				$_SESSION['pedidos'][$indice] = array(
				    'indice' => $indice,
				    'pedido_id' => $item['Pedido']['id'],
				    'cliente' => $item['Pedido']['Cliente']['nome'],
				    'obra' => $item['Pedido']['Obra']['nome'],
				    'produto_id' => empty($item['ItemNota']['produto_id'])? $item['Pedido']['ItemPedido'][0]['Produto']['id'] : $item['ItemNota']['produto_id'],
				    'material' => !empty($item['Produto']['nome'])? $item['Produto']['nome'] :$item['Pedido']['ItemPedido'][0]['Produto']['nome'],
				    'quantidade' => empty($item['ItemNota']['quantidade'])? $item['Pedido']['ItemPedido'][0]['quantidade']: $item['ItemNota']['quantidade'],
				    'valor_unitario' => empty($item['ItemNota']['valor_unitario'])? null: $item['ItemNota']['valor_unitario'],
				    'situacao_tributaria' => empty($item['ItemNota']['situacao_tributaria'])? null: $item['ItemNota']['situacao_tributaria'],
				    'imprimir' => $item['ItemNota']['imprimir']
				);
				$indice++;
			}//foreach
		}//if pedido
		//pr($_SESSION['pedidos']);
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
			    ,'quantidade' => $item['quantidade']
				,'valor_unitario' => $item['valor_unitario'],
			);
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