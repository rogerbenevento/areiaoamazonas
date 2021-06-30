<?php

class NFe extends AppModel {

	public $useTable = false;
	private $diretorio = "nf/";
	// Tamanho validos para o codigo EAN
	private $EAN = array(8, 12, 13, 14);
	// Tamanho validos para o codigo NCM
	private $NCM = array(8, 2);
	private $QUEBRAR_LINHA = "\r\n";
	private $PAGAMENTO_A_VISTA = array(2, 3, 4, 12);
	private $PAGAMENTO_OUTROS = array(5);
	private $tipo_ambiente = 1; // 1 - Producao , 2 - Homologacao

	public function getPath() {
		return $this->diretorio;
	}

	private function FormataMoeda($valor, $decimais = 2, $lenght = false) {
		$valor = number_format($valor, $decimais, '.', '');
		return ($lenght) ? $valor : str_pad($valor, $lenght, '0', STR_PAD_LEFT);
	}

	private function ImprimiProdutos($itemnota, &$file, $nota) {
		$contador = 1;
		$this->ItemPedido = ClassRegistry::init('ItemPedido');
		$unidades = $this->ItemPedido->unidade;
		$itens=array();
		#pr($itemnota);
		foreach ($itemnota as $itemtmp) {
			if ($itemtmp['imprimir'] == false)
				continue;
			if (empty($itemtmp['unidade']))
				$itemtmp['unidade'] = 1;
			$i=$itemtmp['produto_id'].'|'.$itemtmp['valor_unitario'].'|'.$itemtmp['unidade'];
			if(empty($itens[$i])){
				$itens[$i]=$itemtmp;
			}else{
				$itens[$i]['quantidade']+=$itemtmp['quantidade'];
			}
		}
		#exit();
//		foreach ($itemnota as $item) {
		foreach ($itens as $item) {
			if ($item['imprimir'] == false)
				continue;

			if (empty($item['unidade']))
				$item['unidade'] = 1;
			$string = "H|" . $contador++ . $this->QUEBRAR_LINHA;
			//Verifica se o cfop é valido
			//if(strlen($item['Produto']['cfop'])!=4) return -1;
			//if(!in_array(strlen($item['Produto']['codigo']),$this->EAN)) return -2;
			//if(!in_array(strlen($item['Produto']['ncm']),$this->NCM)) return -3;
			$string.= "I|"
					. $item['Produto']['id'] . "|"  // cProd
//				.$item['Produto']['codigo']."|"	//cEAN
					. "|" //cEAN
					. substr($item['Produto']['nome'], 0, 120) . "|" /* xProd */
					. $item['Produto']['ncm'] . "|" // NCM
					. "|" /* TIPI */
					. "|" /* cBenef */
					. $nota['Nota']['cfop'] . "|"/* CFOP */
					. strtoupper($unidades[$item['unidade']]) . "|" /* uCom -   Unidade Comercial */
					. $item['quantidade'] . "|"/*  qCOm - Quantidade Comercial */
					. $this->FormataMoeda($item['valor_unitario'], 2, 15) . "|" /* vUnCom - Valor Unitario Comercial */
					. $this->FormataMoeda($item['quantidade'] * $item['valor_unitario'], 2, 15) . "|" /* vProd Valor Total dos Produtos */
//				.$item['Produto']['codigo']."|"/* cEANTrib - EAN Tributario*/
					. "|"/* cEANTrib - EAN Tributario */
					. strtoupper($unidades[$item['unidade']]) . "|" /* uTrib - Unidade Comercial */
					. $item['quantidade'] . "|"/* qTrib - Quantidade Comercial */
					. $this->FormataMoeda($item['valor_unitario'], 2, 21) . "|"/* vUnTrib Valor Unitario Tributavel */
					. "|" /* vFrete - Valor do Frete */
					. "|" /* vSeg - Valor do Seguro */
					. "|" /* vDesc - Valor do Desconto */
					. "|" /* vOutro - Outra despesa */
					. "1|" /* indTotal - 0 => valor do vProd nao compoe o valor da nota, 1 => valor compoe a nota */
					. $item['pedido_id'] . "|" /* xPed */
					. $item['id'] . "|" /* nItemPed */
					. $this->QUEBRAR_LINHA;
			if (in_array($nota['Nota']['empresa_id'], [2,3,4,5,6])) {
				$string.= "M|" . $this->QUEBRAR_LINHA
						. "N|" . $this->QUEBRAR_LINHA
						. "N10d|"
						. "0|"//orig
						. "400|"//CSOSN
						. $this->QUEBRAR_LINHA
						. "O|"
						//. "|"//clEnq
						. "|"//CNPJProd
						. "|"//cSelo
						. "|"//qSelo
						. "301|"////cEnq
						. $this->QUEBRAR_LINHA
						. "O08|"
						. "52|" // CST
						. $this->QUEBRAR_LINHA


						. 'Q|' . $this->QUEBRAR_LINHA
						. 'Q04|'
						. '07|'//CST
						. $this->QUEBRAR_LINHA
						. 'S|' . $this->QUEBRAR_LINHA
						. 'S04|'
						. '07|'//CST
						. $this->QUEBRAR_LINHA;
			}
			fwrite($file, $string);
		}
	}

	public function ExportarTxt($nota_id) {

		$this->Nota = ClassRegistry::init('Nota');

		$this->Nota->Behaviors->attach('Containable');
		$this->Nota->contain([
			'Cliente',
			'Cliente.Endereco' => [
				'conditions' => ['tipo_id=1'],
				'Cidade.Estado'
			],
//				'Cliente.Cidade.Estado',
			'Empresa.Cidade.Estado',
			//'User',
//				'Pagamento.Tipopagamento',
			//'Entrega',
			'ItemNota.Produto',
			'ItemNota.Pedido.ItemPedido.Produto'
				//'Itempedido.Transferencia.ProdutolojaSaida'
				//'Empresa.EmpresaConta'				
		]);
		$notas = $this->Nota->find('all', array('conditions' => array('Nota.id' => $nota_id)));
		//Numero aleatorio para evitar acessos indevidos da NF-e.
		$numero_aleatorio = substr(time() * rand(1, 1000), 0, 8);
		if (count($notas) > 0) {
			// LIMPA O DIRETORIO ANTES DE CRIAR O NOVO ARQUIVO
			@array_map("unlink", glob($this->diretorio . '*.txt'));

			$arquivo = $this->diretorio . "nf" . date('YmdH') . ".txt";

			$quebra = '';
			$fp = fopen($arquivo, "wb");
			$cabecalho = "NOTAFISCAL|" . count($notas) . $this->QUEBRAR_LINHA;
			fwrite($fp, $cabecalho);
			foreach ($notas as $nota) {
				#pr($nota);exit();
				/*
				 * VERIFICA SE A LOJA ESTA CONFIGURADA PARA EMITIR NFE
				 * 
				 */
				if (empty($nota['Empresa']['cnpj']))
					return -1;

				$nota['Empresa']['cnpj'] = str_ireplace(array('.', '-', '/'), '', $nota['Empresa']['cnpj']);
				$nota['Empresa']['cep'] = str_ireplace(array('.', '-', '/'), '', $nota['Empresa']['cep']);
				$nota['Empresa']['telefone'] = str_ireplace(array('(', ')', '.', '-', '/'), '', $nota['Empresa']['telefone']);
				$nota['Empresa']['ie'] = str_ireplace(['-', '.'], '', $nota['Empresa']['ie']);
				if (!is_numeric($nota['Empresa']['ie']))
					$nota['Empresa']['ie'] = '';
				///////////////////////////////////////////////////////////////
				$nota['Empresa']['im'] = str_ireplace(['-', '.'], '', $nota['Empresa']['im']);
				if (!is_numeric($nota['Empresa']['im']))
					$nota['Empresa']['im'] = '';
				///////////////////////////////////////////////////////////////
				$nota['Cliente']['rg_ie'] = str_ireplace(['-', '.'], '', $nota['Cliente']['rg_ie']);
				if (!is_numeric($nota['Cliente']['rg_ie']))
					$nota['Cliente']['rg_ie'] = '';

				$nota['Cliente']['cpf_cnpj'] = str_ireplace(array('.', '-', '/'), '', $nota['Cliente']['cpf_cnpj']);
				$nota['Cliente']['Endereco'][0]['cep'] = str_ireplace(array('.', '-', '/'), '', $nota['Cliente']['Endereco'][0]['cep']);
				$nota['Cliente']['telefone'] = str_ireplace(array('(', ')', '.', '-', '/'), '', $nota['Cliente']['telefone']);

				if (empty($nota['Cliente']['numero'])) {
					$numero = trim(substr($nota['Cliente']['endereco'], -6));
					$num_count = strlen($numero);
					for ($c = 0; $c < $num_count; $c++)
						if (!is_numeric($numero[$c]))
							$numero[$c] = " ";
					$nota['Cliente']['numero'] = trim($numero);
				}

				//PAGAMENTOS A VISTA
				$v1 = empty($nota['Nota']['fatura_vencimento1']);
				$v2 = empty($nota['Nota']['fatura_vencimento2']);
				$v3 = empty($nota['Nota']['fatura_vencimento3']);
				if (!$v1 && !$v2 && !$v3) {
					//Possui 3 parcelas pagamento parcelato
					$tipo_pagamento = '1';
				} else if (!$v1 && $v2 && $v3) {
					$tipo_pagamento = '1';
				} else {
					$tipo_pagamento = '2';
				}
				$destino_operacao = 1;
				if ($nota['Empresa']['Cidade']['Estado']['nome'] != $nota['Cliente']['Endereco'][0]['Cidade']['Estado']['nome']) {
					$destino_operacao = 2;
				}

				$indpres = $nota['Nota']['indpres'];
				$procemi = $nota['Nota']['procemi'];
				//Abre o arquivo da nota
				//$fp = fopen($this->diretorio."nf".date('YmdH').".txt", "w");
				$string = //"NOTAFISCAL|1".$this->QUEBRAR_LINHA
						$quebra . "A|4.00|NFe|" . $this->QUEBRAR_LINHA
						. "B|"
						. $nota['Empresa']['Cidade']['Estado']['ibge'] . "|" /* cUF - CODIGO UF IBGE */
						. $numero_aleatorio . "|" /* cNF - NUMERO ALETAORIO */
						. $nota['Nota']['natureza_operacao']."|" /* natOp - Descricao da Natureza da OPeracao */
						//. $tipo_pagamento . "|" /* indPag - Indicador da forma de pagamento */
						. "55|" /* mod - Código do Modelo do Documento Fiscal */
						. "1|" /* serie - Serie Documento Fiscal */
						. ($nota['Nota']['numero'] * 1) . "|" /* nNF - Numero DOcumento Fiscal */
						. date("Y-m-d\Th:i:sP") . "|" /* dEmi - Data Emissao do Documento Fiscal */
						. "|" /* dSaiEnt - Data de Saida do Produto */
						. "1|" /* tpNF - Tipo Operacao */
						. $destino_operacao . '|' /* idDest nova tag para informar o local de destino da operação.
						  Valores válidos:
						  1=Operação interna;
						  2=Operação interestadual;
						  3=Operação com exterior. */
						. $nota['Empresa']['Cidade']['ibge'] . "|" /* cMunFG - Código do Município de Ocorrência do Fato Gerador */
						. "1|" // TpImp
						. "1|" // TpEmis 
						. "|" // cDV
						. "{$this->tipo_ambiente}|" // tpAmb
						. "1|" // finNFe
						. "1|" // indFinal 
						.$indpres."|" // indPres
						/*
							INDPRES
							0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste); 
							1=Operação presencial; 
							2=Operação não presencial, pela Internet;
							3=Operação não presencial, Teleatendimento; 
							4=NFC-e em operação com entrega a domicílio; 
							9=Operação não presencial, outros
						*/
						.$procemi."|" // procEmi
						/*
							PROCEMI
							0=Emissão de NF-e com aplicativo do contribuinte;
							1=Emissão de NF-e avulsa pelo Fisco; 
							2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco; 
							3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco
						*/
						. "4.0|" // VerProc
						. "|" // dhCont
						. "|" // xJust
						. $this->QUEBRAR_LINHA
						. "C|"
						. trim($nota['Empresa']['razao']) . "|" //XNome
						. substr($nota['Empresa']['nome'], 0, 60) . "|" // XFant
						. trim($nota['Empresa']['ie']) . "|"  // IE
						. "|"  //IEST
						. trim($nota['Empresa']['im']) . "|"  // IM
						. trim($nota['Empresa']['cnae']) . "|" //CNAE
						. trim($nota['Empresa']['crt']) . "|" //CRT
						. $this->QUEBRAR_LINHA
						. "C02|" . $nota['Empresa']['cnpj'] . "|"
						. $this->QUEBRAR_LINHA
						. "C05|"
						. trim($nota['Empresa']['endereco']) . "|" //XLgr|
						. trim($nota['Empresa']['numero']) . "|"  //Nro|
						. trim($nota['Empresa']['complemento']) . "|" //Cpl|
						. trim($nota['Empresa']['bairro']) . "|" //Bairro|
						. $nota['Empresa']['Cidade']['ibge'] . "|" //CMun|
						. $nota['Empresa']['Cidade']['nome'] . "|" //XMun|
						. $nota['Empresa']['Cidade']['Estado']['nome'] . "|"  //UF|
						. trim($nota['Empresa']['cep']) . "|" //CEP|
						. "1058|"  //cPais|
						. "BRASIL|" //xPais|
						. $nota['Empresa']['telefone'] . "|" //fone|
						. $this->QUEBRAR_LINHA
						//IDENTIFICACAO DO DESTINATARIO DA NF
						. "E|"
						. trim($nota['Cliente']['nome']) . "|" //xNome|
						/*
						 * 1=Contribuinte ICMS (informar a IE do destinatário);
						  2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
						  9=Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS;
						  Nota 1: No caso de NFC-e informar indIEDest=9 e não informar a tag IE do destinatário;
						  Nota 2: No caso de operação com o Exterior informar indIEDest=9 e não informar a tag IE do destinatário;
						  Nota 3: No caso de Contribuinte Isento de Inscrição (indIEDest=2), não informar a tag IE do destinatário.
						 */
						. (!empty($nota['Cliente']['rg_ie']) ? 1 : 2) . "|"  //indIEDest|
						. (!empty($nota['Cliente']['rg_ie']) ? $nota['Cliente']['rg_ie'] : "") . "|"  //IEDest|
						. "|" //ISUF|
						. "|" //IM|
						. $nota['Cliente']['email'] . "|" //email|
						. $this->QUEBRAR_LINHA
						. (strlen($nota['Cliente']['cpf_cnpj']) > 11 ?
								"E02|" . $nota['Cliente']['cpf_cnpj']//CNPJ
								: "E03|" . $nota['Cliente']['cpf_cnpj']
						) . "|" . $this->QUEBRAR_LINHA //CPF
						. "E05|"
						. trim($nota['Cliente']['Endereco'][0]['endereco']) . "|" //XLgr|
						. trim($nota['Cliente']['Endereco'][0]['numero']) . "|" //Nro|
						. trim($nota['Cliente']['Endereco'][0]['complemento']) . "|" //Cpl|
						. trim($nota['Cliente']['Endereco'][0]['bairro']) . "|" //Bairro|
						. $nota['Cliente']['Endereco'][0]['Cidade']['ibge'] . "|" //CMun|
						. $nota['Cliente']['Endereco'][0]['Cidade']['nome'] . "|" //XMun|
						. $nota['Cliente']['Endereco'][0]['Cidade']['Estado']['nome'] . "|" //UF|
						. (strlen(trim($nota['Cliente']['Endereco'][0]['cep']))!=8?'':$nota['Cliente']['Endereco'][0]['cep']) . "|" //CEP|
						. "1058|"  //cPais|
						. "BRASIL|" //xPais|
						. $nota['Cliente']['telefone'] . "|" //fone|
						. $this->QUEBRAR_LINHA;

				fwrite($fp, $string);
				$this->ImprimiProdutos($nota['ItemNota'], $fp, $nota);

				$valor = $this->ValorNota($nota);
				#pr($valor);exit();
				//TOTAIS
				$string2 = "W|". $this->QUEBRAR_LINHA
						. "W02|"
						. "0.00|" //"vBC|"
						. "0.00|" //"vICMS|"
						. "0.00|" //"vICMSDeson|"
						. "0.00|" //"vFCP|"
						. "0.00|" //"vBCST|"
						. "0.00|" //"vST|"
						. "0.00|" //"vFCPST"
						. "0.00|" //"vFCPSTRet"
						. $this->FormataMoeda($valor)."|" //"vProd|"
						. "0.00|" //"vFrete|"
						. "0.00|" //"vSeg|"
						. "0.00|" //"vDesc|"
						. "0.00|" //"vII|"
						. "0.00|" //"vIPI|"
						. "0.00|" //"vIPIDevol|"
						. "0.00|" //"vPIS|"
						. "0.00|" //"vCOFINS|"
						. "0.00|" //"vOutro|"
						. $this->FormataMoeda($valor). "|" //"vNF|"
						. "0.00|" //"vTotTrib|"
						. $this->QUEBRAR_LINHA; //modFrete 
				//
				//Transporte
				$string2 .= "X|0|" . $this->QUEBRAR_LINHA; //modFrete
				//Cobrancas
				$string2.="Y|" 
				.$this->FormataMoeda($nota['Nota']['vTroco'])."|"//"vTroco"
				."|"
				.$this->QUEBRAR_LINHA; //Cobrancas
				

				$string2.="YA|".$this->QUEBRAR_LINHA;

				$string2.="YA01a|" 
				."|"//indPag
				.$nota['Nota']['tPag']."|"//"tPag"
				.$this->FormataMoeda($nota['Nota']['vPag'])."|"//"vPag"
				."|"//"CNPJ"
				."|"//"tBand"
				."|"//"cAut"
				."|"//"tpIntegra"
				. $this->QUEBRAR_LINHA;

				$vLiq = $nota['Nota']['vPag'] - $nota['Nota']['vDesc'];

				$string2.="Y02|"
				.$nota['Nota']['numero']."|"//nFat
				.$this->FormataMoeda($nota['Nota']['vPag'])."|"//vOrig
				.$this->FormataMoeda($nota['Nota']['vDesc'])."|"//vDesc
				.$this->FormataMoeda($vLiq)."|"//vLiq
				.$this->QUEBRAR_LINHA;
				

				$duplicatas = 0;
				for ($if = 0; $if < 4; $if++) {
					if (empty($nota['Nota']['fatura_valor' . $if]) || $nota['Nota']['fatura_valor' . $if] == '0.00')
						continue;
					$string2.="Y07|" //Fatura
							. $nota['Nota']['fatura_ordem' . $if] . "|" //nDup
							. str_replace(' ','',datePhpToMysql($nota['Nota']['fatura_vencimento' . $if])) . "|" //dVenc
							. $this->FormataMoeda($nota['Nota']['fatura_valor' . $if]) . "|" //vDup
							. $this->QUEBRAR_LINHA;
					$duplicatas++;
				}
				if ($duplicatas == 0) {
					//Nenhuma duplicata possui valor, somar o valor da nota e gerar uma duplicata
					if (!empty($nota['Nota']['fatura_vencimento1'])) {
						#Configure::write('debug',2);
						
						#exit();
						$string2.="Y07|" //Fatura
								. $nota['Nota']['numero'] . "|" //nDup
								. str_replace(' ','',datePhpToMysql($nota['Nota']['fatura_vencimento1'])) . "|" //dVenc
								. $this->FormataMoeda($valor) . "|" //vDup
								. $this->QUEBRAR_LINHA;
					}
				}
				//Infomraçoes adicionais
				
				$nota['Nota']['dados_adicionais']=utf8_decode($nota['Nota']['dados_adicionais']);
				$nota['Nota']['observacao']=utf8_decode( $nota['Nota']['observacao']);
				
				$nota['Nota']['dados_adicionais']=preg_replace( "/\r|\n|\t/", "", $nota['Nota']['dados_adicionais']);
				$nota['Nota']['observacao']=preg_replace( "/\r|\n|\t/", "", $nota['Nota']['observacao']);
				
				
				$nota['Nota']['dados_adicionais']=str_ireplace(['?'],[''], $nota['Nota']['dados_adicionais']);
				$nota['Nota']['observacao']=str_ireplace(['?'], [''], $nota['Nota']['observacao']);
				
				$nota['Nota']['dados_adicionais']=utf8_encode($nota['Nota']['dados_adicionais'] );
				$nota['Nota']['observacao']=utf8_encode($nota['Nota']['observacao'] );
				
				$string2.="Z|"
						. $nota['Nota']['dados_adicionais'] . '|'//infAdFisco
						. $nota['Nota']['observacao'] . '|'//infCpl
						. $this->QUEBRAR_LINHA; //Cobrancas
				fwrite($fp, $string2);
				$quebra = $this->QUEBRAR_LINHA;
			}//foreach
			fclose($fp);
			return $arquivo;
		}//if count>0
		return 0;
		#print_r($nota);
	}

//ExportarTxt

	private function ValorNota($nota) {
		//pr($nota);
		//pr(count($nota['ItemNota']));
		$i=$valor = 0;
		$produtos_id=array();
		foreach ($nota['ItemNota'] as $itemnota):
			if ($itemnota['imprimir'] == 1):
				if (empty($itemnota['produto_id'])) {
					//nao possui um produto vinculado
					foreach ($itemnota['Pedido']['ItemPedido'] as $item):
						if (in_array($item['produto_id'], $produtos_id)) {
							$tmp_i = array_search($item['produto_id'], $produtos_id);
							$produtos[$tmp_i]['quantidade']+=$item['quantidade'];
							$produtos[$tmp_i]['unidade'] = $item['unidade'];

							$produtos[$tmp_i]['pago']+=$item['pago'];
						} else {
							$produtos_id[$i] = $item['produto_id'];
							$produtos[$i++] = $item;
						}
						$valor+=$item['pago'];

					endforeach;
				} else {
					$itemnota['pago'] = 0;
					

					if (!empty($itemnota['valor_unitario']))
						$itemnota['pago']+=$itemnota['valor_unitario'] * $itemnota['quantidade'];
					
					$itemnota['unidade'] = 1;
					foreach ($itemnota['Pedido']['ItemPedido'] as $item):
						if ($item['produto_id'] == $itemnota['produto_id']) {
							$itemnota['unidade'] = $item['unidade'];
							if (empty($itemnota['valor_unitario']))
								$itemnota['pago']+=$item['pago'];
						}
					endforeach;
					#debug($itemnota['pago']);
					if (in_array($itemnota['produto_id'], $produtos_id)) {
						$tmp_i = array_search($itemnota['produto_id'], $produtos_id);
						$produtos[$tmp_i]['unidade'] = $itemnota['unidade'];
						$produtos[$tmp_i]['quantidade']+=$itemnota['quantidade'];
						$produtos[$tmp_i]['pago']+=$itemnota['pago'];
						if (!empty($itemnota['situacao_tributaria']))
							$produtos[$tmp_i]['situacao_tributaria'] = $itemnota['situacao_tributaria'];
					}else {
						if (!empty($itemnota['situacao_tributaria']))
							$produtos[$i]['situacao_tributaria'] = $itemnota['situacao_tributaria'];
						$produtos_id[$i] = $itemnota['produto_id'];
						$produtos[$i++] = $itemnota;
					}
					#debug($itemnota['pago']);
					$valor+=$itemnota['pago'];
				}
			endif;
			#debug($valor);
		endforeach;
		#debug($valor);
		return $valor;
	}

}
