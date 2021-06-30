<?php
class TiposPagamentosController extends AppController {
        public $model = 'TipoPagamento';
        public $uses = array('TipoPagamento');
//        public $filter_with_like = Array( 'Subcategoria.nome','Categoria.nome');
        
     public function condicao(){
		$this->autoRender = false;		
                $this->TipoPagamento->recursive=-1;
		echo json_encode($this->TipoPagamento->find('first',array('conditions'=>array('TipoPagamento.id'=>$this->request->data['forma_pagto']))));
	}
        
        public function admin_index(){
		parent::index();
	}
	
	public function admin_edit( $id = null ){
		parent::edit( $id );
	}
	public function admin_del($id){
		$this->request->data['TipoPagamento']['habilitado']=0;
		parent::edit( $id );
	}
	public function admin_add(){
		$this->setAction( 'admin_edit' );
	}
	public function admin_condicao(){
        $this->setAction('condicao');
	}

	//
	// GERENTE
	//
     public function gerente_condicao() {$this->setAction('admin_condicao');}

	//
	// FINANCEIRO
	//
     public function financeiro_condicao() {	$this->setAction('admin_condicao');}
	
	//
	// FINANCEIRO ADM
	//
     public function financeiroadm_index(){$this->setAction('admin_index'); }
     public function financeiroadm_add(){ $this->setAction('admin_add'); }
     public function financeiroadm_condicao(){ $this->setAction('admin_condicao'); }
     public function financeiroadm_edit($id=null){ $this->setAction('admin_edit',$id); }
     public function financeiroadm_del($id){ $this->setAction('admin_del',$id); }
	//
	// FINANCEIRO ADM
	//
     public function conta_index(){$this->setAction('admin_index'); }
     public function conta_add(){ $this->setAction('admin_add'); }
     public function conta_condicao(){ $this->setAction('admin_condicao'); }
     public function conta_edit($id=null){ $this->setAction('admin_edit',$id); }
     public function conta_del($id){ $this->setAction('admin_del',$id); }

	//
	//VENDEDOR
	//
	public function vendedor_condicao() {$this->setAction('condicao');}

}

//Class
?>
