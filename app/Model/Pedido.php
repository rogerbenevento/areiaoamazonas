<?php

class Pedido extends AppModel {

	public $order = array('Pedido.id' => 'DESC');
	public $validate = array(
	    'cliente_id' => array('rule' => 'notEmpty', 'message' => 'Informe o cliente'),
	    'user_id' => array('rule' => 'notEmpty', 'message' => 'Selecione um vendedor')
//		'data_entrega' => array('rule' => 'notEmpty', 'message' => 'Informe a data de entrega'),
//		'hora_entrega' => array('rule' => 'notEmpty', 'message' => 'Informe a hora de entrega'),
	);
	public $hasOne = array('Conta');
	public $belongsTo = array(
	    'Periodo',
	    'Cliente',
	    'Obra',
	    'User'
	);
	public $hasMany = array(
	    'ItemPedido',
	    'ItemNota'
	);
	public $recursive = 1;

	public $status = array(
	    0 => 'Em Aberto',
	    1 => 'Finalizado',
	    2 => 'Cancelado',
	);
	
	public $porcentagem_freteiro = 0.65;
	public $limite_frete_casa=70;
	public $limite_frete_freteiro=50;
	public $preco_diurno = 22;
	public $preco_noturno= 30;


	/**
	 * VerificaPagamento() - verifica se o pedido possui tipo de pagamento
	 * @param array $itens - Pagamentos do pedido
	 * @param integer $pedido - ID do pedido
	 * @return boolean - Verdadeiro caso todos os itens seja cadastrados com sucesso
	 */
	public function VerificaPagamento($id) {
		return (count($this->PagamentoPedido->findAllByPedidoId($id)) < 1) ? false : true;
	}

	/**
	 * putInFinancial() - Coloca os valores das formas de pagamento no financeiro
	 * @param integer $id - ID do pedido
	 */
	public function incluirFinanceiro($id) {
		$pagamentos = $this->PagamentoPedido->findAllByPedidoId($id);
		#echo "<pre>"; print_r($pagamentos); echo "</pre>"; exit();
		if (count($pagamentos)) {
			foreach ($pagamentos as $p) {
				$data['Conta'] = array(
				    'descricao' => "Pedido {$p['PagamentoPedido']['pedido_id']} - {$p['Pagamento']['nome']}",
				    'tipo' => 'R',
				    'observacao' => "Pedido {$p['PagamentoPedido']['pedido_id']} - {$p['Pagamento']['nome']}",
				    'valor' => ($p['PagamentoPedido']['valor'] / $p['PagamentoPedido']['parcelas']),
				    'parcela' => '1',
				    'parcelas' => $p['PagamentoPedido']['parcelas'],
				    'pedido_id' => $p['PagamentoPedido']['pedido_id'],
				    'data' => addDayIntoDate($p['PagamentoPedido']['created'], $p['Pagamento']['compensacao']),
				);
				#echo "<pre>"; print_r($data); echo "</pre>";

				if ($this->Conta->saveParcels($data))
					continue;
				else
					return false;
			}
			#exit();
		}
		return true;
	}

	/**
	 * limpar_carrinho() - Limpa a sessao carrinho
	 * @param integer $id - ID do pedido
	 */
	public function limpar_carrinho() {
		unset($_SESSION['carrinho']);
	}

	public function RelacaoFormaPagto($conditions) {
		$sql = "SELECT Pagamento.forma_pagto,TipoPagamento.nome,
                           SUM(Pagamento.valor) as total
                      FROM pagamentos Pagamento
                     LEFT JOIN tipos_pagamentos TipoPagamento ON Pagamento.forma_pagto = TipoPagamento.id
                     INNER JOIN pedidos Pedido ON Pagamento.pedidos_id = Pedido.id
                     WHERE 1 = 1";
		if (count($conditions)) {
			foreach ($conditions as $campo => $valor) {
				$igual = (substr_count($campo, '=') != 0) ? '' : '=';
				$sql.= " AND {$campo} {$igual} '{$valor}' ";
			}
		}
		$sql .= " GROUP BY Pagamento.forma_pagto ORDER BY total DESC";

		return $this->query($sql);
	}

	public function relatoriomensal($params) {

		$pedidos = $this->find('all', array('conditions' => $params, 'order' => 'Pedido.created ASC'));
//		pr($params);
//                pr($pedidos);exit();

		$relacao = array();

		$i = 0;
		$valorRT = $valorCB = $valorCC = $valorCDC = $valorCheque = $valorCredipar = $valorDesconto = $valorDinheiro
			   = $valorDM = $valorPermuta = $valorSorocred = $valorFatura = $valorCredi = 0;
		foreach ($pedidos as $pedido) {
			$relacao[$i] = array(
			    'id' => $pedido['Pedido']['id'],
			    'created' => $pedido['Pedido']['created'],
			    'nota' => $pedido['Pedido']['nota_fiscal'],
			    'loja' => $pedido['Loja']['descricao'],
			    'vendedor' => $pedido['User']['nome'],
			    'cliente' => $pedido['Cliente']['nome'],
			    'arredondamento' => $pedido['Pedido']['arredondamento'],
			    'valor_cupom' => $pedido['Pedido']['valor_cupom'],
			    'status' => $pedido['Pedido']['status'],
			);

			$valor_total = 0;
			$valor_desconto = 0;
			foreach ($pedido['ItemPedido'] as $item) {
				$valor_desconto += (($item['valor_unitario'] * $item['desconto']) / 100) * $item['quantidade'];

				if (!$item['desconto'] || !empty($item['desconto'])) {
					$valor_total += ($item['valor_garantia']) + ($item['valor_unitario'] * (1 - ($item['desconto']) / 100)) * $item['quantidade'];
				} else
					$valor_total += ($item['valor_garantia']) + ($item['valor_unitario'] * $item['quantidade']);
			}


			$relacao[$i]['desconto'] = $valor_desconto + $pedido['Pedido']['valor_cupom'];
			$relacao[$i]['valor_total'] = $valor_total - $pedido['Pedido']['valor_cupom'];
			$relacao[$i]['valor'] = $valor_total - $pedido['Pedido']['arredondamento'] - $pedido['Pedido']['valor_cupom'];

			//Captura os métodos de pagamentos
			foreach ($pedido['Pagamento'] as $pagamento) {
				//Separa os pagamentos de acordo com o método
				switch ($pagamento["forma_pagto"]) {
					//Cartão Crédito Cielo
					case 1:
						$valorCC += $pagamento["valor"];
						break;
					//Cartão Crédito Redecard
					case 11:
						$valorCC += $pagamento["valor"];
						break;
					//Cartão Débito Cielo
					case 2:
						$valorCB += $pagamento["valor"];
						break;
					//Cartão Débito Redecard
					case 12:
						$valorCB += $pagamento["valor"];
						break;
					//Crediario CDC
					case 6:
						$valorCredi += $pagamento["valor"];
						break;
					//Cheque
					case 4:
						$valorCheque += $pagamento["valor"];
						break;
					//Crediario -> Credipar
					case 8:
						$valorCredi += $pagamento["valor"];
						break;
					//Desconto em Folha
					case 7:
						$valorDesconto += $pagamento["valor"];
						break;
					//Dinheiro
					case 3:
						$valorDinheiro += $pagamento["valor"];
						break;
					//Crediario DM card
					case 10:
						$valorCredi += $pagamento["valor"];
						break;
					case 5:
						//Permuta
						$valorPermuta+= $pagamento["valor"];
						break;
					//Crediario sorocred
					case 9:
						$valorCredi += $pagamento["valor"];
						break;
					//Fatura
					case 13:
						$valorFatura += $pagamento["valor"];
						break;
				}

				//Valor Total
				$valorRT += $pagamento["valor"];
			}

			$relacao[$i]['valorCC'] = $valorCC;
			$relacao[$i]['valorCB'] = $valorCB;
			$relacao[$i]['valorCredi'] = $valorCredi;
			$relacao[$i]['valorCheque'] = $valorCheque;
			$relacao[$i]['valorDP'] = $valorDesconto + $valorPermuta;
			$relacao[$i]['valorDinheiro'] = $valorDinheiro;
			$relacao[$i]['valorFatura'] = $valorFatura;

			//Sera os valores
			$valorCB = $valorCC = $valorCDC = $valorCheque = $valorCredipar = $valorDesconto = $valorDinheiro
				   = $valorDM = $valorPermuta = $valorSorocred = $valorFatura = $valorCredi = 0;


			$i++;
		}
		//pr($relacao);exit();
		return $relacao;
	}

	public function comissao($conditions) {

		$pedidos = $this->find('all', array('conditions' => $conditions, 'order' => array('Pedido.user_id,Pedido.created' => 'ASC')));
		//pr($pedidos);
		$relacao = array();

		$i = 0;
		foreach ($pedidos as $pedido) {
			$relacao[$i] = array(
			    'id' => $pedido['Pedido']['id'],
			    'created' => $pedido['Pedido']['created'],
			    'nota' => $pedido['Pedido']['nota_fiscal'],
			    'loja' => $pedido['Loja']['descricao'],
			    'vendedor_id' => $pedido['User']['id'],
			    'vendedor' => $pedido['User']['nome'],
			    'cliente' => $pedido['Cliente']['nome'],
			    'arredondamento' => $pedido['Pedido']['arredondamento'],
			    'valor_cupom' => $pedido['Pedido']['valor_cupom'],
			    'status' => $pedido['Pedido']['status'],
			);

			$valor_total = 0;
			$valor_desconto = 0;
			foreach ($pedido['ItemPedido'] as $item) {
				$valor_desconto += $item['valor_unitario'] * ($item['desconto'] / 100) * $item['quantidade'];
				if (!empty($item['desconto'])) {
					$valor_total += ($item['valor_unitario'] * (1 - ($item['desconto'] / 100))) * $item['quantidade'];
				} else {
					$valor_total += ($item['valor_unitario'] * $item['quantidade']);
				}
			}//foreach 

			$relacao[$i]['desconto'] = $valor_desconto + $pedido['Pedido']['valor_cupom'];
			;
			$relacao[$i]['valor_garantia'] = $item['valor_garantia'];

			//$relacao[$i]['valor'] = $item['quantidade'] * $valor_total - $pedido['arredondamento'];
			$relacao[$i]['valor'] = $valor_total - $pedido['Pedido']['arredondamento'] - $pedido['Pedido']['valor_cupom'];
			$i++;
		}
		//pr($relacao);
		return $relacao;
	}

//comissao()

	public function rankingLojas($inicio, $fim) {
		$sql = "SELECT Pedido.loja_id,
                                Loja.descricao,
                                sum(ItemPedido.valor_unitario * ItemPedido.quantidade) as total
                        FROM pedidos Pedido
                       INNER JOIN itens_pedidos ItemPedido ON Pedido.id = ItemPedido.pedido_id
                       INNER JOIN lojas Loja ON Pedido.loja_id = Loja.id
                       WHERE Pedido.created BETWEEN '{$inicio}' and '{$fim}'
                       GROUP BY Pedido.loja_id
                       ORDER BY total DESC
                       LIMIT 0, 5";
		$rows = $this->query($sql);

		$lojas = array();
		foreach ($rows as $row) {
			$lojas[$row['Loja']['descricao']] = $row[0]['total'];
		}
		return $lojas;
	}

//rankindLojas

	public function rankingVendedores($inicio, $fim) {
		$sql = "SELECT Pedido.user_id,
                                User.nome,
                                Pedido.loja_id,
                                SUM(ItemPedido.valor_unitario * ItemPedido.quantidade) as total
                        FROM pedidos Pedido
                       INNER JOIN itens_pedidos ItemPedido ON Pedido.id = ItemPedido.pedido_id
                       INNER JOIN users User ON Pedido.user_id = User.id
                       WHERE Pedido.created between '{$inicio}' AND '{$fim}'
                       GROUP BY Pedido.user_id
                       ORDER BY total DESC
                       LIMIT 0, 5";
		$rows = $this->query($sql);

		$vendedores = array();
		foreach ($rows as $row) {
			$vendedores[$row['User']['nome']] = $row[0]['total'];
		}
		return $vendedores;
	}

//rankingVendedores()
}

//MOdel