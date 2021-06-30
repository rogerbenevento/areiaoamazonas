<?php if ( count( $itens ) ): ?>
	<h3 style="text-align: left;">Itens do Pedido</h3>
	<table style="width: 100%;" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Produto</th>
				<th>Quantidade</th>
				<th>Valor Unit.</th>
				<th>Desconto</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $itens as $item ): ?>
			<?php 
				//Se comprou mais de 1 produto, irá mostrar 1 por linha
				for ( $i = 1; $i <= $item['ItemPedido']['quantidade']; $i++) { ?>
					<tr class="<?php echo $item['ItemPedido']['id'] ?>">
						<td style="text-align: center"><input type="checkbox" name="itens[]" id="Item<?php echo $item['ItemPedido']['id'] ?>" value="<?php echo $item['ItemPedido']['id'] ?>" /></td>
						<td><label for="Item<?php echo $item['ItemPedido']['id'] ?>"><?php echo $item['Produto']['codigo'] ?> - <?php echo $item['Produto']['nome'] ?></label></td>
						<td><label for="Item<?php echo $item['ItemPedido']['id'] ?>">1</label></td>
						<td><label for="Item<?php echo $item['ItemPedido']['id'] ?>"><?php echo $model->moedaBr( $item['ItemPedido']['valor_unitario'] ) ?></label></td>
                                                <td><label for="Item<?php echo $item['ItemPedido']['id'] ?>"><?php echo number_format( $item['ItemPedido']['desconto'] ) ?>%</label></td>
					</tr>
			<?php } endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php echo 'false'; ?>
<?php endif; ?>