$(document).ready(function(){
	
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
		$.post(APP+'/'+$('#role').val()+'/tipospagamentos/condicao',{ 
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
                
		$.post(APP+'/'+$('#role').val()+'/pedidos/arrendodar',{ 
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
			{indice: indice},
			function(data){ 
				$('.pagamentos tbody').find('.'+indice).remove();
			},
			'html'
		);
	}
		
	// Calcula o valor total do pedido
        function valorTotal(){
                $.post(APP+'/'+$('#role').val()+"/itenspedidos/valorTotal",{  
                },function(data){ 
                        $('.valorTotal').html(data.valorTotal);
                        $('.valorArredondamento').html(data.valorArredondamento);
                },'json');
        }
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
        
        $('#nota_fiscal').focus();
});