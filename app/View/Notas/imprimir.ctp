<?php

App::import('Vendor', 'xtcpdf');
$a = array(31.7,21.8); 
$pdf = new XTCPDF('P','cm' ,$a); 
$pdf->DisableCabecalho();
$pdf->DisableRodape();
$pdf->AddPage(); 

$border = 0;

$produtos=array();
$produtos_id=array();
$valor=$i=0;
//if($_SERVER['REMOTE_ADDR'] == '186.214.4.178'){
//	Configure::write('debug',2);
//}
#if ($nota['Cliente']['nome'] == 'HOOMWEB') {
#	Configure::write('debug',2);
#	pr($nota);exit();
#}

//echo json_encode($nota['ItemNota']); exit;

foreach($nota['ItemNota'] as $itemnota):
	
	if($itemnota['imprimir']==1):
		if(empty($itemnota['produto_id'])){
			//nao possui um produto vinculado
			
			foreach($itemnota['Pedido']['ItemPedido'] as $item):
				if(in_array($item['produto_id'],$produtos_id)){
					$tmp_i=array_search($item['produto_id'], $produtos_id);
					$produtos[$tmp_i]['quantidade']+=$item['quantidade'];
					$produtos[$tmp_i]['unidade']=$item['unidade'];
					
					$produtos[$tmp_i]['pago']+=$item['pago'];
				}else{
					$produtos_id[$i]=$item['produto_id'];
					$produtos[$i++]=$item;
				}
				$valor+=$item['pago'];
				
			endforeach;
		}else{
			$itemnota['pago']=0;
			
			
			if(!empty($itemnota['valor_unitario']))
				$itemnota['pago']+=$itemnota['valor_unitario']*$itemnota['quantidade'];
			
			$itemnota['unidade']=1;
			foreach($itemnota['Pedido']['ItemPedido'] as $item):
				if($item['produto_id']==$itemnota['produto_id']){
					$itemnota['unidade']=$item['unidade'];
					if(empty($itemnota['valor_unitario']))
						$itemnota['pago']+=$item['pago'];
				}
			endforeach;
			
			if(in_array($itemnota['produto_id'],$produtos_id)){
				$tmp_i=array_search($itemnota['produto_id'], $produtos_id);
				$produtos[$tmp_i]['unidade']=$itemnota['unidade'];
				$produtos[$tmp_i]['quantidade']+=$itemnota['quantidade'];
				$produtos[$tmp_i]['pago']+=$itemnota['pago'];
				if(!empty($itemnota['situacao_tributaria']))
					$produtos[$tmp_i]['situacao_tributaria']=$itemnota['situacao_tributaria'];
			}else{
				if(!empty($itemnota['situacao_tributaria']))
					$produtos[$i]['situacao_tributaria']=$itemnota['situacao_tributaria'];
				$produtos_id[$i]=$itemnota['produto_id'];
				$produtos[$i++]=$itemnota;
				
			}
			$valor+=$item['pago'];
			
		}
	endif;
endforeach;
#pr($produtos);
#exit();
//if($_SERVER['REMOTE_ADDR'] == '186.214.4.178'){
//	Configure::write('debug',2);
//	pr($produtos);
//	exit();
//}
$margem=1.0;

//Ajuste adicionado para impressao utilizando a impressora microline 320 turbo
$ajuste_impressora=($ajustar_impressora)? 0.18 : 0;

$pdf->SetFontSize(9);
//$pdf->SetXY($margem, 1.8);	// REtirado pela mudanca do papel
$pdf->SetXY($margem, 1.35-$ajuste_impressora);
//Numero da Fatura
$pdf->SetX($pdf->GetX()+15.8);
#$pdf->Cell(3.45,1.25,$nota['Nota']['numero'],$border,1,'R');
$pdf->Cell(3.45,1.25,'',$border,1,'R');
$x=$pdf->getX();
$y=$pdf->getY();
//$pdf->SetXY(11.6,2.8);
//$pdf->SetXY(11.4,3.0);	// REtirado pela mudanca do papel
$pdf->SetXY(11.5,2.6-$ajuste_impressora);
$pdf->Cell(0.5,0.5,'X',$border,0,'L',0,'',0,0,'T','B');


$pdf->SetXY($x, $y);
//$pdf->Ln(1.1);
$pdf->Ln(1.5);
$pdf->SetX($margem);
//Natureza Operacao
$pdf->Cell(5.2,0.83,$nota['Nota']['natureza_operacao'],$border,0,'',0,'',0,0,'T','B');
//CFOP
#$pdf->Cell(2.1,0.83,($nota['Cliente']['Endereco'][0]['estado_id']==25? 5102:6102),$border,0,'',0,'',0,0,'T','B');
$pdf->Cell(2.1,0.83,$nota['Nota']['cfop'],$border,0,'',0,'',0,0,'T','B');
//IE
//$pdf->Cell(3.19,0.83,$nota['Empresa']['ie'],$border,1,'',0,'',0,0,'T','B');
$pdf->Cell(3.19,0.83,'',$border,1,'',0,'',0,0,'T','B');

//
// DESTINATARIO
//
//$pdf->Ln(0.8);
$pdf->Ln(0.5);
$pdf->SetX($margem);
//Razao Social
$pdf->Cell(9.7,0.75,substr($nota['Cliente']['nome'],0,50),$border,0,'',0,'',0,0,'T','B');
//CNPJ/CPF
$pdf->Cell(6.45,0.75,$nota['Cliente']['cpf_cnpj'],$border,0,'',0,'',0,0,'T','B');
//DIA
$pdf->Cell(1,0.75,substr($nota['Nota']['emissao'],0,2),$border,0,'C',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+0.1);
//MES
$pdf->Cell(0.8,0.75,substr($nota['Nota']['emissao'],3,2),$border,0,'C',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+0.1);
//ANO
$pdf->Cell(1,0.75,substr($nota['Nota']['emissao'],6,4),$border,1,'C',0,'',0,0,'T','B');

$pdf->SetX($margem);
//ENdereco
$endereco = $nota['Cliente']['Endereco'][0]['endereco'].(!empty($nota['Cliente']['Endereco'][0]['complemento'])?' ':'').@$nota['Cliente']['Endereco'][0]['complemento'];
$pdf->Cell(9.7,0.7,substr($endereco,0,50),$border,0,'',0,'',0,0,'T','B');
//Bairro
$pdf->Cell(3.25,0.7,substr($nota['Cliente']['Endereco'][0]['bairro'],0,17),$border,0,'',0,'',0,0,'T','B');
//CEP
$pdf->Cell(3.2,0.7,$nota['Cliente']['Endereco'][0]['cep'],$border,0,'',0,'',0,0,'T','B');
//DIA
$pdf->Cell(1,0.7,'',$border,0,'C',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+0.1);
//MES
$pdf->Cell(1,0.7,'',$border,0,'C',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+0.1);
//ANO
$pdf->Cell(1,0.7,'',$border,1,'C',0,'',0,0,'T','B');

$pdf->SetX($margem);
//Municipio
$pdf->Cell(4.2,0.7,  substr($nota['Cliente']['Endereco'][0]['Cidade']['nome'],0,21),$border,0,'',0,'',0,0,'T','B');
//FoneFax
$pdf->Cell(3.85,0.7,'',$border,0,'',0,'',0,0,'T','B');
//UF
$pdf->Cell(1.65,0.7,$nota['Cliente']['Endereco'][0]['Estado']['nome'],$border,0,'',0,'',0,0,'T','B');
//IE
$pdf->Cell(6.45,0.7,$nota['Cliente']['rg_ie'],$border,0,'',0,'',0,0,'T','B');
//Hora Saida
$pdf->Cell(3.2,0.7,'',$border,1,'C',0,'',0,0,'T','B');


$pdf->Ln(0.35);
//
//FATURA
//
$pdf->Ln(0.04);
$pdf->SetX($margem);
//Numero
$pdf->Cell(2.1,0.76,'',$border,0,'',0,'',0,0,'T','B');
//Vencimento
$pdf->Cell(2.5,0.76,'',$border,0,'',0,'',0,0,'T','B');
//Valor
$pdf->Cell(3.5,0.76,'',$border,0,'',0,'',0,0,'T','B');
//N??
$pdf->Cell(2.19,0.76,'',$border,0,'',0,'',0,0,'T','B');
//Vencimento
$pdf->Cell(2.42,0.76,'',$border,0,'',0,'',0,0,'T','B');
//Valor
$pdf->Cell(3.45,0.76,'',$border,0,'',0,'',0,0,'T','B');
//Cond. Pag.
$pdf->Cell(3.35,0.76,'',$border,1,'',0,'',0,0,'T','B');

$pdf->SetX($margem);
$pdf->SetX($pdf->GetX()+1.7);
//Valor por extenso
$pdf->Cell(17.7,0.75,'',$border,1,'',0,'',0,0,'T','B');
#$pdf->Cell(17.7,0.75,  valorPorExtenso($valor),$border,1,'',0,'',0,0,'T','B');

//
// DADOS DOS PRODUTOS
//
$pdf->SetFontSize(8);
$pdf->Ln(0.5);
$pdf->Ln(0.9);
$pdf->SetX($margem);
$pdf->Cell(0,8.6,'',$border);

$total_produtos=0;

$colunas=array(
	0.9,
	7.9,
	0.85,
	0.85,
	0.85,
	1.6,
	//2.09,
	1.9,
	//2.1,
	2.0,
	0.6,
	0.6,
	1.05
);
$altura_linha = 0.717;
//$border=1;

for($c=0;$c<12;$c++):
	$pdf->SetX($margem);
	$ci=0;
	if(!empty($produtos[$c])){
		$pdf->Cell($colunas[$ci++],$altura_linha,$c+1,$border,0,'',0,'',0,0,'T','B');
		//DEscricao do produto
		$pdf->Cell($colunas[$ci++],$altura_linha,$produtos[$c]['Produto']['nome'],$border,0,'',0,'',0,0,'T','B');
		// Classificacal Fiscal
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Situacao Tributaria
		$pdf->Cell($colunas[$ci++],$altura_linha,$produtos[$c]['situacao_tributaria'],$border,0,'',0,'',0,0,'T','B');
		// UNidade
		$pdf->Cell($colunas[$ci++],$altura_linha, $unidades[$produtos[$c]['unidade']],$border,0,'',0,'',0,0,'T','B');
		//Quantidade
		$pdf->Cell($colunas[$ci++],$altura_linha,  number_format($produtos[$c]['quantidade'],3,',','.'),$border,0,'',0,'',0,0,'T','B');
		//Valor UNitario
		$pdf->Cell($colunas[$ci++],$altura_linha,moedaBr($produtos[$c]['pago']/$produtos[$c]['quantidade'],false,' '),$border,0,'C',0,'',0,0,'T','B');
		//Valor Total
		$pdf->Cell($colunas[$ci++],$altura_linha,moedaBr(round($produtos[$c]['pago'], 2),false,' '),$border,0,'R',0,'',0,0,'T','B');
		$total_produtos+=moedaBD(moedaBr(round($produtos[$c]['pago'], 2),false,' '));
		//ICMS
		//Empresa joao nao desconta ICMS
		if($nota['Nota']['imposto']==1)
			$imposto=$nota['Empresa']['id']!=2?'12%':'';
		else
			$imposto='';
		$pdf->Cell($colunas[$ci++],$altura_linha,$imposto,$border,0,'L',0,'',0,0,'T','B');
		//IPI
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Valor IPI
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,1,'',0,'',0,0,'T','B');
	}else{
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//DEscricao do produto
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		// Classificacal Fiscal
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Situacao Tributaria
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		// UNidade
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Quantidade
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Valor UNitario
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Valor Total
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//ICMS
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//IPI
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,0,'',0,'',0,0,'T','B');
		//Valor IPI
		$pdf->Cell($colunas[$ci++],$altura_linha,'',$border,1,'',0,'',0,0,'T','B');
	}
endfor;
$x=$pdf->GetX();
$y=$pdf->GetY();

$pdf->setXY($margem+$colunas[0],$y-($altura_linha*4));
$pdf->writeHTMLCell($colunas[1], $altura_linha*4, $pdf->GetX(), $pdf->getY(), $nota['Nota']['observacao']);

$pdf->setXY($x,$y);
//
// CALCULO DO IMPOSTO
//
$pdf->SetFontSize(9);
$pdf->Ln(0.4);
//Linha a mais adicionada pelo mal posicionamento na impressora 17/09/2014
$pdf->Ln(0.2);
$pdf->SetX($margem);
//Base ICMS
if($nota['Nota']['imposto']==1){
	if(!empty($nota['Nota']['base_calculo_icms']) && $nota['Nota']['base_calculo_icms']>0){
		$base = $nota['Nota']['base_calculo_icms'];
	}else{
		$base=$total_produtos;
	}
	$pdf->Cell(3.85,0.73,($nota['Empresa']['id']!=2? moedaBr($base,1,' '):''),$border,0,'C',0,'',0,0,'T','B');
}else $pdf->Cell(3.85,0.73,'',$border,0,'C',0,'',0,0,'T','B');
//Valor ICMS
if($nota['Nota']['imposto']==1){
	
	$base_temp = number_format($base*0.12,4,'.','');
	
	$icm = substr($base_temp,  strlen($base_temp)-2)*1;
	if($icm >= 50 && $icm < 60){
		$base_temp-=0.0010;
	}	
	$base_temp=round($base_temp,2);
	//pr($base_temp);exit();
	$icms_total=number_format($base_temp,4,',','.');
	
	$pdf->Cell(3.9,0.73,($nota['Empresa']['id']!=2? substr($icms_total,0,  strlen($icms_total)-2):''),$border,0,'C',0,'',0,0,'T','B');
}else $pdf->Cell(3.9,0.73,'',$border,0,'C',0,'',0,0,'T','B');
//Base ICMS Subs
$pdf->Cell(3.9,0.73,'',$border,0,'',0,'',0,0,'T','B');
//Valor ICMS Subs
$pdf->Cell(3.88,0.73,'',$border,0,'',0,'',0,0,'T','B');
//Valor Total dos Produtos
$pdf->Cell(3.9,0.73,moedaBr($total_produtos,1,' '),$border,1,'C',0,'',0,0,'T','B');
$pdf->SetX($margem);
//Valor Frete
$pdf->Cell(3.85,0.73,'',$border,0,'',0,'',0,0,'T','B');
// Valor Seguro
$pdf->Cell(3.9,0.73,'',$border,0,'',0,'',0,0,'T','B');
// Outras Despesas
$pdf->Cell(3.9,0.73,'',$border,0,'',0,'',0,0,'T','B');
//Valor IPI
$pdf->Cell(3.88,0.73,'',$border,0,'',0,'',0,0,'T','B');
//Valor total nota
$pdf->Cell(3.9,0.73,  moedaBr($total_produtos,true,' '),$border,1,'C',0,'',0,0,'T','B');
//
// TRANSPORTADOR
//
$pdf->Ln(0.5);
$pdf->SetX($margem);
//Razao Social
$pdf->Cell(8.8,0.7,'O MESMO',$border,0,'',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+2.1);
//Frete Conta 1-Emitente, 2- Destinatario
$pdf->Cell(0.7,0.7,'1',$border,0,'C',0,'',0,0,'T','B');
$pdf->SetX($pdf->GetX()+0.2);
//PLaca VEiculo
$pdf->Cell(2.8,0.7,'',$border,0,'',0,'',0,0,'T','B');
//UF
$pdf->Cell(0.95,0.7,'SP',$border,0,'',0,'',0,0,'T','B');
//CNPJ
$pdf->Cell(3.85,0.7,'',$border,1,'',0,'',0,0,'T','B');
$pdf->SetX($margem);
// ENdereco
$pdf->Cell(8.8,0.7,'ACIMA',$border,0,'',0,'',0,0,'T','B');
//Municipio
$pdf->Cell(5.8,0.7,'',$border,0,'C',0,'',0,0,'T','M');
//UF
$pdf->Cell(0.95,0.7,'',$border,0,'',0,'',0,0,'T','B');
//IE
$pdf->Cell(3.85,0.7,'',$border,1,'',0,'',0,0,'T','B');
$pdf->SetX($margem);
// Quantidade
$pdf->Cell(3.2,0.7,'',$border,0,'',0,'',0,0,'T','B');
//Especie
$pdf->Cell(3.25,0.7,'',$border,0,'C',0,'',0,0,'T','B');
//Marca
$pdf->Cell(3.25,0.7,'',$border,0,'',0,'',0,0,'T','B');
//Numero
$pdf->Cell(3.30,0.7,'',$border,0,'',0,'',0,0,'T','B');
//Peso Bruto
$pdf->Cell(3.25,0.7,'',$border,0,'',0,'',0,0,'T','B');
//Peso Liquido
$pdf->Cell(3.15,0.7,'',$border,1,'',0,'',0,0,'T','B');

//
// DADOS ADICIONAIS
//
$pdf->Ln(0.4);
$pdf->Ln(0.55);
$pdf->SetX($margem);
$pdf->SetX($pdf->GetX()+1.5);

//Numero
$pdf->Cell(3,0.85,$nota['Nota']['fatura_ordem1'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.85,$nota['Nota']['fatura_ordem2'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.85,$nota['Nota']['fatura_ordem3'],$border,1,'C',0,'',0,0,'T','B');
//Vencimento
$pdf->SetX($margem);
$pdf->SetX($pdf->GetX()+1.5);
$pdf->Cell(3,0.85,$nota['Nota']['fatura_vencimento1'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.85,$nota['Nota']['fatura_vencimento2'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.85,$nota['Nota']['fatura_vencimento3'],$border,1,'C',0,'',0,0,'T','B');
//Valor
//$pdf->SetY($pdf->GetY()-0.5);
$pdf->SetX($margem);
$pdf->SetX($pdf->GetX()+1.5);
$pdf->Cell(3,0.82,$nota['Nota']['fatura_valor1'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.82,$nota['Nota']['fatura_valor2'],$border,0,'C',0,'',0,0,'T','B');
$pdf->Cell(3,0.82,$nota['Nota']['fatura_valor3'],$border,1,'C',0,'',0,0,'T','B');
//Dados Adicionais
$pdf->SetX($margem);
//$pdf->SetY($pdf->GetY()-0.5);
$pdf->SetFontSize(5.7);
$pdf->Cell(10.5,0.75,$nota['Nota']['dados_adicionais'],$border,1,'L',0,'',0,0,'T','M');

//Dados dos Produtos
ob_clean();
ob_end_clean();
ob_start();

$nome = ($ajustar_impressora ? 'micro320-' :'micro321-' ).$nota['Nota']['id'] . date('ymdhi') . '.pdf';

#echo $pdf->Output($nome, 'I' );
echo $pdf->Output($nome, (($nota['Cliente']['nome'] != 'HOOMWEB')?'D':'I') );
#echo $pdf->Output($nome, ( ($_SERVER['REMOTE_ADDR']!='186.214.4.178') ?'D':'I') );


ob_end_flush();

?>