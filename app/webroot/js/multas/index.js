$(function(){
	$('#MultaIndicacao,#MultaData').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	
	$('#MultaPlaca').mask('AAA-9999');
	
	$('#MultaValor').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: "."
	});
});