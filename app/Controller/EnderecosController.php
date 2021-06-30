<?php

class EnderecosController extends AppController {

	public $model = 'Endereco';
	public $filter_with_like = Array( 'Endereco.endereco');
	public $uses = Array('Endereco','View');
	
	//
	//ADMIN
	//
	public function admin_options($id) {
		//$this->autoRender = false;
		$this->layout = '';			
		$this->conditions = array('Endereco.cliente_id'=>$id,'Endereco.habilitado'=>1);
		
		$fields = array('Endereco.id', 'Endereco.endereco', 'Endereco.nome');

		$obras = $this->Endereco->find('all', array('conditions' => $this->conditions,'order'=>array('Endereco.nome'=>'ASC')));
		
		$this->set('obras', $obras);
		$this->set('selected',(!empty($this->request->data['selected'])?$this->request->data['selected']:''));
	}
	
	public function admin_index($id) {
		$this->conditions = array('Endereco.habilitado'=>1,'Endereco.cliente_id'=>$id);
		parent::index();
		$this->set('cliente',$this->Endereco->Cliente->findById($id));
	}

	public function admin_edit($cliente , $id = null) {
		
		parent::edit($id,$cliente);
		
		$estados = $this->Endereco->Estado->find('list', array('fields' => array('Estado.nome')));
		foreach ($estados as &$val)
			$val = utf8_encode($val);
		$this->set('estados', $estados);
		if (!empty($this->request->data['Endereco']['estado_id'])) {
			$this->set('cidades', $this->Endereco->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Endereco']['estado_id']))));
		}
		$this->set('cliente',$this->Endereco->Cliente->findById($cliente));
	}

	public function admin_del($cliente ,$id) {
		$this->request->data['Endereco']['habilitado']=0;
		parent::edit($id,$cliente);
	}

	public function admin_add($cliente) {
		$this->setAction('admin_edit',$cliente);
	}
	
	public function tabela(){
		$this->layout='';
	}
	
    	//
	// AdminAJAX
	//
	public function admin_adicionar(){
		$this->Session->read();
		if(!isset($this->request->data['cliente_id']))$this->request->data['cliente_id']=null;
		$ret=$this->Endereco->incluir($this->request->data['Endereco']);
		$this->set('tipos',$this->Endereco->tipo);
		if($ret['error']==false){			
			$view = new View($this, false);
			$ret['html'] = $view->element('clientes/tbenderecos');
			//$ret['html'] = "<tr class='{$ret['indice']}'>"
//						. "<td><a href='#' class='delete btn btn-del-endereco' id='{$ret['indice']}' title='REMOVER'><div class='icon icon-trash'></div></a></td>"
//						. "<td>{$this->request->data['dados']['nome']}</td>"
//						. "</tr>";
		}
		$this->autoRender=false;
		echo json_encode($ret);
	}
	
	public function admin_remover($indice){
		$this->autoRender=false;
		$this->Session->read();
		$ret=$this->Endereco->remover($indice);
		
		if($ret['error']==false){
			$view = new View($this, false);
			$ret['html']=$view->element('clientes/tbenderecos');
		}
		echo json_encode($ret);
	}
	
	public function admin_tabela(){	$this->setAction('tabela');}
	
	
	//
	// Gerente
	//
	public function gerente_options($id) {	$this->setAction('admin_options',$id);}
	public function gerente_edit($cliente , $id = null) {	$this->setAction('admin_edit',$cliente,$id);}
	public function gerente_del($cliente ,$id) {	$this->setAction('admin_del',$cliente,$id);}
	public function gerente_add($cliente) {	$this->setAction('admin_add',$cliente);}
	public function gerente_adicionar(){$this->setAction('admin_adicionar');}
	public function gerente_remover($indice){	$this->setAction('admin_remover',$indice);}
	public function gerente_tabela(){	$this->setAction('tabela');}
}