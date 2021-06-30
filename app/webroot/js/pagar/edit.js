$(document).ready(function(){
	
	$('#PagarValor').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: ""
	});
	$('#PagarDataVencimento').mask('99/99/9999');

});