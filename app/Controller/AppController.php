<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
//	public $components = array('Session', 'RequestHandler', 'Auth', 'Mail');
	public $components = array( /*'Security',*/ 'Session', 'RequestHandler', 'Auth' );
	public $helpers = array( 'Html', 'Form', 'Session', 'Time', 'Text', 'Number' );
	public $model;
	public $not_condition = array('page', 'direction', 'sort');
	public $filter_with_like = array();
	public $conditions = array();
	public $limit = 30;
	
	/**
	 * Verifica se está dentro de um prefixo
	 *
	 * @param string $prefix
	 *
	 * @return boolean
	 */
	protected function isPrefix( $prefix )
	{
		return isset( $this->request->params['prefix'] ) &&
			   $this->request->params['prefix'] === $prefix;
	}
	
	/**
	 * Antes de filtrar as actions da aplicaçao
	 * 
	 * Troca o layout do admin
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		
		//$this->Auth->authError = 'Área restrita';
		// No windows 'controller' funciona, mas no linux deve ser 'Controller'
		$this->Auth->authorize = 'Controller';
		$this->Auth->loginRedirect = array('controller' => 'dashboard', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true);
		
//                pr($this->request->params['prefix']);
                
		// Painel de controle
		if ( $this->isPrefix( 'admin' ) 
			|| $this->isPrefix( 'vendedor' ) 
			|| $this->isPrefix( 'financeiro' ) 
			|| $this->isPrefix( 'gerente' ) 
			|| $this->isPrefix( 'logistica' ) 
			|| $this->isPrefix( 'programacao' ) 
			|| $this->isPrefix( 'pedido' ) 
			|| $this->isPrefix( 'pedidos1' ) 
			|| $this->isPrefix( 'pedidos2' ) 
			|| $this->isPrefix( 'programacao1' ) 
			|| $this->isPrefix( 'programacao2' ) 
			|| $this->isPrefix( 'conta' ) 
			|| $this->isPrefix( 'nota' ) 
			) {
			#$this->layout = 'admin';
			
			// Configuração do AuthComponent
			
			//$this->Auth->sessionKey = 'Auth.User';
			//pr($this->Session->read()()
		} else {
			//$this->Session->destroy();
			//$this->Auth->allow();
			//$this->layout = 'site';
		}
	}
	
	/**
	 * Define se um usuário pode acessar uma página
	 * 
	 * @param array $user
	 */
	public function isAuthorized( $user )
	{
//		return $this->Session->check( $this->Auth->sessionKey );
	    //if the prefix is setup, make sure the prefix matches their role 
	    if( isset($this->params['prefix'])) 
		return (strcasecmp($this->params['prefix'],$this->Auth->user('nivel'))===0); 

	    //shouldn't get here, better be safe than sorry 
	    return false;  
	}
	
	protected function gerarFiltros()
	{
		if ( !empty( $this->params->query ) ) {
			$this->params->named = array_merge( $this->params->named, $this->params->query );
		}
		
		if ( !empty( $this->params->named ) ) {
			foreach ( $this->params->named as $campo => $valor ) {
				if ( $valor != "" && !in_array( $campo, $this->not_condition ) ) {
					$campo = alias( $campo );
					if ( in_array( $campo, $this->filter_with_like ) ) {
						$this->conditions[$campo .' LIKE _utf8'] = "%{$valor}%";
					} else $this->conditions[$campo] = $valor;
				}
			}
		}
		#echo "<pre>"; pr( $this->conditions ); echo "</pre>";
	}
	
	public function index($options=null) {
		
		self::gerarFiltros();
		
		if(empty($options))
			$options = array();
		
		if(empty($options['limit']))
			$options['limit'] = $this->limit;
//		if(!empty($options['contain']))
//			$this->{$this->model}->Behaviors->load('Containable');
		if(empty($options['extra']['recursive']))
		$options['extra']['recursive'] = -1;
		$options['conditions']=$this->conditions;
		
		$this->paginate = $options;
		
		
		//$this->paginate['maxLimit'] = 500;
		$rows = $this->paginate($this->model);
		if (isset($this->params['requested'])) {
			return $rows;
		} else {
			$this->set('rows', $rows);
			$this->set('total', $this->params['paging'][$this->model]['count']);
			
		}
	}
	
	public function edit( $id = null,$posfix=null)
	{
		$posfix=($posfix!=null)?'/'.$posfix : '';
		$this->{$this->model}->id = $id;
		if (!empty($this->request->data)) {
			if ($this->{$this->model}->save($this->request->data)) {
				$this->Session->setFlash('Registro editado com sucesso!', 'default', array('class'=>'alert alert-success'));
				$this->redirect( array( 'action' => 'index'.$posfix ) );
			}
		} else $this->data = $this->{$this->model}->read();
	}
	
	public function add()
	{
		$this->setAction('edit');
	}
	
	public function del($id,$posfix=null)
	{
                $posfix=($posfix!=null)?'/'.$posfix : '';
		$this->autoRender = false;
		if ($this->{$this->model}->delete($id))
			$this->Session->setFlash('Registro excluido com sucesso!', 'default', array('class'=>'alert alert-success'));
		else $this->Session->setFlash('Ocorreu um erro ao excluir o registro.', 'default', array('class'=>'alert alert-error'));
		$this->redirect(array('action' => 'index'.$posfix));
	}
	
	public function SqlDump(){
		$view = new View($this);
		return $view->element('sql_dump');		
	}
}

//nao esquece do Git!