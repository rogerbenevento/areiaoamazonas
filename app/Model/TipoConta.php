<?php
class TipoConta extends AppModel{
	public $useTable = 'tipos_contas';
	public $order = array( 'TipoConta.nome' => 'ASC' );
	public $displayField = 'nome';	
	
	public $validate = array(
		'tipo' => array('rule' => 'notEmpty', 'message' => 'Informe o Tipo'),
		'nome' => array('rule' => 'notEmpty', 'message' => 'Informe o Nome')			
	);
	
	public function beforeSave($options = array()) {
		if ( !empty( $this->data['TipoConta']['nome'] ) ) {
			$this->data['TipoConta']['nome'] = TextoToUp($this->data['TipoConta']['nome']);
		}
		return parent::beforeSave($options);
	}
}
?>