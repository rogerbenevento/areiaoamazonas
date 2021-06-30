<?php
class Abastecimento extends AppModel{
	public $belongsTo = array('Motorista');
	public $hasOne = array('Conta');
	public $validate = array(
		'motorista_id' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Motorista!' ),
		'valor' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Valor da Abastecimento!' )
	);
}