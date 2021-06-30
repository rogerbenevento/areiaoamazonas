<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array( 'jquery-ui.min','jquery.mask','jquery.price_format.min',  'multas/index'), array('inline' => false)) ?>
<p class="lead">MULTAS</p>
<p>
	<a href="<?php echo Router::url( array( 'controller' => 'multas', 'action' => 'edit' ) ) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
</p>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Multa.id', 'Nº' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Multa.data', 'Data' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Multa.motorista_id', 'Motorista' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Multa.placa', 'Placa' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Multa.valor', 'Valor' ) ?></th>
			<th></th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><?php echo $this->Form->input('Multa.id',array('name'=>'Multa.id','label'=>false,'div'=>false,'type'=>'text','class'=>'span1','value'=>@$this->params->named['Multa_id'])); ?></td>
			<td><?php echo $this->Form->input('Multa.data',array('name'=>'Multa.data','label'=>false,'div'=>false,'type'=>'text','class'=>'span2','value'=>@$this->params->named['Multa_data'])); ?></td>
			<td><?php echo $this->Form->input('Multa.motorista_id',array('name'=>'Multa.motorista_id','empty'=>'[ Todos ]','label'=>false,'div'=>false,'options'=>$motoristas,'selected'=>@$this->params->named['Multa_motorista_id'])) ?></td>
			<td><?php echo $this->Form->input('Multa.placa',array('name'=>'Multa.placa','class'=>'span1','label'=>false,'div'=>false,'value'=>@$this->params->named['Multa_placa'])) ?></td>
			<td></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end(); ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Multa']['id'] ?></td>
					<td><?php echo $this->Time->format('d/m/Y', $row['Multa']['data']) ?></td>
					<td><?php echo $row['Motorista']['nome'] ?></td>
					<td><?php echo $row['Multa']['placa'] ?></td>
					<td><?php echo moedaBr($row['Multa']['valor'])?></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="5"><strong>No momento não há registros cadastrados.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong>Total de registros cadastrados: <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>