$(document).ready(function(){
	
	$('#UserComissao').priceFormat({
			prefix: "",
			limit: 4,
			centsLimit: 2,
			centsSeparator: ",",
			thousandsSeparator: ""
			
		});
	
	
	function VerificaUsuario(){
	
		if($('#UserNivelId').val() == 2 ){
			$('#div-comissao').show();
			$('#UserComissao').val('0.00');
		}else{
			$('#div-comissao').hide();
			$('#UserComissao').val('');
		}
	}
	
	$('#UserNivelId').change(function(){
		VerificaUsuario();
	})
	VerificaUsuario();
});