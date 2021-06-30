<?php

class ProdutosController extends AppController {

	public $model = 'Produto';
	public $filter_with_like = Array('Produto.nome');
	public $uses = array('Produto');

	public function all() {
		$this->layout = '';
		$keyword = $_REQUEST['query'];
		$this->conditions['Produto.nome LIKE _utf8'] = "%{$keyword}%";
		$fields = array('Produto.id', 'Produto.nome', 'Produto.preco');

		$produtos = $this->Produto->find('all', array('conditions' => $this->conditions));
		$this->set('produtos', $produtos);
	}

	public function index() {
		parent::index();
	}

	public function edit($id = null) {
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
	public function admin_all() {	$this->setAction('all');}
	public function admin_index() {	$this->setAction('index');}
	public function admin_edit($id = null) {$this->setAction('edit',$id);}
	public function admin_add() {$this->setAction('admin_edit');}
	public function admin_del($id) {$this->setAction('del',$id);}

	/**
	 * Logistica
	 */
	public function logistica_all() {	$this->setAction('admin_all');}

	/**
	 * Vendedor
	 */
	public function vendedor_all() {	$this->setAction('admin_all');}
	/**
	 * Pedido
	 */
	public function pedido_all() {	$this->setAction('admin_all');}

	/**
	 * Financeiro
	 */
	public function financeiro_all() {	$this->setAction('admin_all');}
	//
	// GERENTE
	//
	public function gerente_all() {	$this->setAction('all');}
	public function gerente_index() {	$this->setAction('index');}
	public function gerente_edit($id = null) {$this->setAction('edit',$id);}
	public function gerente_add() {$this->setAction('admin_edit');}
	public function gerente_del($id) {$this->setAction('del',$id);}
}

//class