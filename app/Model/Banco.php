<?php
class Banco extends AppModel
{
	public $order = array( 'Banco.nome' => 'ASC' );
	public $displayField = 'nome';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o nome' ),
	);
	
}