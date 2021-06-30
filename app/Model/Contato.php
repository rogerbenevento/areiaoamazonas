<?php
App::uses('AppModel', 'Model') ;
class Contato extends AppModel
{
	public $useDbConfig = 'empty';
	public $useTable = false;
	public $validate = array(
		'nome'		=> array('rule' => 'notempty', 'message' => 'Informe seu nome'),
		'email'		=> array(
			array('rule' => 'notempty', 'message' => 'Informe seu email'),
			array('rule' => 'email', 'message' => 'Informe um email válido')
		),
		'ddd'		=> array(
			array('rule' => 'numeric', 'message' => 'Informe o ddd', 'required' => true),
			array('rule' => array('minLength', 2), 'message' => 'DDD Incorreto')
		),
		'fone'		=> array('rule' => 'numeric', 'message' => 'Informe o telefone', 'required' => true),
		'msg'		=> array('rule' => 'notempty', 'message' => 'Informe a mensagem que deseja enviar')
	);
}