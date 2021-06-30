<p class="lead">Listagem de Categorias</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'categorias', 'action' => 'add', 'admin' => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Categoria.id', 'Id' ) ?></th>
                        <td><?php echo $this->Paginator->sort( 'Categoria.nome', 'Descrição' ) ?></td>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td></td>
			<td><input type="text" name="Categoria.nome" class="span1" value="<?php echo ( !empty( $this->params->named['Categoria_nome'] ) ? $this->params->named['Categoria_nome'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Categoria']['id'] ?></td>
					<td><?php echo $row['Categoria']['nome'] ?></td>
					<td style="text-align: center;">
						<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'categorias', 'action' => 'edit', $row['Categoria']['id'], 'admin' => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ) ) ?>
						<?php echo $this->Html->image( 'icones/delete.png', array( 'url' => array( 'controller' => 'categorias', 'action' => 'del', $row['Categoria']['id'], 'admin' => true ), 'border' => '0', 'title' => 'Apagar registro', 'width' => 16 ) ) ?>
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