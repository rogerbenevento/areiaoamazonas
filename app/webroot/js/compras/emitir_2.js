$(document).ready(function(){
	$('#pagamento_data').mask('99/99/9999')
		.datepicker(DATE_PICKER_CONFIG);
	
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
	
	// Formata o campo valor de pagamento
	$('#valor,#pagamento_valor').priceFormat({
	    prefix: '',
	    centsSeparator: ',',
	    thousandsSeparator: '.'
	});
	
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
	
	
	
	/*=========================================== CONTROLE DE FORMULARIO ================================================*/
	submit = false;
	
	$('#CompraEmitirForm').submit(function(e){
		if(!submit){
			e.preventDefault();
			var valorTotal = toFloat($('.valorTotal').text()), 
			valorPagtos = totalPagamentos(),
			valorRestante = valorPagtos-valorTotal;
			//alert(valorTotal+" "+valorPagtos+" "+valorRestante);
			if ( valorRestante < 0 ) {
				alert("Valor do Pagamento é menor do que o Valor da COmpra!");
				return false;
			}
			if ( valorRestante > 0 ) {
				alert("Valor do Pagamento é maior do que o Valor da Compra!");
				return false;
			}
			if ( $('#CompraFornecedorId').val()=='' || $('#CompraFornecedorId').val()==null ) {
				alert("Selecione um Fornecedor!");
				$('#CompraFornecedorId').focus();
				return false;
			} 
			submit=true;
			$(this).submit();    
		}
	})
	
	/*=========================================== CONTROLE DE PAGAMENTOS ================================================*/
	
	/** Chama o box para adicionar uma nova forma de pagamento **/
	$('.addFormaPagto').click(function(){
		var valorTotal = toFloat($('.valorTotal').text()), 
			valorPagtos = totalPagamentos(),
			valorRestante = valorTotal - valorPagtos;
		
		//alert('Valor Total pedido: '+ valorTotal +'\nValor total de pagamentos: '+ valorPagtos);
		if (valorTotal > valorPagtos) {
			$('.addFormaPagtoBox').fadeIn('fast');
			$('#pagamento_valor').val(brFloat(valorRestante));
			$('#pagamento_descricao').focus();
		} else alert('O valor da compra já está diluido nas formas de pagamento.');
	});
	
		
	/** Adiciona forma de pagamento a sessão de pagamentos do carrinho **/
	$('.novaFormaPagto').click(function(){
		var valor = toFloat($('#pagamento_valor').val()),
			descricao = $('#pagamento_descricao').val(),
			pgdate = $('#pagamento_data').val();
		
		$.post(
			APP+'/admin/pagamentos_compras/add',{
				valor: valor, 
				descricao: descricao,
				date: pgdate
			},function(response){
				$('.pagamentos tbody').append(response);
				$('.addFormaPagtoBox').fadeOut('fast');
				$('#pagamento_valor').val('');
				$('#pagamento_descricao').val('');
				$('#pagamento_data').val('');
			},'html');
	});
	
	/** Remove uma forma de pagamento da sessão de pagamentos do carrinho **/
	$('.removerPagto').live('click', function(e){
		e.preventDefault();
		
		var id = $(this).parents('tr').attr('class');
		if (confirm('Deseja realmente remover?')) {
			var indice = $(this).parents('tr').attr('class');
			$.get(APP+'/admin/pagamentos_compras/del',{
				indice: indice
			},function(data){ 
				$('.pagamentos tbody').find('.'+indice).remove();
			},'html');
		}
	});
	
	/*=================================================== FUNÇÕES ====================================================*/
	
	//Limpar forma de pagamento
	function removerPagto() {
		var indice = 0;
		$.get(
			APP+'/admin/pagamentos_pedidos/del',
			{indice: indice},
			function(data){ 
				$('.pagamentos tbody').find('.'+indice).remove();
			},
			'html'
		);
	}
	
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
	//Tranforma a moeda para o formado americano
	function moedaAmericana(moeda) {
		moeda = moeda.replace(".","");
		moeda = moeda.replace(",",".");
		return parseFloat(moeda);
	}
	
	// Calcula o valor total já cadastrado nos pagamentos
	function totalPagamentos() {
		var valorPagtos = 0,
			valorLinha = 0;
		
		// Calcula os valores de pagamento
		$('.pagamentos tbody tr').each(function(i){
			var valorLinha = $(this).find('.valorPagto').text();
			valorLinha = toFloat(valorLinha);
			valorPagtos += valorLinha;
		});
		
		return valorPagtos;
	}
	
	// Formata um valor float(com , nas casas decimais) e devolve um valor Float nativo do JavaScript
	function toFloat(valor) {
		return parseFloat(valor.replace('R$ ', '').replace('.', '').replace(',', '.'));
	}
	
	// Formata um valor float no padrão brasileiro
	function brFloat(valor) {
		return number_format(valor, 2, ',', '.');
	}
});