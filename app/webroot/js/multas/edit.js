$(function(){
	$('#MultaIndicacao,#MultaData').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	$('#MultaValor').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: "."
	});
});