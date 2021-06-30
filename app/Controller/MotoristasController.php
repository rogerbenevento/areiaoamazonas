<?php

class MotoristasController extends AppController {

	public $model = 'Motorista';
	public $filter_with_like = Array('Motorista.nome', 'Motorista.cpf_cnpj', 'Motorista.telefone', 'Motorista.email', 'Motorista.contato');
	public $uses = array('Motorista', 'Estado', 'Cidade');

	
	public function index() {
		$this->conditions = array('Motorista.ativo' => 1 );
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}
	
	public function inativos(){
		$this->conditions=array('Motorista.ativo'=>0);
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	

	public function edit($id = null) {
		parent::edit($id);
		$this->set('estados', $this->Estado->find('list', array('fields' => array('Estado.nome'))));

		if (!empty($this->request->data['Motorista']['estado_id'])) {
			$this->set('cidades', $this->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Motorista']['estado_id']))));
		}
		
		$this->set('tipos',  $this->Motorista->tipos);
	}

	public function add() {
		$this->setAction('edit');
	}

	public function del($id) {
		parent::del($id);
	}

	public function ativar($id){
		$this->Motorista->save(['id'=> $id,'ativo' => 1]);
		$this->redirect( array( 'action' => 'index' ) ); 
	}
	
	public function desativar($id){
		$this->Motorista->save(['id'=> $id,'ativo' => 0]);
		$this->redirect( array( 'action' => 'index' ) );
	}

	//
	// ADMIN
	//
	public function admin_index() {
		$this->setAction('index');
	}
	public function admin_inativos() {
		$this->setAction('inativos');
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

	public function admin_ativar($id) {
		$this->setAction('ativar', $id);
	}

	public function admin_desativar($id) {
		$this->setAction('desativar', $id);
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

	public function logistica_ativar($id) {
		$this->setAction('ativar', $id);
	}
	
	public function logistica_desativar($id) {
		$this->setAction('desativar', $id);
	}
	//
	// MOTORISTA
	//
	public function gerente_index() {
		$this->setAction('admin_index');
	}

	public function gerente_edit($id) {
		$this->setAction('admin_edit', $id);
	}

	public function gerente_add() {
		$this->setAction('admin_edit');
	}

	public function gerente_del($id) {
		$this->setAction('admin_del', $id);
	}

	public function gerente_ativar($id) {
		$this->setAction('ativar', $id);
	}
	
	public function gerente_desativar($id) {
		$this->setAction('desativar', $id);
	}

}