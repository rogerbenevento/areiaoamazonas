<?php
class Programacao extends AppModel {
	public $useTable  = 'programacao';
	public $order = array('Programacao.created' => 'ASC');
	public $displayField = 'item_pedido_id';
	public $belongsTo = array(
		'ItemPedido' => array('className' => 'ItemPedido', 'foreignKey' => 'item_pedido_id'),
	);

}
?>