<?php
class Pagamento extends AppModel
{
	public $displayField = 'valor';
	public $order = array('Pagamento.id' => 'ASC');
	public $validate = array(
		'pedidos_id' => array('rule' => 'notEmpty', 'message' => 'Informe a descrição da forma de pagamento'),
		'forma_pgto' => array('rule' => 'notEmpty', 'message' => 'Informe a descrição da forma de pagamento'),
		'valor' => array('rule' => 'notEmpty', 'message' => 'Informe em quantas vezes é permitido parcelar')
	);
        
        public $belongsTo = array( 
		'Pedido'=> array( 'foreignKey' => 'pedidos_id' ),
		'TipoPagamento' => array( 'foreignKey' => 'forma_pagto' )
	);
        
        /**
	 * Records of any payment request
	 * @param array $payments - Money supply
	 * @param integer $order - Order Id
	 * @return boolean
	 */
	public function incluir( Array $payments, $order ){
		//pr($payments);
                foreach ( $payments as $p ) {
                        $item['Pagamento'] = array( 
                                'valor' 	=> $p['valor'],
                                'forma_pagto' 	=> $p['forma_pagto'],
                                'parcelas' 	=> $p['parcelas'],
                                'pedidos_id'	=> $order,
                                'entrada'	=> $p['entrada'],
                                'tac'		=> $p['tac']
                        );
                        if ( $this->save( $item ) ) $this->id = null;
                        else return false;
		}
		return true;
	}
        
	public function montarPagamentos($pedido)
	{
		if(isset($_SESSION['pagamentos_pedidos'])){
                    unset($_SESSION['pagamentos_pedidos']);
                    $_SESSION['pagamentos_pedidos']=Array();
                }
		//$Pagamento = ClassRegistry::init('Pagamento');
                if(!empty($pedido)){
                    $itens = $this->findAllByPedidosId($pedido);		
                    #pr($itens); #exit();
                    $indice=0;
                    foreach ($itens as $item) {
                            $_SESSION['pagamentos_pedidos'][$indice] = array(
                                    'item' => $indice,
                                //Nao existe coluna intervalo e primeira_parcela
    //				'intervalo' => $item['PagamentoPedido']['intervalo'],
    //				'primeira_parcela' => $item['PagamentoPedido']['primeira_parcela'],
                                    'pagamento_id' => $item['Pagamento']['id'], 
                                    'forma_pagto' => $item['Pagamento']['forma_pagto'], 
                                    'nome' => $item['TipoPagamento']['nome'], 
                                    'valor' => $item['Pagamento']['valor'],
                                    'parcelas' => $item['Pagamento']['parcelas'],
                                    'entrada' => $item['TipoPagamento']['entrada'],
                                    'tac' => $item['TipoPagamento']['tac']
                            );
                            $indice++;
                    }//foreach
                }//pedido
	}//montarPagamentos
        
	/**
	 * valorTotal() - Calcula o valor total de pagamentos
	 * @param array $payments - Itens de forma de pagamento
	 * @return float
	 */
	public function valorTotal( $payments ){
		$total = 0;
                //Verifica se é um array, senão não altera o valor total
                if(is_array($payments)){
                    foreach ( $payments as $p ) {
                        $total += $p['valor'];
                    }
                }                    
		return round( $total, 2 );
	}//valorTotal()
	
	public function limpar( $id ){
                $conditions = array( 'Pagamento.pedidos_id' => $id );
		$remover = $this->deleteAll( $conditions  );
		if ( $remover ) return true;
		return false;
	}//limpar()

	public function allByPedidos( $pedido ){
		$pagamentos = $this->allByPedidosId( $pedido );
		$pag = array();
		foreach ( $pagamentos as $key => $p ) {
			$pag[$key] = $p;
			$pag[$key]['item'] = $key;
		}
		return $pag;
	}
        
}//Class Pedidos