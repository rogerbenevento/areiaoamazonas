<?php
class ContasController extends AppController{
	public $model = 'Conta';
	public $uses = array('Conta','TipoPagamento','TipoConta','Vale');
	public $_id;
	
	public function beforeFilter(){
		parent::beforeFilter();
		

		if(!empty($this->request->query)){
			if(!empty($this->request->query['Conta_data_vencimento']))
				$this->request->query['Conta_data_vencimento']= datePhpToMysql($this->request->query['Conta_data_vencimento']);
		}		
	}
	
	public function admin_index(){
		
	}


	
	public function admin_all(){
		$this->layout = '';
		
		if ( !empty( $this->params->query ) ) {
			$ano = $this->params->query['ano'];
			$mes = $this->params->query['mes'];
			#$loja_id = $this->params->query['loja_id'] ;
		} else {
			//Primeiro acesso
			$ano = date('Y');
			$mes = date('m');	
			#$loja_id = null;
			$this->params->query['tipo'] = 0;			
		}
		
		self::gerarFiltros();
		$this->conditions=array();
		$this->conditions['status >='] = 0;
		$this->conditions['MONTH(data_vencimento)'] = $mes;
		$this->conditions['YEAR(data_vencimento)'] = $ano;
		#if(!empty($loja_id))$this->conditions['Conta.loja_id']=$loja_id ;
				
		if( isset($this->params->query['tipo'])  ){
			//debug($this->params->query['tipo']);
			$this->conditions['Conta.tipo'] = $this->params->query['tipo'];
		}
		
		$this->Conta->Behaviors->load('Containable');
		$this->Conta->contain(array(
				'Empresa',		
				'TipoConta',
				'TipoPagamento',
				'Fornecedor'
			));
		
		
		$rows = $this->Conta->find('all', array( 'conditions' => $this->conditions ));
		
		$this->set('tipos_pagamentos', $this->Conta->TipoPagamento->find('list'));
		$this->set('tipos',$this->Conta->TipoConta->find('list'));
		$this->set( 'total', count( $rows ) );
		$this->set( 'rows', $rows );
		$dump=new View($this);
		$this->set('sql_debug',$dump->element('sql_dump'));
	}
	
	public function admin_add(){
		if (!empty($this->request->data)){
			$this->request->data['Conta']['user_created']=$this->Session->read('Auth.User.id');
			$this->Conta->set($this->request->data);
			if ($this->Conta->validates()){
				
				if(!empty($this->request->data['IntervaloParcela']))
					$this->request->data['Conta']['IntervaloParcela']=$this->request->data['IntervaloParcela'];
			
				$conta_id=$this->Conta->saveParcels($this->request->data);
				if ($conta_id){

					$this->Conta->ContaVale->deleteAll(array('ContaVale.conta_id'=>$conta_id));
					if(!empty($_SESSION['vales'])){
						foreach ($_SESSION['vales'] as &$value){
							$value['conta_id']=$conta_id;
						}
						$this->Conta->ContaVale->saveAll($_SESSION['vales']);					
					}
					
					$this->Session->setFlash('Conta adicionada com sucesso!', 'default', array('class'=>'alert alert-success'));
				}else $this->Session->setFlash('Ocorreu um erro ao adicionar as conta.', 'default', array('class'=>'alert alert-error'));
				
				$this->redirect(array('action' => 'index'));
			}
		}
		
		$this->Conta->ContaVale->montar();
		
		$this->set('fornecedores', $this->Conta->Fornecedor->find('list',array('conditions'=>'Fornecedor.ativo = 1')));
		$this->set('empresas', $this->Conta->Empresa->find('list',array('conditions'=>'Empresa.habilitado = 1')));
		$this->set('tipos_pagamentos', $this->Conta->TipoPagamento->find('list'));
		$this->set('tipos_contas', $this->Conta->TipoConta->find('list'));
		$this->set('tipos', $this->Conta->tipos);
	}
	
	
	public function admin_print(){
		$this->layout = '';
		$this->autoRender = false; 

		$fornecedor = $this->Conta->Fornecedor->findById($_REQUEST['fornecedor']);
		$empresa = $this->Conta->Empresa->findById($_REQUEST['empresa']);

		$ids = explode(',',str_replace(']', '', str_replace('[', '', $_REQUEST['vales'])));


		//pr($ids);
		$vales = $this->Vale->find('all',[
			'fields'=>[
				'Vale.id',
				'Vale.nota_fiscal',
				'Vale.created',
				'ItemPedido.valor_total',
				'ItemPedido.quantidade_original',
				'ItemPedido.pedido_id'
			],			
			'conditions'=>[				
				'Vale.id'=>$ids
			]
		]);

		//pr($vales);

		$html = '';
		foreach ($vales as $valor) {
			$resultado = $valor['ItemPedido']['valor_total'] / $valor['ItemPedido']['quantidade_original'];

			$resultado = number_format($resultado, 2, ',', ' ');

			$html .= "<tr>
			 		<td>{$valor['ItemPedido']['pedido_id']}</td>
			 		<td>{$valor['Vale']['nota_fiscal']}</td>
			 		<td>{$valor['Vale']['created']}</td>
			 		<td>{$valor['ItemPedido']['quantidade_original']}</td>
			 		<td>R$ {$valor['ItemPedido']['valor_total']}</td>
			 		<td>R$ {$resultado}</td>
			 	</tr>";
			
		}

		$parcelas = '';
		$i = 2;
		foreach ($_REQUEST['asparcelas'] as $key => $value) {
			$parcelas .= "<tr>
						<td><b>{$i}/{$_REQUEST['parcelas']}</b></td>
						<td>{$value[0]['text']}</td>
						<td colspan=\"4\">R$ {$value[0]['value']}</td>
				</tr>";
				$i++;
		}

$emissao = dateMysqlToPhp($_REQUEST['vencimento']);
		
		$json_new = http_build_query($_REQUEST);
		$nivel = $this->Session->read('Auth.User.nivel');
		echo "
		<style>
			@media print {
				button{
					display:none;
				}
			}
		</style>
		<button onclick=\"window.print();\">PDF</button>
		<button><a 
		style=\"color:#000; text-decoration: none;\"
		href=\"{$this->webroot}{$nivel}/contas/execel/?{$json_new}\">EXCEL</a></button>
		<table border=\"1\" width=\"100%\">
			<tr>
				<td><b>Tipo de Operação</b></td>
				<td>Despesa</td>
				<td><b>Tipo Conta</b></td>
				<td colspan=\"3\">Fornecedor</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Empresa</b></td>
				<td colspan=\"3\">{$empresa['Empresa']['nome']}</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Fornecedor</b></td>
				<td colspan=\"3\">{$fornecedor['Fornecedor']['nome']}</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Descrição</b></td>
				<td colspan=\"3\">{$_REQUEST['descricao']}</td>
			<tr>
				<td><b>Número</b></td>
				<td>{$_REQUEST['numero_documento']}</td>
				<td><b>Fatura</b></td>
				<td colspan=\"3\">{$_REQUEST['fatura']}</td>
			</tr>

			<tr>
				<td colspan=\"3\"><b>Observação</b></td>
				<td colspan=\"3\">{$_REQUEST['obs']}</td>
			</tr>

			
			<tr>
				<td colspan=\"6\"><b>Parcelas</b></td>
			</tr>
			<tr>
				<td><b>1/{$_REQUEST['parcelas']}</b></td>
				<td>{$emissao}</td>
				<td colspan=\"4\">R$ {$_REQUEST['valor']}</td>				
			</tr>
			{$parcelas}
			<tr>
				<td colspan=\"6\"><b>Vales</b></td>
			</tr>
			<tr>
				<th>Pedido</th>
				<th>Nota</th>
				<th>Emissão</th>
				<th>Quantidade</th>
				<th>Valor</th>
				<th>Valor M²</th>
			</tr>
			{$html}
		</table>
		";


	}


	public function admin_execel(){
		$this->layout = '';
		$this->autoRender = false; 

		$data = date('d_m_Y_H_i_s');
		header('Content-Type: application/force-download;  charset=utf-8');
		header('Content-disposition: attachment; filename='.$data.'export.xls');





		$fornecedor = $this->Conta->Fornecedor->findById($_REQUEST['fornecedor']);
		$empresa = $this->Conta->Empresa->findById($_REQUEST['empresa']);

		$ids = explode(',',str_replace(']', '', str_replace('[', '', $_REQUEST['vales'])));


		//pr($ids);
		$vales = $this->Vale->find('all',[
			'fields'=>[
				'Vale.id',
				'Vale.nota_fiscal',
				'Vale.created',
				'ItemPedido.valor_total',
				'ItemPedido.quantidade_original',
				'ItemPedido.pedido_id'
			],			
			'conditions'=>[				
				'Vale.id'=>$ids
			]
		]);

		//pr($vales);

		$html = '';
		foreach ($vales as $valor) {
			$resultado = $valor['ItemPedido']['valor_total'] / $valor['ItemPedido']['quantidade_original'];

			$resultado = number_format($resultado, 2, ',', ' ');

			$html .= "<tr>
			 		<td>{$valor['ItemPedido']['pedido_id']}</td>
			 		<td>{$valor['Vale']['nota_fiscal']}</td>
			 		<td>{$valor['Vale']['created']}</td>
			 		<td>{$valor['ItemPedido']['quantidade_original']}</td>
			 		<td>R$ {$valor['ItemPedido']['valor_total']}</td>
			 		<td>R$ {$resultado}</td>
			 	</tr>";
			
		}

		$parcelas = '';
		$i = 2;
		foreach ($_REQUEST['asparcelas'] as $key => $value) {
			$parcelas .= "<tr>
						<td><b>{$i}/{$_REQUEST['parcelas']}</b></td>
						<td>{$value[0]['text']}</td>
						<td colspan=\"4\">R$ {$value[0]['value']}</td>
				</tr>";
				$i++;
		}
		echo "
		
		<table border=\"1\" width=\"100%\">
			<tr>
				<td><b>Tipo de Operação</b></td>
				<td>Despesa</td>
				<td><b>Tipo Conta</b></td>
				<td colspan=\"3\">Fornecedor</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Empresa</b></td>
				<td colspan=\"3\">{$empresa['Empresa']['nome']}</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Fornecedor</b></td>
				<td colspan=\"3\">{$fornecedor['Fornecedor']['nome']}</td>
			</tr>
			<tr>
				<td colspan=\"3\"><b>Descrição</b></td>
				<td colspan=\"3\">{$_REQUEST['descricao']}</td>
			<tr>
				<td><b>Número</b></td>
				<td>{$_REQUEST['numero_documento']}</td>
				<td><b>Fatura</b></td>
				<td colspan=\"3\">{$_REQUEST['fatura']}</td>
			</tr>

			<tr>
				<td colspan=\"3\"><b>Observação</b></td>
				<td colspan=\"3\">{$_REQUEST['obs']}</td>
			</tr>

			
			<tr>
				<td colspan=\"6\"><b>Parcelas</b></td>
			</tr>
			<tr>
				<td><b>1/{$_REQUEST['parcelas']}</b></td>
				<td>{$_REQUEST['vencimento']}</td>
				<td colspan=\"4\">R$ {$_REQUEST['valor']}</td>				
			</tr>
			{$parcelas}
			<tr>
				<td colspan=\"6\"><b>Vales</b></td>
			</tr>
			<tr>
				<th>Pedido</th>
				<th>Nota</th>
				<th>Emissão</th>
				<th>Quantidade</th>
				<th>Valor</th>
				<th>Valor M²</th>
			</tr>
			{$html}
		</table>
		";


	}

	public function admin_dados($id){
		$this->layout = '';
		$this->autoRender = false; 

		$contas = $this->Conta->findById($id);

		$vales = '';
		
		foreach ($contas['ContaVale'] as $key => $value) {
			$vales[] = $value['vale_id'];
		}

		$array_vales = $this->Vale->find('all',['conditions'=>['Vale.id'=>$vales]]);

		$vales_html = '';
		foreach ($array_vales as $key => $value) {
			$resultado = $value['ItemPedido']['valor_total'] / $value['ItemPedido']['quantidade_original'];

			$resultado = number_format($resultado, 2, ',', ' ');
			$data =  dateMysqlToPhp($value['Vale']['created']);
			$vales_html .= "
				<tr>
					<td>{$value['ItemPedido']['pedido_id']}</td>
					<td>{$value['Vale']['nota_fiscal']}</td>
					<td>{$data}</td>
					<td>{$value['ItemPedido']['quantidade_original']}</td>
					<td>R$ {$value['ItemPedido']['valor_total']}</td>
					<td>R$ {$resultado}</td>
				</tr>
			";
		}

		//echo('<pre>');
		echo
		"
			<style>
				table{
					border:solid 1px #000;
				}
				table tr{
					border:solid 1px #000;
				}
				table td{
					border:solid 1px #000;
				}
			</style>

		";

		echo "
		<table style=\"\" width=\"100%\">
			<tr>
				<td><strong>Tipo de Operação</strong></td>
				<td  colspan=\"2\">Despesa</td>
				<td><strong>Tipo Conta</strong></td>
				<td  colspan=\"2\">Fornecedor</td>
			</tr>
			<tr>
				<td><strong>Empresa</strong></td>
				<td colspan=\"5\">{$contas['Empresa']['nome']}</td>
			</tr>
			<tr>
				<td><strong>Fornecedor</strong></td>
				<td colspan=\"5\">{$contas['Fornecedor']['nome']}</td>
			</tr>
			<tr>
				<td><strong>Descrição</strong></td>
				<td colspan=\"5\">{$contas['Conta']['descricao']}</td>
			</tr>
			<tr>
				<td><strong>Número</strong></td>
				<td colspan=\"2\">{$contas['Conta']['numero_documento']}</td>
				<td><strong>Fatura</strong></td>
				<td colspan=\"2\">{$contas['Conta']['fatura']}</td>
			</tr>
			<tr>
				<td><strong>Observação</strong></td>
				<td colspan=\"5\">{$contas['Conta']['observacao']}</td>
			</tr>
			<tr>
				<td colspan=\"2\">
					<strong>Parcela</strong>
				</td>
				<td>
					{$contas['Conta']['parcela']} / {$contas['Conta']['parcelas']}
				</td>
				<td>
					{$contas['Conta']['data_vencimento']}
				</td>
				<td colspan=\"2\">
					R$ {$contas['Conta']['valor']}					
				</td>
			</tr>
			<tr align=\"center\">
				<td><strong>Pedido</strong></td>
				<td><strong>Nota</strong></td>
				<td><strong>Emissão</strong></td>
				<td><strong>Quantidade</strong></td>
				<td><strong>Valor</strong></td>
				<td><strong>Valor M²</strong></td>
			</tr>
			{$vales_html}
		</table>
		";

		//print_r($array_vales);
		//print_r($contas);		
	}	

	public function admin_edit( $id = null ){
		if(!isset($id) && isset($this->_id))$id=$this->_id;
	    
		$this->Conta->id = $id;
		if (!empty($this->request->data)){
			$this->request->data['Conta']['id'] = $id;
			if(!empty($this->request->data['IntervaloParcela']))
					$this->request->data['Conta']['IntervaloParcela'] = $this->request->data['IntervaloParcela'];
			
			#echo "<pre>"; print_r($this->request->data); echo "</pre>"; exit();
			
			//Verifica status da Conta
			$verifica=$this->Conta->findById($id);
			if( $verifica['Conta']['status'] == 1 ){
				$this->request->data['Conta']['user_modified']=$this->Session->read('Auth.User.id');
			}
			if( !empty($this->request->data['Conta']['pago']) ){
				$this->request->data['Conta']['pago']= moedaBD( $this->request->data['Conta']['pago'] );
			}
			
			if ($this->request->data['Conta']['editar'] == 'todas'){
				$this->Conta->set($this->request->data);
				if ($this->Conta->validates()) {
					if ($this->Conta->saveParcels($this->request->data)){
						$this->Session->setFlash('Contas editadas com sucesso!', 'default', array('class'=>'alert alert-success'));
					}else{
						$this->Session->setFlash('Ocorreu um erro editar as contas.', 'default', array('class'=>'alert alert-error'));
					}
					$this->redirect(array('action'=>'index'));
				}
			}else{
			
				if ($this->Conta->save($this->request->data)){
					$this->Session->setFlash('Editada com sucesso conta #'.$id.'!', 'default', array('class'=>'alert alert-success'));
					$this->redirect(array('action' => 'index'));
				}
			}
		}else $this->request->data = $this->Conta->read();
		
		//echo json_encode( $this->request->data); exit;
		
		//$this->set('contas', $this->Conta->Banco->find('list',array('conditions'=>'Banco.habilitado = 1')));
		$this->set('empresas', $this->Conta->Empresa->find('list',array('conditions'=>'Empresa.habilitado = 1')));
		$this->set('fornecedores', $this->Conta->Fornecedor->find('list',array('conditions'=>'Fornecedor.ativo = 1')));
		$this->set('tipos_contas', $this->Conta->TipoConta->find('list', array('conditions' => array('TipoConta.tipo'=>$this->request->data['Conta']['tipo']))));
		//$this->set('subtipos_contas', $this->Conta->SubTipoConta->find('list', array('conditions' => array('SubTipoConta.tipo_conta_id'=>@$this->request->data['Conta']['tipo_conta_id']))));
		$this->set('tipos_pagamentos', $this->Conta->TipoPagamento->find('list'));
		$this->set('tipos', $this->Conta->tipos);
		
		if(!empty($this->request->data['Conta']) and ($this->request->data['Conta']['parcelas']-$this->request->data['Conta']['parcela'])>0 ){
			// So executa caso exista parcelas a frente
			$auxiliar = $this->Conta->find('all',array(
				 'fields'=>array('Conta.data_vencimento','Conta.valor'),
				'conditions'=>array(					 
					 'Conta.parcela >'=>$this->request->data['Conta']['parcela'],
					 'Conta.conta_id'=>(empty($this->request->data['Conta']['conta_id'])? $this->request->data['Conta']['id']: $this->request->data['Conta']['conta_id'])
				 ) 
			));
			$intervalos=array();
			foreach ($auxiliar as $value) {
				$intervalos[]=array(
					'intervalo'=> dateMysqlToPhp($value['Conta']['data_vencimento']),
					'valor'=>  $value['Conta']['valor']
					);
			}
			
			$this->set('IntervalosParcelas',$intervalos);
		}
	}
	
	/**
	 * confirmar que uma conta já foi paga
	 * @param integer $id - ID do conta
	 */
	public function admin_consolidar( $id,$origem=null){
		$conta = $this->Conta->findById( $id );
		$conta['Conta']['valor_original']=$conta['Conta']['valor'];
		
		$this->Conta->id=$id;
		//pr($this->Pagamento->getFinanceiraId());
		if($conta['Conta']['status']==0){
			//Conta em aberto prosseguir com o processo
			if ( !empty( $this->request->data ) ) {
				// Não é uma operacao de credito
				
				if(!empty($this->request->data['Conta']['pago']))
					$this->request->data['Conta']['pago'] = moedaBD ($this->request->data['Conta']['pago']);
				
						
				
				$this->Conta->id = $id;
				
								
				$this->request->data['Conta']['user_modified'] = $this->Session->read('Auth.User.id');				
				$this->request->data['Conta']['status'] = 1;
				
				//pr($this->request->data);exit();
				
				if($this->Conta->save( $this->request->data )){
					$this->Session->setFlash( 'Conta consolidada com sucesso!','default', array('class'=>'alert alert-success'));
					if($conta['Conta']['tipo_pagamento_id']==14)
						$this->Session->write('BoletoImpressao',$id);
				}
				if(!empty($origem))
					switch($origem){
						case 'financeiro':
							$redirect_id =(!empty($conta['Conta']['conta_id']))? $conta['Conta']['conta_id'] : $conta['Conta']['id'];
							$this->redirect( array( 'controller' => 'contas', 'action' => 'boleto_view',$redirect_id ) );
							break;
						default:
							$this->redirect( array( 'controller' => 'contas', 'action' => 'boleto' ) );
					}
				else
					$this->redirect( array( 'controller' => 'contas', 'action' => 'index' ) );

			}
			if(!empty($origem))
				$this->set( 'origem',$origem );
			
			$this->set( 'conta', $conta );
			
		}else{
			// Conta nao esta em aberto retornar para o indice
			$this->Session->setFlash( 'Conta não esta Em Aberto!','default', array('class'=>'alert alert-success'));		
			if(!empty($origem))
					switch($origem){
						case 'financeiro':
							$redirect_id =(!empty($conta['Conta']['conta_id']))? $conta['Conta']['conta_id'] : $conta['Conta']['id'];
							$this->redirect( array( 'controller' => 'contas', 'action' => 'boleto_view',$redirect_id ) );
							break;
						default:
							$this->redirect( array( 'controller' => 'contas', 'action' => 'index' ) );
					}
				else
					$this->redirect( array( 'controller' => 'contas', 'action' => 'index' ) );
							
		}
		
		$this->set('tipos_pagamentos',$this->TipoPagamento->find('list',array('conditions'=>'TipoPagamento.habilitado=1')));
	}
	/**
	 * adianta o recebimento de uma conta
	 * @param integer $id - ID do conta
	 */
	public function admin_adiantamento( $id ){
		$conta = $this->Conta->findById( $id );
		$this->Conta->id=$id;
		if ( !empty( $this->request->data ) ) {
			if($conta['Conta']['credito']==1){
				if($conta['Conta']['parcela']==1){
					$dataSource = $this->Conta->getDataSource();
					$dataSource->begin();
					$data= array();
					$this->request->data['Conta']['taxa_adiantamento']= moedaBD($this->request->data['Conta']['taxa_adiantamento']);
					$data['status']=$this->request->data['Conta']['status'] = 2;
					$this->request->data['Conta']['pago'] = DboSource::expression('( (valor * parcelas)*(1-('.$this->request->data['Conta']['taxa_adiantamento'].'/100)))');
					
					$data['pago'] = 0;
					$data['data_pagamento']="'".  datePhpToMysql($this->request->data['Conta']['data_pagamento'])."'";
					$data['taxa_adiantamento']="'".$this->request->data['Conta']['taxa_adiantamento']."'";
					
					if($this->Conta->save($this->request->data)){
						$this->Conta->recursive = -1;
						if($this->Conta->updateAll($data, array('conta_id'=>$id))){
							$this->Session->setFlash( 'Adiantamento realizado com sucesso!','default', array('class'=>'alert alert-success'));							
							$dataSource->commit();
						}else{
							$this->Session->setFlash( 'Falha ao atualizar parcelas!','default', array('class'=>'alert alert-error'));
							$dataSource->rollback();
						}
					}
					$dataSource->rollback();
				}else{
					//Não é a primeira parcela
					$this->Session->setFlash( 'Somente é possivel adiantar a partir da 1ª parcela!!','default', array('class'=>'alert alert-error'));
				}				
			}else $this->Session->setFlash( 'Somente é possivel adiantar recebimentos de Crédito!!','default', array('class'=>'alert alert-error'));
			$this->redirect( array( 'controller' => 'contas', 'action' => 'index' ) );
		}
		$this->set( 'conta', $conta );
	}
	
	public function admin_del( $id ){
		if($this->Conta->cancelParcels( $id )){
			$this->Session->setFlash( 'Conta removida com sucesso!','default', array('class'=>'alert alert-success'));
		}  else {
			$this->Session->setFlash( 'Não foi possivel remover esta conta!','default', array('class'=>'alert alert-error'));
		}
		$this->redirect(array('action'=>'index'));
	}
	
	public function admin_receitas(){
		$rows = $this->Conta->findAllByDataAndTipo( date('Y-m-d'), 'R' );
		if ( isset ( $this->params['requested'] ) )
			return $rows;
		else $this->set( 'rows', $rows );
	}
	
	public function admin_dispesas(){
		$rows = $this->Conta->findAllByDataAndTipo( date('Y-m-d'), 'D' );
		if ( isset ( $this->params['requested'] ) )
			return $rows;
		else $this->set( 'rows', $rows );
	}
	public function admin_boletos(){$this->setAction('boletos');}
	public function admin_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function admin_comprovante($id){ $this->setAction('comprovante',$id);}
	public function admin_boleto($id){ $this->setAction('boleto',$id);}
	
	/**
	 * Financeiro
	 */
	public function financeiro_index(){
	    $this->layout = 'default';
	    $this->setAction('admin_index');
	}
	public function financeiro_all(){ $this->setAction('admin_all');}		
	public function financeiro_add(){$this->setAction('admin_add');}
	public function financeiro_edit( $id = null ){ $this->setAction('admin_edit',$id);}
	public function financeiro_consolidar( $id,$origem ){ $this->setAction('admin_consolidar',$id,$origem);}
	public function financeiro_boletos(){$this->setAction('boletos');}
	public function financeiro_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function financeiro_del($id){$this->setAction('admin_del',$id);}
	public function financeiro_receitas(){$this->setAction('admin_receitas');}
	public function financeiro_dispesas(){$this->setAction('admin_dispesas');}
	public function financeiro_comprovante($id){ $this->setAction('comprovante',$id);}
	public function financeiro_boleto($id){ $this->setAction('boleto',$id);}

	public function financeiro_print(){ $this->setAction('admin_print');}
	public function financeiro_execel(){ $this->setAction('admin_execel');}
	
	/*
	 * FINANCEIRA
	 */
	public function financeira_boletos(){$this->setAction('boletos');}
	public function financeira_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function financeira_consolidar( $id,$origem ){ $this->setAction('admin_consolidar',$id,$origem);}
	public function financeira_comprovante($id){ $this->setAction('comprovante',$id);}
	public function financeira_boleto($id){ $this->setAction('boleto',$id);}

	public function financeira_print(){ $this->setAction('admin_print');}
	public function financeira_execel(){ $this->setAction('admin_execel');}

	/*
	 * GERENTE
	 */
	public function gerente_boletos(){$this->setAction('boletos');}
	public function gerente_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function gerente_consolidar( $id,$origem ){ $this->setAction('admin_consolidar',$id,$origem);}
	public function gerente_comprovante($id){ $this->setAction('comprovante',$id);}
	public function gerente_boleto($id){ $this->setAction('boleto',$id);}

	public function gerente_print(){ $this->setAction('admin_print');}
	public function gerente_execel(){ $this->setAction('admin_execel');}


	/**
	 * FINANCEIRO ADMIN
	 */
	public function financeiroadm_index(){$this->setAction('financeiro_index');}
	public function financeiroadm_adiantamento($id){ $this->setAction('admin_adiantamento',$id);}		
	public function financeiroadm_all(){ $this->setAction('admin_all');}		
	public function financeiroadm_add(){$this->setAction('admin_add');}
	public function financeiroadm_edit( $id = null ){ $this->setAction('financeiro_edit',$id);}
	public function financeiroadm_consolidar( $id ){$this->setAction('financeiro_consolidar',$id,null);	}
	public function financeiroadm_del($id){$this->setAction('admin_del',$id);}
	public function financeiroadm_receitas(){$this->setAction('admin_receitas');}
	public function financeiroadm_dispesas(){$this->setAction('admin_dispesas');}
	public function financeiroadm_boletos(){$this->setAction('boletos');}
	public function financeiroadm_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function financeiroadm_comprovante($id){ $this->setAction('comprovante',$id);}
	public function financeiroadm_boleto($id){ $this->setAction('boleto',$id);}

	public function financeiroadm_print(){ $this->setAction('admin_print');}

	public function financeiroadm_execel(){ $this->setAction('admin_execel');}
	
	/**
	 * CONTA
	 */
	public function conta_index(){$this->setAction('financeiro_index');}
	public function conta_adiantamento($id){ $this->setAction('admin_adiantamento',$id);}		
	public function conta_all(){ $this->setAction('admin_all');}		
	public function conta_add(){$this->setAction('admin_add');}
	public function conta_edit( $id = null ){ $this->setAction('financeiro_edit',$id);}
	public function conta_consolidar( $id ){$this->setAction('financeiro_consolidar',$id,null);	}
	public function conta_del($id){$this->setAction('admin_del',$id);}
	public function conta_receitas(){$this->setAction('admin_receitas');}
	public function conta_dispesas(){$this->setAction('admin_dispesas');}
	public function conta_boletos(){$this->setAction('boletos');}
	public function conta_boleto_view($id){$this->setAction('boleto_view',$id);}
	public function conta_comprovante($id){ $this->setAction('comprovante',$id);}
	public function conta_boleto($id){ $this->setAction('boleto',$id);}

	public function conta_print(){ $this->setAction('admin_print');}

	public function conta_execel(){ $this->setAction('admin_execel');}
	public function conta_dados($id){ $this->setAction('admin_dados',$id);}
	
}
?>