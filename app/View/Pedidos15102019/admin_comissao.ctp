<?php
    //echo $this->element('sql_dump');
    //pr($pedidos);exit();
    App::import('Vendor','xtcpdf'); 
    
    //itens_por_pagina = 19;     
    //font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
    $c = 0;                     //contador de itens impressos
    
    define('itens_por_pagina',20);
    define('font', 'freesans');
    
    //pr(itens_por_pagina);exit();
    function moedaBr($valor){
        $valor=str_replace(',', '&', number_format($valor,2));
        $valor=str_replace('.', ',', $valor);
        $valor=str_replace('&', '.', $valor);
        return "R$ ".$valor;
    }
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
    function addPage($pdf,&$imprimir_cabecalho_pedido){
        $pdf->AddPage();
        $pdf->SetXY(110,10);
        $pdf->SetFont(font, '', 20);
        $pdf->writeHTML('Relação de Comissão');
        $pdf->SetXY(10,24);
        $pdf->SetFont(font, '', 10);
        $imprimir_cabecalho_pedido=true;
    }
    
    function nome_vendedor($pedido,$pdf){
        $pdf->SetFont(font, '', 10);            
        $pdf->writeHTMLCell(100, 0, $pdf->GetX(), $pdf->GetY(), "<b>Vendedor(a): ".$pedido['vendedor']);
        $pdf->writeHTMLCell(100, 0, $pdf->GetX(), $pdf->GetY(), "<b>Loja: ".$pedido['loja']);
        $pdf->Ln();
        //========Linha
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
           
    }//nome_vendedor
    
    function cabecalho_pedido($pedido,&$imprimir_cabecalho_pedido,$pdf){
        if($imprimir_cabecalho_pedido){ 
            nome_vendedor($pedido,$pdf);
            $pdf->SetFont(font, '', 10);            
            $pdf->writeHTMLCell(15, 0, $pdf->GetX(), $pdf->GetY(), '<b>Pedido',0,0,false,true,'C');
            $pdf->writeHTMLCell(35, 0, $pdf->GetX(), $pdf->GetY(), '<b>Nota');
            $pdf->writeHTMLCell(45, 0, $pdf->GetX(), $pdf->GetY(), '<b>Cliente');
            $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Data',0,0,false,true,'C');
            $pdf->writeHTMLCell(20, 0, $pdf->GetX(), $pdf->GetY(), '<b>Arred.',0,0,false,true,'R');
            $pdf->writeHTMLCell(25, 0, $pdf->GetX(), $pdf->GetY(), '<b>Desconto',0,0,false,true,'R');
            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Valor',0,0,false,true,'R');
            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Comissão',0,0,false,true,'R');
            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>V.Garantia',0,0,false,true,'R');
            $pdf->writeHTMLCell(30, 0, $pdf->GetX(), $pdf->GetY(), '<b>Comissão',0,0,false,true,'R');
            $pdf->Ln();
            //========Linha
            $pdf->SetY($pdf->GetY()+2);
            $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
            $pdf->SetY($pdf->GetY()+2);
            $imprimir_cabecalho_pedido=false;
        }//if imprimir cabecalho
    }//cabecalho_pedido
    
    function totais_vendedor($pedido,&$totais,$pdf,&$c){
        nome_vendedor($pedido, $pdf);
        $c++;
        $pdf->SetFont(font, 'B', 10);
        if(($c%itens_por_pagina)==0) addPage($pdf,$a);
        $pdf->writeHTMLCell(280, 0, $pdf->GetX(), $pdf->GetY(), "<b>Valor total de Vendas: ". moedaBr( $totais['TotalVendedor'] ));
        $pdf->Ln();
        //========Linha
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
        $c++;
        if(($c%itens_por_pagina)==0) addPage($pdf,$a);
        $pdf->writeHTMLCell(280, 0, $pdf->GetX(), $pdf->GetY(), "<b>Valor total de Comissão por Vendas de Produtos: ". moedaBr( $totais['ComissaoVendedor'] ));
        $pdf->Ln();
        //========Linha
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
        $c++;
        if(($c%itens_por_pagina)==0) addPage($pdf,$a);
        $pdf->writeHTMLCell(280, 0, $pdf->GetX(), $pdf->GetY(), "<b>Valor total de Comissão por Vendas de Garantia: ". moedaBr( $totais['ComGarantiaVendedor'] ));
        $pdf->Ln();
        //========Linha
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
        $c++;
        if(($c%itens_por_pagina)==0) addPage($pdf,$a);
        $pdf->writeHTMLCell(280, 0, $pdf->GetX(), $pdf->GetY(), "<b>Valor Total de Comissao: ". moedaBr( $totais['ComissaoVendedor'] + $totais['ComGarantiaVendedor'] ));
        $pdf->Ln();
        //========Linha
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
        $c++;
        //========Linha
        $pdf->Ln();
        $pdf->SetY($pdf->GetY()+2);
        $pdf->Line(10, $pdf->GetY()-7, 150, $pdf->GetY()-7); // Desenha uma linha
        $pdf->Line(10, $pdf->GetY()-6, 150, $pdf->GetY()-6); // Desenha uma linha
        $pdf->Line(10, $pdf->GetY()-5, 150, $pdf->GetY()-5); // Desenha uma linha
        $pdf->Line(10, $pdf->GetY()-4, 150, $pdf->GetY()-4); // Desenha uma linha
        $pdf->SetY($pdf->GetY()+2);
        $c++;
        $totais['TotalVendedor']=0;
        $totais['ComGarantiaVendedor']=0;
        $totais['ComissaoVendedor']=0;        
    }//totais_vendedor
    
    
    $totais['TotalVendedor']=0;
    $totais['ComGarantiaVendedor']=0;
    $totais['ComissaoVendedor']=0;
    $imprimir_cabecalho_pedido=true;
//    addPage($tcpdf,font);
    $vendedor=$pedidos[0];
    if(count($pedidos)>0){
            foreach ( $pedidos as $pedido ) {
                if($vendedor['vendedor_id'] != $pedido['vendedor_id']){
                    //Vendedor Diferente Imprimir Totais do Vendedor                    
                    totais_vendedor($vendedor,$totais,$tcpdf,$c);
                    $vendedor=$pedido;
                    
                    $imprimir_cabecalho_pedido=true;
                    //========Linha
                }//If vendedor
                //Verifica se é preciso imprimir o nome do vendedor
                
                /**
                 * Cabecalho dos Pedidos
                 */
                if($imprimir_cabecalho_pedido){
                    if(($c%itens_por_pagina)==0)addPage($tcpdf,$imprimir_cabecalho_pedido);
                    cabecalho_pedido($pedido,$imprimir_cabecalho_pedido, $tcpdf);
                    $c++;
                    $c++;                    
                }

                if(($c%itens_por_pagina)==0){
                    addPage($tcpdf,$imprimir_cabecalho_pedido);
                    cabecalho_pedido($pedido,$imprimir_cabecalho_pedido, $tcpdf);
                    $c++;
                    $c++;
                }
                $tcpdf->SetFont(font, '', 10);
                $tcpdf->writeHTMLCell(15, 0, $tcpdf->GetX(), $tcpdf->GetY(), $pedido['id'],0,0,false,true,'C');
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString($pedido['nota'],10));
                $tcpdf->writeHTMLCell(50, 0, $tcpdf->GetX(), $tcpdf->GetY(), limitarString( $pedido['cliente'], 20 ));
                $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), formataData( $pedido['created'] ),0,0,false,true,'C');
                $tcpdf->writeHTMLCell(20, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $pedido['arredondamento'] ),0,0,false,true,'R');
                $tcpdf->writeHTMLCell(25, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $pedido['desconto'] ),0,0,false,true,'R');
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $pedido['valor'] ),0,0,false,true,'R');
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( ($pedido['valor']) * 0.04),0,0,false,true,'R');
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $pedido['valor_garantia']),0,0,false,true,'R');
                $tcpdf->writeHTMLCell(30, 0, $tcpdf->GetX(), $tcpdf->GetY(), moedaBr( $pedido['valor_garantia'] * 0.07),0,0,false,true,'R');
                //========Linha
                $tcpdf->Ln();
                $tcpdf->SetY($tcpdf->GetY()+2);
                $tcpdf->Line(10, $tcpdf->GetY(), 290, $tcpdf->GetY()); // Desenha uma linha
                $tcpdf->SetY($tcpdf->GetY()+2);
                $c++;

                $totais['TotalVendedor'] +=$pedido['valor'];                
                $totais['ComissaoVendedor'] += $pedido['valor']* 0.04;
                $totais['ComGarantiaVendedor']+= $pedido['valor_garantia'] * 0.07;

                
                
            }//foreach
            //Imprime a ultima linha
            
            /**
             * Cabecalho dos Pedidos
             */
   
            totais_vendedor($vendedor,$totais, $tcpdf, $c);
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