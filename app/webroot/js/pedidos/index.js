$(function(){
	//    $('#frmPedido').dialog({
	//            height: 600,
	//            width: 900,
	//            autoOpen:false,
	//            modal: true,
	//            beforeClose: function( event, ui ) {$('#frmPedido').html('');}
	//    });
	$('.frete').priceFormat({
			prefix: "",
			centsSeparator: ",",
			thousandsSeparator: ""
	});
	$('.pedido-print').click(function(e){
		e.preventDefault();
		window.open(APP+'/'+$('#role').val()+'/pedidos/detalhes/'+$(this).attr('id')+"/1",'', 'width=750, height=550, scrollbars=yes');
	})

	$('.pedido-view').click(function(e){
		e.preventDefault();
		$.post(APP+'/'+$('#role').val()+'/pedidos/detalhes/'+$(this).attr('id'),{
		},function(resp){
			$('#frmPedido div.modal-body').html(resp);
			$('#frmPedido').modal('show');
		});		
	});
	$('.pedido-copiar').click(function(e){
		e.preventDefault();
		$('#modalCopiar #PedidoId').val($(this).attr('id'));
		$('#modalCopiar').modal('show');		
		$('#modalCopiar #PedidoQuantidade').focus();
	});
    
	$('.btn-loja').live('click',function(){
		$.post(APP+'/'+$('#role').val()+'/lojas/loja/'+$(this).attr('rel'),{          
			},function(resp){
				$('#frmPedido').html(resp);
			})
	})
    
	$('.cancelarPedido').click(function(e){
		e.preventDefault();
		$('#ModalCancelar div.modal-body').html('Carregando...');
		$('#ModalCancelar div.modal-body').load($(this).attr('href'),function(){
			$('#ModalCancelar').modal('show');
		});		
		//pcancelar($(this).attr('id'));
	});
});

function pcancelar(id) {
	var motivo;
	motivo = prompt ("Qual o motivo do cancelamento?");
	if (motivo != null && motivo != "") {
		$('.content p').append('<div class="msgLoading"><img src="'+ APP +'/img/icones/ajax-loader.gif" alt="Loader" />&nbsp;Cancelando Pedido...</div>');
		$.post(APP +'/'+$('#role').val()+'/pedidos/cancelar/'+id, {
			motivo: motivo
		},function(response){
			location.reload(true);
		},'json');	
	}
	else {
		alert ("Para todos os cancelamentos é necessário informar um motivo!");
	}
}//pcancelar