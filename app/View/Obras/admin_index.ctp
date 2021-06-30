<p class="lead">
	<?php echo $this->Html->link('CLIENTES', array('controller' => 'clientes', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true)) ?> 
	:: Obras (<?php echo substr(utf8_encode($cliente['Cliente']['nome']), 0, 25) ?>)
</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'obras', 'action' => 'add',$cliente['Cliente']['id'], $this->params['prefix'] => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('Obra.nome', 'Nome') ?></th>
			<th><?php echo $this->Paginator->sort('Obra.endereco', 'Endereço') ?></th>
			<th><?php echo $this->Paginator->sort('Obras.bairro', 'Bairro') ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($total): #pr($rows);?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?php echo $row['Obra']['nome'] ?></td>
					<td><?php echo $row['Obra']['endereco'] ?></td>
					<td><?php echo $row['Obra']['bairro'] ?></td>
					<td style="text-align: center;">
					<?php echo $this->Html->image( 'icones/desconto.png', array( 'url' => array( 'controller' => 'obras', 'action' => 'precos/'.$row['Obra']['cliente_id'],$row['Obra']['id'], $this->Session->read('Auth.User.nivel') => true ), 'border' => '0', 'title' => 'Preços', 'width' => 16,'class'=>'btn') ) ?>
						

						<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'obras', 'action' => 'edit/'.$row['Obra']['cliente_id'],$row['Obra']['id'], $this->Session->read('Auth.User.nivel') => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16,'class'=>'btn') ) ?>
						<?php echo $this->Html->image( 'icones/delete.png', array( 'url' => array( 'controller' => 'obras', 'action' => 'del/'.$row['Obra']['cliente_id'],$row['Obra']['id'], $this->Session->read('Auth.User.nivel') => true ), 'border' => '0', 'title' => 'Apagar registro', 'width' => 16,'class'=>'btn') ) ?>
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

<div class="form-actions">
	<?php echo $this->Html->link('Voltar', array('controller' => 'seguradoras', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true)) ?>
</div>
<?php echo $this->element('paginacao', array('url' => $this->params->query)) ?>