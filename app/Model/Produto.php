<?php

class Produto extends AppModel {

	public $order = array('Produto.nome' => 'ASC');
	public $displayField = 'nome';
	public $validate = array(
	    'nome' => array('rule' => 'notEmpty', 'message' => 'Informe o nome')
	);
	
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		if (!empty($this->data['Produto']['preco'])) {
			$this->data['Produto']['preco'] = $this->recebeFloatBeforeSave($this->data['Produto']['preco']);
		}

		if (!empty($this->data['Produto']['custo'])) {
			$this->data['Produto']['custo'] = $this->recebeFloatBeforeSave($this->data['Produto']['custo']);
		}
		return true;
	}


}

//Class