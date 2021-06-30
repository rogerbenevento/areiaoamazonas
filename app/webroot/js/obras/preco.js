$(document).ready(function () {
    $( window ).load(function() {
        var obra_id = $('#obra_id').val();
        $.post(APP + '/' + $('#role').val() + "/obras/precos_list/"+obra_id)
        .done(function (data) {
            var linhas = "";

            $.each(JSON.parse(data), function(i, item) {
                linhas += "<tr><td>"+item.produto+"</td><td>"+item.preco+"</td><td></td></tr>";
            });
            $( "#listPrecos" ).html( "<table class='table table-striped'>"+linhas+"</table>" );
           console.log(data);
        });

    });
    $('#addPreco').click(function(e){
       
        var cliente_id =  $('#cliente_id').val();
        var obra_id = $('#obra_id').val();
        var preco = $('#preco').val();
        var produto_id = $('#produto_id').val();

        $.post(APP + '/' + $('#role').val() + "/obras/precos_save/", { 
            cliente_id,
            obra_id,
            produto_id,
            preco 
        })
        .done(function (data) {
            var linhas = "";

            $.each(JSON.parse(data), function(i, item) {
                linhas += "<tr><td>"+item.produto+"</td><td>"+item.preco+"</td><td></td></tr>";
            });
            $( "#listPrecos" ).html( "<table class='table table-striped'>"+linhas+"</table>" );
           console.log(data);
        });
    });
});