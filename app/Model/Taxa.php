<?php
class Taxa extends AppModel
{
	public $useTable = 'taxas_impostos';
	public $order = array( 'Taxa.id' => 'DESC' );
	
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe um Nome!' ),
		'valor' => array( 'rule' => 'notEmpty', 'message' => 'Informe um Valor!' )
	);
}