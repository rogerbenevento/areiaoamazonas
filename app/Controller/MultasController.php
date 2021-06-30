<?php
class MultasController extends AppController{
	
	public $model = 'Multa';
	public $uses = array( 'Multa');
	public $filter_with_like = Array('Multa.placa');
	
	public function admin_index(){
		if(!empty($this->request->query['Multa_data']))
			$this->request->query['Multa_data'] =  datePhpToMysql($this->request->query['Multa_data']);
		if(!empty($this->request->query['Multa_placa']))
			$this->request->query['Multa_placa'] = PlacaBD($this->request->query['Multa_placa']);
		parent::index();
		$this->set('motoristas', $this->Multa->Motorista->find('list') );
		
		if(!empty($this->request->query['Multa_data']))
			$this->request->named['Multa_data'] = dateMysqlToPhp($this->request->query['Multa_data']);
		if(!empty($this->request->query['Multa_placa']))
			$this->request->named['Multa_placa'] = PlacaBr($this->request->query['Multa_placa']);
	}
	
	public function admin_edit($id=null){
		if(!empty($this->request->data)){
			$DataSource = $this->Multa->getDataSource();
			$DataSource->begin();
			
			$this->request->data['Multa']['placa'] = DboSource::expression('(SELECT placa FROM motoristas WHERE id='.$this->request->data['Multa']['motorista_id'].')');
			
			$this->Multa->id = $id;
			if (!empty($this->request->data)) {
				if ($this->Multa->save($this->request->data)) {
					$DataSource->commit();
					$this->Session->setFlash('Multa cadastrada com sucesso!', 'default', array('class'=>'alert alert-success'));
					$this->redirect( array( 'action' => 'index' ) );
				}
				$DataSource->rollback();
			} else $this->data = $this->Multa->read();
		}
		
		$this->set( 'motoristas', $this->Multa->Motorista->find('list') );
	}
	
	public function admin_cancelar($id){
		
	}
	
	public function gerente_index(){ $this->setAction('admin_index');}
	public function gerente_edit($id=null){$this->setAction('admin_edit',$id);}
}