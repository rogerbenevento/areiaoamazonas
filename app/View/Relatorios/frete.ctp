<?php
	// if($_SERVER['REMOTE_ADDR']=='201.46.18.179'){
	// 	pr($vales);exit();
	// }
	//echo $this->element('sql_dump');
	//pr($vales);exit();
	App::import('Vendor', 'xtcpdf');
	define('itens_por_pagina', 35);
	define('font', '');
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
	function getCol() {
		return array(
		    10, // Nº
		    20, // Data
		    20, // Carrego
		    15, // Nota
		    20, // Material
		    20, // Vale
		    20, // Val. Carre.
		    80, // Obra
		    15, // Vl. M³
		    10, // M³
		    22, // Total
		    10, // % / (d/N)
		    18 // Frete
		);
	}
	
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

	$tcpdf = new XTCPDF();
	$tcpdf->setPageOrientation('L');


	$tcpdf->SetTitle('Relação Mensal');
	$tcpdf->SetAuthor("Areiao Amazonas");

	// add a page (required with recent versions of tcpdf)
	function addPage($pdf, &$imprimir_cabecalho) {
		$pdf->AddPage();
		$pdf->SetXY(110, 10);
		$pdf->SetFontSize(20);
		$pdf->writeHTML('Relação de Frete');
		$pdf->SetXY(10, 24);
		$pdf->SetFontSize(10);
		$imprimir_cabecalho = true;
	}

	function nome_motorista($vale, &$pdf) {
		$pdf->SetFont('', 'B', 10);
		$pdf->Cell(100, 0, "Motorista: " . $vale['Motorista']['nome']);
		$pdf->Cell(100, 0, "Data: " . $_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim']);
		$pdf->Ln();
		//========Linha
		$pdf->SetY($pdf->GetY() + 0.5);
		$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
		$pdf->SetY($pdf->GetY() + 0.5);
	}

	//nome_motorista

	function cabecalho($vale, &$imprimir_cabecalho, &$pdf) {
		if ($imprimir_cabecalho) {
			nome_motorista($vale, $pdf);
			$pdf->SetFont('', 'B', 9);
			$i = 0;
			$col = getCol();
			$pdf->Cell($col[$i++], 0, 'Nº', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Data', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Carrego', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Nota', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Material', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Vale.', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Val. Carre.', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Obra', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Vl. M³', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'M³', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Total', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'P.', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Frete', 0, 0,'C');
			$pdf->Ln();
			//========Linha
			$pdf->SetY($pdf->GetY() + 0.5);
			$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
			$pdf->SetY($pdf->GetY() + 0.5);
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}

	//cabecalho

	function totais_motorista($vale, &$totais, $pdf, &$c) {
		$lucro_liquido = $totais['Lucro']-$totais['Abastecimento'];
		$msg=$msg2='';
		if($lucro_liquido>0){
			$msg = 'Recebi a quantia de '.moedaBr($lucro_liquido).' referente as viagens feitas entre '.$_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim'];
		}
		
		
		$pdf->SetFont(font, 'B', 10);
		if (($c % itens_por_pagina) == 0)
			addPage($pdf, $a);
		$pdf->Cell(230, 0, "");
		$pdf->Cell(50, 0, "Total: " . moedaBr($totais['Lucro']),1,0,'R');
		$pdf->Ln();
		//========Linha
//		$pdf->SetY($pdf->GetY() + 2);
//		$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
//		$pdf->SetY($pdf->GetY() + 2);
		$c++;
		
		/*======= ABASTECIMENTO*/
		if (($c % itens_por_pagina) == 0)
			addPage($pdf, $a);
		$pdf->SetFont(font, 'B', 8);
		$pdf->Cell(230, 0, $msg);
		$pdf->SetFont(font, 'B', 10);
		if($vale['Vale']['motorista_tipo'] == 2)$pdf->Cell(50, 0, "Abastecimento: " . moedaBr($totais['Abastecimento']),1,0,'R');
		$pdf->Ln();
		//========Linha
//		$pdf->SetY($pdf->GetY() + 2);
//		$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
//		$pdf->SetY($pdf->GetY() + 2);
		$c++;
		/*======= TOTAL LIQUIDO*/
		if (($c % itens_por_pagina) == 0)
			addPage($pdf, $a);
		$pdf->Cell(230, 0, $msg2);
		if($vale['Vale']['motorista_tipo'] == 2)$pdf->Cell(50, 0, "Total Liquido: " . moedaBr($lucro_liquido),1,0,'R');
		$pdf->Ln();
		//========Linha
		$pdf->SetY($pdf->GetY());
		$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY()); // Desenha uma linha
		$pdf->SetY($pdf->GetY());
		$c++;
		/*======== Nome Motorista*/
		nome_motorista($vale, $pdf);
		$c++;
		//========Linha
		$pdf->Ln();
		$pdf->SetY($pdf->GetY());
		$pdf->Line(10, $pdf->GetY() - 4, 150, $pdf->GetY() - 4); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 3, 150, $pdf->GetY() - 3); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 2, 150, $pdf->GetY() - 2); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 1, 150, $pdf->GetY() - 1); // Desenha uma linha
		$pdf->SetY($pdf->GetY());
		$c++;
		$totais['Lucro'] = 0;
		$totais['Abastecimento'] = 0;
		
	}

	//totais_motorista

	
	$totais['Abastecimento'] = 0;
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

			/**
			 * Cabecalho dos Pedidos
			 */
			if ($imprimir_cabecalho) {
				if (($c % itens_por_pagina) == 0)
					addPage($tcpdf, $imprimir_cabecalho);
				cabecalho($vale, $imprimir_cabecalho, $tcpdf);
				$c++;
				$c++;
			}

			if (($c % itens_por_pagina) == 0) {
				addPage($tcpdf, $imprimir_cabecalho);
				cabecalho($vale, $imprimir_cabecalho, $tcpdf);
				$c++;
				$c++;
			}
			$tcpdf->SetFontSize(8);
			$i = 0;
			$col = getCol();
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
			$vale['ItemPedido']['valor_unitario']=$vale['ItemPedido']['valor_unitario']?: $vale['ItemPedido']['pago']/$vale['ItemPedido']['quantidade'];
			$tcpdf->SetFont ('', '');
			$color=(($c%2)==0)? 255:245;
			$tcpdf->SetFillColor($color, $color, $color);
			$tcpdf->Cell($col[$i++], 0, ++$ln ,0,0,'C',1);;
			$tcpdf->Cell($col[$i++], 0, dateMysqlToPhp($data),0,0,'C',1);
			$tcpdf->Cell($col[$i++], 0, limitarString($vale['Fornecedor']['nome'],10),0,0,'',1);
			$tcpdf->Cell($col[$i++], 0, $vale['Vale']['nota_fiscal'],0,0,'C',1);
			$tcpdf->Cell($col[$i++], 0, limitarString($vale['ItemPedido']['Produto']['nome'], 10),0,0,'',1);
			$tcpdf->Cell($col[$i++], 0, $vale['Vale']['codigo']?:$vale['ItemPedido']['pedido_id'],0,0,'C',1);
			$tcpdf->Cell($col[$i++], 0, moedaBr($vale['ItemPedido']['valor_total']), 0, 0,'',1);
			$tcpdf->Cell($col[$i++], 0, substr($vale['ItemPedido']['Pedido']['Cliente']['nome'],0,18).' - '.$vale['ItemPedido']['Pedido']['Obra']['endereco'] ,  0,0,'',1);
			$tcpdf->Cell($col[$i++], 0, moedaBr($vale['ItemPedido']['valor_unitario']),0,0,'R',1);
			$tcpdf->Cell($col[$i++], 0, $vale['ItemPedido']['quantidade'],0,0,'C',1);
			$tcpdf->Cell($col[$i++], 0, moedaBr($total,false),0,0,'R',1);
			$tcpdf->Cell($col[$i++], 0, $calculo_frete,0,0,'C',1);
			$tcpdf->Cell($col[$i++], 0, moedaBr($frete,false),0,0,'R',1);
			//========Linha
			$tcpdf->Ln();
			$tcpdf->SetY($tcpdf->GetY()+0.5 );
			$tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
			$tcpdf->SetY($tcpdf->GetY()+0.5 );
			$c++;
			
			$totais['Lucro']+=$lucro;
		}//foreach
		//Imprime a ultima linha

		/**
		 * Cabecalho dos Pedidos
		 */
		totais_motorista($motorista, $totais, $tcpdf, $c);
	} else {
		addPage($tcpdf);
		$tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
	}

	//    $tcpdf->setFont( null, 'B', 12 );
	//    $tcpdf->write( 'Total de entregas: '. $i, null, 1, 280 );
	//    $tcpdf->close();

	ob_clean();
	ob_end_clean();
	ob_start();
	echo $tcpdf->Output();
	ob_end_flush();
?>