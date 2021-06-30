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
    $tcpdf->setPageOrientation('L');
    

    $tcpdf->SetTitle( 'Relação de Vendas');
    $tcpdf->SetAuthor("Eletroja");

    
    // add a page (required with recent versions of tcpdf)
    function addPage($pdf,$font='freesans'){
        $pdf->AddPage();
        $pdf->SetXY(110,10);
        $pdf->SetFont($font, '', 20);
        $pdf->writeHTML('Relação de Vendas');
        $pdf->SetXY(10,24);
        $pdf->SetFont($font, 'B', 11);
        $pdf->writeHTMLCell(15, 0, $pdf->GetX(), $pdf->GetY(), '<b>Pedido</b>');
        $pdf->writeHTMLCell(40, 0, $pdf->GetX(), $pdf->GetY(), '<b>NF</b>');
        $pdf->writeHTMLCell(50, 0, $pdf->GetX(), $pdf->GetY(), '<b>Loja</b>');
        $pdf->writeHTMLCell(40, 0, $pdf->GetX(), $pdf->GetY(), '<b>Vendedor</b>');
        $pdf->writeHTMLCell(40, 0, $pdf->GetX(), $pdf->GetY(), '<b>Cliente</b>');
        $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Data</b>');
        $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Arred.</b>');
        $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Desconto</b>');
        $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Valor</b>');
        $pdf->Ln();
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 285, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
    }
    
    $valorDia=$descontoDia=$valorTotal=0;
    $valorDesconto=0;
    $valorTotalGeral=0;
    $data = formataData($pedidos[0]['Pedido']['created']);
//    addPage($tcpdf,$font);
    if(count($pedidos)>0){
            foreach ( $pedidos as $pedido ) {
                $desconto=$valor=0;
                $data_atual=formataData($pedido['Pedido']['created']);
                
                //Calcula o total do valor atraves dos pagamentos
                foreach ($pedido['Pagamento'] as $pagamento) {
                        $valor += $pagamento['valor'];
                }
                if(($c%$itens_por_pagina)==0){
                    //Adiciona uma nova pagina + o "cabecalho"
                        addPage($tcpdf);
                }
                //
                //DESCONTO
                //
                foreach ($pedido['ItemPedido'] as $itempedido) {
                        $desconto += ($itempedido['valor_unitario']*$itempedido['quantidade'])*($itempedido['desconto']/100);
                }
                $desconto+=$pedido['Pedido']['valor_cupom'];
                //
                //ARREDONDAMENTO
                //
                $arredondamento = (!empty($pedido['Pedido']['arredondamento']))?$pedido['Pedido']['arredondamento'] : 0;
                
                /**
                 *  DATA
                 */
                // Se dia for diferente mostrar total do dia
                if($data != $data_atual ){
                        $tcpdf->setFont( $font, 'B', 12 );
                        $tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor desconto: ". $model->moedaBr($descontoDia));
                        $tcpdf->Ln();
                        $c++;
                        if(($c%$itens_por_pagina)==0) addPage($tcpdf);
                        $tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor total: ". $model->moedaBr($valorDia));
                        $tcpdf->Ln();
                        $tcpdf->SetY($tcpdf->GetY()+2);
                        $tcpdf->Line(10, $tcpdf->GetY(), 285, $tcpdf->GetY()); // Desenha uma linha
                        $tcpdf->SetY($tcpdf->GetY()+2);
                        $c++;
                        if(($c%$itens_por_pagina)==0) addPage($tcpdf);
                        $data = $data_atual;
                        $valorTotal = 0;
                }else{
                    $valorDia += $valor;
                    $descontoDia += $desconto;
                }
                
                
//                $valor_cupom = (!empty($pedido['Pedido']['valor_cupom']))?$pedido['Pedido']['valor_cupom'] : 0;
                $tcpdf->SetFont($font, '', 10);
                $tcpdf->writeHTMLCell(15, 0, $tcpdf->GetX(), $tcpdf->GetY(), $pedido['Pedido']['id']);
                $tcpdf->writeHTMLCell(40, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($pedido['Pedido']['nota_fiscal'],20));
                $tcpdf->writeHTMLCell(50, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($pedido['Loja']['descricao'],20));
                $tcpdf->writeHTMLCell(40, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($pedido['User']['nome'],16));
                $tcpdf->writeHTMLCell(40, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($pedido['Cliente']['nome'],16));
                $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $data_atual);
                $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($arredondamento));
                $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($desconto));
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), $model->moedaBr($valor));

                $tcpdf->Ln();
                $tcpdf->SetY($tcpdf->GetY()+2);
                $tcpdf->Line(10, $tcpdf->GetY(), 285, $tcpdf->GetY()); // Desenha uma linha
                $tcpdf->SetY($tcpdf->GetY()+2);
                $c++;
                
                $valorTotal +=  $valor ;
                $valorTotalGeral +=  $valor;
                $valorDesconto += $desconto;
            }
    }else{
            addPage($tcpdf);
            $tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
    }
    $tcpdf->setFont( $font, 'B', 12 );
    $tcpdf->writeHTMLCell( 280, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor Total: ". ( $model->moedaBr($valorTotal) ));
    $tcpdf->Ln();
    $tcpdf->SetY($tcpdf->GetY()+2);
    $tcpdf->Line(10, $tcpdf->GetY(), 285, $tcpdf->GetY()); // Desenha uma linha
    $tcpdf->SetY($tcpdf->GetY()+2);
    $c++;
    if(($c%$itens_por_pagina)==0) addPage($tcpdf);
    $tcpdf->writeHTMLCell( 280, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor Desconto Geral: ". $model->moedaBr( $valorDesconto ));
    $tcpdf->Ln();
    $tcpdf->SetY($tcpdf->GetY()+2);
    $tcpdf->Line(10, $tcpdf->GetY(), 285, $tcpdf->GetY()); // Desenha uma linha
    $tcpdf->SetY($tcpdf->GetY()+2);
    $c++;
    if(($c%$itens_por_pagina)==0) addPage($tcpdf);
    $tcpdf->writeHTMLCell( 280, 0, $tcpdf->GetX(), $tcpdf->GetY(),"Valor Total Geral: ". $model->moedaBr( $valorTotalGeral ));
    $tcpdf->Ln();
    $tcpdf->SetY($tcpdf->GetY()+2);
    $tcpdf->Line(10, $tcpdf->GetY(), 285, $tcpdf->GetY()); // Desenha uma linha
    $tcpdf->SetY($tcpdf->GetY()+2);
//    $tcpdf->setFont( null, 'B', 12 );
//    $tcpdf->write( 'Total de entregas: '. $i, null, 1, 280 );

//    $tcpdf->close();

    ob_clean();
    ob_end_clean();
    ob_start();
    echo $tcpdf->Output();
    ob_end_flush();
?>