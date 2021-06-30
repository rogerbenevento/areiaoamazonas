<?php
	
echo "<table>";

	$strike = false;	
	
	//itens_por_pagina = 19;     
	//font = 'freesans';         // looks better, finer, and more condensed than 'dejavusans'
	$c = 0;				 //contador de itens impressos
	//Array  de configuracao da coluna
	
	
	$mes=array('jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez');
	

	function cabecalho($vale, &$imprimir_cabecalho, $c) {
		if ($imprimir_cabecalho) {
			
			
			$i = 0;
			echo "<tr>";
			echo "<td>". 'Nº'.'</td>';
			echo "<td>". 'Data'.'</td>';
			echo "<td>". 'Empresa'.'</td>';
			echo "<td>". 'Cliente'.'</td>';
			echo "<td>". 'Observacao'.'</td>';
			echo "<td>". 'Produto'.'</td>';
			echo "<td>". 'Qtde'.'</td>';
			echo "<td>". 'Total'.'</td>';		
			echo "<td>". '4%'.'</td>';
			echo "<tr>";
			
			$imprimir_cabecalho = false;
		}//if imprimir cabecalho
	}

	//cabecalho

	
	//totais_motorista

	$totais['Lucro']=0;
	$totais['TotalMotorista'] = 0;
	$totais['ComGarantiaMotorista'] = 0;
	$totais['ComissaoMotorista'] = 0;
	$imprimir_cabecalho = true;
	//    addPage($tcpdf,font);
        $cab=true;
	if (count($notas) > 0) {
		foreach ($notas as $nota) {
		
						/**
			 * Cabecalho dos Pedidos
			 */
			if ($imprimir_cabecalho && $cab) {
				cabecalho($nota, $imprimir_cabecalho, $c);
				$c++;
				$c++;
				$cab=false;
			}			
			
			$i = 0;
			
			
			
			
			$html.= "<tr>";
			$html.= "<td>". $nota['Nota']['numero']."</td>";
			$html.= "<td>". $nota['Nota']['emissao']."</td>";
			$html.= "<td>". $nota['Empresa']['nome']."</td>";
			$html.= "<td>". $nota['Cliente']['nome']."</td>";
			$html.= "<td>". $nota['Nota']['observacao']."</td>";
			
			if(!empty($nota['ItemNota'])){
				//Calcula o valor total da nota
				$total=0;			
				foreach($nota['ItemNota'] as $int):
					$total+=$int['valor_unitario'];
				endforeach;
				
			
				$espaco = false;
				$align=$align2='';
				foreach($nota['ItemNota'] as $in):
					if($espaco){
						$html.= '<tr>';
						$html.= "<td colspan='5'></td>";
					}
					$html.= "<td>".$in['Produto']['nome']."</td>";
					$html.= "<td>".$in['quantidade']."</td>";
					if(!$espaco){
						$html.= "<td>".  moedaBr($total)."</td>";
						$html.= "<td>".  moedaBr($total*0.04)."</td>";
					}

					$html.= "</tr>";
					$c++;
					$espaco=true;
				endforeach;
			}else{
				$html.= "</tr>";
				$c++;
			}
			
			
			
			
			echo utf8_decode($html);
			
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

