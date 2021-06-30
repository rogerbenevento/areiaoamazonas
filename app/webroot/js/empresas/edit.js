$(document).ready(function(){

	$('#EmpresaCnpj').mask('99.999.999/9999-99');
	$('#EmpresaCep').mask('99999-999');
	$('#EmpresaEstadoId').change(function(){
		var estado_id = $(this).val();
		var cidades = $('#EmpresaCidadeId');
		
		cidades.html('<option value="">Carregando...</option>');
		$.get(APP +'/'+$('#role').val()+'/cidades/by_estado',{ 
                    estado_id: estado_id 
                },function(data){
                        cidades.html(data);
		},'html');
	});
//	$('#EmpresaCnpj').mask('999.999.999.999');


	$("#EmpresaCep").blur(function() {
	    // Remove tudo o que não é número para fazer a pesquisa
	    var cep = this.value.replace(/[^0-9]/, "");
	    var url = "https://viacep.com.br/ws/" + cep + "/json/";

	    $.getJSON(url, function(dadosRetorno) {
	        try {
	            // Preenche os campos de acordo com o retorno da pesquisa
	            $("#EmpresaEndereco").val(dadosRetorno.logradouro);
	            $("#EmpresaBairro").val(dadosRetorno.bairro);
	            var estado = 1;
	            var cidade = '';
	            var cidades = $('#EmpresaCidadeId');


	            ESTADOS.forEach(function(item, indice, array) {
	                console.log(item, indice);
	                if (dadosRetorno.uf == item) {
	                    estado = indice + 1;
	                }
	            });

	            $('#EmpresaEstadoId').val(estado);

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