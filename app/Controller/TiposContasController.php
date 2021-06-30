<?php
	class TiposContasController extends AppController {

		public $model = 'TipoConta';
		public $filter_with_like = Array('TipoConta.nome');
		public $uses = Array('TipoConta','Conta');

		public function by_tipo() {
			$this->conditions = array('TipoConta.tipo' => $this->params->query['tipo']);
			$this->set('tipocontas', $this->TipoConta->find('list', array('conditions' => $this->conditions)));
		}

		public function getAllJson($categoria_id = null) {
			$this->autoRender = false;
			if ($this->param('categoria_id') != '')
				$categoria_id = $this->param('categoria_id');
			$TipoContas = $this->TipoConta->find('all', array(
				'conditions' => array('categoria_id' => $categoria_id),
				'recursive' => -1,
				'order' => 'nome'
				)
			);
			echo json_encode($TipoContas);
		}

		//
		//ADMIN
		//
		public function admin_index() {
			parent::index();
			$this->set('tipos', $this->Conta->tipos);
		}
		public function admin_edit($id = null) {
			parent::edit($id);
			$this->set('tipos', $this->Conta->tipos);
		}
		public function admin_add(){$this->setAction('admin_edit');	}
		public function admin_by_tipo(){$this->setAction('by_tipo');}
		
		//
		// FINANCEIRO ADM
		//
		public function financeiro_index(){$this->setAction('admin_index');}
		public function financeiro_add(){$this->setAction('admin_add');}
		public function financeiro_by_tipo(){$this->setAction('admin_by_tipo');}
		public function financeiro_edit($id=null){$this->setAction('admin_edit',$id);}
		//
		// CONTA
		//
		public function conta_index(){$this->setAction('admin_index');}
		public function conta_add(){$this->setAction('admin_add');}
		public function conta_by_tipo(){$this->setAction('admin_by_tipo');}
		public function conta_edit($id=null){$this->setAction('admin_edit',$id);}
	}
?>
