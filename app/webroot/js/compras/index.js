$(function(){
    $('#frmView').dialog({
            title:'Detalhes da Compra',
            height: 600,
            width: 750,
            autoOpen:false,
            modal: false,            
	    show: "puff",
            //position:{ my: "center", at: "center", of: window },
	    hide: "puff",
            beforeClose: function( event, ui ) {$('#frmView').html('');}
    });
        
    $('.compra-view').click(function(e){
        e.preventDefault();
        $.post(APP+'/'+$('#role').val()+'/compras/view/'+$(this).attr('id'),{
            
        },function(resp){
            $('#frmView').html(resp);
        })
        $('#frmView').dialog( "open" );
    })
})

