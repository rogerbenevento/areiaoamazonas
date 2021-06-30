$(function(){
	$('#AbastecimentoData').datepicker(DATE_PICKER_CONFIG);
	$('#AbastecimentoValor,#AbastecimentoQuantidade,#AbastecimentoQuilometragem').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: ""
	});
});