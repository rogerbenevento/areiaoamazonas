<?php
class AbastecimentosController extends AppController
{
	public $model = 'Abastecimento';
	public $uses = array( 'Abastecimento');
	
	public $filter_with_like = Array('Abastecimento.placa');
	
	public function admin_index(){
		if(!empty($this->request->query['Abastecimento_data']))
			$this->request->query['Abastecimento_data'] =  datePhpToMysql($this->request->query['Abastecimento_data']);
		if(!empty($this->request->query['Abastecimento_placa']))
			$this->request->query['Abastecimento_placa'] = PlacaBD($this->request->query['Abastecimento_placa']);
		parent::index();
		$this->set( 'motoristas', $this->Abastecimento->Motorista->find('list') );
		
		if(!empty($this->request->query['Abastecimento_data']))
			$this->request->named['Abastecimento_data'] = dateMysqlToPhp($this->request->query['Abastecimento_data']);
		if(!empty($this->request->query['Abastecimento_placa']))
			$this->request->named['Abastecimento_placa'] = PlacaBr($this->request->query['Abastecimento_placa']);
	}
	
	public function admin_edit($id=null){
		if(!empty($this->request->data)){
			$DataSource = $this->Abastecimento->getDataSource();
			$DataSource->begin();
			
			$this->request->data['Abastecimento']['placa'] = DboSource::expression('(SELECT placa FROM motoristas WHERE id='.$this->request->data['Abastecimento']['motorista_id'].')');
			
			$this->Abastecimento->id = $id;
			if (!empty($this->request->data)) {
				if ($this->Abastecimento->save($this->request->data)) {
					$valor	= $this->request->data['Abastecimento']['valor'];
					$data	= $this->request->data['Abastecimento']['data'];
					// Gera a despesa respectiva ao abastecimento cadastrado!
					if($this->Abastecimento->Conta->Cadastrar($this->Abastecimento->id,$valor,$data,'a')){
						$DataSource->commit();
						$this->Session->setFlash('Abastecimento cadastrada com sucesso!', 'default', array('class'=>'alert alert-success'));
						$this->redirect( array( 'action' => 'index' ) );
					}
				}
				$DataSource->rollback();
			} else $this->data = $this->Abastecimento->read();
		}
		
		$this->set( 'motoristas', $this->Abastecimento->Motorista->find('list') );
	}
	
	public function admin_cancelar($id){
		
	}
	
	public function gerente_index(){ $this->setAction('admin_index');}
	public function gerente_edit($id=null){$this->setAction('admin_edit',$id);}
}