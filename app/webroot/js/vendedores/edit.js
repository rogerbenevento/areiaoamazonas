$(document).ready(function(){
	$('#VendedorTelefone').mask('(99)9999-9999',{
		onKeyPress: function(phone, e, currentField, options){
			var new_sp_phone = phone.match(/^(\(11\) 9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])[0-9]{1})/g);
			new_sp_phone ? $(currentField).mask('(00) 00000-0000', options) : $(currentField).mask('(00) 0000-0000', options)
		}
	});
	$('#VendedorDataNascimento').mask('99/99/9999').datepicker({ dateFormat: "dd/mm/yy" });;
	$('#VendedorDataAdmissao').mask('99/99/9999').datepicker({ dateFormat: "dd/mm/yy" });;
	$('#VendedorAfastamento').mask('99/99/9999').datepicker({ dateFormat: "dd/mm/yy" });;
	$('#VendedorDatasaida').mask('99/99/9999').datepicker({ dateFormat: "dd/mm/yy" });;
	$('#VendedorCep').mask('99999-999');
	
	
	
	$('#VendedorCpf').mask('999.999.999-99');
	$('#VendedorComissao').priceFormat({
		prefix: "",
		limit:4,
		centslimit:2,
		centsSeparator: "."
	});
	// Habilita ou desabilida os campos de uma determinado box
//	function toggleHabilty(classe, acao){
//		acao = ( acao === undefined ) ? true : acao;
//		$('.'+ classe).find('input[type=text]').each(function(){
//			if ( !acao ) {
//				$(this).attr('disabled', true);
//				$('.'+ classe).hide('slide');
//			} else {
//				$(this).removeAttr('disabled');
//				$('.'+ classe).show('slide');
//			}
//		});
//	}
	
	$('#VendedorEstadoId').change(function(){
		var estado_id = $(this).val();
		var cidades = $('#VendedorCidadeId');
		
		cidades.html('<option value="">Carregando...</option>');
		$.get(
			APP+'/'+ $('#role').val() +'/cidades/by_estado',
			{ estado_id: estado_id },
			function(data){
				cidades.html(data);
			},
			'html'
		);
	});


	$("#VendedorCep").blur(function() {
	    // Remove tudo o que não é número para fazer a pesquisa
	    var cep = this.value.replace(/[^0-9]/, "");
	    var url = "https://viacep.com.br/ws/" + cep + "/json/";

	    $.getJSON(url, function(dadosRetorno) {
	        try {
	            // Preenche os campos de acordo com o retorno da pesquisa
	            $("#VendedorEndereco").val(dadosRetorno.logradouro);
	            $("#VendedorBairro").val(dadosRetorno.bairro);
	            var estado = 1;
	            var cidade = '';
	            var cidades = $('#VendedorCidadeId');


	            ESTADOS.forEach(function(item, indice, array) {
	                console.log(item, indice);
	                if (dadosRetorno.uf == item) {
	                    estado = indice + 1;
	                }
	            });

	            $('#VendedorEstadoId').val(estado);

	            cidade = dadosRetorno.localidade;



	            $.get(APP + '/' + $('#role').val() + '/cidades/by_estado_cidade', {
	                estado_id: estado,
	                cidade: cidade
	            }, function(data) {
	                console.log(data);
	                cidades.html(data);
	            }, 'html');


	        } catch (ex) {}
	    });
	});
});