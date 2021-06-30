<?php
    //pr($pedidos);exit();
    App::import('Vendor','xtcpdf'); 
    
    $itens_por_pagina = 19;     
    $font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
    $c = 0;                     //contador de itens impressos
    
    function formataData($dateString){
       $arr = date_parse($dateString);
       if($arr["day"]<10)$arr["day"]="0".$arr["day"];
       return $arr["day"]."/".$arr["month"]."/".$arr["year"];
    }
    function limitarString($string,$msn){
        return substr($string,0,$msn);
    } 
    
    $tcpdf = new XTCPDF();
    $tcpdf->setPageOrientation('L');
    

    $tcpdf->SetTitle('Relação Mensal' );
    $tcpdf->SetAuthor("Eletroja");

    
    // add a page (required with recent versions of tcpdf)
    function addPage($pdf,$font='freesans'){
        $pdf->AddPage();
        $pdf->SetXY(110,10);
        $pdf->SetFont($font, '', 20);
        $pdf->writeHTML('Relação Mensal');
        $pdf->SetXY(10,24);        
        $pdf->SetFont($font, 'B', 11);
        $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Data</b>');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Valor</b>');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Dinheiro</b>');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Débito</b>');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Crédito</b>');
        $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Crediário</b>',0,0,false,true,'C');
        $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Perm./Desc.</b>',0,0,false,true,'R');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>VD. Faturada</b>',0,0,false,true,'R');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Cheque</b>',0,0,false,true,'R');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Total</b>',0,0,false,true,'R');
        $pdf->Ln();
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
    }
    
    $valorTotalGeral=0;
//    addPage($tcpdf,$font);
    $data=formataData($pedidos[0]['created']);
    
    if(count($pedidos)>0){
            foreach ( $pedidos as $pedido ) {
                if($data != formataData($pedido['created'])){
                    if(($c%$itens_por_pagina)==0) addPage($tcpdf);
                    $tcpdf->SetFont($font, '', 10);
                    $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), $data);
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valor));
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorDinheiro));
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCB));
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCC));
                    $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCredi),0,0,false,true,'C');
                    $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorDP),0,0,false,true,'R');
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorFatura),0,0,false,true,'R');
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCheque),0,0,false,true,'R');
                    $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valor_total),0,0,false,true,'R');$tcpdf->Ln();
                    $tcpdf->SetY($tcpdf->GetY()+2);
                    $tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
                    $tcpdf->SetY($tcpdf->GetY()+2);
                    $c++;
                    
                    $valorTotal = 0;
                    $data = formataData( $pedido['created']);
                    
                    $ws_data = "";
                    $ws_valor = 0;
                    $ws_valorDinheiro = 0;
                    $ws_valorCB = 0;
                    $ws_valorCC = 0;
                    $ws_valorDP = 0;
                    $ws_valorCheque = 0;
                    $ws_valorCredi = 0;
                    $ws_valorFatura = 0;
                    $ws_valor_total=0;
                }//if
                
                //Realiza a soma por data
                $ws_data = $pedido['created'];
                $ws_valor += $pedido['valor'] ;
                $ws_valorDinheiro += $pedido['valorDinheiro'];
                $ws_valorCB += $pedido['valorCB'];
                $ws_valorCC += $pedido['valorCC'];
                $ws_valorDP += $pedido['valorDP'];
                $ws_valorCheque += $pedido['valorCheque'];
                $ws_valorCredi += $pedido['valorCredi'];
                $ws_valorFatura += $pedido['valorFatura'];
                $ws_valor_total += $pedido['valor_total'];

                //Realiza a soma no geral
                $ws_valorGeral += $pedido['valor'];
                $ws_valorDinheiroGeral += $pedido['valorDinheiro'];
                $ws_valorCBGeral += $pedido['valorCB'];
                $ws_valorCCGeral += $pedido['valorCC'];
                $ws_valorDPGeral += $pedido['valorDP'];
                $ws_valorChequeGeral += $pedido['valorCheque'];
                $ws_valorCrediGeral += $pedido['valorCredi'];
                $ws_valorFaturaGeral += $pedido['valorFatura'];
                $ws_valorTotalGeral += $pedido['valor_total'];
            }//foreach
            //Imprime a ultima linha
            if(($c%$itens_por_pagina)==0) addPage($tcpdf);
            $tcpdf->SetFont($font, '', 10);
            $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), formataData($data));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valor));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorDinheiro));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCB));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCC));
            $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCredi),0,0,false,true,'C');
            $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorDP),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorFatura),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valorCheque),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($ws_valor_total),0,0,false,true,'R');
            $tcpdf->Ln();
            $tcpdf->SetY($tcpdf->GetY()+2);
            $tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
            $tcpdf->SetY($tcpdf->GetY()+2);
            $c++;
            //Imprime os totais
            $tcpdf->SetFont($font, 'B', 12);
            if(($c%$itens_por_pagina)==0) addPage($tcpdf);
            $tcpdf->SetFont($font, '', 10);
            $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>Total:</b>');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorGeral));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorDinheiroGeral));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorCBGeral));
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorCCGeral));
            $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorCrediGeral),0,0,false,true,'C');
            $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorDPGeral),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorFaturaGeral),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorChequeGeral),0,0,false,true,'R');
            $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), '<b>'.$model->moedaBr($ws_valorTotalGeral).'</b>',0,0,false,true,'R');
            $tcpdf->Ln();
            $tcpdf->SetY($tcpdf->GetY()+2);
            $tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
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