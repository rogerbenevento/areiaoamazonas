<p class="lead">Listagem de Formas de Pagamentos</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url(array('action' => 'add')) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('nome') ?></th>
			<th><?php echo $this->Paginator->sort('parcelas') ?></th>
			<th><?php echo $this->Paginator->sort('compensacao', 'Compensação') ?></th>
			<th style="width: 10%">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($total): ?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?php echo $row['Pagamento']['nome'] ?></td>
					<td><?php echo $row['Pagamento']['parcelas'] ?></td>
					<td><?php echo $row['Pagamento']['compensacao'] ?></td>
					<td style="text-align: center;">
						<?php echo $this->Html->image('icones/edit.png', array('url' => array('action' => 'edit', $row['Pagamento']['id']), 'border' => '0', 'title' => 'Editar registro', 'width' => 16)) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="4"><strong>Não há registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><strong>Total de registros <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>