<h1>Vale</h1>
<?php #pr($row) ?>
<fieldset>
    <legend>Dados do Pedido</legend>
    <label><span>Pedido: </span><?php echo $row['ItemPedido']['pedido_id']; ?>&nbsp;</label>
</fieldset>
<fieldset>
    <legend>Dados do Item</legend>
    <label><span>C&oacute;digo: </span><?php echo $row['ItemPedido']['Produto']['codigo']; ?>&nbsp;</label>
    <label><span>Descri&ccedil;&atilde;o: </span><?php echo $row['ItemPedido']['Produto']['nome']; ?>&nbsp;</label>
    <label><span>Quantidade: </span><?php echo $row['ItemPedido']['quantidade']; ?>&nbsp;</label>    
</fieldset>

<fieldset>
    <legend>Dados do Cliente</legend>
    <label><span>Nome: </span><?php echo @$row['ItemPedido']['Pedido']['Cliente']['nome']; ?>&nbsp;</label>
    <label><span>Endere&ccedil;o: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['endereco']; ?>&nbsp;</label>
    <label><span>Complemento: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['complemento']; ?>&nbsp;</label>
    <label><span>Bairro: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['Cliente']['bairro']; ?>&nbsp;</label>
    <label><span>Cidade: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['Cidade']['nome']; ?>&nbsp;</label>
    <label><span>Estado: </span><?php echo @utf8_encode( $row['ItemPedido']['Pedido']['Obra']['Estado']['nome'] ); ?>&nbsp;</label>
</fieldset>