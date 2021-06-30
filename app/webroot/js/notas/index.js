$(function(){

	$('.endereco-print').click(function(e){
		e.preventDefault();
		window.open(APP+'/'+$('#role').val()+'/notas/print_endereco/'+$(this).attr('id'),'', 'width=750, height=350, scrollbars=no');
	})
	
	
	$('form').submit(function(e){
		$(this).find('[type=submit]').prop('disabled',true).val('Enviando...');
		return true;
	});
})