<?php

class ObrasController extends AppController {

	public $model = 'Obra';
	public $filter_with_like = Array( 'Obra.endereco');
	public $uses = Array('Obra','ObraProduto','Produto','ObraProdutoHistorico');
	
	//
	//ADMIN
	//
	public function admin_options($id) {
		//$this->autoRender = false;
		$this->layout = '';			
		$this->conditions = array('Obra.cliente_id'=>$id,'Obra.habilitado'=>1);
		
		$fields = array('Obra.id', 'Obra.endereco', 'Obra.nome');

		$obras = $this->Obra->find('all', array('conditions' => $this->conditions,'order'=>array('Obra.nome'=>'ASC')));
		
		$this->set('obras', $obras);
		$this->set('selected',(!empty($this->request->data['selected'])?$this->request->data['selected']:''));
	}
	
	public function admin_options_json($id) {
		//echo 'admin_options_json: ' . $id;
		
		$this->autoRender = false;
		
		
		$this->layout = '';			
		
		
		$this->conditions = array('Obra.cliente_id'=>$id,'Obra.habilitado'=>1);
		$fields = array('Obra.id', 'Obra.endereco', 'Obra.nome');
		$obras = $this->Obra->find('all', array('conditions' => $this->conditions,'order'=>array('Obra.nome'=>'ASC')));
		//var_dump($obras);
		
		echo json_encode($obras);
		exit;
	}
	
	public function admin_index($id) {
		$this->conditions = array('Obra.habilitado'=>1,'Obra.cliente_id'=>$id);
		parent::index();
		$this->set('cliente',$this->Obra->Cliente->findById($id));
		$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	public function admin_edit($cliente , $id = null) {
		
		parent::edit($id,$cliente);
//		if (!empty($this->request->data)) {
//			pr($this->request->data);
//			if ( $this->Obra->save($this->request->data)) {
//				$this->Session->setFlash('Registro editado com sucesso!', 'default', array('class'=>'alert alert-success'));
//				$this->redirect( array( 'action' => 'index'.$cliente ) );
//			}
//		}
		
		$estados = $this->Obra->Estado->find('list', array('fields' => array('Estado.nome')));
		foreach ($estados as &$val)
			$val = utf8_encode($val);
		$this->set('estados', $estados);
		if (!empty($this->request->data['Obra']['estado_id'])) {
			$this->set('cidades', $this->Obra->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Obra']['estado_id']))));
		}
		$vendedores=$this->Obra->User->find('list',array('conditions'=>array('User.nivel_id'=>2))); // Pesquisa somente vendedores
		
		$this->set('vendedores',$vendedores);
		$this->set('cliente',$this->Obra->Cliente->findById($cliente));
		$this->set('precos', $this->ObraProduto->find('all',array('conditions' => array('ObraProduto.obra_id'=>$id))));
		$this->set('produtos', $this->Produto->find('list'));
		
	}
	public function admin_precos($cliente , $id = null) {
		$this->set('cliente',$this->Obra->Cliente->findById($cliente));
		$this->set('precos', $this->ObraProduto->find('all',array('conditions' => array('ObraProduto.obra_id'=>$id))));
		$this->set('produtos', $this->Produto->find('list'));
		$this->set('cliente_id',$cliente);
		$this->set('obra',$id);
	}
	public function admin_precos_save() {
		$this->autoRender = false;
		$this->layout = '';	

		
		$item = $this->ObraProduto->find('first', array(
			
			'conditions' => array(
				'ObraProduto.obra_id'=>$this->request->data['obra_id'],
				'ObraProduto.produto_id'=>$this->request->data['produto_id']
			)
		));

		
		if($item){
			$this->request->data['id'] = $item['ObraProduto']['id'];
		}


		$this->request->data['preco'] = moedaBD($this->request->data['preco']);	
		$this->ObraProduto->save($this->request->data);

		$obra_id = $this->request->data['obra_id'];

		$this->request->data['id'] = null;
		$this->ObraProdutoHistorico->save($this->request->data);

		$json = $this->admin_precos_list($obra_id);
		return $json;
	}

	public function precos_list($id) {
		$this->autoRender = false;
		$this->layout = '';	
		$arr = $this->ObraProduto->find('all',array(
			'fields' => array(
				'ObraProduto.id',
				'ObraProduto.produto_id',
				'ObraProduto.preco'
			),
			'conditions' => array('ObraProduto.obra_id'=>$id)));

		$produtos  = $this->Produto->find('list');
		
		$json = array();
		foreach($arr as $k => $v){
			$json[] = array(
				'produto'=>$produtos[$v['ObraProduto']['produto_id']],
				'preco'=> moedaBr($v['ObraProduto']['preco']),
			);
		}

		return json_encode($json,true);
	}

	public function admin_precos_list($id) {
		$this->autoRender = false;
		$this->layout = '';	
		$arr = $this->ObraProduto->find('all',array(
			'fields' => array(
				'ObraProduto.id',
				'ObraProduto.produto_id',
				'ObraProduto.preco'
			),
			'conditions' => array('ObraProduto.obra_id'=>$id)));

		$produtos  = $this->Produto->find('list');
		
		$json = array();
		foreach($arr as $k => $v){
			$json[] = array(
				'produto'=>$produtos[$v['ObraProduto']['produto_id']],
				'preco'=> moedaBr($v['ObraProduto']['preco']),
			);
		}

		return json_encode($json,true);
	}

	public function gerente_precos_save() {
		$this->autoRender = false;
		$this->layout = '';	

		
		$item = $this->ObraProduto->find('first', array(
			
			'conditions' => array(
				'ObraProduto.obra_id'=>$this->request->data['obra_id'],
				'ObraProduto.produto_id'=>$this->request->data['produto_id']
			)
		));

		
		if($item){
			$this->request->data['id'] = $item['ObraProduto']['id'];
		}


		$this->request->data['preco'] = moedaBD($this->request->data['preco']);	
		$this->ObraProduto->save($this->request->data);

		$obra_id = $this->request->data['obra_id'];

		$this->request->data['id'] = null;
		$this->ObraProdutoHistorico->save($this->request->data);

		$json = $this->gerente_precos_list($obra_id);
		return $json;
	}


	public function gerente_precos_list($id) {
		$this->autoRender = false;
		$this->layout = '';	
		$arr = $this->ObraProduto->find('all',array(
			'fields' => array(
				'ObraProduto.id',
				'ObraProduto.produto_id',
				'ObraProduto.preco'
			),
			'conditions' => array('ObraProduto.obra_id'=>$id)));

		$produtos  = $this->Produto->find('list');
		
		$json = array();
		foreach($arr as $k => $v){
			$json[] = array(
				'produto'=>$produtos[$v['ObraProduto']['produto_id']],
				'preco'=> moedaBr($v['ObraProduto']['preco']),
			);
		}

		return json_encode($json,true);
	}



	
	

	public function admin_del($cliente ,$id) {
		$this->request->data['Obra']['habilitado']=0;
		parent::edit($id,$cliente);
	}

	public function admin_add($cliente) {$this->setAction('admin_edit',$cliente);}
	
	//
	//	VENDEDOR
	//
	public function vendedor_options($id) {$this->setAction('admin_options',$id);}
	//
	//	VENDEDOR
	//
	public function pedido_options($id) {$this->setAction('admin_options',$id);}
	
	//
	// Financeiro
	//
	public function financeiro_options($id) {$this->setAction('admin_options',$id);}
	
	//
	// Financeiro
	//
	public function programacao1_options($id) {$this->setAction('admin_options',$id);}
	
	//
	// Gerente
	//
	public function gerente_options( $id ){$this->setAction('admin_options',$id);}
	public function gerente_edit($cliente , $id = null){$this->setAction('admin_edit',$cliente,$id);}
	public function gerente_del($cliente , $id ){$this->setAction('admin_del',$cliente,$id);}
	public function gerente_add($cliente) {$this->setAction('admin_add',$cliente);}
	public function gerente_index($id) {$this->setAction('admin_index',$id);}
	public function gerente_options_json($id) {$this->setAction('admin_options_json',$id);}

	public function gerente_precos($cliente , $id ){$this->setAction('admin_precos',$cliente,$id);}
	//public function gerente_precos_save(){$this->setAction('admin_precos_save');}
	//public function gerente_precos_list($id){$this->setAction('precos_list',$id);}

	
	//
	// PEDIDOS1
	//
	public function pedidos1_options( $id ){$this->setAction('admin_options',$id);}
	public function pedidos1_edit($cliente , $id = null){$this->setAction('admin_edit',$cliente,$id);}
	public function pedidos1_del($cliente , $id ){$this->setAction('admin_del',$cliente,$id);}
	public function pedidos1_add($cliente) {$this->setAction('admin_add',$cliente);}
	public function pedidos1_index($id) {$this->setAction('admin_index',$id);}
	public function pedidos1_options_json($id) {$this->setAction('admin_options_json',$id);}
	//
	// PEDIDOS2
	//
	public function pedidos2_options( $id ){$this->setAction('admin_options',$id);}
	public function pedidos2_edit($cliente , $id = null){$this->setAction('admin_edit',$cliente,$id);}
	public function pedidos2_del($cliente , $id ){$this->setAction('admin_del',$cliente,$id);}
	public function pedidos2_add($cliente) {$this->setAction('admin_add',$cliente);}
	public function pedidos2_index($id) {$this->setAction('admin_index',$id);}
	public function pedidos2_options_json($id) {$this->setAction('admin_options_json',$id);}
	
}