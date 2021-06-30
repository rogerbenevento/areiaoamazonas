<?php
    //pr($pedidos);exit();
    App::import('Vendor','xtcpdf'); 
    
    $itens_por_pagina = 19;     
    $font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
    $c = 0;                     //contador de itens impressos
    
    function formataData($dateString){
       $arr = date_parse($dateString);
       return $arr["day"]."/".$arr["month"]."/".$arr["year"];
    }
    function limitarString($string,$msn){
        return substr($string,0,$msn);
    } 
    
    $tcpdf = new XTCPDF();
    $tcpdf->setPageOrientation('P');
    

    $tcpdf->SetTitle('Forma De Pagamento' );
    $tcpdf->SetAuthor("Eletroja");

    
    // add a page (required with recent versions of tcpdf)
    function addPage($pdf,$font='freesans'){
        $pdf->AddPage();
        $pdf->SetXY(10,24);
        $pdf->SetFont($font, '', 20);
        $pdf->writeHTML('Relação de Vendas - Forma De Pagamento');
//        $pdf->SetXY(10,24);
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+10);
        
        $pdf->SetFont($font, 'B', 11);
        $pdf->writeHTMLCell(150, 0, $pdf->GetX(), $pdf->GetY(), '<b>Forma de Pagamento</b>');
        $pdf->writeHTMLCell(40, 0, $pdf->GetX(), $pdf->GetY(), '<b>Total</b>',0,0,false,true,'R');
        $pdf->Ln();
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
    }
    
    $valorTotalGeral=0;
//    addPage($tcpdf,$font);
    if(count($pedidos)>0){
            foreach ( $pedidos as $pedido ) {
                if(($c%$itens_por_pagina)==0) addPage($tcpdf);
                $tcpdf->SetFont($font, '', 10);
                $pedido['Pagamento']['forma_pagto']=($pedido['Pagamento']['forma_pagto']>9)?$pedido['Pagamento']['forma_pagto']:'0'.$pedido['Pagamento']['forma_pagto'];
                $tcpdf->writeHTMLCell(150, 0, $tcpdf->GetX(), $tcpdf->GetY(),  limitarString($pedido['Pagamento']['forma_pagto'].' - '.$pedido['TipoPagamento']['nome'],56));
                $tcpdf->writeHTMLCell(40, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($pedido[0]['total']),0,0,false,true,'R');
                $tcpdf->Ln();
                $tcpdf->SetY($tcpdf->GetY()+2);
                $tcpdf->Line(10, $tcpdf->GetY(), 200, $tcpdf->GetY()); // Desenha uma linha
                $tcpdf->SetY($tcpdf->GetY()+2);
                $c++;
                
                $valorTotalGeral +=  $pedido[0]['total'];
            }
            $tcpdf->SetFont($font, 'B', 12);
            
            $tcpdf->writeHTMLCell( 150, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor Total Geral: ");
            $tcpdf->writeHTMLCell( 40, 0, $tcpdf->GetX(), $tcpdf->GetY(),$model->moedaBr($valorTotalGeral),0,0,false,true,'R');
            $tcpdf->Ln();
            $tcpdf->SetY($tcpdf->GetY()+2);
            $tcpdf->Line(10, $tcpdf->GetY(), 200, $tcpdf->GetY()); // Desenha uma linha
            $tcpdf->SetY($tcpdf->GetY()+2);
            
    }else{
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