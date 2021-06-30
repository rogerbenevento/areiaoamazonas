<?php

echo "<table>";
	// CONFIGURACOES
	$limite_frete_casa=70;
	$limite_frete_freteiro=50;
	$preco_diurno = 20;
	$preco_noturno= 30;
	$porcentagem_freteiro = 0.65;
	$perc_icms=0.08;
	//$percent_comissao=0.04;
	$strike = false;	
	
	
	//
	
	//itens_por_pagina = 19;     
	//font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
	$c = 0;				 //contador de itens impressos
	//Array  de configuracao da coluna
	
	
	$mes=array('jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez');
	
	

	function formataData($dateString) {
		$arr = date_parse($dateString);
		if ($arr["day"] < 10)
			$arr["day"] = "0" . $arr["day"];
		return $arr["day"] . "/" . $arr["month"] . "/" . $arr["year"];
	}

	function limitarString($string, $msn) {
		return substr($string, 0, $msn);
	}

	

	function nome_motorista($vale) {
		echo "<tr>";
		echo "<td colspan='7'>Motorista: " . $vale['Motorista']['nome']."</td>";
		echo "<td colspan='6'>Data: " . $_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim'].'</td>';
		echo "</tr>";
	}

	//nome_motorista

	function cabecalho($vale, &$imprimir_cabecalho) {
		if ($imprimir_cabecalho) {
			nome_motorista($vale );
			$i = 0;
			
			echo "<tr>";
			echo "<td>". 'Nº'."</td>";
			echo "<td>". 'Data'."</td>";
			echo "<td>". 'Carrego'."</td>";
			echo "<td>". 'Nota'."</td>";
			echo "<td>". 'Material'."</td>";
			echo "<td>". 'Vale.'."</td>";
			echo "<td>". 'Val. Carre.'."</td>";
			echo "<td>". 'Obra'."</td>";
			echo "<td>". 'Vl. M³'."</td>";
			echo "<td>". 'M³'."</td>";
			echo "<td>". 'Total'."</td>";
			echo "<td>". 'P.'."</td>";
			echo "<td>". 'Frete'."</td>";
			echo "</tr>";
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}
	
	//cabecalho

	function totais_motorista($vale, &$totais, &$c) {
		$lucro_liquido = $totais['Lucro']-$totais['Abastecimento'];
		$msg=$msg2='';
		if($lucro_liquido>0){
			$msg = 'Recebi a quantia de '.moedaBr($lucro_liquido).' referente as viagens feitas entre '.$_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim'];
		}
		
		
		echo "<tr>";
		echo "<td colspan='13'>Total: " . moedaBr($totais['Lucro']).'</td>';
		echo "</tr>";
		
		if($vale['Vale']['motorista_tipo'] == 2)
			echo "<tr><td colspan='13'>Abastecimento: ".moedaBr($totais['Abastecimento'])."</td></tr>";
		
		
		/*======= TOTAL LIQUIDO*/
		
		echo "<tr><td colspan='13'>Abastecimento: ".$msg2."</td></tr>";
		if($vale['Vale']['motorista_tipo'] == 2)
			echo "<tr><td colspan='13'>Total Liquido: " . moedaBr($lucro_liquido)."</td></tr>";
		
		/*======== Nome Motorista*/
		nome_motorista($vale, $pdf);
		$c++;
		$totais['Lucro'] = 0;
		$totais['Abastecimento'] = 0;
		
	}

	//totais_motorista

	
	$totais['Lucro']=$totais['Abastecimento'] = 0;
	$totais['TotalMotorista'] = 0;
	$totais['ComGarantiaMotorista'] = 0;
	$totais['ComissaoMotorista'] = 0;
	$imprimir_cabecalho = true;
	//    addPage($tcpdf,font);
	$motorista = $vales[0];
	if (count($vales) > 0) {
		$ln = 0;
		foreach ($vales as $vale) {
			
			if($vale['ItemPedido']['Pedido']['freteiro']>0)
				$porcentagem_freteiro = $vale['ItemPedido']['Pedido']['freteiro']/100;
			
			if ($motorista['Vale']['motorista_id'] != $vale['Vale']['motorista_id']) {
				//Motorista Diferente Imprimir Totais do Motorista                    
				totais_motorista($motorista, $totais, $tcpdf, $c);
				$ln = 0;
				$motorista = $vale;

				$imprimir_cabecalho = true;
				//========Linha
			}//If motorista
			//Verifica se é preciso imprimir o nome do motorista

			if ($imprimir_cabecalho) {
				cabecalho($vale, $imprimir_cabecalho);
			}
			//$total = $vale['ItemPedido']['valor'] * $vale['ItemPedido']['quantidade'];
			$total = $vale['ItemPedido']['pago'];
			
			$lucro =$total - $vale['ItemPedido']['valor_total'];
			
			$icms = $total * $perc_icms;
			$comissao =$total*$vale['ItemPedido']['Pedido']['comissao']*0.01;
			
			
			
			if($vale['Vale']['motorista_tipo'] == 1){
				// motorista da Casa
				if($vale['Vale']['periodo_id'] == 1){
//					$frete=$preco_diurno;
					$calculo_frete = 'D';
				}else{
//					$frete=$preco_noturno;
					$calculo_frete = 'N';
				}
			}else{
				// freteiro
//				$frete= $lucro * $porcentagem_freteiro;
				$calculo_frete = ($porcentagem_freteiro*100).'%';
//				if(count($vale['Motorista']['Abastecimento'])>0)
//				foreach ($vale['Motorista']['Abastecimento'] as $value) {
//					$totais['Abastecimento']+=$value['valor'];
//				}
			}
			
			$frete = $vale['ItemPedido']['frete'];
			
			$lucro = $frete;
			
			$data = ($vale['Vale']['data_entrega']);
			$vale['ItemPedido']['valor_unitario']= ( !empty($vale['ItemPedido']['valor_unitario']) )?$vale['ItemPedido']['valor_unitario']: $vale['ItemPedido']['pago']/$vale['ItemPedido']['quantidade'];
			echo "<tr>";
			echo "<td>".(++$ln)."</td>";
			echo "<td>".dateMysqlToPhp($data)."</td>";
			echo "<td>".limitarString($vale['Fornecedor']['nome'],10)."</td>";
			echo "<td>".$vale['Vale']['nota_fiscal']."</td>";
			echo "<td>".limitarString($vale['ItemPedido']['Produto']['nome'], 10)."</td>";
			echo "<td>".$vale['Vale']['codigo']?:$vale['ItemPedido']['pedido_id']."</td>";
			echo "<td>".moedaBr($vale['ItemPedido']['valor_total'])."</td>";
			echo "<td>".substr($vale['ItemPedido']['Pedido']['Cliente']['nome'],0,20).' - '.$vale['ItemPedido']['Pedido']['Obra']['endereco'] ."</td>";
			echo "<td>".moedaBr($vale['ItemPedido']['valor_unitario'])."</td>";
			echo "<td>".$vale['ItemPedido']['quantidade']."</td>";
			echo "<td>".moedaBr($total,false)."</td>";
			echo "<td>".$calculo_frete."</td>";
			echo "<td>".moedaBr($frete,false)."</td>";
			echo "</tr>";
			$c++;
			
			$totais['Lucro']+=$lucro;
		}//foreach
		//Imprime a ultima linha

		/**
		 * Cabecalho dos Pedidos
		 */
		totais_motorista($motorista, $totais,  $c);
	} else {
		//addPage($tcpdf);
		//$tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
	}

	//    $tcpdf->setFont( null, 'B', 12 );
	//    $tcpdf->write( 'Total de entregas: '. $i, null, 1, 280 );
	//    $tcpdf->close();

echo "</table>";
#exit();
?>