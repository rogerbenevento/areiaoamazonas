$(document).ready(function(){

	$('#ObraCnpj').mask('99.999.999/9999-99');
	$('#ObraCep').mask('99999-999');
	$('#ObraCustoExtra').priceFormat({
		prefix: "",
		centslimit:2,
		centsSeparator: ","
	});
	$('#ObraComissao,#ObraFreteiro').priceFormat({
		prefix: "",
		limit:4,
		centslimit:2,
		centsSeparator: "."
	});

	$('#ObraEstadoId').change(function(){
		var estado_id = $(this).val();
		var cidades = $('#ObraCidadeId');
		
		cidades.html('<option value="">Carregando...</option>');
		$.get(APP +'/'+$('#role').val()+'/cidades/by_estado',{ 
                    estado_id: estado_id 
                },function(data){
                        cidades.html(data);
		},'html');
	});

	$("#ObraCep").blur(function() {
	    // Remove tudo o que não é número para fazer a pesquisa
	    var cep = this.value.replace(/[^0-9]/, "");
	    var url = "https://viacep.com.br/ws/" + cep + "/json/";

	    $.getJSON(url, function(dadosRetorno) {
	        try {
	            // Preenche os campos de acordo com o retorno da pesquisa
	            $("#ObraEndereco").val(dadosRetorno.logradouro);
	            $("#ObraBairro").val(dadosRetorno.bairro);
	            var estado = 1;
	            var cidade = '';
	            var cidades = $('#ObraCidadeId');


	            ESTADOS.forEach(function(item, indice, array) {
	                console.log(item, indice);
	                if (dadosRetorno.uf == item) {
	                    estado = indice + 1;
	                }
	            });

	            $('#ObraEstadoId').val(estado);

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