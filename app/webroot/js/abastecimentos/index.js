$(function(){
	$('#AbastecimentoIndicacao,#AbastecimentoData').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	
	$('#AbastecimentoPlaca').mask('AAA-9999');
	
	$('#AbastecimentoValor').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: "."
	});
});