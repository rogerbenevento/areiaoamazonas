<?php
class ComprasController extends AppController
{
	public $model = 'Compra';
	public $uses = array( 'Compra','Fornecedor', 'CompraItem','Conta');
	
	public function admin_index()	{
		
		parent::index();
	}
	
	/**
	 * Faz a emissão de uma compra, adicionando os itens na tabela compras_itens
	 */
	public function admin_emitir(){
		$error = false;
		if ($this->request->is('post')){
			if ($this->Compra->Incluir($this->request->data)){
				$this->Session->setFlash('Compra cadastrada com sucesso!', 'default', array('class'=>'alert alert-success'));
			}else $this->Session->setFlash('Falha ao salvar compra!', 'default', array('class'=>'alert alert-error'));	

			$this->redirect(array('controller' => 'compras', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true));
			
		}
		$this->set( 'fornecedores', $this->Fornecedor->find('list') );
	}
	
	public function admin_cancelar( $id ){
		
	}
	
	//
	// DIRETOR
	//
	public function diretor_index(){ $this->setAction('admin_index');}
	public function diretor_emitir(){ $this->setAction('admin_emitir');}
	public function diretor_cancelar($id){ $this->setAction('admin_cancelar',$id);}
	//
	// Conta
	//
	public function conta_index(){ $this->setAction('admin_index');}
	public function conta_emitir(){ $this->setAction('admin_emitir');}
	public function conta_cancelar($id){ $this->setAction('admin_cancelar',$id);}
}