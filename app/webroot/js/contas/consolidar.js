$(document).ready(function() {
	
	$('#ContaDataPagamento,#ChequeDataCompensacao').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	$('#ContaPago').priceFormat({
		prefix: "",
		centsSeparator: ",",
		thousandsSeparator: ""
	});
	
	$('#ContaTipoPagamentoId').change(function(e){
		TipoPagamentoChange();
	});
	
	$('.btnConsolidar').prop('disabled', false);
	$('.btnConsolidar').click(function(e) {
		e.preventDefault();
		$(this).prop('disabled', 'disabled');
		$(this).prop('value', 'Consolidando...');
		$('.frmConsolidar').submit();
		//window.open(APP + '/' + $('#role').val() + '/contas/comprovante/' + $(this).attr('id'), '', 'width=750, height=350, scrollbars=yes');
	});
	
	function TipoPagamentoChange(){
		if($('#ContaTipoPagamentoId').val()==1){
			// Cheque selecionado
			$('.cheque').slideDown(400);
		}else{
			$('.cheque').slideUp(400);
			$('.cheque input').val('');
			
		}
	}
	
	
	TipoPagamentoChange();
});