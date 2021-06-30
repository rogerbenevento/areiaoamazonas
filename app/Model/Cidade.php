<?php
class Cidade extends AppModel
{
	public $order = array( 'Cidade.nome' => 'ASC' );
	public $displayField = 'nome';
	public $belongsTo = array( 'Estado' );
}