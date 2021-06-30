<p class="lead">Listagem de pedidos</p>

<p>
	<a href="<?php echo Router::url( array( 'controller' => 'pedidos', 'action' => 'add', 'financeiro' => true ) ) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'id', 'Nº' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'created', 'Data' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Cliente.nome', 'Cliente' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'data_entrega' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'status' ) ?></th>

			<th style="text-align: center !important; width: 8% !important;"></th>
		</tr>

		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Pedido.id" value="<?php echo ( !empty( $this->params->named['Pedido_id'] ) ? $this->params->named['Pedido_id'] : '' ) ?>" /></td>
			<td></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td></td>
			<td>
				<select name="Pedido.status"> 
					<option value="" >Todos</option>
					<option value="0" >Aberto</option>
					<option value="1" >Finalizado</option>
					<option value="2" >Cancelado</option>
				</select>
			</td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>


	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Pedido']['id'] ?></td>
					<td><?php echo $this->Time->format('d/m/Y', $row['Pedido']['created']) ?></td>
					<td><?php echo $row['Cliente']['nome'] ?></td>
					<td><?php echo !empty($row['Pedido']['data_entrega']) ? $this->Time->format('d/m/Y H:i', $row['Pedido']['data_entrega']) : 'Não' ?></td>
					<td><?php echo getStatusPedido($row['Pedido']['status']) ?></td>
					<td style="text-align: center !important;">
						<?php if (!$row['Pedido']['status']): ?>
							<?php echo $this->Html->image('icones/check.png', array('url'=>array('action'=>'finalizar', $row['Pedido']['id']), 'title'=>'Finalizar Pedido', 'width'=>16, 'onclick'=>"return confirm('Deseja realmente finalizar o pedido #{$row['Pedido']['id']}?')")) ?>
						
							<?php echo $this->Html->image('icones/delete.png', array('url'=>array('action'=>'cancelar', $row['Pedido']['id']), 'title'=>'Cancelar Pedido', 'width'=>16, 'onclick'=>"return confirm('Deseja realmente cancelar?')")) ?>

							<?php echo $this->Html->image('icones/edit.png', array('url'=>array('action'=>'edit', $row['Pedido']['id']), 'width'=>18, 'title'=>'Editar pedido')) ?>

						<?php endif; ?>
						<?php echo $this->Html->image('icones/view.png', array('url'=>array('action'=>'detalhes', $row['Pedido']['id']), 'width'=>18, 'title'=>'Detalhes do Pedido')) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><strong>No momento não há registros cadastrados.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><strong>Total de registros cadastrados: <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>