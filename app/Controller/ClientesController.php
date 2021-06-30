<?php

class ClientesController extends AppController {

	public $model = 'Cliente';
	public $uses = array('Cliente', 'Estado', 'Cidade','Produto','ClienteProduto','ClienteProdutoHistorico');
	public $filter_with_like = array('Cliente.cpf_cnpj', 'Cliente.nome', 'Cliente.email', 'Cidade.telefone');
	
	public function index(){
		$this->conditions = array('Cliente.ativo'=>1);		
		parent::index();
		
		//var_dump(validaEmail('camila.silva@eppo.com.br'));
	}
	
	public function inativos() {
		$this->conditions = array('Cliente.ativo'=>0);
		parent::index();
	}
	
	public function all() {
		//$this->autoRender = false;
		$this->layout = '';
		if (strlen($keyword = $_REQUEST['query']) > 3) {

			$this->conditions = array(
				'Cliente.ativo'=>1,
				'or' => array('Cliente.cpf_cnpj LIKE ' => "%{$keyword}%", 'Cliente.nome LIKE ' => "%{$keyword}%")
				);
			$fields = array('Cliente.id', 'Cliente.nome', 'Cliente.cpf_cnpj');

			$clientes = $this->Cliente->find('all', array('conditions' => $this->conditions));
			$this->set('clientes', $clientes);
		} else {
			$this->set('clientes', array());
		}
	}
	
	public function edit($id=null){
		$this->{$this->model}->id = $id;
		if (!empty($this->request->data)) {
			
			if ($this->{$this->model}->save($this->request->data)) {
				//print_r($this->request->data);exit();
				$this->Cliente->Endereco->deleteAll(array('cliente_id'=>$id));
				if(!empty($_SESSION['enderecos'])){
					foreach ($_SESSION['enderecos'] as &$value)
						$value['cliente_id']=$this->Cliente->id;
					$this->Cliente->Endereco->saveAll($this->Session->read('enderecos'));
				}
				$this->Session->setFlash('Registro editado com sucesso!', 'default', array('class'=>'alert alert-success'));
				unset($_SESSION['enderecos']);
				//pr($_SESSION['enderecos']);exit();
				$this->redirect( array( 'action' => 'index' ) );
			}
		} else $this->data = $this->{$this->model}->read();
		
		$estados = $this->Estado->find('list', array('fields' => array('Estado.nome')));

		foreach ($estados as &$val)
			$val = utf8_encode($val);
		for($c=1;$c<32;$c++)
			$dias[]=$c;
		$this->set('dias', $dias);
		$this->set('vendedores',$this->Cliente->Vendedor->find('list',array('conditions'=>array('Vendedor.nivel_id'=>2),'order'=>'Vendedor.nome'))); // Pesquisa somente vendedores);
		$this->set('tipos', $this->Cliente->Endereco->tipo);
		$this->set('empresas', $this->Cliente->Empresa->find('list'));
		$this->set('estados', $estados);
		if (!empty($this->request->data['Cliente']['estado_id'])) {
			$this->set('cidades', $this->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Cliente']['estado_id']))));
		}
		$this->Cliente->Endereco->montar($id);
	}
	
	public function view($id){
		
		$cliente=$this->Cliente->findById($id);		
		$this->Cliente->Endereco->montar($id);
		
		for($c=1;$c<32;$c++)
			$dias[]=$c;
		$this->set('dias', $dias);
		$this->set('tipos', $this->Cliente->Endereco->tipo);
		
		$this->set('cliente',$cliente);
	}
	
	public function del($id){
		$this->request->data['Cliente']['ativo']=0;
		parent::edit($id);
	}
	//
	//  ADMIN
	//
	public function admin_index(){$this->setAction('index');}
	public function admin_inativos(){	$this->setAction('inativos');	}
	public function admin_edit($id = null){$this->setAction('edit',$id);	}
	public function admin_add($pedido = null){
		if (isset($pedido))
			$this->set('pedido', $pedido);
		$this->setAction('admin_edit');
	}
	public function admin_del($id){$this->setAction('del',$id);}
	public function admin_view($id){$this->setAction('view',$id);}
	public function admin_all(){	$this->setAction('all');	}
	/**
	 *  Vendedor
	 */
	public function vendedor_index() {	$this->setAction('admin_index');}
	public function vendedor_add() {$this->setAction('vendedor_edit');}
	public function vendedor_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function vendedor_all() {$this->setAction('admin_all');}
	/**
	 *  PEDIDO
	 */
	public function pedido_index() {	$this->setAction('admin_index');}
	public function pedido_add() {$this->setAction('vendedor_edit');}
	public function pedido_edit($id = null) {	$this->setAction('admin_edit', $id);}
	public function pedido_all() {$this->setAction('admin_all');}
	//
	// GERENTE
	//
	public function gerente_index() {	$this->setAction('admin_index');}
	public function gerente_add() {$this->setAction('gerente_edit');}
	public function gerente_edit($id = null) {$this->setAction('admin_edit', $id);}
	public function gerente_all() {$this->setAction('admin_all');}
	//
	// FINANCEIRO
	//
	public function financeiro_index() {	$this->setAction('admin_index');}
	public function financeiro_add() {$this->setAction('admin_edit');}
	public function financeiro_edit($id = null) {$this->setAction('admin_edit', $id);}
	public function financeiro_all() {$this->setAction('admin_all');}
	//
	// PROGRAMACAO1
	//
	public function programacao1_index() {	$this->setAction('admin_index');}
	public function programacao1_view($id) {	$this->setAction('admin_view',$id);}
	
	
	public function programacao1_all() {$this->setAction('admin_all');}
	//
	// NOTA
	//
	public function nota_all() {$this->setAction('admin_all');}
	//
	// PEDIDOS1
	//
	public function pedidos1_all() {$this->setAction('admin_all');}
	//
	// PEDIDOS2
	//
	public function pedidos2_all() {$this->setAction('admin_all');}





	public function admin_precos($cliente){
		$this->set('produtos', $this->Produto->find('list'));
		$this->set('cliente', $this->Cliente->findById($cliente));
		$this->set('precos', $this->ClienteProduto->find('all',array('conditions' => array('ClienteProduto.cliente_id'=>$cliente))));
	}
	
	public function admin_preco($cliente = null, $produto = null){
		$this->autoRender = false;
		$this->layout = '';	
		$result = [];
		if($cliente && $produto){
			$data =	$this->ClienteProduto->find('first',array(
			'conditions' => array(
				'ClienteProduto.cliente_id'=>$cliente,
				'ClienteProduto.produto_id'=>$produto
			)));
			
			if(	$data ){
				$result = $data["ClienteProduto"];
			}
			
			
		}
		
		
		return json_encode($result);
	}
	
	public function admin_precos_save() {
		$this->autoRender = false;
		$this->layout = '';	

		
		$item = $this->ClienteProduto->find('first', array(
			
			'conditions' => array(
				'ClienteProduto.cliente_id'=>$this->request->data['cliente_id'],
				'ClienteProduto.produto_id'=>$this->request->data['produto_id']
			)
		));

		
		if($item){
			$this->request->data['id'] = $item['ClienteProduto']['id'];
		}


		$this->request->data['preco'] = moedaBD($this->request->data['preco']);	
		$this->ClienteProduto->save($this->request->data);

		$produto_id = $this->request->data['produto_id'];
		$cliente_id = $this->request->data['cliente_id'];

		$this->request->data['id'] = null;
		$this->ClienteProdutoHistorico->save($this->request->data);

		$json = $this->admin_precos_list($cliente_id);
		return $json;
	}
	
	public function admin_precos_list($id) {
		$this->autoRender = false;
		$this->layout = '';	
		$arr = $this->ClienteProduto->find('all',array(
			'fields' => array(
				'ClienteProduto.id',
				'ClienteProduto.produto_id',
				'ClienteProduto.preco'
			),
			'conditions' => array('ClienteProduto.cliente_id'=>$id)));

		$produtos  = $this->Produto->find('list');
		
		$json = array();
		foreach($arr as $k => $v){
			$json[] = array(
				'produto'=>$produtos[$v['ClienteProduto']['produto_id']],
				'preco'=> moedaBr($v['ClienteProduto']['preco']),
			);
		}

		return json_encode($json,true);
	}

}//class