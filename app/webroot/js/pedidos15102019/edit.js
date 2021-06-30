$(document).ready(function(){
	// Datepicker da Data de Entrega
	$('#PedidoDataEntrega').datepicker(DATE_PICKER_CONFIG);
	
	
	function VerificaMascaraQtd(){	
		var un = $('#unidade').val();
		if (un == undefined){
			un=$('#ItemPedidoUnidade').val();
		}
		if( un==2 ){
			$('#quantiadade,#ItemPedidoQuantidade').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:3
			});
		}else{
			$('#quantidade,#ItemPedidoQuantidade').priceFormat({
				prefix: "",
				centsSeparator: ".",
				thousandsSeparator: "",
				centsLimit:2
			});
		}
	}
	$('#unidade,#ItemPedidoUnidade').change(function(){
		VerificaMascaraQtd();
	})
	//alert(2);
	VerificaMascaraQtd();
	//$('#quantidade,#ItemPedidoQuantidade').priceFormat({
	//		prefix: "",
	//		centsSeparator: ".",
	//		thousandsSeparator: ""
	//});
	$('#valor,.frete,#ItemPedidoPago').priceFormat({
			prefix: "",
			centsSeparator: ",",
			thousandsSeparator: ""
	});
	
	// Bloqueando o Enter no campo Produto
//	$('#produto').keypress(function(e){
//		if ( e.which == 13 ) {
//			return false;
//		}
//	});
//	$("#produto").simpleAutoComplete(APP +'/'+$('#role').val()+'/produtos/all', {}, callbackProduto);
//	function callbackProduto( par ) { 
//		$("#produto_id").val( par[0] );
//	}
	
	// Bloqueando o Enter no campo Material
	$('#PedidoCliente').keypress(function(e){
		if ( e.which == 13 ) {
			return false;
		}
	}).simpleAutoComplete(APP +'/'+$('#role').val()+'/clientes/all', {}, callbackCliente);
	function callbackCliente( par ) { 
		$("#PedidoClienteId").val( par[0] );
		$("#PedidoUserId").val( par[2] );
		$('#ObraId').html("<option value=''>Carregando...</option>");
		$('#ObraId').load(APP +'/'+$('#role').val()+'/obras/options/'+par[0]);
		$('#boxObra').fadeIn();
		$('#SeguroApolice').focus();
	}
	
	function carregaObras(){
		if(!isNaN($("#PedidoClienteId").val()) && $("#PedidoClienteId").val()>0 ){
			$.post(APP +'/'+$('#role').val()+'/obras/options/'+$("#PedidoClienteId").val(),{
				selected:$('#PedidoObraId').val() 
			},function(data){
				$('#ObraId').html(data);
			});
			$('#boxObra').show();
		}
	}
	
	$('#PedidoCliente').change(function(){
		if($(this).val()==''){
			$("#PedidoClienteId").val('');
			$("#PedidoObraId").val('');
			$('#boxObra').slideUp();
		}
	})
	
	$('#ObraId').change(function(){
		$('#PedidoObraId').val($(this).val());
	})	
	
	function AtualizaUnidadeMedida(){
		$('.unidadeMedida').html("/"+$('#unidade option:selected').text());
	}
	$('#unidade').change(function(){
		AtualizaUnidadeMedida();
	})	
	
	$('.btn-add-cliente').click(function(){
		window.location.href=APP +'/'+$('#role').val()+'/clientes/add/'+$('#pedido_id').val();
	});
	
       
	var submit=false;
       
	$('#PedidoEditForm').submit(function(e){
		if(!submit){
			e.preventDefault();
			var valorTotal = toFloat($('.valorTotal').text()), 
			valorPagtos = totalPagamentos(),
			valorRestante = valorPagtos-valorTotal;
			//alert(valorTotal+" "+valorPagtos+" "+valorRestante);
//			if ( valorRestante < 0 ) {
//				alert("Valor do Pagamento é menor do que o Valor do Pedido!");
//				return false;
//			}
//			if ( valorRestante > 0 ) {
//				alert("Valor do Pagamento é maior do que o Valor do Pedido!");
//				return false;
//			}
			if ( $('#PedidoClienteId').val()=='' || $('#PedidoClienteId').val()==null ) {
				alert("Selecione um Cliente!");
				$('#PedidoCliente').focus();
				return false;
			} 
			
			submit=true;
			$(this).submit();    
		}
	});

	$('#frmProduto').submit(function(e){
		e.preventDefault();
		produto_id = $('#produto_id').val();
		produto = $('#produto_id option:selected').text();
		quantidade = $('#quantidade').val();
		unidade = $('#unidade').val();
//		valor = $('#valor').val();
		
		if ( produto_id == undefined || produto_id =='' ) {
			alert("Selecione um Produto!");
			$('#produto').focus();
			return false;
		}
//		if ( quantidade == undefined || quantidade =='' ) {
//			alert("Informe a Quantidade!");
//			$('#quantidade').focus();
//			return false;
//		}
//		if ( unidade == undefined || unidade =='' ) {
//			alert("Informe a Unidade!");
//			$('#unidade').focus();
//			return false;
//		}
//		if ( valor == undefined || valor =='' || !valor >0) {
//			alert("Informe o Valor do Produto!");
//			$('#valor').focus();
//			return false;
//		}
//		
		//return false;
		$.post(APP +"/"+$('#role').val()+'/item_pedidos/add',{ 
			produto_id: produto_id, 
			produto: produto, 
//			valor: valor, 
			quantidade: quantidade,
			unidade: unidade
		},function(data){
			if ( data.error ) {
				alert('Erro ao inserir produto no Pedido!');
			} else {
				$('#tableItensPedidos').load( APP +'/'+$('#role').val()+'/item_pedidos/tbitenspedido');
				$('#produto_id, #produto').val('');
				$('#quantidade').val('');
				$('#produto').focus();
//				$('.boxProduto').hide();
			}
		},'json');
		
	});
	$('#buscar-produto').live('click', function(e){
		e.preventDefault();
		$('#frmBuscarProduto').submit();		
	});
	// Responsável por adicionar novos itens ao carrinho de Compras
	
	/* Adição de um novo item ao carrinho de COMPRAS */
	$('#add').live('click',function(e){
		e.preventDefault();
		if( $("input[name='produto_loja_saida_id']:checked").val() > 0 ){
			$('#frmBuscarProduto').submit();	
		}else alert("Selecione uma Loja!");
	});
	
	
        
	
	
	
	
	/*=========================================== APLICAÇÃO DE DESCONTO ================================================*/
	// Pega o valor do cupom e busca no banco de dados o valor que deve ser abativo
	$('#calcularCupomDesconto').click(function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var chave = $('#cupom_desconto').val();
		$('.valorCupomDesconto').html('Calculando...');
		
		$.post(APP +'/'+$('#role').val()+'/devolucoes/valor_cupom',{ 
			chave: chave 
		},function(data){
			if (!data.error) {
				$('.valorArredondamento').html("R$ -0,00");
				$('.valorCupomDesconto').html("R$ -"+data.valor_cupom);                                
			}else{
				alert( 'Cupom especificado não foi encontrado.' );
				$('.valorCupomDesconto').html("R$ -0,00");
			}
			valorTotal();
		},'json');
	});
	
	
	/*=============================== BOTOES MAIS e MENOS QUANTIDADE ======================================*/
	//========= Mais
	// Soma mais uma unidade a um item do pedido
	$('#mais').live('click', function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var row = $(this).parents('tr');
		indice = row.attr('class');
		$.post(APP +'/'+$('#role').val()+"/item_pedidos/mais_um",{ 
			indice: indice 
		},function(data){
			if ( !data.retorno ) alert( 'Nao ha mais quantidade em estoque.\nAdicione o mesmo produto de outras lojas.' );
			else {
				row.find('.qtde').html(data.quantidade);
				row.find('.itemTotal').html(data.valor_total);
				valorTotal();
			}
		},'json');
	});
	//========= Menos
	// Subtrai mais uma unidade de um item do pedido
	$('#menos').live('click', function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var row = $(this).parents('tr');
		indice = row.attr('class');
		$.post(APP +'/'+$('#role').val()+"/item_pedidos/menos_um",{ 
			indice: indice 
		},function(data){
			row.find('.qtde').html(data.quantidade);
			row.find('.itemTotal').html(data.valor_total);
			valorTotal();
		},'json');
	});
        
        
	/*=============================== BOTOES REMOVER,DESCONTO,DARANTIA DE UM PRODUTO ======================================*/
	//========= Remover
	$('.remover').live('click', function(e){
		e.preventDefault();	
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		if (confirm('Deseja realmente remover este item?')) {
			var id = $(this).parents('tr').attr('class');
			$.post(APP +'/'+$('#role').val()+'/item_pedidos/del',{ 
				indice: id 
			},function(data){
				$('.'+ id).remove();
//				$('.boxProduto').fadeIn();
				valorTotal();
			},'html');
		}
	});        
	//========= Fim Remover
	//========= Desconto
	$('.desconto').live('click', function(e){
		e.preventDefault();
		                
		if($('#aplicarDesconto').css('display')=='block'){
			$('.autenticarDesconto').fadeOut();
		}                
		var item = $(this).parents('tr').attr('class');
		$('#aplicarDesconto').find('input[type=text], input[type=password]').each(function(){
			$(this).val('');
		});
		$('#aplicarDesconto').slideToggle('fast');
		$('input#valor_desconto').focus();
		$('#item_carrinho').val(item);
	});
	
	$('#aplicar').live('click', function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var desconto = $('#valor_desconto').val(),
		item = $('#item_carrinho').val(),
		desconto_user = $('#desconto_user').val(),
		desconto_pass = $('#desconto_pass').val(),
		token = $('#token').val(),
		row = $('tr.'+item);
		
		$.post(APP +'/'+$('#role').val()+'/item_pedidos/aplicarDesconto',{
			item: item, 
			desconto: desconto, 
			desconto_user: desconto_user, 
			desconto_pass: desconto_pass, 
			token: token
		},function(data){	
			if ( data.autorizado ) {
				row.find('.itemTotal').html(data.valor);
				row.find('.valor_desconto').html(data.desconto);
				valorTotal();

				$('#aplicarDesconto').find('input[type=text], input[type=password]').each(function(){
					$(this).val('');
				});

				$('#aplicarDesconto').slideUp('fast');
				$('.tokenDesconto, .autenticarDesconto').css('display', 'none');
				$('#resultToken').html('');
			} else {
				switch ( data.retorno ) {
					case 'requerLogin':
						$('.autenticarDesconto').slideDown('fast');
						$('#desconto_user').focus();
						break;
					case 'requerToken':
						$('.tokenDesconto').slideDown('fast');
						break;
					case 'loginIncorreto':
						alert( 'Login/Senha do gerente estao invalidos' );
						break;
					case 'tokenIncorreto':
						alert( 'Token incorreto, informe um token valido' );
						break;
					case 'requerAvariado':
						alert( 'O produto deve estar avariado para aplicar um desconto de '+ data.desconto +'%' );
						break;
					case 'valorNaoPermitido':
						alert( 'O valor do desconto excede o permitido!' );
						break;
				}
			}
		},'json');
	});
	//========= Fim Desconto
	//========= Garantia
	$('.garantia').live('click', function(e){
		e.preventDefault();
		var item = $(this).parents('tr').attr('class'),
		boxGarantia = $('#garantiaExtendida');
		boxGarantia.fadeToggle('fast');
		$('#periodo_garantia').focus();
		$('#item_garantia').val(item);
	});
	$('.aplicarGarantia').click(function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var periodo = $('#periodo_garantia').val(),
		valor = $('#valor_garantia').val(),
		item = $('#item_garantia').val(),
		row = $('tr.'+item), // Linha que será editada
		valor_unitario = '', // Calcula o valor unitário do produto com desconto
		feedback = '<div class="feedback" style="position: fixed; top: 0; left: 0; width: 100%; padding: 10px; text-align: center; background: #fff; border-bottom: 1px solid #ccc; font-size: 14px;"></div>';
		
		$('body').append(feedback);
		$('.feedback').html('Aguarde, aplicando garantia extendida...');
		
		$.post(APP +'/'+$('#role').val()+'/item_pedidos/aplicar_garantia',{ 
			item: item, 
			periodo: periodo, 
			valor: valor 
		},function(data){
			row.find('.itemTotal').html(data.valorTotal);
			row.find('.valor_garantia').html(data.valorgarantia);
			row.find('.periodo_garantia').html(data.periodogarantia);
                                               
			$('.feedback').html('Garantia extendida adicionada com sucesso!');
			$('#garantiaExtendida').find('input[type=text]').val('').end().fadeOut('fast');
			$('#item_garantia').val('');
			valorTotal();
		},'json');
	});
	//========= Fim Garantia
	/*=========================================== CONTROLE DE PAGAMENTOS ================================================*/
	var minimoEntrada = 0,
	totalParcelas = 1;
	$('#frmPagamento').submit(function(e){
		e.preventDefault();
		var valorTotal = toFloat($('.valorTotal').text()), 
		valorPagtos = totalPagamentos(),
		valorRestante = valorTotal - valorPagtos;
		if ( valorRestante == 0 ) {
			alert('O valor do pedido já está diluido nas formas de pagamentos adicionadas');
			limparPagamentoBox();
			return false;
		} 
		if($('#forma_pagto').val()==''){
			alert('Selecione uma forma de pagamento!');
			$('#forma_pagto').focus();
			return false;
		}
		if($('#forma_pagto').val()!= $('#forma_pagto_validate').val()){
			alert('Selecione uma parcela!');
			$('#parcelas').focus();
			return false;
		}

		$.post(APP+'/'+$('#role').val()+'/pagamentos/add',{
			valor: toFloat($('#valor').val()), 
			forma_pagto: $('#forma_pagto').val(), 
			parcelas: $('#parcelas').val(), 
			entrada: toFloat($('#entrada').val()), 
			tac: toFloat($('#tac').val()), 
			nome: $('#forma_pagto option:selected').text()
		},function(data){
			$('.pagamentos tbody').append(data);
			limparPagamentoBox();
		},'html');
            
	})
	/** Chama o box para adicionar uma nova forma de pagamento **/
	$('.addFormaPagto').click(function(){
		var valorTotal = toFloat($('.valorTotal').text()), 
		valorPagtos = totalPagamentos(),
		valorRestante = valorTotal - valorPagtos;
		//alert(valorTotal+" "+valorPagtos+" "+valorRestante);
		if ( valorRestante == 0 ) {
			alert('O valor do pedido já está diluido nas formas de pagamentos adicionadas');
		}else if( valorRestante < 0 ) {
			alert('O valor dos pagamentos supera o valor do pedido\nReveja os pagamentos!');
		}else {
			//alert('Valor do Pedido: '+ valorTotal +'\nPagamento: '+ valorPagtos +'\nFalta pagar: '+ formataMoeda(faltaPagar));
			$('.addFormaPagtoBox').fadeIn('fast');
			$('#valor').val(brFloat(valorRestante));
		}
		return false;
	});
	$('#forma_pagto').change(function(){
		var valor = toFloat( $('#valor').val() ),
		forma_pagto = $(this).val(),
		parcelas = $('#parcelas'),
		entrada,
		parcelar = 0;
		
		parcelas.html('<option value="">Carregando...</option>');
		$.post(APP +'/'+$('#role').val()+'/tipospagamentos/condicao',{ 
			forma_pagto: forma_pagto 
		},function(data){
			$('#forma_pagto_validate').val(forma_pagto);
			entrada = ( ( valor * data.TiposPagamentos.entrada ) / 100 );
			$('#tac').val(brFloat(data.TiposPagamentos.tac));
			$('#nome_fpagto').val(data.TiposPagamentos.nome);
			minimoEntrada = entrada;
			totalParcelas = data.TiposPagamentos.parcelas;
			parcelar = ( valor - entrada );
			gerarParcelas( parcelar, totalParcelas, '#parcelas' );				
			$('#entrada').val(brFloat(entrada));
		},'json');
	});
	
	$('#entrada').blur(function(){
		var entrada = toFloat( $(this).val() ),
		valor = '',
		parcelar = '';

		if ( entrada >= minimoEntrada ) {
			valor = toFloat( $('#valor').val() );
			parcelar = ( valor - entrada );
			gerarParcelas( parcelar, totalParcelas, '#parcelas' );
		} else {
			alert( 'A entrada não pode ser menor que: R$ '+ brFloat( minimoEntrada ) );
			$('#entrada').val(brFloat(minimoEntrada));
		}
	});
	
	/** Remove uma forma de pagamento da sessão de pagamentos do carrinho **/
	$('.removerPagto').live('click', function(e){
		e.preventDefault();
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var id = $(this).parents('tr').attr('class');
		if (confirm('Deseja realmente remover?')) {
			var indice = $(this).parents('tr').attr('class');
			$.post(APP+'/'+$('#role').val()+'/pagamentos/del',{
				indice: indice
			},function(data){ 
				$('.pagamentos tbody').find('.'+indice).remove();
			},'json');
		}
	});
	
	$('.arredondar').live('click', function(){
		arredondar();
	});
        
	/*=================================================== FUNÇÕES ====================================================*/
	
	function arredondar(){
		if($('.addFormaPagtoBox').css('display')!='none'){
			limparPagamentoBox();
		}
		var valor_total = $('.valorTotal').text(),
		valor = 0,
		centavos = '',
		length = 0,
		valor_final = '',
		casa_decimal = '',
		arredondamento = 0;
		
		valor_total = valor_total.replace('R$ ', '').replace('.', '').replace(',', '.');
		valor = valor_total.split("."); // Separa o valor de suas casas decimais
		centavos = '00';
		
		length = valor[0].length;
		if ( length >= 3 ) {
			casa_decimal = valor[0].split("");
			for ( i in casa_decimal ) 
				valor_final+= ( i == (length - 1) )? '0' : casa_decimal[i];
			
		} else valor_final = valor[0];
		
		valor_total = parseFloat( valor_total );
		valor_final = parseFloat( valor_final );
		arredondamento = valor_total - valor_final;
		arredondamento = Math.round(arredondamento * 100) / 100;
		
		valor_final += '.'+ centavos;
		//alert( 'Valor total: '+ valor_total +'\nValor arredondado: '+ valor_final +'\nValor do arredondamento: '+ arredondamento );
		
		$('.valorTotal, .valorArredondamento').html('Calculando...');
		//alert(arredondamento+" "+valor_final);
                
		$.post(APP +'/'+$('#role').val()+'/pedidos/arredondar',{ 
			valorArredondamento: arredondamento,
			valorTotal: valor_final 
		},function(data){
			$('.valorTotal').html(data.valorTotal);
			$('.valorArredondamento').html(data.valorArredondamento);
		},'json');
	}
        
	function limparPagamentoBox(){
		$('#valor,#forma_pagto, #entrada, #tac').val('');
		$('#parcelas').empty();
		$('.addFormaPagtoBox').slideUp('fast');
	}
        
	//Limpar forma de pagamento
	function removerPagto() {
		var indice = 0;
		$.get(
			APP+'/'+$('#role').val()+'/pagamentos_pedidos/del',
			{
				indice: indice
			},
			function(data){ 
				$('.pagamentos tbody').find('.'+indice).remove();
			},
			'html'
			);
	}
	/** faz a validação dos campos que devem ser preenchidos para adicionar um item **/
	function validarCampos() {
		var i, item = ['produto_id', 'produto', 'quantidade'], itens = item.length, campo, error = true;
		
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
	function valorTotal(){
		$.post(APP +'/'+$('#role').val()+"/item_pedidos/valorTotal",{  
			},function(data){ 
				//alert(data.valorTotal)
				$('.valorTotal').html(data.valorTotal);
				$('.valorArredondamento').html(data.valorArredondamento);
			},'json');
	}
	//	function valorTotal() {
	//		var valorTotal = 0,
	//			valor;
	//		
	//		$('.grid tbody tr').each(function(i){
	//			valor = $(this).find('.itemTotal').text();
	//			valor = valor.replace('R$ ', '').replace('.', '').replace(',', '.');
	//			valorTotal += parseFloat(valor);
	//		});
	//		$('.valorTotal').text('R$ '+ number_format(valorTotal, 2, ',', '.'));
	//	}

	//Tranforma a moeda para o formado americano
	function moedaAmericana(moeda) {
		moeda = moeda.replace(".","");
		moeda = moeda.replace(",",".");
		return parseFloat(moeda);
	}

	// Calcula o valor total do pedido com desconto
	function valorTotalDesconto(descontoreal) {
		var valorTotal = 0,
		valor;
		
		$('.grid tbody tr').each(function(i){
			valor = $(this).find('.itemTotal').text();
			valor = valor.replace('R$ ', '').replace('.', '').replace(',', '.');
			valorTotal += parseFloat(valor);
		});
		$('.valorTotal').text('R$ '+ number_format(valorTotal - descontoreal, 2, ',', '.'));
	}
	
	// Calcula o valor total já cadastrado nos pagamentos
	function totalPagamentos() {
		var valorPagtos = 0;		
		// Calcula os valores de pagamento
		$('.pagamentos tbody tr').each(function(i){
			valorPagtos += toFloat($(this).find('.valorPagto').text());
		});
		
		return valorPagtos.toFixed(2);
	}
	
	// Formata um valor float(com , nas casas decimais) e devolve um valor Float nativo do JavaScript
	function toFloat(valor) {
		return parseFloat(valor.replace('R$ ', '').replace('.', '').replace(',', '.'));
	}
	
	// Formata um valor float no padrão brasileiro
	function brFloat(valor) {
		return number_format(valor, 2, ',', '.');
	}
	/**
	 * gerarParcelas() - de acordo com os dados recebidos monta o campo select com as opções de pagamento
	 * @param Number valor
	 * @param Number totalParcelas
	 * @param String element
	 * @return void
	 */
	function gerarParcelas( valor, totalParcelas, element ) {
		var element = $(element).empty(),
		i = new Number;

		for (i = 1; i <= totalParcelas; i++) {
			element.append('<option value="'+ i +'">'+ i +'x R$ '+ brFloat( valor / i ) +'</option>');
		}
	}
	/**
	 * somarTotal() - Soma o total de pagamentos de um pedido
	 * @return String total
	 */
	function somarTotal() {
		var total = new Number,
		valor = new Number,
		garantia = new Number;
		
		$('table.gridPagamentos tbody tr').each(function(){
			valor = recebeFloat($(this).find('.valor').text().replace('R$ ', ''));
			garantia = recebeFloat($(this).find('.valor_garantia').text().replace('R$ ', ''));
			if (garantia != '')
				total += parseFloat(valor) + parseFloat(garantia);
			else total += parseFloat(valor);
		});
		return formataMoeda(total);
	}
	
	/**
	 * valorTotalPedido() - Retorna o valor total do pedido já calculado pelo PHP
	 */
	function valorTotalPedido() {
		return $('.valorTotal').text().replace('R$ ', '');
	}
	AtualizaUnidadeMedida();
	carregaObras();
});