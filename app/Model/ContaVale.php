<?php

class ContaVale extends AppModel {

	public $validate = array(
	    'conta_id' => array('rule' => 'notEmpty', 'message' => 'Informe uma conta'),
	    'vale_id' => array('rule' => 'notEmpty', 'message' => 'Informe um vale'),
	);
	
		
	public $belongsTo = array(
		'Vale',
		'Conta',		
		);	
	
	/**
	 * montarCarrinho() - Recupera a sessão de carrinho de um determinada conta
	 * @param integer $vale - ID do vale
	 * @return Sessão de Carrinho
	 */
	public function montar($conta = null, $limpar = true) {
		if ($limpar and isset($_SESSION['vales'])) {
			unset($_SESSION['vales']);
			$_SESSION['vales'] = Array();
		}
		if (!empty($vale)) {
			$itens = $this->findAllByContaId($conta);
			$indice = 0;
			if(!empty($itens))
			foreach ($itens as $item){				
				$_SESSION['vales'][$indice] = array(
				    'indice' => $indice,
				    'vale_id' => $item['Vale']['id'],
				    'cliente' => $item['Cliente']['nome'],
				    'obra' => $item['Cliente']['Obra']['nome']
				);
				$indice++;
			}//foreach
		}//if vale
	}//montarCarrinho



//incluir

	public function limpar($conta_id) {
		$conditions = array('ContaVale.conta_id' => $conta_id);
		$remover = $this->deleteAll($conditions);
		if ($remover)
			return true;
		return false;
	}
}