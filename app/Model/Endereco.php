<?php
class Endereco extends AppModel{
	
	public $order = array( 'Endereco.id' => 'ASC' );
	public $displayField = 'endereco';
	public $validate = array(
		'endereco' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Endereco!' ),
		'tipo_id' => array( 'rule' => 'notEmpty', 'message' => 'Informe o Endereco!' )		
	);
	public $belongsTo=array('Cliente','Estado','Cidade');
	
	public $tipo = array(		
		//2=>'Endereço da Empresa',
		1=>'Endereço de Faturamento',
		3=>'Endereço p/ Envio de Nota Fiscal'
	);
	
	
	private function getIndice(){
		$indice = 0 ;
		if(!empty($_SESSION['enderecos']) and is_array($_SESSION['enderecos']))
			foreach ($_SESSION['enderecos'] as $key => $value)
				$indice = $key +1;			
		return $indice;
	}
	/**
	 * incluir() - Inclui um item do endereco
	 * @param integer $cliente - ID do cliente
	 * @param array $value - Array do item do endereco
	 * @return boolean
	 */
	public function incluir($value,$dados_ext=null){
		//Ajusta o array caso venha do metodo find(montar)
		//pr($dados_ext);
		if(!empty($dados_ext)){
			$value['estado_id']=$dados_ext['Estado']['id'];		
			$value['estado_nome']=$dados_ext['Estado']['nome'];
			$value['cidade_id']=$dados_ext['Cidade']['id'];		
			$value['cidade_nome']=$dados_ext['Cidade']['nome'];
		}
		//pr($value);
		$indice = $this->getIndice();
		//pr($value);exit();
		$_SESSION['enderecos'][$indice] = Array(
			'indice'		=> $indice,
			'tipo_id'	=>  @$value['tipo_id'],	
			'endereco'	=>@$value['endereco'],
			'cep'	=>  @$value['cep'],
			'numero'	=>  @$value['numero'],
			'bairro'		=>@$value['bairro'],
			'estado_id'	=>@$value['estado_id'],
			'estado_nome'	=>@$value['estado_nome'],
			'cidade_id'	=>@$value['cidade_id'],
			'cidade_nome'	=>@$value['cidade_nome'],
			'complemento'	=>@$value['complemento']
		 );

		if(!empty($_SESSION['enderecos'][$indice])){
			return array('error'=>false,'indice'=>$indice);
		}else return array('error'=>true);
		
	}//incluir
	/**
	 * remover() - Remove um item do endereco
	 * @param array $item - Id do item do endereco
	 * @return boolean
	 */
	public function remover($indice){
		unset($_SESSION['enderecos'][$indice]);
		if(empty($_SESSION['enderecos'][$indice])){
			return array('error'=>false);
		}else{
			return array('error'=>true);
		}
	}
	/**
	 * montar() - Recupera a sessão de horaatendimento de um determinado cliente
	 * @param integer $cliente - ID do cliente
	 * @return Sessão de Carrinho
	 */
	public function montar($cliente=null,$limpar=true)
	{
		if($limpar and isset($_SESSION['enderecos'])){
			unset($_SESSION['enderecos']);
			$_SESSION['enderecos']=Array();
		 }
		if(!empty($cliente)){
			$this->recursive = 1;
			$this->Behaviors->attach('Containable');
			$this->contain('Cidade','Estado');
			$enderecos = $this->findAllByClienteId($cliente);
			//pr($enderecos);exit();
			if(!empty($enderecos))
				foreach ($enderecos as $local) {					
					$this->incluir($local['Endereco'],$local);
				}//foreach
		}//if pedido
	}//montarCarrinho
}

