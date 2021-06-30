$(document).ready(function(){
       
        // Bloqueando o Enter no campo Material
	$('#material').keypress(function(e){
		if ( e.which == 13 ) {
			return false;
		}
	});
	
	// Faz a sugestão de materiais no campo onde o usuário irá adicionar os itens, com suas 
	// quantidades e valor
	$("#material").simpleAutoComplete(APP +'/admin/materiais/all', {}, callbackProduto);
	function callbackProduto( par ) { 
		$("#material_id").val( par[0] );
		$('.unidadeMedida').text('/'+ par[3]);
	}
	
	// Responsável por adicionar novos itens ao carrinho de Compras
	// Excluir itens, e somar ou subtrair quantidade
	
	/* Adição de um novo item ao carrinho de COMPRAS */
	$('.addItem').click(function(){
		var url = APP +'/admin/compras_itens/add',
			material_id = $('#material_id').val(),
			quantidade = $('#quantidade').val(),
			valor = $('#valor').val();
		
		if ( validarCampos() ) {
			$.post(
				url,
				{ material_id: material_id, quantidade: quantidade, valor: valor },
				function(data){
					$('.grid tbody').append(data);
					$('#material_id, #material, #valor').val('');
					$('#quantidade').val('1');
					valorTotal();
				},
				'html'
			);
		} else alert( 'Os campos abaixo são obrigatórios:\nMaterial\nQuantidade\nValor\n' );
	});
	
	/* Exclusão de um item do carrinho de COMPRAS */
	$('.remover').live('click', function(e){
		e.preventDefault();
		var indice, url;
		
		if ( confirm( 'Deseja realmente remover este item?' ) ) {
			indice = $(this).parents('tr').attr('class');
			url = APP +'/admin/compras_itens/del';
			
			$.get(
				url, { indice: indice }, function(data){
					if ( data == true ) {
						$('.'+ indice).remove();
						valorTotal();
					}
				}, 'html'
			);
		}
	});
	
	/*=================================================== FUNÇÕES ====================================================*/
	
	/** faz a validação dos campos que devem ser preenchidos para adicionar um item **/
	function validarCampos() {
		var i, item = ['material_id', 'material', 'quantidade', 'valor'], itens = item.length, campo, error = true;
		
		for (i = 0; i < itens; i++) {
			campo = $('#'+ item[i]).val();
			if (campo == '' || campo == undefined) {
				error = false;
				break;
			}
		}
		return error;
	}
	
	// Calcula o valor total do pedido
	function valorTotal() {
		var valorTotal = 0,
			valor;
		
		$('.grid tbody tr').each(function(i){
			valor = $(this).find('.itemTotal').text();
			valor = valor.replace('R$ ', '').replace('.', '').replace(',', '.');
			valorTotal += parseFloat(valor);
		});
		$('.valorTotal').text('R$ '+ number_format(valorTotal, 2, ',', '.'));
	}
});