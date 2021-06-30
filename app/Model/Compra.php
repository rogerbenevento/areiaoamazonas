<?php
class Compra extends AppModel
{
	public $order = array( 'Compra.created' => 'DESC' );
	public $validate = array(
		'fornecedor_id' => array( 'rule' => 'notEmpty', 'message' => 'Informe o fornecedor' )
	);
	public $belongsTo = array('Fornecedor');
	public $hasOne = array('Conta');
	
	public function Incluir($compra){
		//Verifica se o parametro é um array
		if(!is_array($compra)) return false;
		//Verifica se o Array possui um array dentro
		if(isset($compra['Compra'])) $compra = $compra['Compra'];
		//Verifica se o array é valido
		if(!isset($compra['fornecedor_id']) && !isset($compra['valor'])) return false;
		$dataSource = $this->getDataSource();

		//pr($compra['valor']);
		
		if($this->save($compra)){
			// Salva a compra na tabela de contas
			if($this->Conta->Cadastrar($this->id,$compra['valor'],null,'c')){
				//	Atualiza Saldo do Fornecedor
				$this->Fornecedor->id = $compra['fornecedor_id'];
				if($this->Fornecedor->save(array('saldo'=>  DboSource::expression('(saldo+'.moedaBD($compra['valor']).')')))){
					$dataSource->commit();
					return true;
				}
			}				
		}
		$dataSource->rollback();
		return false;
		 
	}//Incluir
	
}