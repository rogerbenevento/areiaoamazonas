<?php
	#pr($notas);exit();
	//echo $this->element('sql_dump');
	//pr($notas);exit();
	if($_SERVER["REMOTE_ADDR"] == "179.178.190.32"){
		//Configure::write('debug',2);
		//echo $this->element('sql_dump');
		// pr($notas);
		// exit();
	}
	App::import('Vendor', 'xtcpdf');
	define('ITENS_POR_PAGINA', 33);
	define('LINE_HEIGHT', 5);
	define('font', '');
	
	// CONFIGURACOES
	$limite_frete_casa 		= 70;
	$limite_frete_freteiro 	= 50;
	$preco_diurno 			= 20;
	$preco_noturno 			= 30;
	$porcentagem_freteiro 	= 0.65;
	$perc_icms 				= 0.08;
	//$percent_comissao=0.04;
	$strike 				= false;	
	
	
	//
	
	//ITENS_POR_PAGINA = 19;     
	//font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
	$c = 0;				 //contador de itens impressos
	//Array  de configuracao da coluna
	function getCol() {
		return array(
		    10, // Nº
		    14, // Data
		    18, // Carrego
		    55, // Cliente
		    100, // Observacao
		    25, // Produto
		    10, // Qtde
		    14, // Valor Unitario
		    16, // Valor Total
		    14, // Valor Total 4%
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
		$pdf->writeHTML('Relação de Notas');
		$pdf->SetXY(10, 24);
		$pdf->SetFontSize(10);
		$imprimir_cabecalho = true;
	}


	

	function cabecalho($nota, &$imprimir_cabecalho,  TCPDF &$pdf,$c) {
		if ($imprimir_cabecalho) {
			
			$pdf->SetFont('', 'B', 9);
			$i = 0;
			$col = getCol();
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Nº', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Data', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Empresa', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Cliente', 0, 0,'C');			
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Observação', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Produto', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Qtde', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Vl.Uni.', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, 'Total', 0, 0,'C');
			$pdf->Cell($col[$i++], LINE_HEIGHT, '4%', 0, 0,'C');
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
			$pdf->SetFont('', '');
			//========Linha
//			$pdf->SetY($pdf->GetY() + 1);
//			$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
//			$pdf->SetY($pdf->GetY() + 1);
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}

		
	$imprimir_cabecalho = true;
	//    addPage($tcpdf,font);
	$nota_numero = 0;
	if (count($notas) > 0) {
		//echo json_encode($notas); exit;
		foreach ($notas as $nota) {
			
			/**
			 * Cabecalho dos Pedidos
			 */
			if ($imprimir_cabecalho) {
				if (($c % ITENS_POR_PAGINA) == 0 || (($c+1) % ITENS_POR_PAGINA) == 0|| (($c+2) % ITENS_POR_PAGINA) == 0){
					addPage($tcpdf, $imprimir_cabecalho);
					$c=0;
				}
				cabecalho($nota, $imprimir_cabecalho, $tcpdf,$c);
				$c++;
				$c++;
			}

			if (($c % ITENS_POR_PAGINA) == 0) {
				addPage($tcpdf, $imprimir_cabecalho);
				$c=0;
				cabecalho($nota, $imprimir_cabecalho, $tcpdf,$c);
				$c++;
				$c++;
			}
			$tcpdf->SetFontSize(7);
			$i = 0;
			$col = getCol();
		
			
			$data = ($nota['Nota']['data_entrega']);
			$tcpdf->SetFont ('', '');
			
			$nome_obra='';
			$ligacao = false;
			if(!empty($nota['Cliente']['nome'])){
				if(mb_detect_encoding ($nota['Cliente']['nome'])=='UTF-8')
					$nota['Cliente']['nome']= RemoverAcentuacoes($nota['Cliente']['nome']);
				
				//$nota['Cliente']['nome']= RemoverAcentuacoes( $nota['Cliente']['nome'] );
				
				$nome_obra .= substr($nota['Cliente']['nome'],0,18);
				
				$ligacao=true;
			}
			
			$color=($color==235)? 255:235;
			$tcpdf->SetFillColor($color, $color, $color);
			if($nota_numero==0){
				$nota_numero= $nota['Nota']['numero']*1;
			}else if($nota_numero < $nota['Nota']['numero']*1){
				//echo $nota_numero . ' < ' .  $nota['Nota']['numero']*1;
				//echo json_encode($nota); exit;
				
				for($cn=$nota_numero;$cn < $nota['Nota']['numero']*1 ;$cn++){
					$tcpdf->Cell($col[$i++], LINE_HEIGHT, $cn,'TB',0,'',true);
					$tcpdf->Cell(
						$col[$i++]+$col[$i++]+
						$col[$i++]+$col[$i++]+
						$col[$i++]+$col[$i++]+$col[$i++]+$col[$i++]+$col[$i++], LINE_HEIGHT, '#### CANCELADA #####','TB',1,'',true);
					$i=0;
				}
				$nota_numero= $nota['Nota']['numero']*1;				
			}
		
			#$tcpdf->Cell($col[$i++], 0, $nota['Nota']['id']);
			
			$tcpdf->Cell($col[$i++], LINE_HEIGHT, $nota['Nota']['numero'],'TB',0,'',true);
			$tcpdf->Cell($col[$i++], LINE_HEIGHT, $nota['Nota']['emissao'],'TB',0,'',true);
			$tcpdf->Cell($col[$i++], LINE_HEIGHT, limitarString($nota['Empresa']['nome'],10),'TB',0,'',true);
			$tcpdf->Cell($col[$i++], LINE_HEIGHT, limitarString($nota['Cliente']['nome'], 100),'TB',0,'',true);
			$tcpdf->Cell($col[$i++], LINE_HEIGHT,limitarString($nota['Nota']['observacao'],90),'TB',0,'L',true);
			$nota_numero++;
			if(!empty($nota['ItemNota'])){
				//Calcula o valor total da nota
				$total=0;			
				
				foreach($nota['ItemNota'] as &$int):
					if(!empty($int['valor_unitario']))
						$int['pago']+=$int['valor_unitario']*$int['quantidade'];
					else{
						foreach($int['Pedido']['ItemPedido'] as $item):
							if($item['produto_id']==$int['produto_id']){
								$int['unidade']=$item['unidade'];
								if(empty($int['valor_unitario']))
									$int['pago']+=$item['pago'];
								
							}
						endforeach;						
					}
					$total+=$int['pago'];
				endforeach;
				$espaco = false;
				$align=$align2='';

				$prduto = ''; 
				$quantidade = '';
				$valor = 0;

				$index = 0;				
				$array = [];



				$indexJ = 0;
				$array_compartivo = [];


				foreach($nota['ItemNota'] as $in):
					$vluni=$in['pago']/$in['quantidade'];
				 	$array_compartivo[$indexJ]=['n'=>$in['Produto']['nome'],'q'=>$in['quantidade'],'v'=>$vluni];
				 	$indexJ++;
				endforeach;

				foreach($nota['ItemNota'] as $in):
					$qty = 0;
					$val = 0;

					$array[$index]=['n'=>$in['Produto']['nome'],'q'=>$in['quantidade'],'v'=>$vluni];

					 $m_item = false;
					 if($index>0){
					 	if($array[$index-1]['n']==$array[$index]['n']){
					 		$m_item = true;
					 	}
					}

					if($array_compartivo[$index+1]['n'] == $array[$index]['n']){
						$name = '*';
						$qty = $array_compartivo[$index+1]['q'];
						$val = $array_compartivo[$index+1]['v'];
					}



				if(!$m_item): //repitido
					if (($c % ITENS_POR_PAGINA) == 0) {
						addPage($tcpdf, $imprimir_cabecalho);
						$c=0;
						cabecalho($nota, $imprimir_cabecalho, $tcpdf,$c);
						$c++;
						$c++;
					}
					if($espaco){
						$i=0;
						$tcpdf->SetX(10+ $col[$i++]+$col[$i++]+$col[$i++]+$col[$i++]+$col[$i++]);
						$align='R';
						$align2='L';
					}
					$tcpdf->SetFontSize(7);
					$vluni=$in['pago']/$in['quantidade'];


					


					
						$tcpdf->Cell($col[$i++], LINE_HEIGHT,limitarString($in['Produto']['nome'],90),$align2.'TB',0,'L',true);
						$tcpdf->Cell($col[$i++], LINE_HEIGHT,limitarString($in['quantidade']+$qty,90),'TB',0,'L',true);
						$tcpdf->Cell($col[$i++], LINE_HEIGHT,moedaBr($vluni+$val),$align.'TB',0,'L',true);
					
					



					if(!$espaco){
						$tcpdf->Cell($col[$i++], LINE_HEIGHT,  moedaBr($total),'TB',0,'R',true);
						$tcpdf->Cell($col[$i++], LINE_HEIGHT,  moedaBr($total*0.04),'TB',0,'R',true);
					}

					$tcpdf->Ln();

				endif;//repitido
					$c++;
					$espaco=true;
					$index++;
				endforeach;
			}else{
				$tcpdf->Ln();
				$c++;
			}
			//========Linha
		
			
			
			
			
			
		}//foreach
		//Imprime a ultima linha

			
		
	} else {
		addPage($tcpdf);
		$tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
	}

	
	ob_clean();
	ob_end_clean();
	ob_start();
	echo $tcpdf->Output();
	ob_end_flush();
?>