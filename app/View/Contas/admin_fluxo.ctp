<?php

	//pr($contas);exit();
	App::import('Vendor', 'xtcpdf');

	$font = 'freesans';	    // looks better, finer, and more condensed than 'dejavusans'
	$c = 0;				 //contador de itens impressos

	$tcpdf = new XTCPDF();
	$tcpdf->setPageOrientation('P');


	$tcpdf->SetTitle('Fluxo de Caixa');
	$tcpdf->SetAuthor("Areião Amazonas");
	
	// add a page (required with recent versions of tcpdf)
	function addPage($pdf, $font = 'freesans') {
		$pdf->AddPage();
		$pdf->SetXY(80, 0);
		$pdf->SetFont($font, '', 20);
		
		$pdf->write(30, "Fluxo de Caixa");
		//$pdf->writeHTML('Fluxo de Caixa');
		$pdf->SetXY(10, 24);
		$pdf->SetFont($font, 'B', 11);
		//$pdf->Cell(190, 10, "Contas a Pagar", 1, 1, "C");
		$pdf->Cell(100, 5, "Título", 1, 0, "C");
		$pdf->Cell(30, 5, "Dt. Venc.", 1, 0, "C");
		$pdf->Cell(30, 5, "Valor", 1,0, "C");
		$pdf->Cell(30, 5, "Valor", 1, 1, "C");
	}

	addPage($tcpdf,$font);
	$page=$tcpdf->PageNo();
	$receitaTotal=$despesaTotal=$valorTotal=0;
	if (count($contas) > 0) {
		for($i=0;$i<80;$i++)
		foreach ($contas as $conta) {
			
			if($conta['Conta']['tipo']=='D'){
				$valor=$conta['Conta']['valor']*-1;
				$despesa=moedaBr($conta['Conta']['valor']);
				$receita='';
				$despesaTotal += $valor;
			}else{	
				$valor=$conta['Conta']['valor'];
				$despesa='';
				$receita=moedaBr($conta['Conta']['valor']);
				$receitaTotal += $valor;
			}
			$valorTotal += $valor;
			$tcpdf->Cell(100, 5, $conta['Conta']['descricao'], 1, 0);
			$tcpdf->Cell(30, 5, dateMysqlToPhp($conta['Conta']['data']), 1, 0, "C");
			$tcpdf->Cell(30, 5, $despesa, 1, 0, "R");
			$tcpdf->Cell(30, 5, $receita, 1, 1, "R");
			$c++;
			if($page!=$tcpdf->PageNo() || $c==49){
				$c=0;
				addPage($tcpdf,$font);
				$page=$tcpdf->PageNo();
			}
			
			
			
		}
		$tcpdf->Cell(130, 5, 'Total:', 1, 0,'R');
		$tcpdf->Cell(30, 5, moedaBr($despesaTotal), 1, 0, "R");
		$tcpdf->Cell(30, 5, moedaBr($receitaTotal), 1, 1, "R");
		$c++;
		if($page!=$tcpdf->PageNo() || $c==49){
			$c=0;
			addPage($tcpdf,$font);
			$page=$tcpdf->PageNo();
		}
		$tcpdf->Cell(130, 5, 'Total Geral:', 1, 0);
		$tcpdf->Cell(30, 5, '', 1, 0, "R");
		$tcpdf->Cell(30, 5, moedaBr($valorTotal), 1, 1, "R");
	} else {
		addPage($tcpdf);
		$tcpdf->Cell(190, 0, 'No momento não há não há pedidos finalizados!');
//		$tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
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