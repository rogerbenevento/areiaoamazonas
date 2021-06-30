$(document).ready(function() {
	function AtivarFiltro() {
		$('#data').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
		$('#valor').priceFormat({
			prefix: "",
			centsSeparator: ",",
			centsLimit: 2,
			thousandsSeparator: "."
		});
		$('#filtroContas').submit(function(e) {
			e.preventDefault();

			$(".tbcontas tbody tr").show();
			$('#filtroContas input').each(function() {
				var index = $(this).parent().index();
				var nth = ".tbcontas td:nth-child(" + (index + 1).toString() + ")";
				var valor = $(this).val().toUpperCase();
				if (valor != '') {
					$(nth).each(function() {
						if ($(this).text().toUpperCase().indexOf(valor) < 0 && $(this).parent().is(":visible")) {
							$(this).parent().hide();
						}
					});
				}
			});
		});
	}
	$('.grid').load(APP + '/' + $('#role').val() + '/contas/all', {}, function() {
		AtivarFiltro();
	})
//	//FILTROS
//	.on("keyup",".tbcontas input",function(){  
//		
//		var index = $(this).parent().index();
//		
//		var nth = ".tbcontas td:nth-child("+(index+1).toString()+")";
//		var valor = $(this).val().toUpperCase();
//		$(".tbcontas tbody tr").show();
//		$(nth).each(function(){
//		    if($(this).text().toUpperCase().indexOf(valor) < 0){
//			   $(this).parent().hide();
//		    }
//		});
//	})
//	.on("blur",".tbcontas input",function(){
//	    $(this).val("");
//    });
	//=======================================

	$('.mes').click(function(e) {
		e.preventDefault();
		var mes = $(this).attr('rel'),
				ano = $('.ano').text();

		//alert('Ano: '+ ano +'\nMes: '+ mes);
		$('.grid').html('<p>Aguarde, carregando as contas...</p>');
		$.get(APP + '/' + $('#role').val() + '/contas/all', {
			mes: mes,
			ano: ano
		}, function(data) {
			$('.grid').html(data);
			$('#ano').val(ano);
			$('#mes').val(mes);
			AtivarFiltro();
		}, 'html');
	});

	$('.maisUm').click(function() {
		var ano = $('.ano').text(),
				novoAno = parseInt(ano) + 1,
				mes = $('#mes').val();

		//alert('Ano escolhido: '+ novoAno +'\nAno atual: '+ $('#ano').val());
		if (novoAno == $('#ano').val())
			$('button[rel=' + parseInt(mes) + ']').addClass('active');
		else
			$('.meses').find('.active').removeClass('active');

		$('.ano').text(novoAno);
		//$('#ano').val(novoAno);
	});

	$('.menosUm').click(function() {
		var ano = $('.ano').text(), anoAnterior = parseInt(ano) - 1, mes = $('#mes').val();

		//alert('Ano escolhido: '+ ano +'\nAno atual: '+ $('#ano').val());
		if (anoAnterior == $('#ano').val())
			$('button[rel=' + parseInt(mes) + ']').addClass('active');
		else
			$('.meses').find('.active').removeClass('active');

		$('.ano').text(anoAnterior);
		//$('#ano').val(anoAnterior);
	});



});