<?php

class EmpresasController extends AppController{

	public $model = 'Empresa';
	public $filter_with_like = Array('Empresa.nome', 'Empresa.cnpj');
	public $uses = array('Empresa');

	
	public function index() {
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}
	
	public function edit($id = null) {
		parent::edit($id);
		
		$estados = $this->Empresa->Estado->find('list', array('fields' => array('Estado.nome')));

		foreach ($estados as &$val)
			$val = utf8_encode($val);
		$this->set('estados', $estados);
		if (!empty($this->request->data['Empresa']['estado_id'])) {
			$this->set('cidades', $this->Empresa->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Empresa']['estado_id']))));
		}
		$this->set('crts',$this->Empresa->crt);
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
	public function admin_index() {	$this->setAction('index');	}
	public function admin_edit($id) {	$this->setAction('edit', $id);	}
	public function admin_add() {	$this->setAction('edit');	}
	public function admin_del($id) {$this->setAction('del', $id);	}
	//
	// DIRETOR
	//
	public function diretor_index() {	$this->setAction('index');	}
	public function diretor_edit($id) {	$this->setAction('edit', $id);	}
	public function diretor_add() {	$this->setAction('edit');	}
	public function diretor_del($id) {$this->setAction('del', $id);	}
	//
	// DIRETOR
	//
	public function financeiro_index() {	$this->setAction('index');	}
	public function financeiro_edit($id) {	$this->setAction('edit', $id);	}
	public function financeiro_add() {	$this->setAction('edit');	}
	public function financeiro_del($id) {$this->setAction('del', $id);	}
}