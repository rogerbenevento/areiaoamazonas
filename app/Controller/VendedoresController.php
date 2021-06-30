<?php

class VendedoresController extends AppController {

	public $model = 'Vendedor';
	public $uses = array('Vendedor', 'Estado','Cidade', 'User');
	public $filter_with_like = array('Vendedor.nome');

	public function admin_all() {
		//$this->autoRender = false;
		$this->layout = '';
		if (strlen($keyword = $_REQUEST['query']) > 3) {

			$this->conditions = array('or' => array('Vendedor.cpf_cnpj LIKE ' => "%{$keyword}%", 'Vendedor.nome LIKE ' => "%{$keyword}%"));
			$fields = array('Vendedor.id', 'Vendedor.nome', 'Vendedor.cpf_cnpj');

			$Vendedores = $this->Vendedor->find('all', array('conditions' => $this->conditions));
			$this->set('Vendedores', $Vendedores);
		} else {
			$this->set('Vendedores', array());
		}
	}

	/**
	 *  Admin
	 */
	public function admin_index() {
		parent::index();
		$this->set('departamentos', $this->Vendedor->departamentos );

		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
	}

	public function admin_edit($id = null) {
		if (!empty($this->request->data)) {
			foreach ($this->request->data['Vendedor'] as $campo => &$value) {
				if (($campo == 'data_nascimento' || $campo == 'data_admissao' || $campo == 'datasaida' || $campo == 'afastamento') && $value != '0000-00-00' and !empty($value))
					$value = datePhpToMysql($value);

				if ($campo == 'salario' and !empty($value))
					$value = moedaBD($value, 2);
			}
		}
//		pr($this->request->data);exit();
		$this->Vendedor->id = $id;
		if(!empty($this->request->data)){
			$dataSource=$this->Vendedor->getDataSource();
			$dataSource->begin();
			if ($this->Vendedor->save($this->request->data['Vendedor'])) {
				//Cria/Edita a conta do usuario
				$Vendedor = $this->Vendedor->read();
				
				$this->User->id = $Vendedor['Vendedor']['user_id'];
				$usuario['User'] = Array(
				    'nome'=>$Vendedor['Vendedor']['nome'],
				    'email'=>$Vendedor['Vendedor']['email'],
				    'username'=>$this->request->data['User']['username'],
				    'nivel_id'=>'2' //Vendedor
				);		
				if(empty($Vendedor['Vendedor']['user_id']))
					$usuario['User']['password'] = str_ireplace(array('.','-'), array(''), $this->request->data['User']['password']);
				//Salva o usuario referente ao vendedor
				if($this->User->save($usuario)){
					//Verifica se o vendedor ja possui a relacao com o usario
					if(empty($Vendedor['Vendedor']['user_id'])){
						//Atualiza o vendedor com o id do seu usuario
						$Vendedor['Vendedor']['user_id']=$this->User->getLastInsertID();
						if($this->Vendedor->save($Vendedor)){								
							$dataSource->commit();
							$this->Session->setFlash('Vendedor editado com sucesso!', 'default', array('class' => 'alert alert-success'));
							$this->redirect(array('action' => 'index' ));
						}
					}else{
						//Vendedor ja possui usuario atrelado
						$dataSource->commit();
						$this->Session->setFlash('Vendedor editado com sucesso!', 'default', array('class' => 'alert alert-success'));
						$this->redirect(array('action' => 'index' ));
					}
					
				}
			}
			$dataSource->rollback();
		}else
			$this->data = $this->Vendedor->read();


		$estados = $this->Estado->find('list', array('fields' => array('Estado.nome')));

		foreach ($estados as &$val)
			$val = utf8_encode($val);

		$this->set('estados', $estados);
		if (!empty($this->request->data['Vendedor']['estado_id'])) {
			$this->set('cidades', $this->Cidade->find('list', array('conditions' => array('Cidade.estado_id' => $this->request->data['Vendedor']['estado_id']))));
		}
	}

	public function admin_add() {
		$this->setAction('admin_edit');
	}

	public function admin_del($id) {
		parent::del($id);
	}

	//
	//  RH
	//
	public function rh_index() {
		$this->setAction('admin_index');
	}

	public function rh_add() {
		$this->setAction('rh_edit');
	}

	public function rh_edit($id = null) {
		$this->setAction('admin_edit', $id);
	}

	public function rh_del($id) {
		$this->setAction('admin_del', $id);
	}
	//
	//  GERENTE
	//
	public function gerente_index() {
		$this->setAction('admin_index');
	}

	public function gerente_add() {
		$this->setAction('rh_edit');
	}

	public function gerente_edit($id = null) {
		$this->setAction('admin_edit', $id);
	}

	public function gerente_del($id) {
		$this->setAction('admin_del', $id);
	}

}

//class