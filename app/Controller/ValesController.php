<?php
class ValesController extends AppController {

	public $model = 'Vale';
	//public $filter_with_like = Array('Vale.nome', 'Vale.cpf_cnpj', 'Vale.telefone', 'Vale.email', 'Vale.contato');
	public $uses = array('Vale', 'Empresa', 'Pedido', 'ObraProduto', 'ClienteProduto');
	
	public function by_fornecedor($id) {
		$this->layout = '';
		
		$this->Vale->Behaviors->load('Containable');
		$this->Vale->contain(array(
			'ItemPedido.Pedido.Cliente',
			'ItemPedido.Pedido.Obra'
			));


		$array_conditions = [];
		
		if(isset($_REQUEST['inicio']) && $_REQUEST['inicio'] != '--'){
			$array_conditions[0] = [ 'OR' => [
				'Vale.created >=' => $_REQUEST['inicio'].' 00:00:00',
				'Vale.nota_fiscal_emissao >=' => $_REQUEST['inicio'].' 00:00:00'
				]
			]; 
		}

		if(isset($_REQUEST['fim']) && $_REQUEST['fim'] != '--'){
			$array_conditions[1]= [ 
				'OR' => [
					'Vale.created <=' => $_REQUEST['fim'].' 23:59:59',
					'Vale.nota_fiscal_emissao <=' => $_REQUEST['fim'].' 23:59:59',
				]
			]; 
		}

		if(isset($_REQUEST['nota_fiscal']) && $_REQUEST['nota_fiscal'] != ''){
			$array_conditions[2]=['Vale.nota_fiscal LIKE '=> '%'.$_REQUEST['nota_fiscal'].'%'];
		}



		$conditions = array(
			'Vale.fornecedor_id' => $id
			//'Vale.created >=' => '2018-02-01 00:00:00',
			//'Vale.created <=' => '2018-03-20 00:00:00'
		);

		$conditions = array_merge($conditions, $array_conditions);


		$joins=null;
		if(!empty( $this->request->data['conta'] ) ){
			$this->loadModel('ContaVale');
			$joins=array(
				array(
					'table'=>$this->ContaVale->table,
					'alias'=>$this->ContaVale->alias,
					'type'=>'LEFT',
					'conditions'=>array("Vale.id = {$this->ContaVale->alias}.vale_id")
				)
			);
			$conditions[]='ContaVale.vale_id IS NULL ';
			//$conditions[]='Vale.created >= \'2014-07-01 00:00:00\' ';

			
			

			//pr($joins);exit();
			
		}
		$vales = $this->Vale->find('all', array('conditions' => $conditions,'joins'=>$joins));
		$this->set('vales', $vales);

		$this->set('inicio', $_REQUEST['inicio']);
		$this->set('fim', $_REQUEST['fim']);
		
	}
	
	public function avulso(){
				
		$fornecedores = $this->Vale->Fornecedor->find('list', array('fields' => 'nome'));
		foreach ($fornecedores as &$val)
			$val = utf8_encode($val);
		$motoristas = $this->Vale->Motorista->find('list', array('fields' => 'nome'));
		foreach ($motoristas as &$val)
			$val = utf8_encode($val);
		$this->loadModel('Produto');
		$this->set('motoristas', $motoristas);
		$this->set('fornecedores', $fornecedores);
		$this->set('unidades', $this->Vale->ItemPedido->unidade);
		$this->set('periodos', $this->Vale->Periodo->find('list'));
		$this->set('produtos', $this->Produto->find('list'));
	}
	
	public function imprimir_avulso(){
		if($this->request->data['Vale']['layout']!=1)
			$this->layout = "scorpion";
		else
			$this->layout = "areiaoamazonas";
		
		//pr($this->request->data);		
		$this->loadModel('Obra');
		$this->loadModel('Motorista');
		$this->loadModel('Produto');
		$this->loadModel('ValeAvulso');
		
		$vale_avulso=$this->ValeAvulso->findById(1);
		switch($this->layout){
			case "areiaoamazonas":
				$entrega['Vale']['codigo']=$vale_avulso['ValeAvulso']['areiao']++;
				break;
			case "scorpion":
				$entrega['Vale']['codigo']=$vale_avulso['ValeAvulso']['scorpion']++;
				break;
		}
		$this->ValeAvulso->save($vale_avulso);
		$obra=$this->Obra->findById($this->request->data['Vale']['obra_id']);
		$this->Motorista->recursive = -1;
		$motorista=$this->Motorista->findById($this->request->data['Vale']['motorista_id']);
		$produto=$this->Produto->findById($this->request->data['Vale']['produto_id']);
		
		
		$entrega['Vale']['data_entrega']=dateFormatBeforeSave($this->request->data['Vale']['data_entrega']);
		$entrega['ItemPedido']=$this->request->data['ItemPedido'];
		$entrega['ItemPedido']['Pedido']['Obra']=$obra['Obra'];
		$entrega['ItemPedido']['Pedido']['Obra']['Cidade']=$obra['Cidade'];
		$entrega['ItemPedido']['Pedido']['Obra']['Cliente']=$obra['Cliente'];
		$entrega['ItemPedido']['Produto']=@$produto['Produto'];
		$entrega['Motorista']=@$motorista['Motorista'];
		
		
		//pr($this->Vale->getDataSource()->getLog(false, false));
		$this->set('row', $entrega);
		$this->set('unidade', $this->Vale->ItemPedido->unidade);
		$this->render('imprimir');
	}
	
	public function index($id = null) {
		$this->Vale->Behaviors->attach('Containable');
		$this->Vale->contain(
			   'ItemPedido.Produto', 'ItemPedido.Pedido.Cliente', 'Motorista'
		);
		if ($id != null)
			$this->conditions = array('ItemPedido.pedido_id' => $id);
		
		$options['extra']['contain']=array(
			'ItemPedido'
		);
		parent::index($options);
		// Remove o inicio da action para o Paginator funcionar adequadamente 
		//$this->params['action'] = substr($this->params['action'], strpos($this->params['action'], '_') + 1);
		
		$this->set('motoristas',$this->Vale->Motorista->find('list'));
	}

	public function imprimir($id) {
		//$this->layout = 'login';
		//array_push($this->uses, 'Produto');
		//array_push($this->uses, 'Cliente');
		switch($this->request->params['pass'][1]):
			case 's': $this->layout = "scorpion";
				break;
			case 2: $this->layout = "scorpion";
				break;
			case 3:
				$this->layout = "scorpion";
				break;

			// case 2:
			// case '2':
			// 	$this->layout = "castelo";
			// 	break;
			case 4:
			case '4':
				$this->layout = "toptruck";
				break;
			case 5:
			case '5':
				$this->layout = "imperio";
				break;

			case 6:
			 case '6':
				$this->layout = "castelo";
			break;
			default:
				$this->layout = "areiaoamazonas";
				break;
		endswitch;
		
		
		$this->Vale->Behaviors->attach('Containable');
		$associations= array(
			   'ItemPedido.Produto',
			   'ItemPedido.Pedido.Cliente',
			   'ItemPedido.Pedido.Obra',
			   'ItemPedido.Pedido.Obra.Cidade',
			   'ItemPedido.Pedido.ItemNota.Nota'
		);
		if(!empty( $this->request->params['pass'][2] ) ){
			$this->set('motorista',$this->Vale->Motorista->findById($this->request->params['pass'][2]));
		}else{
			$associations[]='Motorista';
		}
		$this->Vale->contain($associations);
		$entrega = $this->Vale->findById($id);
		//pr($this->Vale->getDataSource()->getLog(false, false));
		$this->set('row', $entrega);
		$this->set('unidade', $this->Vale->ItemPedido->unidade);
	}

	public function edit($id = null) {
		parent::edit($id);
	}


	public function finalizar($id) {
		if (!empty($this->request->params['pass'][1])) {
			$this->set('pedido', $this->request->params['pass'][1]);
		}
		$this->Vale->id = $id;
		if (!empty($this->request->data['Vale']['data_entrega'])) {
			unset($this->request->data['ItemPedido']['valor_unitario']);
			$this->request->data['ItemPedido']['frete']=moedaBD($this->request->data['ItemPedido']['frete']);
			$this->request->data['ItemPedido']['valor_total']=moedaBD($this->request->data['ItemPedido']['valor_total']);
			
			$dataSource = $this->Vale->getDataSource();
			$dataSource->begin();
			$this->request->data['Vale']['id'] = $id;
			$this->request->data['Vale']['status'] = 1;
			$this->request->data['Vale']['nota_fiscal_emissao'] = datePhpToMysql($this->request->data['Vale']['nota_fiscal_emissao']);
			$this->request->data['Vale']['motorista_tipo'] = DboSource::expression('(SELECT tipo FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');
			$this->request->data['Vale']['placa'] = DboSource::expression('(SELECT placa FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');
			// ALTERARAÇÕES DEVEM SER REFLETIDAS NO CONTROLLER PEDIDOS
			$this->request->data['ItemPedido']['valor'] = DboSource::expression('('. $this->request->data['ItemPedido']['valor_total'].'/ '.moedaBD($this->request->data['ItemPedido']['quantidade']) . ')');
			//pr($this->request->data);
			if ($this->Vale->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Vale finalizado com sucesso!', 'default', array('class' => 'alert alert-success'));
				$dataSource->commit();
				if (empty($this->request->params['pass'][1]))
					$this->redirect(array('controller' => 'pedidos', 'action' => 'index'));
				else
					$this->redirect(array('controller' => 'pedidos', 'action' => 'finalizar', $this->request->params['pass'][1]));
			}
			$dataSource->rollback();
		}else{
			$this->Vale->Behaviors->load('Containable');
			$this->Vale->contain(array(
			    'ItemPedido.Pedido',
			));
			$data = $this->Vale->read();
                        $data['Vale']['data_entrega']=  dateMysqlToPhp($data['Vale']['data_entrega']);
                        $data['Vale']['nota_fiscal_emissao']=  dateMysqlToPhp($data['Vale']['nota_fiscal_emissao']);
			$this->data = $data;
		}

		$fornecedores = $this->Vale->Fornecedor->find('list', array('fields' => 'nome'));
		foreach ($fornecedores as &$val)
			$val = utf8_encode($val);
		$motoristas = $this->Vale->Motorista->find('list', array('fields' => 'nome','conditions'=>array('Motorista.ativo=1')));
		foreach ($motoristas as &$val)
			$val = utf8_encode($val);
		$this->set('motoristas', $motoristas);
		$this->set('fornecedores', $fornecedores);
		$this->set('unidades', $this->Vale->ItemPedido->unidade);
		$this->set('periodos', $this->Vale->Periodo->find('list'));
		$this->set('empresas', $this->Empresa->find('list'));
		
		$this->set('freteiros',$this->Vale->Motorista->find('list',array('conditions'=>array('Motorista.tipo=2','Motorista.ativo=1'))));
		
		//$this->request->data['ItemPedido']['quantidade'] =  number_format($this->request->data['ItemPedido']['quantidade'],2);


		$preco = $this->preco($id);
		$preco_cliente = $this->precoCliente($id);
		
		
		$total = $this->request->data['ItemPedido']['quantidade'] * $preco;
		if(	$preco_cliente > 0){
			$total = $this->request->data['ItemPedido']['quantidade'] * $preco_cliente;
		}
		
		$this->set('preco',$preco);
		$this->set('preco_cliente',$preco_cliente);
		$this->set('total',$total);
		
	}
	
	
	public function precoCliente($id){
	// $this->autoRender = false;
	// $this->layout = '';
		$vale = $this->Vale->findById($id);
		$pedido_id = $vale['ItemPedido']['pedido_id'];
		$pedido  =  $this->Pedido->findById($pedido_id);


		$cliente_id = $pedido['Cliente']['id'];
		$produto_id = $vale['ItemPedido']['produto_id'];
		
		$preco =$this->ClienteProduto->find(
			'first',
			array(
				'conditions'=>array(
					'ClienteProduto.cliente_id'=>$cliente_id,
					'ClienteProduto.produto_id'=>$produto_id
				)
			)
		);

		$valor = $preco['ClienteProduto']['preco'];

		return $valor; 
	}

	public function finalizarX($id) {
		if (!empty($this->request->params['pass'][1])) {
			$this->set('pedido', $this->request->params['pass'][1]);
		}
		$this->Vale->id = $id;

		if (!empty($this->request->data['Vale']['data_entrega'])) {

			 
			//$this->request->data['ItemPedido']['valor_total'] = ($this->request->data['ItemPedido']['quantidade'] * $this->request->data['ItemPedido']['valor_unitario_tmp']);


			// $j = json_encode($this->request->data, true);
			//  echo $j;
			//  exit();


			unset($this->request->data['ItemPedido']['valor_unitario']);
			$this->request->data['ItemPedido']['frete']=moedaBD($this->request->data['ItemPedido']['frete']);
			$this->request->data['ItemPedido']['valor_total']=moedaBD($this->request->data['ItemPedido']['valor_total']);
			
			$dataSource = $this->Vale->getDataSource();
			$dataSource->begin();
			$this->request->data['Vale']['id'] = $id;
			$this->request->data['Vale']['status'] = 1;
			$this->request->data['Vale']['nota_fiscal_emissao'] = datePhpToMysql($this->request->data['Vale']['nota_fiscal_emissao']);
			$this->request->data['Vale']['motorista_tipo'] = DboSource::expression('(SELECT tipo FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');
			$this->request->data['Vale']['placa'] = DboSource::expression('(SELECT placa FROM motoristas WHERE id ='.$this->request->data['Vale']['motorista_id'].' )');
			// ALTERARAÇÕES DEVEM SER REFLETIDAS NO CONTROLLER PEDIDOS
			$this->request->data['ItemPedido']['valor'] = DboSource::expression('('. $this->request->data['ItemPedido']['valor_total'].'/ '.moedaBD($this->request->data['ItemPedido']['quantidade']) . ')');

			$this->request->data['ItemPedido']['valor']  = $this->request->data['ItemPedido']['valor_unitario_tmp'];

			

			//pr($this->request->data);
			if ($this->Vale->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Vale finalizado com sucesso!', 'default', array('class' => 'alert alert-success'));
				$dataSource->commit();
				if (empty($this->request->params['pass'][1]))
					$this->redirect(array('controller' => 'pedidos', 'action' => 'index'));
				else
					$this->redirect(array('controller' => 'pedidos', 'action' => 'finalizar', $this->request->params['pass'][1]));
			}
			$dataSource->rollback();
		}else{
			$this->Vale->Behaviors->load('Containable');
			$this->Vale->contain(array(
			    'ItemPedido.Pedido',
			));
			$data = $this->Vale->read();
            $data['Vale']['data_entrega']=  dateMysqlToPhp($data['Vale']['data_entrega']);
            $data['Vale']['nota_fiscal_emissao']=  dateMysqlToPhp($data['Vale']['nota_fiscal_emissao']);
			$this->data = $data;
		}

		$fornecedores = $this->Vale->Fornecedor->find('list', array('fields' => 'nome'));
		foreach ($fornecedores as &$val)
			$val = utf8_encode($val);
		$motoristas = $this->Vale->Motorista->find('list', array('fields' => 'nome'));
		foreach ($motoristas as &$val)
			$val = utf8_encode($val);

		
		$preco = $this->preco($id);
		$this->set('preco',$preco);
		$total = $this->request->data['ItemPedido']['quantidade'] * $preco;
		$this->set('total',$total);



		$this->set('motoristas', $motoristas);
		$this->set('fornecedores', $fornecedores);
		$this->set('unidades', $this->Vale->ItemPedido->unidade);
		$this->set('periodos', $this->Vale->Periodo->find('list'));
		$this->set('empresas', $this->Empresa->find('list'));
		
		$this->set('freteiros',$this->Vale->Motorista->find('list',array('conditions'=>array('Motorista.tipo=2','ativo=1'))));
		
		$this->request->data['ItemPedido']['quantidade'] =  number_format($this->request->data['ItemPedido']['quantidade'],2);
		
	}

	public function preco($id){
	// $this->autoRender = false;
	// $this->layout = '';
		$vale = $this->Vale->findById($id);
		$pedido_id = $vale['ItemPedido']['pedido_id'];
		$pedido  =  $this->Pedido->findById($pedido_id);


		$obra_id = $pedido['Obra']['id'];
		$produto_id = $vale['ItemPedido']['produto_id'];
		
		$preco =$this->ObraProduto->find(
			'first',
			array(
				'conditions'=>array(
					'ObraProduto.obra_id'=>$obra_id,
					'ObraProduto.produto_id'=>$produto_id
				)
			)
		);

		$valor = $preco['ObraProduto']['preco'];

		return $valor; 
	}

	public function add(){ $this->setAction('edit'); }

	public function del($id) {
		parent::del($id);
	}

	//
	// ADMIN
	//
	public function admin_avulso(){ $this->setAction('avulso');}
	public function admin_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function admin_index($id){ $this->setAction('index', $id);}
	public function admin_edit($id){ $this->setAction('edit', $id);}
	public function admin_imprimir($id){$this->setAction('imprimir', $id);}
	public function admin_add(){ $this->setAction('edit');}
	public function admin_del($id){$this->setAction('del', $id);}
	public function admin_finalizar($id){ $this->setAction('finalizar', $id);}
	public function admin_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
	//
	// financeiro
	//
	public function financeiro_avulso(){ $this->setAction('avulso');}
	public function financeiro_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function financeiro_index($id){ $this->setAction('index', $id);}
	public function financeiro_edit($id){ $this->setAction('edit', $id);}
	public function financeiro_imprimir($id){$this->setAction('imprimir', $id);}
	public function financeiro_add(){ $this->setAction('edit');}
	public function financeiro_del($id){$this->setAction('del', $id);}
	public function financeiro_finalizar($id){ $this->setAction('finalizar', $id);}
	public function financeiro_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
	//
	// financeiro
	//
	public function vendedor_avulso(){ $this->setAction('avulso');}
	public function vendedor_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function vendedor_index($id){ $this->setAction('index', $id);}
	public function vendedor_edit($id){ $this->setAction('edit', $id);}
	public function vendedor_imprimir($id){$this->setAction('imprimir', $id);}
	public function vendedor_add(){ $this->setAction('edit');}
	public function vendedor_del($id){$this->setAction('del', $id);}
	public function vendedor_finalizar($id){ $this->setAction('finalizar', $id);}
	public function vendedor_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
	//
	// pedido
	//
	public function pedido_avulso(){ $this->setAction('avulso');}
	public function pedido_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function pedido_index($id){ $this->setAction('index', $id);}
	public function pedido_edit($id){ $this->setAction('edit', $id);}
	public function pedido_imprimir($id){$this->setAction('imprimir', $id);}
	public function pedido_add(){ $this->setAction('edit');}
	public function pedido_del($id){$this->setAction('del', $id);}
	public function pedido_finalizar($id){ $this->setAction('finalizar', $id);}
	public function pedido_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
	//
	// pedidos1
	//
	public function pedidos1_avulso(){ $this->setAction('avulso');}
	public function pedidos1_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function pedidos1_index($id){ $this->setAction('index', $id);}
	public function pedidos1_edit($id){ $this->setAction('edit', $id);}
	public function pedidos1_imprimir($id){$this->setAction('imprimir', $id);}
	public function pedidos1_add(){ $this->setAction('edit');}
	public function pedidos1_del($id){$this->setAction('del', $id);}
	public function pedidos1_finalizar($id){ $this->setAction('finalizar', $id);}
	public function pedidos1_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
	//
	// pedidos2
	//
	public function pedidos2_avulso(){ $this->setAction('avulso');}
	public function pedidos2_imprimir_avulso(){ $this->setAction('imprimir_avulso');}
	public function pedidos2_index($id){ $this->setAction('index', $id);}
	public function pedidos2_edit($id){ $this->setAction('edit', $id);}
	public function pedidos2_imprimir($id){$this->setAction('imprimir', $id);}
	public function pedidos2_add(){ $this->setAction('edit');}
	public function pedidos2_del($id){$this->setAction('del', $id);}
	public function pedidos2_finalizar($id){ $this->setAction('finalizar', $id);}
	public function pedidos2_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}

	//
	// LOGISTICA
	//
	public function logistica_index() {$this->setAction('admin_index');}
	public function logistica_edit($id) {$this->setAction('admin_edit', $id);}
	public function logistica_add() {$this->setAction('admin_edit');}
	public function logistica_del($id) {$this->setAction('admin_del', $id);}
	public function logistica_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}
		
	//
	// CONTA
	//
	public function conta_by_fornecedor($id){ $this->setAction('by_fornecedor', $id);}

}