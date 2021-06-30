<?php
class Estado extends AppModel
{
	public $order = array( 'Estado.nome' => 'ASC' );
	public $displayField = 'nome';
	public $hasMany = array( 'Cidade' );
}