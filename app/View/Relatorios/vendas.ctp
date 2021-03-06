<?php

	//pr($vales);exit();
	//echo $this->element('sql_dump');
	//pr($vales);exit();
	App::import('Vendor', 'xtcpdf');
	define('itens_por_pagina', 30);
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
		    15, // Data
		    20, // Carrego
		    20, // Material
		    20, // Val. Carre.
		    70, // Construtora
		    15, // Vl. M³
		    10, // M³
		    18, // Total  
		    17, // Frete
		    15, // ICMS
		    15, // Custo Extra - Obra
		    18, // Comissão
		    17  // Lucro 
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
		$pdf->SetFont('', '');
		$pdf->writeHTML('Relação de Vendas');
		$pdf->SetXY(10, 24);
		$pdf->SetFontSize(10);
		$imprimir_cabecalho = true;
	}

	function nome_motorista($vale, &$pdf,&$c=null) {
		$pdf->SetFont('', 'B', 10);
		$pdf->Cell(100, 0, "Motorista: " . $vale['Vale']['Motorista']['nome']);
		$pdf->Cell(100, 0, "Data: " . $_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim']);
		$pdf->Ln();
		//========Linha
		$pdf->SetY($pdf->GetY() + 1);
		$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
		$pdf->SetY($pdf->GetY() + 1);
	}

	//nome_motorista

	function cabecalho($vale, &$imprimir_cabecalho,  TCPDF &$pdf,$c) {
		if ($imprimir_cabecalho) {
			nome_motorista($vale, $pdf,$c);
			$pdf->SetFont('', 'B', 9);
			$i = 0;
			$col = getCol();
			$pdf->Cell($col[$i++], 0, 'Nº', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Data', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Carrego', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Material', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Val. Carre.', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Construtora', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Vl. M³', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'M³', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Total', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Frete', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'ICMS', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, '(-)Extra', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Comissão', 0, 0,'C');
			$pdf->Cell($col[$i++], 0, 'Lucro', 0, 0,'C');
	//            $pdf->writeHTMLCell(15, 0, $pdf->GetX(), $pdf->GetY(), '<b>Pedido',0,0,false,true,'C');
	//            $pdf->writeHTMLCell(35, 0, $pdf->GetX(), $pdf->GetY(), '<b>Nota');
	//            $pdf->writeHTMLCell(45, 0, $pdf->GetX(), $pdf->GetY(), '<b>Cliente');
	//            $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Data',0,0,false,true,'C');
	//            $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Arred.',0,0,false,true,'R');
	//            $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Desconto',0,0,false,true,'R');
	//            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Valor',0,0,false,true,'R');
	//            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Comissão',0,0,false,true,'R');
	//            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>V.Garantia',0,0,false,true,'R');
	//            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Comissão',0,0,false,true,'R');
			$pdf->Ln();
			//========Linha
			$pdf->SetY($pdf->GetY() + 1);
			$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
			$pdf->SetY($pdf->GetY() + 1);
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}

	//cabecalho

	function totais_motorista($vale, &$totais, $pdf, &$c) {
		
		$pdf->SetFont(font, 'B', 9);
		if (($c % itens_por_pagina) == 0 || (($c+1) % itens_por_pagina) == 0|| (($c+2) % itens_por_pagina) == 0){
			$c=0;				
			addPage($pdf, $a);
		}
		$i=0;
		$col=  getCol();
		$pdf->Cell($col[0]+$col[1]+$col[2]+$col[3], 0, "Total: ",0,0,'L');
		$pdf->Cell($col[4], 0,  moedaBr($totais['carregado'],true,' '),0,0,'L');
		$pdf->Cell($col[5]+$col[6]+$col[7], 0,  '',0,0,'L');
		//$pdf->Cell($col[6], 0,  moedaBr($totais['vlm2'],true,' '),0,0,'L');
		//$pdf->Cell($col[7], 0,  $totais['m2'],0,0,'L');
		$pdf->Cell($col[8], 0,  moedaBr($totais['total'],true,' '),0,0,'R');
		$pdf->Cell($col[9], 0,  moedaBr($totais['frete'],true,' '),0,0,'R');
		$pdf->Cell($col[10], 0,  moedaBr($totais['icms'],true,' '),0,0,'R');
		$pdf->Cell($col[11], 0,  moedaBr($totais['custo_extra'],true,' '),0,0,'R');
		$pdf->Cell($col[12], 0,  moedaBr($totais['comissao'],true,' '),0,0,'R');
		$pdf->Cell($col[13], 0,  moedaBr($totais['Lucro'],true,' '),0,0,'R');
		$pdf->Ln();
		$c++;
		//========Linha
		$pdf->SetY($pdf->GetY() + 1);
		$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
		$pdf->SetY($pdf->GetY() + 1);
		$c++;
		nome_motorista($vale, $pdf,$c);
		$c++;
		//========Linha
		$pdf->Ln();
		$pdf->Line(10, $pdf->GetY() - 4, 150, $pdf->GetY() - 4); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 3, 150, $pdf->GetY() - 3); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 2, 150, $pdf->GetY() - 2); // Desenha uma linha
		$pdf->Line(10, $pdf->GetY() - 1, 150, $pdf->GetY() - 1); // Desenha uma linha
		$pdf->SetY($pdf->GetY() + 1);
		$c++;
		$totais['carregado'] = 0;
		$totais['vlm2'] = 0;
		$totais['m2'] = 0;
		$totais['total'] = 0;
		$totais['frete'] = 0;
		$totais['icms'] = 0;
		$totais['comissao'] = 0;
		$totais['Lucro'] = 0;
		
	}

	//totais_motorista


	$totais['TotalMotorista'] = 0;
	$totais['ComGarantiaMotorista'] = 0;
	$totais['ComissaoMotorista'] = 0;
	$imprimir_cabecalho = true;
	//    addPage($tcpdf,font);
	$motorista = $vales[0];
	if (count($vales) > 0) {
		foreach ($vales as $vale) {
			#pr($vale);exit();
			if ($motorista['Vale']['motorista_id'] != $vale['Vale']['motorista_id']) {
				//Motorista Diferente Imprimir Totais do Motorista                    
				totais_motorista($motorista, $totais, $tcpdf, $c);
				$motorista = $vale;
				$imprimir_cabecalho = true;
				//========Linha
			}//If motorista
			//Verifica se é preciso imprimir o nome do motorista

			/**
			 * Cabecalho dos Pedidos
			 */
			if ($imprimir_cabecalho) {
				if (($c % itens_por_pagina) == 0 || (($c+1) % itens_por_pagina) == 0|| (($c+2) % itens_por_pagina) == 0){
					addPage($tcpdf, $imprimir_cabecalho);
					$c=0;
				}
				cabecalho($vale, $imprimir_cabecalho, $tcpdf,$c);
				$c++;
				$c++;
			}

			if (($c % itens_por_pagina) == 0) {
				addPage($tcpdf, $imprimir_cabecalho);
				$c=0;
				cabecalho($vale, $imprimir_cabecalho, $tcpdf,$c);
				$c++;
				$c++;
			}
			$tcpdf->SetFontSize(8);
			$i = 0;
			$col = getCol();
			//$total = $vale['ItemPedido']['valor'] * $vale['ItemPedido']['quantidade'];
			$total = $vale['ItemPedido']['pago'];
			
			$lucro = $total - $vale['ItemPedido']['valor_total'] ;
			
//			$frete = ($vale['Vale']['motorista_tipo'] == 1 )? 
//				   ( $vale['Vale']['periodo_id'] == 1 ? $preco_diurno : $preco_noturno)
//				   : $lucro * $porcentagem_freteiro  ;
			$frete = !empty($vale['ItemPedido']['frete'])? $vale['ItemPedido']['frete'] : ($vale['Vale']['motorista_tipo'] == 1 ? 
				   ( $vale['Vale']['periodo_id'] == 1 ? $preco_diurno : $preco_noturno) 
				   : $lucro * $porcentagem_freteiro  );
						
			$icms = $total * $perc_icms;
			if($vale['Pedido']['user_id']==63){
				$comissao=0;
			}else{
				if($total > 880 ){
					$comissao = $total*0.05;
				}else{
					$comissao = $total* $vale['Pedido']['comissao'] *0.01;
				}
			}
			
			$lucro -= ($vale['Obra']['custo_extra']*1);
			$lucro -= $frete;
			$lucro -= $icms;			
			$lucro -= $comissao;
			if($vale['Vale']['motorista_tipo'] == 1){
				// motorista da Casa
				if($lucro < 60){
					$strike=true;
					$lucro += $comissao;
				}else{
					$strike=false;
				}
			}else{
				//freteiro
				if($lucro < 30){
					$strike=true;
					$lucro += $comissao;
				}else{
					$strike=false;
				}
			}
//			pr($total - $vale['ItemPedido']['pago']);
//			pr($frete);
//			pr($icms);
//			pr($comissao);
//			pr($lucro);
			//echo number_format($lucro, 2, ',', '.');exit();
			$data = ($vale['Vale']['data_entrega']);
			$tcpdf->SetFont ('', '');
			
			$nome_obra='';
			$ligacao = false;
			if(!empty($vale['Cliente']['nome'])){
				if(mb_detect_encoding ($vale['Cliente']['nome'])=='UTF-8')
					$vale['Cliente']['nome']= RemoverAcentuacoes($vale['Cliente']['nome']);
				
				//$vale['Cliente']['nome']= RemoverAcentuacoes( $vale['Cliente']['nome'] );
				
				$nome_obra .= substr($vale['Cliente']['nome'],0,18);
				
				$ligacao=true;
			}
			
			if(!empty($vale['Cliente']['nome'])){
				if($ligacao)
					$nome_obra.=' - ';
				$nome_obra .= $vale['Obra']['endereco'];
			}
		
			
			$color=(($c%2)==0)? 255:245;
			$tcpdf->SetFillColor($color, $color, $color);
			
			#$tcpdf->Cell($col[$i++], 0, $vale['Vale']['id']);
			$tcpdf->Cell($col[$i++], 0, $vale['Vale']['codigo']?:$vale['ItemPedido']['pedido_id'],0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, substr($data,0,2).'/'.$mes[(int)substr($data,3,2)-1],0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, limitarString($vale['Vale']['Fornecedor']['nome'],10),0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, limitarString($vale['Produto']['nome'], 10),0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($vale['ItemPedido']['valor_total']),0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, $nome_obra,0,0,'',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($vale['ItemPedido']['pago']/$vale['ItemPedido']['quantidade']),0,0,'R',true);
			$tcpdf->Cell($col[$i++], 0, $vale['ItemPedido']['quantidade'],0,0,'R',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($total,false),0,0,'R',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($frete,false),0,0,'R',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($icms,false),0,0,'R',true);
			$tcpdf->Cell($col[$i++], 0, moedaBr($vale['Obra']['custo_extra'],false),0,0,'R',true);
			if($strike)
				$tcpdf->SetFont ('', 'D');
			$tcpdf->Cell($col[$i++], 0, moedaBr($comissao,false),0,0,'R',true);
			$tcpdf->SetFont ('', '');
			$tcpdf->Cell($col[$i++], 0, moedaBr($lucro),0,0,'R',true);
	//                $tcpdf->writeHTMLCell(15, 0, $tcpdf->GetX(), $tcpdf->GetY(), $vale['id'],0,0,false,true,'C');
	//                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($vale['nota'],10));
	//                $tcpdf->writeHTMLCell(50, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString( $vale['cliente'], 20 ));
	//                $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), formataData( $vale['created'] ),0,0,false,true,'C');
	//                $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $vale['arredondamento'] ),0,0,false,true,'R');
	//                $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $vale['desconto'] ),0,0,false,true,'R');
	//                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $vale['valor'] ),0,0,false,true,'R');
	//                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( ($vale['valor']) * 0.04),0,0,false,true,'R');
	//                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $vale['valor_garantia']),0,0,false,true,'R');
	//                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $vale['valor_garantia'] * 0.07),0,0,false,true,'R');
			//========Linha
			$tcpdf->Ln();
			$tcpdf->SetY($tcpdf->GetY() + 1);
			$tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
			$tcpdf->SetY($tcpdf->GetY() + 1);
			$c++;
			
			$totais['carregado']+=$vale['ItemPedido']['valor_total'];
			$totais['vlm2']+=$vale['ItemPedido']['pago']/$vale['ItemPedido']['quantidade'];
			$totais['m2']+=$vale['ItemPedido']['quantidade'];
			$totais['total']+=$total;
			$totais['frete']+=$frete;
			$totais['icms']+=$icms;
			$totais['custo_extra']+=$vale['Obra']['custo_extra'];
			$totais['comissao']+=$comissao;
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