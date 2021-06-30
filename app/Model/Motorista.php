<?php
class Motorista extends AppModel
{
	public $useTable = 'motoristas';
	public $order = array( 'Motorista.nome' => 'ASC' );
	public $displayField = 'nome';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o nome' ),
		'telefone' => array( 'rule' => 'notEmpty', 'message' => 'Informe o telefone' ),
		'tipo' => array( 'rule' => 'notEmpty', 'message' => 'Informe o telefone' ),
		'placa' => array( 'rule' => 'notEmpty', 'message' => 'Informe a Placa' ),
	);
	public $belongsTo = array( 'Estado', 'Cidade' );
	public $hasMany = array('Abastecimento','Multa');
	
	public $tipos = array( 1 => 'FIXO',2 => 'FRETEIRO');
	

}