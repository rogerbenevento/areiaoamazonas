<?php
class Multa extends AppModel{
	public $belongsTo = array('Motorista');
	public $validate = array(
		'tipo' => array( 'rule' => 'notEmpty', 'message' => 'Informe a Tipo da Multa!' ),
		'valor' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Valor da Multa!' )
	);
}