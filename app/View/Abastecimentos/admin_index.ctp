<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array( 'jquery-ui.min','jquery.mask','jquery.price_format.min',  'abastecimentos/index'), array('inline' => false)) ?>
<p class="lead">ABASTECIMENTOS</p>
<p>
	<a href="<?php echo Router::url( array( 'controller' => 'abastecimentos', 'action' => 'edit', $this->Session->read('Auth.User.nivel') => true ) ) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
</p>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Abastecimento.id', 'Nº' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Abastecimento.data', 'Data' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Abastecimento.motorista_id', 'Motorista' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Abastecimento.placa', 'Placa' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Abastecimento.valor', 'Valor' ) ?></th>
			<th></th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><?php echo $this->Form->input('Abastecimento.id',array('name'=>'Abastecimento.id','label'=>false,'div'=>false,'type'=>'text','class'=>'span1','value'=>@$this->params->named['Abastecimento_id'])); ?></td>
			<td><?php echo $this->Form->input('Abastecimento.data',array('name'=>'Abastecimento.data','label'=>false,'div'=>false,'type'=>'text','class'=>'span2','value'=>@$this->params->named['Abastecimento_data'])); ?></td>
			<td><?php echo $this->Form->input('Abastecimento.motorista_id',array('name'=>'Abastecimento.motorista_id','empty'=>'[ Todos ]','label'=>false,'div'=>false,'options'=>$motoristas,'selected'=>@$this->params->named['Abastecimento_motorista_id'])) ?></td>
			<td><?php echo $this->Form->input('Abastecimento.placa',array('name'=>'Abastecimento.placa','class'=>'span1','label'=>false,'div'=>false,'value'=>@$this->params->named['Abastecimento_placa'])) ?></td>
			<td></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end(); ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Abastecimento']['id'] ?></td>
					<td><?php echo $row['Abastecimento']['data']?></td>
					<td><?php echo $row['Motorista']['nome'] ?></td>
					<td><?php echo $row['Abastecimento']['placa'] ?></td>
					<td><?php echo moedaBr($row['Abastecimento']['valor'])?></td>
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