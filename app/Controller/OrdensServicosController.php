<?php
class OrdensServicosController extends AppController
{
	public $model = 'OrdemServico';
	public $uses = array('OrdemServico', 'OrdemServicoItem', 'Produto', 'Material');
	
	public function admin_index()
	{
		$this->conditions['OrdemServico.status'] = array('0', '1');
		parent::index();
	}
	
	public function admin_abertas()
	{
		$this->layout = 'relatorio';
		$this->conditions['OrdemServico.status'] = '0';
		$this->set('ordens', $this->OrdemServico->find('all', array('recursive'=>2, 'conditions'=>$this->conditions, 'order'=>array('Pedido.data_entrega'=>'ASC'))));
	}
	
	public function admin_add()
	{
		if ($this->request->is('post')) {
			$dataSource = $this->OrdemServico->getDataSource();
			$dataSource->begin();
			
			#echo "<pre>"; print_r($this->request->data); echo "</pre>"; exit();
			if ($this->OrdemServico->save($this->request->data)) {
				if (!$this->OrdemServicoItem->incluir($this->OrdemServico->id, $this->request->data['OrdemServico']['produto_id'], $this->request->data['OrdemServico']['quantidade_solicitada'])) {
					$dataSource->rollback();
					$this->Session->setFlash('Não há estoque de Matéria Prima suficiente para essa quantidade de produto.', 'default', array('class'=>'alert alert-error'));
				} else {
					$dataSource->commit();
					$this->Session->setFlash('Criada Ordem de Serviço #'. $this->OrdemServico->id, 'default', array('class'=>'alert alert-success'));
				}
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('produtos', $this->Produto->find('list'));
	}
	
	public function admin_finalizar($id)
	{
		$this->OrdemServico->id = $id;
		if (!empty($this->request->data)) {
			$dataSource = $this->OrdemServico->getDataSource();
			$dataSource->begin();
			$qtd_pro = number_format($this->request->data['OrdemServico']['quantidade_produzida'], 2, '.', ''); 
			
			if ($this->OrdemServico->finalizar($id, $qtd_pro)) {
				$this->Session->setFlash('Ordem de serviço finalizada com sucesso!', 'default', array('class'=>'alert alert-success'));
				$dataSource->commit();
			} else {
				$this->Session->setFlash('Ocorreu um erro ao finalizar a ordem de serviço.', 'default', array('class'=>'alert alert-error'));
				$dataSource->rollback();
			}
			$this->redirect(array('action' => 'index'));
		} else $this->request->data = $this->OrdemServico->read();
	}
	
	public function admin_del($id)
	{
		parent::del($id);
	}
}