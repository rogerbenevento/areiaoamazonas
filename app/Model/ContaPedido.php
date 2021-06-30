<?php

class ContaPedido extends AppModel {

	public $validate = array(
	    'conta_id' => array('rule' => 'notEmpty', 'message' => 'Informe uma conta'),
	    'pedido_id' => array('rule' => 'notEmpty', 'message' => 'Informe um pedido'),
	);
	
		
	public $belongsTo = array(
		'Pedido',
		'Conta',		
		);	
	
	/**
	 * montarCarrinho() - Recupera a sessão de carrinho de um determinada conta
	 * @param integer $pedido - ID do pedido
	 * @return Sessão de Carrinho
	 */
	public function montar($conta = null, $limpar = true) {
		if ($limpar and isset($_SESSION['pedidos'])) {
			unset($_SESSION['pedidos']);
			$_SESSION['pedidos'] = Array();
		}
		if (!empty($pedido)) {
			$itens = $this->findAllByContaId($conta);
			$indice = 0;
			if(!empty($itens))
			foreach ($itens as $item){				
				$_SESSION['pedidos'][$indice] = array(
				    'indice' => $indice,
				    'pedido_id' => $item['Pedido']['id'],
				    'cliente' => $item['Cliente']['nome'],
				    'obra' => $item['Cliente']['Obra']['nome']
				);
				$indice++;
			}//foreach
		}//if pedido
	}//montarCarrinho



//incluir

	public function limpar($conta_id) {
		$conditions = array('ContaPedido.conta_id' => $conta_id);
		$remover = $this->deleteAll($conditions);
		if ($remover)
			return true;
		return false;
	}
}