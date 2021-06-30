<?php
class PagamentosController extends AppController
{
	public $model = 'Pagamento';
	public $uses = array('Pagamento','Pedido','TipoPagamento','ItemPedido');
        
        public function novoIndice(){
            $indice=0;
            if($this->Session->check('pagamentos_pedidos'))
                foreach ($this->Session->read('pagamentos_pedidos') as $key => $value)
                    $indice = $key+1;
            return $indice;
	}
        
	public function add()
	{
		$this->layout = '' ;
                //$this->autoRender = false;
		//pr( $this->request->data ); exit();
		$indice = $this->novoIndice();
		$this->Session->write('pagamentos_pedidos.'.$indice, $this->request->data);
		$this->Session->write('pagamentos_pedidos.'.$indice.'.item',$indice);
		$this->set('p',$this->Session->read('pagamentos_pedidos.'.$indice));
                //echo json_encode(true);
	}
	
	public function del()
	{
		$this->autoRender = false;
		echo json_encode($this->Session->delete('pagamentos_pedidos.'.$this->request->data['indice']));;
	}
	
        //
        // Admin
        //
        public function admin_add(){
            $this->setAction('add');
        }
        
        public function admin_del(){
            $this->setAction('del');
        }
        
	public function admin_edit( $pedido )
	{
		if ( !empty( $this->request->data ) ) {
			#pr( $_SESSION['pagamentos'] ); exit;
			$conditions = array( 'Pagamento.pedidos_id' => $pedido );
			if ( $this->Pagamento->deleteAll($conditions  ) ) {
				$this->Pagamento->getDataSource()->begin();
				if ( $this->Pagamento->incluir( $this->Session->read('pagamentos_pedidos'), $pedido ) ) {
					$this->Session->delete('pagamentos_pedidos');
                                        $this->Session->setFlash('Formas de pagamentos modificadas com sucesso!', 'default', array('class'=>'alert alert-success'));
                                        $this->Pagamento->getDataSource()->commit();
				} else {
					$this->Session->setFlash('Ocorreu um erro ao modificar as formas de pagamento.', 'default', array('class'=>'alert alert-error'));
					$this->Pagamento->getDataSource()->rollback();
				}
			} else $this->Session->setFlash('Ocorreu um erro ao substituir as formas de pagamento.', 'default', array('class'=>'alert alert-error'));
			//=============== REDIRECIONAR PARA PEDIDOS/FINALIZAR
                        $this->redirect(array('controller'=>'pedidos','action' => 'finalizar',$pedido));
		}
		$itens = $this->ItemPedido->find('all',array("conditions"=>Array('ItemPedido.pedido_id'=>$pedido),"recursive"=>-1));
		$this->ItemPedido->montarCarrinho($pedido);
                $this->Pagamento->montarPagamentos($pedido);
                $ped = $this->Pedido->find('first',array("conditions"=>Array('Pedido.id'=>$pedido) ));
		$this->set('model',  $this->ItemPedido);
                $this->set( 'pedido', $ped );
		$this->set( 'valor_total', $this->ItemPedido->valorTotal( $itens, $ped['Pedido']['arredondamento'] ) );
		$this->Session->write( 'pagamentos', $this->Pagamento->find('first',array("conditions"=>Array('Pagamento.pedidos_id'=>$pedido) )));
		$this->set('tpPagamentos', $this->TipoPagamento->find('list',array('fields'=>array('TipoPagamento.nome'))));
	}
	
        //
        // GERENTE
        //
        public function gerente_add(){
            $this->setAction('add');
        }
        
        public function gerente_del(){
            $this->setAction('del');
        }
        
	public function gerente_edit( $pedido ){
            $this->setAction('admin_edit',$pedido);
        }
        //
        // FINANCEIRO
        //
        public function financeiro_add(){
            $this->setAction('add');
        }
        
        public function financeiro_del(){
            $this->setAction('del');
        }
        
	public function financeiro_edit( $pedido ){
            $this->setAction('admin_edit',$pedido);
        }
	//
	// Vendedor
	//
	public function vendedor_add()
	{
	    $this->setAction('admin_add');
	}
	public function vendedor_del()
	{
	    $this->setAction('admin_del');
	}
}//class