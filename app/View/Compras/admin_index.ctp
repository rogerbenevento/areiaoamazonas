<p class="lead">COMPRAS</p>

<p>
	<a href="<?php echo Router::url( array( 'controller' => 'compras', 'action' => 'emitir', 'admin' => true ) ) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Data</th>
			<th>Fornecedor</th>
			<th>Valor</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Compra']['id'] ?></td>
					<td><?php echo $this->Time->format('d/m/Y', $row['Compra']['created']) ?></td>
					<td><?php echo $row['Fornecedor']['nome'] ?></td>
					<td><?php echo moedaBr($row['Compra']['valor'])?></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="4"><strong>No momento não há registros cadastrados.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><strong>Total de registros cadastrados: <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>