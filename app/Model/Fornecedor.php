<?php
class Fornecedor extends AppModel
{
	public $useTable = 'fornecedores';
	public $order = array( 'Fornecedor.nome' => 'ASC' );
	public $displayField = 'nome';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o nome' ),
		'telefone' => array( 'rule' => 'notEmpty', 'message' => 'Informe o telefone' ),
	);
	public $belongsTo = array( 'Estado', 'Cidade' );
}