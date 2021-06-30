<?php
class Relatorio extends AppModel{
	public $useTable = false;
	
	public $validate = array(
		'inicio'=>array('rule' => 'notEmpty', 'message' => 'Informe data de Inicio')
	);
	
}