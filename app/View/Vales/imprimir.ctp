<?php
	if(empty($row['Motorista']['id'])){
		if(empty($motorista['Motorista']['id'])){
			$row['Motorista']['placa']='__________________________';
			$row['Motorista']['nome']=" _____________________________";
		}else{
			$row['Motorista']['placa']=$motorista['Motorista']['placa'];
			$row['Motorista']['nome']=$motorista['Motorista']['nome'];
		}
	}
?>
<div style="width: 98%;">
	<div style="float:left"><h2>COMPROVANTE DE ENTREGA</h2></div>
	<div style='float: right'>
		<h2>
			<?php 
				
				if(!empty($row['Vale']['codigo']))
					echo 'Nº X'.$row['Vale']['codigo'];
				else if(!empty($row['ItemPedido']['pedido_id']))
					echo 'Nº'.$row['ItemPedido']['pedido_id'];
					//echo 'Nº'.@$row['Vale']['id']; 
				
			?>
		</h2>
	</div>
</div>
<fieldset style="width: 98%">
    <!--<legend>Dados do Item</legend>-->
    <label style='width: 48%;float: left'><span>Nome Obra:&nbsp; </span><?php echo $row['ItemPedido']['Pedido']['Obra']['nome']; ?>&nbsp;</label>
    <label><span>Endere&ccedil;o: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['endereco']; ?>&nbsp;</label>
    <label style='width: 48%;float: left'><span>Bairro: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['bairro']; ?>&nbsp;</label>
    <label><span>Cidade: </span><?php echo @$row['ItemPedido']['Pedido']['Obra']['Cidade']['nome']; ?>&nbsp;</label>
</fieldset>
<fieldset style="width: 98%">
    <!--<legend>Dados do Item</legend>-->
    <label style='float: left;width: 68%'><span>Placa: </span><?php echo @$row['Motorista']['placa']; ?>&nbsp;</label>
    <label ><span>Data: </span><?php echo dateMysqlToPhp((!empty($row['Vale']['data_entrega'])?$row['Vale']['data_entrega']: ''));?>&nbsp;</label>
    <label style='float: left;width: 48%'><span>Motorista: </span><?php echo @$row['Motorista']['nome']; ?>&nbsp;</label>
    <label style='float: right;width: 48%'><span>Nota: </span><?php echo @$row['ItemPedido']['Pedido']['ItemNota'][0]['Nota']['numero']; ?>&nbsp;</label>
    <label style='float: left;width: 33%'><span>Material: </span><?php echo @$row['ItemPedido']['Produto']['nome']; ?>&nbsp;</label>
    <label style='float: left;width: 160px'><span style='min-width: 30px'>Qtde <?php echo @$unidade[$row['ItemPedido']['unidade']]; ?>: </span>______________</label>
    <label style='float: right;width: 215px'>
	<span style='min-width: 30px'>Qtde  <?php echo @$unidade[$row['ItemPedido']['unidade']].'(Entregue)'; ?>: </span>______________</label>
    <div style='float: left;margin-left: 30%;width: 33%;height:44px;'></div>
    <div style='float: left;margin-left: 30%;width: 33%;'><hr></div>
    <div style='float: left;margin-left: 30%;width: 33%;text-align: center'><small>Assinatura</small></div>
</fieldset>
<?php #var_dump($row);?>