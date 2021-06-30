<?php
App::uses('Sanitize', 'Utility');
class UsersController extends AppController {

	public $model = 'User';
	public $filter_with_like = Array('User.nome', 'User.username', 'User.email');
	public $uses = array('User','Vendedor');

	public function admin_dashboard() {
		
	}

	public function by_user() {
		$this->layout = '';
		//Lista apenas vendedores da loja selecionada
		$loja_id = $this->request->data["loja_id"];
		$this->set('vendedores', $this->User->find('all', array('order' => 'User.nome ASC', 'conditions' => array('User.loja_id' => $loja_id))));
	}

	public function login() {
		$this->layout = 'login';
		$this->set('niveis', $this->User->niveis);
		if ($this->request->is('post')) {
//                pr(AuthComponent::password($this->request->data['User']['password']));
			$this->User->contain('Nivel.short');
			$this->User->virtualFields = array('nivel' => 'Nivel.short');
//			$user=$this->User->find('first',array('conditions'=>array(
//			    'User.nome'=>$this->request->data['User']['username'],
//			    'User.password'=>AuthComponent::password($this->request->data['User']['password'])
//				)));
			//$view = new View($this, false);
			//$content = $view->element('sql_dump');
			//pr($content);
			//exit();
			if ($this->Auth->login()) {
				
			
				$this->Session->write($this->Auth->sessionKey, $this->Auth->user());
				$this->redirect($this->Auth->redirect('/' . $this->Auth->user('nivel') . '/dashboard/'));
			} else
				$this->Session->setFlash('Dados incorretos');
		}
	}//login

	public function logout() {
		$this->Session->delete($this->Auth->sessionKey);
		$this->redirect($this->Auth->logout());
	}

	/**
	 * ADMIN
	 */
	public function admin_login() {
		$this->setAction('login');
	}//admin_login()

	public function admin_logout() {
		$this->setAction('logout');
	}//admin_logout

	public function admin_index() {
		$this->conditions['User.habilitado']=1;
		parent::index();
		$this->set('niveis', $this->User->Nivel->find('list'));
	}//admin_index

	public function admin_edit($id = null) {
		$continue = true;
		if(!empty($this->request->data)){
			
			
			if($this->User->find('count',array('conditions'=>array('User.username'=>$this->request->data['User']['username'],'User.id !='=>$id)))>0){
				$continue=false;
				$this->Session->setFlash('Já existe um usuário com esse login!', 'default', array('class'=>'alert alert-error'));
				
			}
			if($this->request->data['User']['nivel_id']==2){
				$vendedor = array(
				    'nome' =>  "'".Sanitize::escape($this->request->data['User']['nome'])."'",
				    'email' => "'".Sanitize::escape($this->request->data['User']['email'])."'"
				);
				$this->Vendedor->updateAll($vendedor,array('Vendedor.user_id'=> $id));
			}
		}
		if($continue)
			parent::edit($id);
		$this->set('niveis', $this->User->Nivel->find('list'));
	}

	public function admin_add() {
		$this->setAction('admin_edit');
	}//admin_add
	public function admin_del($id) {
		$this->request->data['User']['habilitado']=0;
		$this->request->data['User']['password']='DESatiVADO';
		parent::edit($id);
	}//admin_add

	public function admin_alterar_senha($id) {
//                pr($this->request->data);
//                pr($id);
		if (!empty($this->request->data)) {
			parent::edit($id);
//			if ( $this->User->save() )
//                                $this->Session->setFlash( 'Senha de usuário alterado com sucesso!', array('class'=>'alert alert-success'));
//                        else $this->Session->setFlash( 'Ocorreu um erro ao alterar a senha do usuário.', 'default', array('class'=>'alert alert-error'));
//                        $this->redirect( '/'.$this->Auth->user('nivel').'/users' );			
		}
//                pr($this->User->find('first', array('User.id =' => $id)));
		$this->set('user', $this->User->findById($id));
	}

	public function admin_by_user() {
		$this->setAction('by_user');
	}

	/**
	 * VENDEDOR
	 */
	public function vendedor_login() {	$this->setAction('admin_login');	}
	public function vendedor_logout() {		$this->setAction('admin_logout');	}

	/**
	 * FINANCEIRO
	 */
	public function financeiro_login() {	$this->setAction('login');	}
	public function financeiro_logout() {	$this->setAction('logout');	}

	/**
	 * LOGISTICA
	 */
	public function logistica_login() {	$this->setAction('admin_login');	}
	public function logistica_logout() {	$this->setAction('admin_logout');	}

	/**
	 * GERENTE
	 */
	public function gerente_login() {	$this->setAction('admin_login');	}
	public function gerente_logout() {	$this->setAction('admin_logout');}

	/**
	 * RH
	 */
	public function rh_login() {$this->setAction('admin_login');	}
	public function rh_logout() {	$this->setAction('admin_logout');	}
	/**
	 * Programacao
	 */
	public function programacao_login() {	$this->setAction('admin_login');}
	public function programacao_logout() {	$this->setAction('admin_logout');}
	/**
	 * Programacao
	 */
	public function pedido_login() {	$this->setAction('admin_login');}
	public function pedido_logout() {	$this->setAction('admin_logout');}
	/**
	 * Diretor
	 */
	public function diretor_login() {	$this->setAction('admin_login');}
	public function diretor_logout() {	$this->setAction('admin_logout');}
	/**
	 * Programacao1
	 */
	public function programacao1_login() {	$this->setAction('admin_login');}
	public function programacao1_logout() {	$this->setAction('admin_logout');}
	/**
	 * Programacao2
	 */
	public function programacao2_login() {	$this->setAction('admin_login');}
	public function programacao2_logout() {	$this->setAction('admin_logout');}
	/**
	 * Pedidos1
	 */
	public function pedidos1_login() {	$this->setAction('admin_login');}
	public function pedidos1_logout() {	$this->setAction('admin_logout');}
	/**
	 * Pedidos2
	 */
	public function pedidos2_login() {	$this->setAction('admin_login');}
	public function pedidos2_logout() {	$this->setAction('admin_logout');}
	/**
	 * Pedidos2
	 */
	public function nota_login() {	$this->setAction('admin_login');}
	public function nota_logout() {	$this->setAction('admin_logout');}
	/**
	 * Pedidos2
	 */
	public function conta_login() {	$this->setAction('admin_login');}
	public function conta_logout() {	$this->setAction('admin_logout');}

}//class