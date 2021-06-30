<table class="table table-striped table-bordered table-condensed grid">
	<thead>
		<tr>
			<th></th>
			<th>Cliente</th>
			<th>Obra</th>
			<th>Pedido</th>
			<th>Material</th>
			<th>Quantidade</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($this->Session->check('pedidos')):
				foreach ($this->Session->read('pedidos') as $pedido): ?>
				<tr class="<?php echo $pedido['indice'] ?>">
					<td class="span2">
						<div  style="text-align: center">
							<a href="#" class="btn remover" title="REMOVER"><i class="icon-trash"></i></a>
						</div>
					</td>
					<td><?php echo $pedido['cliente'] ?></td>
					<td >
						<span class="qtde"><?php echo $pedido['obra']; ?></span>                                            
					</td>
					<td><?php echo $pedido['pedido_id'] ?></td>
					<td><?php echo $pedido['material'] ?></td>
					<td><?php echo $pedido['quantidade'] ?></td>
				</tr>				
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>