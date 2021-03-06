$(document).ready(function(){
	checarTipoAlteracao();
	var total  = 0;
	var vales = [];
	$('#ContaValor,#ContaPago').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: "."
	});
	$('#ContaDataVencimento').mask('99/99/9999')
		   .datepicker(DATE_PICKER_CONFIG);

	$('#ContaTipo').change(function(){
		$('#ContaTipoContaId').html('<option>Carregando...</option>');
		$.post(APP+'/'+$('#role').val()+'/tipos_contas/by_tipo?tipo='+$(this).val(),{
		},function(data){
			$('#ContaTipoContaId').html(data);
		});
	});
//	$('#ContaTipoContaId').change(function(){
//		$('#ContaSubtipoContaId').html('<option>Carregando...</option>');
//		$.post(APP+'/'+$('#role').val()+'/sub_tipos_contas/by_tipo?tipo='+$(this).val(),{
//		},function(data){
//			$('#ContaSubtipoContaId').html(data);
//		});
//	});
	
	// $('#vincular_vale,.pagto_fornecedor').slideDown(400);
	// $('.pagto_fornecedor select').prop('disabled',false);

	// $('#ContaTipoContaId').change(function(){
	// 	if( $(this).val() == 1 ){
	// 		$('#vincular_vale,.pagto_fornecedor').slideDown(400);
	// 		$('.pagto_fornecedor select').prop('disabled',false);
	// 	}else{
	// 		$('#vincular_vale,.pagto_fornecedor').slideUp(400);
	// 		$('#boxVales').html('');
	// 		$('.pagto_fornecedor select').prop('disabled',true);
	// 	}
	// });
	
	$('.alterar').click(function(){ checarTipoAlteracao(); });
	
	function checarTipoAlteracao(){
		var tipo = $('.alterar:checked').val(),
			campos = ['ContaParcela', 'ContaParcelas'],
			i,
			totalCampos = campos.length;
				
		if (tipo == 'uma') {
			for (i = 0; i < totalCampos; i++) {
				$('#'+campos[i]).attr('disabled', true);
			}
		} else if (tipo == 'todas') {
			for (i = 0; i < totalCampos; i++) {
				$('#'+campos[i]).removeAttr('disabled');
			}
		}
	}
	//
	//=============== INTERVALO ENTRE DATAS
	//
	function IntervaloEntreParcelas(){
		var parcela = $('#ContaParcela').val(),
			parcelas = $('#ContaParcelas').val();
		//alert(parcela+' '+parcelas);
		if(parcela<parcelas){
			$('.boxIntervaloParcelas').html('<p class="lead">Vencimento das pr??ximas parcelas: </p>');
			if(parcela > 0){
				var valor,vlparcela;
				valor = parseFloat($('#ContaValor').val().replace('.','').replace(',','.'));
				if(valor>0)
					//vlparcela = new Number(valor/parcelas).toFixed(2).toString().replace(',','&').replace('.',',').replace('&','.');
					vlparcela = new Number(valor).toFixed(2).toString().replace(',','&').replace('.',',').replace('&','.');
				for(i=parcela-1; i<(parcelas-1); i++){
					$('.boxIntervaloParcelas').append(
						'<div class="input number pull-left">'
							+'<label for="IntervaloParcela' + (i) + '">Parcela ' + (1*i+2) + '</label>'
							+'<input id="IntervaloParcela' + (i) + '" class="span2 intervalos" type="text" name="data[IntervaloParcela][intervalo][' + (i) + ']">'
						+'</div>'
						+'<div class="input number">'
							+'<label for="IntervaloParcelaValor' + (i) + '">Valor</label>'
							+'<input id="IntervaloParcelaValor' + (i) + '" class="span2 money" type="text" name="data[IntervaloParcela][valor][' + (i) + ']" value="'+vlparcela+'">'
						+'</div>'
					);
				}//foreach
			}//if
			AtualizaIntervalo();
		$('.boxIntervaloParcelas').fadeIn();
		}else if(parcela == parcelas){
			$('.boxIntervaloParcelas').html('');
			$('.boxIntervaloParcelas').hide();
			$('#ContaIntervalos').val('');
		}
	}
	
	function AtualizaIntervalo(){
		//		var aux='';
		//		$('.intervalos').each(function(i){
		//			if( i > 0 )
		//				aux+='|';
		//			aux += $('.intervalos:eq('+i+')').val();			
		//		});
		//		$('#ContaIntervalos').val(aux);
		//	}
		//	$('.intervalos').live('change',function(){
		//		AtualizaIntervalo();
		//	});
		$('.intervalos').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
		$('.money').priceFormat({
			prefix: "",
			centsSeparator: ",",
			thousandsSeparator: "."
		});
	}	
	
	
	$('#ContaParcela').change(function(){
		IntervaloEntreParcelas();
	});
	
	$('#ContaParcelas').change(function(){
		IntervaloEntreParcelas();
	});
	//
	//=============== FIM INTERVALO ENTRE DATAS
	//
	
	//
	//====================================== VINCULAR PEDIDOS
	//
	// Bloqueando o Enter no campo Cliente
	//	$('#Fornecedor').keypress(function(e){
	//		if ( e.which == 13 ) {
	//			return false;
	//		}
	//	}).simpleAutoComplete(APP +'/'+$('#role').val()+'/fornecedores/all', {}, callbackCliente);
	//	function callbackCliente( par ) { 
	//		$('#FornecedorId').val(par[0]);
	//		carregarPedidos();
	//	}


	 
	// $('#ContaFornecedorId').change(function(){
	// 	carregarPedidos();
	// });
		
	function carregarPedidos(){


		if(!isNaN($("#ContaFornecedorId").val()) && $("#ContaFornecedorId").val()>0 ){
			$('#boxVales').html('<div style="text-align: center;"><img src="'+APP+'/img/icones/ajax-loader.gif"> Carregando ...</div>').slideDown(400);
		
			$.post(APP +'/'+$('#role').val()+'/vales/by_fornecedor/'+$("#ContaFornecedorId").val(),{
				conta:1,
				selected:$('#ForenecedorId').val() 
			},function(data){
				$('#boxVales').html(data);
			});
			//$('#boxPedidos').show();
		}
	}


	function carregarPedidosData(id,inicio,fim,nota_fiscal){

			
		//if(!isNaN($("#ContaFornecedorId").val()) && $("#ContaFornecedorId").val()>0 ){
			$('#boxVales').html('<div style="text-align: center;"><img src="'+APP+'/img/icones/ajax-loader.gif"> Carregando ...</div>').slideDown(400);
		
			$.post(APP +'/'+$('#role').val()+'/vales/by_fornecedor/'+id+'/?inicio='+inicio+'&fim='+fim+'&nota_fiscal='+nota_fiscal+'',{
				conta:1,
				selected:$('#ForenecedorId').val() 
			},function(data){
				$('#boxVales').html(data);
			});
			//$('#boxPedidos').show();
		//}
	}

	function insertAllVales(id,inicio,fim){
		$.post(APP +'/'+$('#role').val()+'/conta_vales/add_all/',{fornecedor: id,inicio: inicio,fim: fim},function(data){
			//console.log(data.error);

			var obj = JSON.parse(data);	

    		console.log("Somatorias", obj.valor);


			total += obj.valor;
			//contaValorTotalPlus(total);


			$('#tbVales').load(APP +'/'+$('#role').val()+'/item_notas/tbvales');

			$('#boxVales tr').fadeOut(function(){
				$(this).remove();
			});	
			
		});
		
	}


	$('#btnBuscarData').die('click').live('click',function(e){
		e.preventDefault();	
		var id = $("#ContaFornecedorId").val();
		var inicio = $('#inicio').val();
		var fim = $('#fim').val();
		var nota_fiscal = $('#nota_fiscal').val();

		inicio = convertData(inicio);
		fim = convertData(fim);
		
		carregarPedidosData(id,inicio,fim,nota_fiscal);
	});


	$('#addAllVales').die('click').live('click',function(e){
		e.preventDefault();	
		var id = $("#ContaFornecedorId").val();
		var inicio = $('#inicio').val();
		var fim = $('#fim').val();

		inicio = convertData(inicio);
		fim = convertData(fim);
		
		insertAllVales(id,inicio,fim);
	});
	

	$('.btn-vale-add').die('click').live('click',function(e){
		var id= $(this).attr('rel');
		var valor = $(this).data('valor');

		$.post(APP +'/'+$('#role').val()+'/conta_vales/add/',{
			vale_id: id ,
			pedido:$('#boxVales tr.'+id+' .pedido').html(),
			nota_fiscal:$('#boxVales tr.'+id+' .nota').html(),
			nota_fiscal_emissao:$('#boxVales tr.'+id+' .nota_fiscal_emissao').html(),
			quantidade:$('#boxVales tr.'+id+' .quantidade').html(),
			valor_total:$('#boxVales tr.'+id+' .valor_total').html(),
			cliente:$('#boxVales tr.'+id+' .cliente').html(),
			obra:$('#boxVales tr.'+id+' .obra').html()
		},function(data){
			$('#tbVales').load(APP +'/'+$('#role').val()+'/item_notas/tbvales');
			$('#boxVales tr.'+ id).fadeOut(function(){$(this).remove();
			//	VerificaTabela();
			});			
		});
		//alert(valor);
		total = parseFloat(total);

		valor = parseFloat(valor);

		total += valor;
		console.log(valor);
		//contaValorTotalPlus(total);

		vales[id]={value:id};

	});
	$('.remover').die('click').live('click', function(e){
		e.preventDefault();	
		var valor = $(this).data('valor');
		if (confirm('Deseja realmente remover este item?')) {
			var id = $(this).parents('tr').attr('class');
			$.post(APP +'/'+$('#role').val()+'/conta_vales/del',{ 
				indice: id 
			},function(data){
				$('#tbVales .'+ id).remove();
				$('#tbVales').load(APP +'/'+$('#role').val()+'/item_notas/tbvales');
				
				
			},'html');
		}
		//alert(valor);

		total = parseFloat(total);

		valor = parseFloat(valor);

		total -= valor;

		console.log(valor);
		//contaValorTotalMinus(total);
	});
	//
	//====================================== FIM VINCULAR PEDIDOS
	//
	function contaValorTotalPlus(total){
		total = total.toFixed(2);
		total = total.replace('.',',');
		
		$('#ContaValor').val(total);
	}

	function contaValorTotalMinus(total){
		total = total.toFixed(2);
		
		total = total.replace('.',',');

		$('#ContaValor').val(total);

	}
	AtualizaIntervalo();



	function convertData(data){
		
	    data = data.replace('/','');
	    data = data.replace('/','');
	 	var dia = data.substring(0,2);
		var mes = data.substring(2,4);
		var ano = data.substring(4,8);

		var res = ano+'-'+mes+'-'+dia;

		return res;
	}

	// $('#btnImprimir').die('click').live('click',function(e){
	// 	alert('Teste');
	// 	imprimir();
	// });


	
});

function totalReal(){
	var conta_valor = $('#totalCorreto').val();
	
	total = parseFloat(conta_valor).toFixed(2);
	total = total.replace('.',',');

	
	$('#ContaValor').val(total);
}