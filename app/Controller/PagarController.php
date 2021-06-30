<?php

class PagarController extends AppController {

	public $model = 'Pagar';
	public $conditions = array('Pagar.tipo'=>'AP');
	public $filter_with_like = Array('Pagar.nome', 'Pagar.cpf_cnpj', 'Pagar.telefone', 'Pagar.email', 'Pagar.contato');
	public $uses = array('Pagar');

	
	public function index() {
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	
	public function edit($id = null) {
		if(!empty($this->request->data)){
			$this->request->data['Pagar']['tipo']='AP';
		}
		parent::edit($id);		
	}

	public function add() {
		$this->setAction('edit');
	}

	public function del($id) {
		parent::del($id);
	}

	//
	// ADMIN
	//
	public function admin_index() {
		$this->setAction('index');
	}

	public function admin_edit($id) {
		$this->setAction('edit', $id);
	}

	public function admin_add() {
		$this->setAction('edit');
	}

	public function admin_del($id) {
		$this->setAction('del', $id);
	}
	//
	// LOGISTICA
	//
	public function logistica_index() {
		$this->setAction('admin_index');
	}

	public function logistica_edit($id) {
		$this->setAction('admin_edit', $id);
	}

	public function logistica_add() {
		$this->setAction('admin_edit');
	}

	public function logistica_del($id) {
		$this->setAction('admin_del', $id);
	}

}