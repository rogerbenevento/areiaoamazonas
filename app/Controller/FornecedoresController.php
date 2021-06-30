<?php

class FornecedoresController extends AppController {

	public $model = 'Fornecedor';
	public $filter_with_like = Array('Fornecedor.nome', 'Fornecedor.cnpj', 'Fornecedor.telefone', 'Fornecedor.email', 'Fornecedor.contato');
	public $uses = array('Fornecedor', 'Estado', 'Cidade','Produto','FornecedorProduto','FornecedorProdutoHistorico');

	public function all() {
		//$this->autoRender = false;
		$this->layout = '';
		if (strlen($keyword = $_REQUEST['query']) > 3) {

			$this->conditions = array(
				'Fornecedor.ativo'=>1,
				'or' => array('Fornecedor.cnpj LIKE ' => "%{$keyword}%", 'Fornecedor.nome LIKE ' => "%{$keyword}%")
				);
			$fields = array('Fornecedor.id', 'Fornecedor.nome');

			$fornecedores = $this->Fornecedor->find('all', array('conditions' => $this->conditions));
			$this->set('fornecedores', $fornecedores);
		} else {
			$this->set('fornecedores', array());
		}
	}
	public function index() {
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}
	
	public function inativos(){
		$this->conditions=array('Fornecedor.ativo'=>0);
		parent::index();
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}
	
	public function edit($id = null) {
		@parent::edit($id);
		$this->set('estados', $this->Estado->find('list', array('fields' => array('Estado.nome'))));

		if (!empty($this->request->data['Fornecedor']['estado_id'])) {
			$this->set('cidades', $this->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Fornecedor']['estado_id']))));
		}
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
	public function admin_all() {
		$this->setAction('all');
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
	//
	// LOGISTICA
	//
	public function logistica_index() {
		$this->setAction('admin_index');
	}
	public function logistica_all() {
		$this->setAction('admin_all');
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
	//
	// GERENTE
	//
	public function gerente_index() {
		$this->setAction('admin_index');
	}
	public function gerente_all() {
		$this->setAction('admin_all');
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

	public function admin_precos($fornecedor){
		$this->set('produtos', $this->Produto->find('list'));
		$this->set('fornecedor', $this->Fornecedor->findById($fornecedor));
		$this->set('precos', $this->FornecedorProduto->find('all',array('conditions' => array('FornecedorProduto.fornecedor_id'=>$fornecedor))));
	}
	
	public function admin_preco($fornecedor = null, $produto = null){
		$this->autoRender = false;
		$this->layout = '';	
		$result = [];
		if($fornecedor && $produto){
			$data =	$this->FornecedorProduto->find('first',array(
			'conditions' => array(
				'FornecedorProduto.fornecedor_id'=>$fornecedor,
				'FornecedorProduto.produto_id'=>$produto
			)));
			
			if(	$data ){
				$result = $data["FornecedorProduto"];
			}
			
			
		}
		
		
		return json_encode($result);
	}
	
	public function admin_precos_save() {
		$this->autoRender = false;
		$this->layout = '';	

		
		$item = $this->FornecedorProduto->find('first', array(
			
			'conditions' => array(
				'FornecedorProduto.fornecedor_id'=>$this->request->data['fornecedor_id'],
				'FornecedorProduto.produto_id'=>$this->request->data['produto_id']
			)
		));

		
		if($item){
			$this->request->data['id'] = $item['FornecedorProduto']['id'];
		}


		$this->request->data['preco'] = moedaBD($this->request->data['preco']);	
		$this->FornecedorProduto->save($this->request->data);

		$fornecedor_id = $this->request->data['fornecedor_id'];

		$this->request->data['id'] = null;
		$this->FornecedorProdutoHistorico->save($this->request->data);

		$json = $this->admin_precos_list($fornecedor_id);
		return $json;
	}
	
	public function admin_precos_list($id) {
		$this->autoRender = false;
		$this->layout = '';	
		$arr = $this->FornecedorProduto->find('all',array(
			'fields' => array(
				'FornecedorProduto.id',
				'FornecedorProduto.produto_id',
				'FornecedorProduto.preco'
			),
			'conditions' => array('FornecedorProduto.fornecedor_id'=>$id)));

		$produtos  = $this->Produto->find('list');
		
		$json = array();
		foreach($arr as $k => $v){
			$json[] = array(
				'produto'=>$produtos[$v['FornecedorProduto']['produto_id']],
				'preco'=> moedaBr($v['FornecedorProduto']['preco']),
			);
		}

		return json_encode($json,true);
	}
}