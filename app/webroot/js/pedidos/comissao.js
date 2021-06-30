$(function(){
    $('#PedidoLojaId').change(function(){
		var loja_id = $(this).val();
		var vendedores = $('#PedidoUserId');
		
		vendedores.html('<option value="">Carregando...</option>');
		$.post(APP +'/'+$('#role').val()+'/users/by_user',{ 
                    loja_id: loja_id 
                },function(data){
			vendedores.html(data);
		},'html');
	});
})