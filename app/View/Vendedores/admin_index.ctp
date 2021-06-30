<p class="lead">VENDEDORES</p>
<p>
	<br />
	<a href="<?php echo Router::url(array('controller' => 'vendedores', 'action' => 'add', $this->params['prefix'] => true)) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('nome') ?></th>
			<th><?php echo $this->Paginator->sort('email', 'Email') ?></th>
			<th><?php echo $this->Paginator->sort('telefone') ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create(null, array('type' => 'get')) ?>
			<td><input type="text" name="Vendedor.nome" value="<?php echo (!empty($this->params->named['Vendedor_nome']) ? $this->params->named['Vendedor_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Vendedor.email" value="<?php echo (!empty($this->params->named['Vendedor_email']) ? $this->params->named['Vendedor_email'] : '' ) ?>" /></td>
			<td></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ($total): ?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?php echo $row['Vendedor']['nome'] ?></td>
					<td><?php echo $row['Vendedor']['email'] ?></td>
					<td><?php echo $row['Vendedor']['telefone'] ?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<?php echo $this->Html->image('icones/edit.png', array('url' => array('controller' => 'vendedores', 'action' => 'edit', $row['Vendedor']['id'], $this->params['prefix'] => true), 'border' => '0', 'title' => 'Editar registro', 'width' => 16,'class'=>'btn')) ?>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="5"><strong>Não há registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong>Total de registros <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element('paginacao', array('url' => $this->params->query)) ?>