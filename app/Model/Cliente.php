<?php
class Cliente extends AppModel
{
	public $order = array( 'Cliente.nome' => 'ASC' );
	public $displayField = 'nome';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o nome' ),
	);
	public $belongsTo = array('Estado','Cidade','Empresa','Vendedor'=>array('className'=>'User','foreignKey'=>'vendedor_id'));
	public $hasMany = array('Endereco','Obra');
}