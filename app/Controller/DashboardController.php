<?php
class DashboardController extends AppController
{
	public $uses = array('Pedido','ItemPedido','Pagamento');
	
	public function admin_index(){ 
           
        }
	public function financeiro_index(){
		//$this->redirect(array('controller'=>'contas','action'=>'index'));
	}
	public function gerente_index(){ }
	public function vendedor_index(){
            $this->ItemPedido->montarCarrinho(null, true);
            $this->Pagamento->montarPagamentos(null);
            //$this->Session->delete('arredondamento');
            //$this->Session->delete('desconto');
        }
	public function pedido_index(){ $this->setAction('vendedor_index'); }
	public function logistica_index(){ }
	public function rh_index(){ }
	public function programacao_index(){ }
	public function diretor_index(){ }
	public function programacao1_index(){ }
	public function programacao2_index(){ }
	public function pedidos1_index(){ }
	public function pedidos2_index(){ }
	public function conta_index(){ }
	public function nota_index(){ }
}