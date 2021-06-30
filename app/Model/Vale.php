<?php
class Vale extends AppModel
{
	public $useTable = 'vales';
	public $validate = array(
		'fornecedor_id' => array('rule' => 'notEmpty', 'message' => 'Selecione um Fornecedor!'),
		'motorista_id' => array('rule' => 'notEmpty', 'message' => 'Selecione um Motorista!'),
		'item_pedido_id' => array('rule' => 'notEmpty', 'message' => 'Informe um item de pedido!'),
		'ItemPedido.pago' => array('rule' => 'notEmpty', 'message' => 'Informe o valor Pago!'),
		'data_entrega' => array('rule' => 'notEmpty', 'message' => 'Informe uma Data!')
	);
	public $hasMany = array(
		'ContaVale'		
		);
	public $belongsTo = array(
	    'ItemPedido'=>array('className'=>'ItemPedido', 'foreignKey'=>'item_pedido_id'),
	    'Motorista',
	    'Fornecedor',
	    'Periodo',
		'Empresa');

     public $status = array(0=>'Em Aberto',1=>'Entregue',2=>'Cancelado'); 
}