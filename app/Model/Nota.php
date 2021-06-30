<?php
class Nota extends AppModel
{
	public $useTable = 'notas';
	public $order = 'Nota.created DESC';
	public $belongsTo = array( 
		'Empresa','Cliente'
	);
	public $hasMany = array('ItemNota');
	public $situacoes = array(
		'Simples Remessa'   => 'Simples Remessa',
		'Brinde'            => 'Brinde',
		'Cancelado'         => 'Cancelado',
		'Troca'             => 'Troca',
		'Devolução'         => 'Devolução'
	);
    
    
    public $indpres = array(
    	'' => '',
		'0'   => '0 - Não se aplica',
		'1'   => '1 - Operação presencial',
		'2'   => '2 - Operação não presencial, pela Internet',
		'3'   => '3 - Operação não presencial, Teleatendimento',
		'4'   => '4 - NFC-e em operação com entrega a domicílio',
		'9'   => '9 - Operação não presencial, outros'
	); 

    public $procemi = array(
    	'' => '',
		'0'   => '0 - Emissão de NF-e com aplicativo do contribuinte',
		'1'   => '1 - Emissão de NF-e avulsa pelo Fisco',
		'2'   => '2 - Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco',
		'3'   => '3 - Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco'
	);

   /*
    public $procemi = array(
		'0'   => 'Emissão de NF-e com aplicativo do contribuinte',
		'1'   => 'Emissão de NF-e avulsa pelo Fisco',
		'2'   => 'Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco',
		'3'   => 'Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco'
	);*/

	public function beforeSave($options = array())
	{
		
		// Quando usando uuario financeiro, nao encontrava o index Admin
		if ( !empty( $this->data['Nota']['base_calculo_icms'] ) ) {
			$this->data['Nota']['base_calculo_icms'] = moedaBD($this->data['Nota']['base_calculo_icms']);
		}		
		if ( !empty( $this->data['Nota']['emissao'] ) ) {
			$this->data['Nota']['emissao'] = datePhpToMysql($this->data['Nota']['emissao']);
		}		
		if ( !empty( $this->data['Nota']['vencimento'] ) ) {
			$this->data['Nota']['vencimento'] = datePhpToMysql($this->data['Nota']['vencimento']);
		}		
		return true;
	}
}
?>
