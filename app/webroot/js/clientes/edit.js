$(document).ready(function(){
	$('#ClienteTelefone').mask('(99)9999-9999',{
		onKeyPress: function(phone, e, currentField, options){
			var new_sp_phone = phone.match(/^(\(11\) 9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])[0-9]{1})/g);
			new_sp_phone ? $(currentField).mask('(00) 00000-0000', options) : $(currentField).mask('(00) 0000-0000', options)
		}
	});
	$('#ClienteDataNascimento').mask('99/99/9999');
	$('#ClienteCep').mask('99999-999');
	$('#EnderecoCep').mask('99999-999');
	
	checarTipo( $('.tipo:checked').val() );
	
	$('.tipo').click(function(){
		var tipo = $('.tipo:checked').val();
		checarTipo( tipo );
	});
	
	// Checa se é pessoa física ou juridica
	function checarTipo( tipo ){		
		if ( tipo == 1 ) {
			$('label[for=ClienteCpfCnpj]').html('CPF');
			$('#ClienteCpfCnpj').mask('999.999.999-99');
			$('label[for=ClienteRgIe]').html('RG');
			$('#ClienteRgIe').mask('AA.AAA.AAA-A');
//			toggleHabilty('pf');
//			toggleHabilty('pj', false);
			
		} else if ( tipo == 0 ) {
			$('label[for=ClienteCpfCnpj]').html('CNPJ');			
			$('#ClienteCpfCnpj').mask('99.999.999/9999-99');
			$('label[for=ClienteRgIe]').html('Inscrição Estadual');
			$('#ClienteRgIe').mask('AAAAAAAAAAAAA');
//			toggleHabilty('pj');
//			toggleHabilty('pf', false);
		}
	};

	$('#EnderecoEstadoId').change(function(){
		var estado_id = $(this).val();
		var cidades = $('#EnderecoCidadeId');
		
		cidades.html('<option value="">Carregando...</option>');
		$.get(APP +'/'+$('#role').val()+'/cidades/by_estado',{ 
                    estado_id: estado_id 
                },function(data){
                        cidades.html(data);
		},'html');
	});


	$("#EnderecoCep").blur(function(){
				// Remove tudo o que não é número para fazer a pesquisa
				var cep = this.value.replace(/[^0-9]/, "");
				var estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];


				
				// Validação do CEP; caso o CEP não possua 8 números, então cancela
				// a consulta
				// if(cep.length != 8){
				// 	return false;
				// }
				
				// A url de pesquisa consiste no endereço do webservice + o cep que
				// o usuário informou + o tipo de retorno desejado (entre "json",
				// "jsonp", "xml", "piped" ou "querty")
				var url = "https://viacep.com.br/ws/"+cep+"/json/";
				
				// Faz a pesquisa do CEP, tratando o retorno com try/catch para que
				// caso ocorra algum erro (o cep pode não existir, por exemplo) a
				// usabilidade não seja afetada, assim o usuário pode continuar//
				// preenchendo os campos normalmente
				$.getJSON(url, function(dadosRetorno){
					try{
						// Preenche os campos de acordo com o retorno da pesquisa
						$("#EnderecoEndereco").val(dadosRetorno.logradouro);
						$("#EnderecoBairro").val(dadosRetorno.bairro);
						//$("#cidade").val(dadosRetorno.localidade);
						//$("#uf").val(dadosRetorno.uf);
						var estado = 1;
						var cidade = '';
						var cidades = $('#EnderecoCidadeId');

						
						estados.forEach(function (item, indice, array) {
						  console.log(item, indice);
						  if(dadosRetorno.uf == item){
						  	estado = indice+1;
						  }
						});

						$('#EnderecoEstadoId').val(estado);

						cidade = dadosRetorno.localidade;

						

						$.get(APP +'/'+$('#role').val()+'/cidades/by_estado_cidade',{ 
                    		estado_id: estado,
                    		cidade:  cidade
                		},function(data){
                			console.log(data);
                     		cidades.html(data);
						},'html');

						//$(".endereco").html(dadosRetorno.logradouro+' '+dadosRetorno.bairro+' '+dadosRetorno.localidade+' '+dadosRetorno.uf);



					}catch(ex){}
				});
			});
	
	//
	//=============================  Endereco =======================================
	//
	$('#frmEndereco').submit(function(e){
		e.preventDefault();
		var tipo = '#EnderecoTipoId';
		var endereco = '#EnderecoEndereco';
		var numero = '#EnderecoNumero';
		var cep = '#EnderecoCep';
		var bairro = '#EnderecoBairro';
		var estado = '#EnderecoEstadoId';
		var cidade = '#EnderecoCidadeId';
		var complemento = '#EnderecoComplemento';
		
		DATA = new Object();
		DATA['endereco'] = $(endereco).val();
		DATA['numero'] = $(numero).val();
		DATA['cep'] = $(cep).val();
		DATA['bairro'] = $(bairro).val();
		DATA['tipo_id'] = $(tipo).val();
		DATA['estado_id'] = $(estado).val();
		DATA['estado_nome'] = $(estado+' option:selected').text();
		DATA['cidade_id'] = $(cidade).val();
		DATA['cidade_nome'] = $(cidade+' option:selected').text();
		DATA['complemento'] = $(complemento).val();
		
		$.post( APP +'/'+$('#role').val() +'/enderecos/adicionar',{
			Endereco:DATA
		},function(data){
			if(data['error']==false){
				$(endereco).val('');
				$(numero).val('');
				$(cep).val('');
				$(bairro).val('');
				$(tipo).val('');
				$(estado).val('');
				$(cidade).val('');
				$(complemento).val('');
				$('.tbenderecos tbody').html(data['html']);
			}else{
				alert('Não foi possivel realizer o cadastro!');
			}
		},'json');
	});

	$('.btn-del-endereco').unbind('click').bind('click',function(e){
		e.preventDefault();
		var idendereco = $(this).attr('id');
		$.post(APP +'/'+$('#role').val() +'/enderecos/remover/'+idendereco,{
			},function(data){
				$('.tbenderecos .'+idendereco).remove();
			},'json');
	});
	
	$('#btnSubmit').click(function(){
		$('#frmCliente').submit();
	});
});