$(document).ready(function(){
	$('#NotaEmissao,#NotaVencimento,.data,#dateStart,#dateEnd,#NotaFaturaVencimento1,#NotaFaturaVencimento2,#NotaFaturaVencimento3').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	$('#NotaBaseCalculoIcms').priceFormat({
		prefix: "",
		centsSeparator: ".",
		thousandsSeparator: ""
	});	
	$('.price').priceFormat({
		prefix: "",
		centsSeparator: ".",
		thousandsSeparator: ""
	});				
	// Busca o pedido, pelo ID
	$('#frmBuscarPedido').submit(function(e){
                e.preventDefault();
		var pedido_id = $('#pedido_id').val();
		$('#itens_pedido').fadeIn('fast').html('<p style="text-align: center;">Carregando...</p>');
		$.post(APP +'/'+$('#role').val()+'/item_pedidos/by_pedido/1',{ 
                    pedido_id: pedido_id 
                },function(data){
                        if (data) {
                                $('#itens_pedido').html(data);
                                $('#DevolucaoPedidoId').val($('#pedido_id').val());										
                        }else if (!data) $('#itens_pedido').html('<p style="text-align: center; color: red;">Desculpe, não foram encontrados itens do pedido informado, veja se os mesmos já foram devolvidos!</p>');
                },'html');                    
	});
	
	// Bloqueando o Enter no campo Cliente
	$('#NotaCliente').keypress(function(e){
		if ( e.which == 13 ) {
			return false;
		}
	}).simpleAutoComplete(APP +'/'+$('#role').val()+'/clientes/all', {}, callbackCliente);

	$("#dateStart").hide();
	$("#dateEnd").hide();


	$("#dateEnd").blur(function(e){
		carregarPedidos();
	});
	$("#dateStart").blur(function(e){
		carregarPedidos();
	});

	function callbackCliente( par ) { 
		$('#NotaClienteId').val(par[0]);
		$("#dateStart").show();
	    $("#dateEnd").show();
		carregarPedidos();
	}
	
	function carregarPedidos(){
		if(!isNaN($("#NotaClienteId").val()) && $("#NotaClienteId").val()>0 ){
			$.post(APP +'/'+$('#role').val()+'/item_notas/by_cliente/'+$("#NotaClienteId").val()+'?date_start='+$("#dateStart").val()+'&date_end='+$("#dateEnd").val(),{
				selected:$('#NotaClienteId').val() 
			},function(data){
				$('#boxPedidos').html(data);
				$('.quantidade').priceFormat({
					prefix: "",
					centsLimit: 3,
					centsSeparator: ".",
					thousandsSeparator: ""
				});				
				$('.valor_unitario').priceFormat({
					prefix: "",
					centsSeparator: ".",
					thousandsSeparator: ""
				});				
			});
			$('#boxPedidos').show();
		}
		
	}
	function VerificaTabela(){
		if($('#boxPedidos table').find("tr").size() == 1){
			$('#boxPedidos').html('<p>Não há mais pedidos!</p>');
		}
	}
	
	$('.btn-pedido-add').die('click').live('click',function(e){
		//alert(1);
		var id= $(this).attr('rel'),
			cliente_id=$('#boxPedidos tr.'+id+' .cliente').html(),
			obra=$('#boxPedidos tr.'+id+' .obra').html(),
			data_entrega=$('#boxPedidos tr.'+id+' .data_entrega').html(),
			len = $('#boxPedidos tr.'+id+' .material').length;
			
		$('#boxPedidos tr.'+id+' .material').each(function(i,element){
			$.post(APP +'/'+$('#role').val()+'/item_notas/add/',{
				pedido_id: id ,
				data_entrega: data_entrega,
				cliente:cliente_id,
				obra:obra,
				//material:$('#boxPedidos tr.'+id+' .material').html(),
				produto_id: $('#boxPedidos tr.'+id+' .material:eq('+i+')').val(),
				
				material: $('#boxPedidos tr.'+id+' .material:eq('+i+') option:checked').text(),
				//quantidade:$('#boxPedidos tr.'+id+' .quantidade').htm(),
				quantidade:$('#boxPedidos tr.'+id+' .quantidade:eq('+i+')').val(),
				unidade: $('#boxPedidos tr.'+id+' .unidade:eq('+i+')').val(),
				valor_unitario:$('#boxPedidos tr.'+id+' .valor_unitario:eq('+i+')').val(),
				situacao_tributaria:$('#boxPedidos tr.'+id+' .situacao_tributaria:eq('+i+')').val(),
			},function(data){
				if(i == len-1){
					$('#tbPedidos').load(APP +'/'+$('#role').val()+'/item_notas/tbpedidos');
					$('#boxPedidos tr.'+ id).fadeOut(function(){
						$(this).remove();
						VerificaTabela();
					});
				}
			});
		});
	})
	$('.remover').die('click').live('click', function(e){
		e.preventDefault();	
		if (confirm('Deseja realmente remover este item?')) {
			var id = $(this).parents('tr').attr('class');
			$.post(APP +'/'+$('#role').val()+'/item_notas/del',{ 
				indice: id 
			},function(data){
				$('#tbPedidos .'+ id).remove();
			},'html');
		}
	});
	$('.desabilitar').die('click').live('click', function(e){
		e.preventDefault();	
		
			var id = $(this).parents('tr').attr('class');
			$.post(APP +'/'+$('#role').val()+'/item_notas/desabilitar',{ 
				indice: id 
			},function(data){
				$('#tbPedidos .'+ id+' .desabilitar').hide();
				$('#tbPedidos .'+ id+' .habilitar').fadeIn();				
			},'html');
		
	});
	$('.habilitar').die('click').live('click', function(e){
		e.preventDefault();	
		//if (confirm('Deseja realmente remover este item?')) {
			var id = $(this).parents('tr').attr('class');
			$.post(APP +'/'+$('#role').val()+'/item_notas/habilitar',{ 
				indice: id 
			},function(data){
				$('#tbPedidos .'+ id+' .habilitar').hide();
				$('#tbPedidos .'+ id+' .desabilitar').fadeIn();
			},'html');
		//}
	});
	carregarPedidos();
});