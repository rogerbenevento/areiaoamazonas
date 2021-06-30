$(function(){
    $( "#RelatorioInicio,#RelatorioFim" ).mask('99/99/9999')
    .datepicker(DATE_PICKER_CONFIG);
	
	
	// Bloqueando o Enter no campo Material
	$('#Cliente').keypress(function(e){
		if ( e.which == 13 ) {
			return false;
		}
	}).simpleAutoComplete(APP +'/'+$('#role').val()+'/clientes/all', {}, callbackCliente);
	function callbackCliente( par ) { 
		$("#RelatorioClienteId").val( par[0] );
		$('#RelatorioObraId').html("<option value=''>Carregando...</option>").load(APP +'/'+$('#role').val()+'/obras/options/'+par[0]);
		
	}
	
	
	
	$('#Cliente').change(function(){
		if($(this).val()==''){
			$("#RelatorioClienteId").val('');
			$("#RelatorioObraId").val('');
		}
	})
})
