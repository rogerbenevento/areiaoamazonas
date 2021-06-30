$(document).ready(function(){
	
	$('#ReceberValor').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: ""
	});
	$('#ReceberDataVencimento').mask('99/99/9999');

});