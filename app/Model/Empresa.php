<?php
class Empresa extends AppModel
{
	public $displayField = 'nome';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Nome!' ),
		'cnpj' => array( 'rule' => 'notEmpty', 'message' => 'Informe o CNPJ!' ),
	);
	
	public $belongsTo = array(
		'Cidade','Estado'
	);
	
	
	public $crt = array(
		1=>'Simples Nacional',
		2=>'Simples Nacional - excesso de sublimite de receita bruta',
		3=>'Regime Normal',
	);
}