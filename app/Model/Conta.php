<?php

class Conta extends AppModel {

	public $order = array('Conta.data_vencimento' => 'ASC');
	public $validate = array(
	    'tipo' => array('rule' => 'notEmpty', 'message' => 'Informe um tipo de conta'),
	    'descricao' => array('rule' => 'notEmpty', 'message' => 'Informe uma descrição da conta'),
	    'valor' => array('rule' => 'notEmpty', 'message' => 'Informe um valor da conta'),
	    'data_vencimento' => array('rule' => 'notEmpty', 'message' => 'Informe uma data da conta'),
	    'data_pagamento' => array('rule' => 'notEmpty', 'message' => 'Informe uma data de pagamento')
	);
	
	public $tipos = array(0 => 'Despesa', 1 => 'Receita');
	
	public $status= array(
		-1 => 'Cancelado',
		0 => 'Em aberto',
		1 => 'Pago',
		2 => 'Adiantado'
	);
	
	public $belongsTo = array(
		'Fornecedor',
		'Pedido',
		
		'Compra',
		'Abastecimento',
		'Empresa',
		'TipoPagamento',
		'TipoConta',
		);
	
	public $hasMany = array(
		'ContaPedido',
		'ContaVale'
	);
	
	#public $belongsTo = array('Pedido', 'Compra');

	public function beforeSave(){
		$this->data['Conta']['user_id'] = $_SESSION['Auth']['User']['id'];	
		// Quando usando uuario financeiro, nao encontrava o index Admin
		if ( !empty( $this->data['Conta']['valor'] ) ) {
			$this->data['Conta']['valor'] = moedaBD($this->data['Conta']['valor']);
		}		
		
		if (!empty($this->data['Conta']['data_vencimento'])) {
			$this->data['Conta']['data_vencimento'] = !preg_match('/-/', $this->data['Conta']['data_vencimento']) ? datePhpToMysql($this->data['Conta']['data_vencimento']) : $this->data['Conta']['data_vencimento'];
			#$this->data['Conta']['vencimento'] = $this->dateFormatBeforeSave($this->data['Conta']['vencimento']);
		}		
		if ( !empty( $this->data['Conta']['data_pagamento'] ) ) {
			$this->data['Conta']['data_pagamento'] = datePhpToMysql( $this->data['Conta']['data_pagamento'] );
		}
		return true;
	}
	/**
	 * saveParcels() - Recebe os dados vindos do formulário de contas e faz a inclusão de todas as parcelas no banco de dados
	 * @param array $data - Array com os dados vindos do form
	 */
	public function saveParcels(Array $data){
		if(!empty($data['Conta']['id'])){
			$bk=$this->recursive;
			$this->recursive=-1;
			$conta_original = $this->findById($data['Conta']['id']);
			$this->recursive=$bk;
		}else $conta_original=null;
		$datasource = $this->getDataSource();
		$datasource->begin();
		
		if (!empty ($conta_original['Conta']['conta_id'])) {
			// Caso NÃO seja a primeira parcela cancelar, a partir da editada
			if ( !self::cancelParcels( $conta_original['Conta']['conta_id'], $conta_original['Conta']['data_vencimento'] ) ) 
				return false;
			$conta_id = $conta_original['Conta']['conta_id'];
		} else {
			if((!empty($data['Conta']['id']) || empty($data['Conta']['conta_id'])) && !empty($conta_original))
				if ( !self::cancelParcels( $conta_original['Conta']['id'], $conta_original['Conta']['data_vencimento'] ) ) 
					return false;
			$conta_id = null;
		}
		
		$dataAnterior = !preg_match('/-/', $data['Conta']['data_vencimento']) ? datePhpToMysql($data['Conta']['data_vencimento']) : $data['Conta']['data_vencimento'];
		$firstParcel = $data['Conta']['parcela'];
		
		$data['Conta']['saldo'] = $data['Conta']['valor'];
		
		#CONTROLE DE CONTA CORRENTE
		#if(empty($data['Conta']['loja_id']) && empty($data['Conta']['conta_corrente_id']))
		#	if($data['Conta']['tipo']==0)
		#		$data['Conta']['conta_corrente_id'] = DboSource::expression('(SELECT conta_corrente_id FROM lojas_contas WHERE loja_id='.$data['Conta']['loja_id'].' AND tipo_pagamento_id = '.$data['Conta']['tipo_pagamento_id'].')');
		#	else
		#		$data['Conta']['conta_corrente_id'] = DboSource::expression('(SELECT conta_corrente_id FROM lojas_contas WHERE loja_id='.$data['Conta']['loja_id'].' AND tipo_pagamento_id = '.$data['Conta']['tipo_pagamento_id'].')');
		
		if(!empty($data['Conta']['IntervaloParcela'])){
			if(!empty($data['Conta']['IntervaloParcela']['intervalo']))
				$intervalos=$data['Conta']['IntervaloParcela']['intervalo'];
			if(!empty($data['Conta']['IntervaloParcela']['valor']))
				$valores=$data['Conta']['IntervaloParcela']['valor'];
			//pr($valores);
		}
		
		$key_intervalos=0;
		
		for ( $i = $firstParcel; $i <= $data['Conta']['parcelas']; $i++ ) {
			$conta = $data;
			$conta['Conta']['id'] = null;
			$conta['Conta']['parcela'] = $i;
			$conta['Conta']['conta_id'] = $conta_id;
			$conta['Conta']['data_vencimento'] = ( $i == $firstParcel ) ? $dataAnterior : 
				(empty($intervalos[$key_intervalos]) ? addMonthIntoDate($dataAnterior, 1) : $intervalos[$key_intervalos++]);
			
			if(!empty($valores[$key_intervalos]))
				$data['Conta']['valor'] =  moedaBD ($valores[$key_intervalos]);
			
			$dataAnterior = $conta['Conta']['data_vencimento'];
			
			unset($conta['Conta']['intervalos']);
			
			if ( !$this->save( $conta ) ) {
				$datasource->rollback();
				return false;
			} else{ 			
				if( $i == $firstParcel && empty($conta_id) )
					$conta_id = $this->id;
				$this->id = null;
			}
		}//for
//		$datasource->rollback();
//		pr($this->SqlDump());exit();
		$datasource->commit();
		return $conta_id;
	}

	/**
	 * cancelParcels() - Cancela todas as contas de um serviço do cliente
	 * @param integer $conta_id - ID do cliente Serviço
	 * @param integer $status - Status da conta (Por default o padrão é cancelar somente contas em aberto)
	 * @return boolean
	 */
	public function cancelParcels( $conta_id, $data=null, $status = 0 ){
		$conditions = array(
			'or'=>array('Conta.id' => $conta_id,'Conta.conta_id' => $conta_id),
			'Conta.status' => $status			
		);
		if($data!=null)
			$conditions['Conta.data_vencimento >=']=$data;
		else{
			$con = $this->findById($conta_id);
			$conditions['Conta.conta_id'] = $con['Conta']['conta_id'];
			$conditions['Conta.data_vencimento >=']=$con['Conta']['data_vencimento'];
		}
		
		$contas = $this->find('count', array('conditions' => $conditions));
		//pr($this->SqlDump());
		//pr($conditions);exit();
		if ($contas) {
			unset($conditions['or']);
			if(!isset($conditions['Conta.conta_id']))
				$conditions['Conta.conta_id']=$conta_id;
			if ( $this->deleteAll( $conditions, false ) ) {
				unset($conditions['Conta.conta_id']);
				$conditions['Conta.id']=$conta_id;
				return ($this->deleteAll( $conditions, false )) ? true : false;
			}else return false;
		} else return true;
	}

	//Registrar o pedido em conta
	public function Cadastrar($id, $total, $dtconta = null, $foreingkey = 'p') {
		$conta=Array();
		if ($foreingkey == 'p') {
			$label = 'Pedido';
			$conta['pedido_id']=$id;
			$conta['tipo'] = 1;
		} else if ($foreingkey == 'c') {
			$label = 'Compra';
			$conta['compra_id']=$id;
			$conta['tipo'] = 0;
		} else if ($foreingkey == 'a') {
			$label = 'Abastecimento';
			$conta['abastecimento_id']=$id;
			$conta['tipo'] = 0;
		}
		if (!isset($conta['pedido_id']) || !isset($conta['compra_id'])) {
			
			$conta['descricao'] = $label . ": " . $id;
			$conta['parcelas'] = 1;
			$conta['parcela'] = 1;
			$conta['data_vencimento'] = ($dtconta == null) ? date("Y-m-d") : $dtconta;
			$conta['valor']=$total;
			//insere no banco de dados
			if(!$this->save($conta))
				return false;
			//$this->query("INSERT INTO contas (tipo, descricao, observacao, valor, data, {$column}, parcelas, parcela) values 
			//	('{$tipo}', '{$descricao}', '{$descricao}', '{$total}', '{$data}', '{$id}', '{$parcelas}', '{$parcela}')");
			
			return true;
		}
		return false;
	}

}