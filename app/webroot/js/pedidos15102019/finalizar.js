$(document).ready(function(){
	var VALOR_FRETEIRO=0.65;
	var VALOR_FRETE=[20,30];
	
	$('#ValeDataEntrega,#ValeNotaFiscalEmissao').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	$('#ItemPedidoPago,#ItemPedidoValorTotal,#ItemPedidoFrete').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: ""
	});
	$('#ItemPedidoQuantidade,#ItemPedidoQuantidadeOriginal').priceFormat({
		prefix: "",
		centsSeparator: ".",
		thousandsSeparator: ""
	});
	
	$('#valor,.frete,#ItemPedidoValorUnitario').priceFormat({
			prefix: "",
			centsSeparator: ",",
			thousandsSeparator: ""
	});
	function VerificaMascaraQtd(){
		if( $('.unidade_original').val()==2 ){
		
			$('#ItemPedidoQuantidadeOriginal').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:3
			});
		}else{
			$('#ItemPedidoQuantidadeOriginal').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:2
			});
		}
		if( $('.unidade').val()==2 ){
			$('#ItemPedidoQuantidade').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:3
			});
		}else{
			$('#ItemPedidoQuantidade').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:2
			});
		}
	}
	$('.unidade_original').change(function(){
		VerificaMascaraQtd();
	});
	$('.unidade').change(function(){
		VerificaMascaraQtd();
	});
	
	$('#ItemPedidoValorTotal,#ItemPedidoValorUnitario,#ItemPedidoQuantidade').keyup(function(){
		var valor_total=($('#ItemPedidoValorUnitario').val().replace('R$ ','').replace('.','').replace(',','.')*1)
					* $('#ItemPedidoQuantidade').val();
		$('#ItemPedidoPago,#ItemPedidoPagoDisplay').val( number_format(valor_total,2,',','.'));
		AtualizarFreteiro(0);
	});
	
	$('#ValeMotoristaId').change(function(){
		if( $.inArray( $(this).val()*1 ,FRETEIROS) > -1){
			freteiro= true;		
			$('#ValePeriodoId').off('change');
			AtualizarFreteiro(0);
		}else{
			freteiro = false;
			$('#ValePeriodoId').on('change',function(){
				AtualizarFrete();
			});
			$('#ItemPedidoFrete').val('');
			AtualizarFrete();
		}
	});
	var atualizar = false;
	var freteiro = false;
	function AtualizarFreteiro(valor){
		if(freteiro){
			if(valor==0){
				valor=($('#ItemPedidoValorUnitario').val().replace('R$ ','').replace('.','').replace(',','.')*1)
					* $('#ItemPedidoQuantidade').val();
			}
			valor -= ($('#ItemPedidoValorTotal').val().replace('R$ ','').replace('.','').replace(',','.')*1);
			var frete= valor*VALOR_FRETEIRO;
			$('#ItemPedidoFrete').val(number_format(frete,2,',','.'));
		}
	}
	function AtualizarFrete(){
		$('#ItemPedidoFrete').val(number_format(VALOR_FRETE[$('#ValePeriodoId').val()-1],2,',','.'));
	}
	VerificaMascaraQtd();

});