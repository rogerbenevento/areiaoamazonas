<p class="lead">PRODUTOS</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'produtos', 'action' => 'add', 'admin' => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'codigo' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'nome' ) ?></th>
			<th style="width: 10%">&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td>&nbsp;</td>
			<td><input type="text" name="Produto.nome" value="<?php echo ( !empty( $this->params->named['Produto_nome'] ) ? $this->params->named['Produto_nome'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Produto']['codigo'] ?></td>
					<td><?php echo $row['Produto']['nome'] ?></td>
					<td style="text-align: center;">
						<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'produtos', 'action' => 'edit', $row['Produto']['id'], 'admin' => true ), 'border' => '0', 'title' => strtoupper('Editar registro'), 'width' => 16,'class'=>'btn' ) ) ?>
						<?php echo $this->Html->image('icones/delete.png', array('url'=>array('action'=>'del', $row['Produto']['id']), 'width'=>'18', 'title'=>strtoupper('Remover produto'), 'onclick'=>"return confirm('Deseja realmente remover?')",'class'=>'btn')) ?>
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
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>