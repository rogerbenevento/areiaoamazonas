<?php if ( count( $pedidos ) > 0 ): ?>
	<table style="width: 100%;" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Cliente</th>
				<th>Obra</th>
				<th>Pedido</th>
				<th>Material</th>
				<th>Quantidade</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $pedidos as $pedido ): ?>
			<?php ?>
					<tr class="<?php echo $pedido['Pedido']['id'] ?>">
						<td style="text-align: center"><input type="button" name="btnPedido<?php echo $pedido['Pedido']['id'] ?>" rel="<?php echo $pedido['Pedido']['id'] ?>" id="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="btn btn-primary btn-pedido-add" value="Inserir"/></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="cliente"><?php echo $pedido['Cliente']['nome'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="obra"><?php echo $pedido['Obra']['nome'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="pedido"><?php echo $pedido['Pedido']['id'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="material"><?php echo $pedido['ItemPedido'][0]['Produto']['nome'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="quantidade"><?php echo $pedido['ItemPedido'][0]['quantidade'].$unidades[$pedido['ItemPedido'][0]['unidade']] ?></label></td>
					</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>