$(document).ready(function(){
//	$('#ClienteTelefone').mask('(99)9999-9999');
//	$('#ClienteDataNascimento').mask('99/99/9999');
//	$('#ClienteCep').mask('99999-999');
	
	// Habilita ou desabilida os campos de uma determinado box
	function toggleHabilty(classe, acao){
		acao = ( acao === undefined ) ? true : acao;
		$('.'+ classe).find('input[type=text]').each(function(){
			if ( !acao ) {
				$(this).attr('disabled', true);
				$('.'+ classe).hide('slide');
			} else {
				$(this).removeAttr('disabled');
				$('.'+ classe).show('slide');
			}
		});
	}
	
        $('#ProdutoValorCusto,#ProdutoValor').priceFormat({
            prefix: '',
            centsSeparator: ',',
            thousandsSeparator: '.'});
        
	$('#ProdutoCategoriaId').change(function(){
		var categoria_id = $(this).val();
		var subcategorias = $('#ProdutoSubcategoriaId');
		
		subcategorias.html('<option value="">Carregando...</option>');
		$.get(APP +'/'+$('#role').val()+'/subcategorias/by_categoria',{
                    categoria_id: categoria_id 
                },function(data){
                        subcategorias.html(data);
                },'html');
	});
});