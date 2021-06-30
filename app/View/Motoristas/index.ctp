<p class="lead">MOTORISTAS</p>
<div class="actions" style="width: 100%;">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'motoristas', 'action' => 'add', $this->params['prefix'] => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>

	<?php //if($_SERVER['REMOTE_ADDR']=='201.46.19.114'):?>
		<a href="<?php echo Router::url( array( 'controller' => 'motoristas', 'action' => '/inativos', $this->params['prefix'] => true ) ) ?>" class="btn btn-danger" style="float: right;">Inativos</a>
		<?php //endif ?>
	<br /><br />
</div>







<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Motorista.nome', 'Nome' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.cnh', 'CNH' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.placa', 'Placa' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.telefone', 'Telefone' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Motorista.nome" value="<?php echo ( !empty( $this->params->named['Motorista_nome'] ) ? $this->params->named['Motorista_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.cnh" value="<?php echo ( !empty( $this->params->named['Motorista_cnh'] ) ? $this->params->named['Motorista_cnh'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.placa" class="span1" value="<?php echo ( !empty( $this->params->named['Motorista_placa'] ) ? $this->params->named['Motorista_placa'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.telefone" class="span1" value="<?php echo ( !empty( $this->params->named['Motorista_telefone'] ) ? $this->params->named['Motorista_telefone'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Motorista']['nome'] ?></td>					
					<td><?php echo $row['Motorista']['cnh'] ?></td>
					<td><?php echo $row['Motorista']['placa'] ?></td>					
					<td><?php echo $row['Motorista']['telefone'] ?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'motoristas', 'action' => 'edit', $row['Motorista']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16,'class'=>'btn' ) ) ?>

							<?php //if($_SERVER['REMOTE_ADDR']=='201.46.19.114'):?>
								<?php echo $this->Html->image( 'icones/dislike.png', array( 'url' => array( 'controller' => 'motoristas', 'action' => 'desativar', $row['Motorista']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Dixar inativo', 'width' => 16,'class'=>'btn' ) ) ?>
							<?php //endif; ?>

						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><strong>Não há registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><strong>Total de registros <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>