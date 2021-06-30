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

	
	
	function nome_motorista($vale, &$c=null) {
//		echo "<tr\>";
//		echo "<td colspan='7'>Motorista: " . $vale['Vale']['Motorista']['nome']."</td>";
//		echo "<td colspan='6'>Data: " . $_POST['data']['Relatorio']['inicio'].'~'.$_POST['data']['Relatorio']['fim'].'</td>';
//		echo "</tr>";
		
	}

	//nome_motorista

	function cabecalho($vale, &$imprimir_cabecalho, $c) {
		if ($imprimir_cabecalho) {
			nome_motorista($vale, $c);
			
			$i = 0;
			echo "<tr>";
			echo "<td>". 'Nº'.'</td>';
			echo "<td>". 'Motorista'.'</td>';
			echo "<td>". 'Data'.'</td>';
			echo "<td>". 'Carrego'.'</td>';
			echo "<td>". 'Material'.'</td>';
			echo "<td>". 'Val. Carre.'.'</td>';
			echo "<td>". 'Construtora'.'</td>';
			echo "<td>". 'Vl. M³'.'</td>';
			echo "<td>". 'M³'.'</td>';
			echo "<td>". 'Total'.'</td>';
			echo "<td>". 'Frete'.'</td>';
			echo "<td>". 'ICMS'.'</td>';
			echo "<td>". 'Comissão'.'</td>';
			echo "<td>". 'Lucro'.'</td>';
			echo "<tr>";
			
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}

	//cabecalho

	function totais_motorista($vale, &$totais,  &$c) {
//		echo "<tr>";
//		echo "<td colspan='13'>Total: ".moedaBr($totais['Lucro'])."</td>";
//		echo "</tr>";		
		
		nome_motorista($vale, $c);
		
		
		$totais['Lucro'] = 0;
		
	}

	//totais_motorista

	$totais['Lucro']=0;
	$totais['TotalMotorista'] = 0;
	$totais['ComGarantiaMotorista'] = 0;
	$totais['ComissaoMotorista'] = 0;
	$imprimir_cabecalho = true;
	//    addPage($tcpdf,font);
	$motorista = $vales[0];
        $cab=true;
	if (count($vales) > 0) {
		foreach ($vales as $vale) {
			if ($motorista['Vale']['motorista_id'] != $vale['Vale']['motorista_id']) {
				//Motorista Diferente Imprimir Totais do Motorista                    
				totais_motorista($motorista, $totais,  $c);
				$motorista = $vale;
				$imprimir_cabecalho = true;
				//========Linha
			}//If motorista
			//Verifica se é preciso imprimir o nome do motorista

			/**
			 * Cabecalho dos Pedidos
			 */
			if ($imprimir_cabecalho && $cab) {
                            cabecalho($vale, $imprimir_cabecalho, $c);
                            $c++;
                            $c++;
                            $cab=false;
			}			
			
			$i = 0;
			$total = $vale['ItemPedido']['pago'];
			
			$lucro = $total - $vale['ItemPedido']['valor_total'] ;
			
//			$frete = ($vale['Vale']['motorista_tipo'] == 1 )? 
//				   ( $vale['Vale']['periodo_id'] == 1 ? $preco_diurno : $preco_noturno)
//				   : $lucro * $porcentagem_freteiro  ;
			$frete = !empty($vale['ItemPedido']['frete'])? $vale['ItemPedido']['frete'] : ($vale['Vale']['motorista_tipo'] == 1 ? 
				   ( $vale['Vale']['periodo_id'] == 1 ? $preco_diurno : $preco_noturno) 
				   : $lucro * $porcentagem_freteiro  );
						
			$icms = $total * $perc_icms;
			if($total > 880 ){
				$comissao = $total*0.05;
			}else{
				$comissao = $total* $vale['Pedido']['comissao'] *0.01;
			}
			
						
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
			$data = ($vale['Vale']['data_entrega']);
			
			
			$nome_obra='';
			$ligacao = false;
			if(!empty($vale['Cliente']['nome'])){
				$nome_obra .= substr($vale['Cliente']['nome'],0,20);
				$ligacao=true;
			}
			if(!empty($vale['Cliente']['nome'])){
				if($ligacao)
					$nome_obra.=' - ';
				$nome_obra .= $vale['Obra']['endereco'];
			}
			
			echo "<tr>";
			echo "<td>". $vale['Vale']['codigo']?:$vale['ItemPedido']['pedido_id']."</td>";
			echo "<td>".  $vale['Vale']['Motorista']['nome']."</td>";
			echo "<td>". substr($data,0,2).'/'.$mes[(int)substr($data,3,2)-1 ]."</td>";
			echo "<td>". limitarString($vale['Vale']['Fornecedor']['nome'],10)."</td>";
			echo "<td>". limitarString($vale['Produto']['nome'], 10)."</td>";
			echo "<td>". moedaBr($vale['ItemPedido']['valor_total'])."</td>";
			echo "<td>". $nome_obra."</td>";
			echo "<td>". moedaBr($vale['ItemPedido']['pago']/$vale['ItemPedido']['quantidade'])."</td>";
			echo "<td>". $vale['ItemPedido']['quantidade']."</td>";
			echo "<td>". moedaBr($total,false)."</td>";
			echo "<td>". moedaBr($frete,false)."</td>";
			echo "<td>". moedaBr($icms,false)."</td>";
			
			$str=($strike)?array('<strike>','</strike>'):array('','');
			
			echo "<td>".$str[0]. moedaBr($comissao,false).$str[1]."</td>";
			
			echo "<td>". moedaBr($lucro)."</td>";
			echo "</tr>";
			$c++;
			
			$totais['Lucro']+=$lucro;
		}//foreach
		//Imprime a ultima linha

		/**
		 * Cabecalho dos Pedidos
		 */
		//totais_motorista($motorista, $totais, $tcpdf, $c);
	} else {
		//addPage($tcpdf);
		//$tcpdf->writeHTMLCell(280, 0, $tcpdf->GetX(), $tcpdf->GetY(), 'No momento não há não há pedidos finalizados!');
	}
echo "</table>";
#exit();
?>