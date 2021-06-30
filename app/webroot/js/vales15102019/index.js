$(function(){
	$('.btnimprimir-modal').click(function(e){
		e.preventDefault();
		$('#print_id').val($(this).attr('vale_id'));
		$('#layout').val($(this).attr('layout'));
		$('#myModal').modal('show');
	})	
	$('.btnimprimir').click(function(){
        var link =APP + "/" + $('#role').val() + '/vales/imprimir/' + $('#print_id').val() +"/"+$('#layout').val()+'/'+$('#motorista').val();
        window.open(link, '', 'width=600, height=500, scrollbars=yes' );
    });
})