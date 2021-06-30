$(function(){
    $( "#ProdutoInicio,#ProdutoFim" ).mask('99/99/9999')
    .datepicker({ dateFormat: "dd/mm/yy" });
    
    $('#ProdutoCategoriaId').change(function(){
            var categoria_id = $(this).val();
            var subcategorias = $('#ProdutoSubcategoriaId');

            subcategorias.html('<option value="">Carregando...</option>');
            $.get(APP +'/subcategorias/by_categoria',{ 
                categoria_id: categoria_id 
            },function(data){
                    subcategorias.html(data);
            },'html');
    });
})
