<?php
class Obra extends AppModel{
	
	public $order = array( 'Obra.id' => 'ASC' );
	public $displayField = 'endereco';
	public $validate = array(
		'nome' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Nome!' ),
		'endereco' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Endereco!' )		
	);
	public $belongsTo=array(
		'Cliente','Estado','Cidade',
		'User'=>array('foreignKey'=>'vendedor_id')
		);
	
	public function beforeSave(){
		
		if ( !empty( $this->data['Obra']['custo_extra'] ) ) {
			$this->data['Obra']['custo_extra'] = moedaBD($this->data['Obra']['custo_extra']);
		}		
		
		return true;
	}
}

