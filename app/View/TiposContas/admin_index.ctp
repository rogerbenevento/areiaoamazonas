<p class="lead">Tipos de Contas</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'tipos_contas', 'action' => 'add' ) ) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
	<br /><br />
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'TipoConta.tipo', 'Tipo' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'TipoConta.nome', 'Descrição' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><?php echo $this->Form->input('TipoConta.tipo',array('name'=>'TipoConta.tipo','label'=>false,'div'=>false,"empty"=>'[Todos os Tipos ]','options'=>$tipos,'selected'=>@$this->params->named['TipoConta_tipo'])) ?></td>
			<td><input type="text" name="TipoConta.nome" class="span1" value="<?php echo ( !empty( $this->params->named['TipoConta_nome'] ) ? $this->params->named['TipoConta_nome'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end(); ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $tipos[$row['TipoConta']['tipo']] ?></td>
					<td><?php echo $row['TipoConta']['nome'] ?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<a href="<?php echo $this->Html->url(array( 'controller' => 'tipos_contas', 'action' => 'edit', $row['TipoConta']['id'] ))?>" title="Editar" class="btn">
								<?php echo $this->Html->image( 'icones/edit.png', array( 'border' => '0','width' => 16 ) ) ?>						
							</a>
<!--							<a href="<?php echo $this->Html->url(array( 'controller' => 'sub_tipos_contas', 'action' => 'index', $row['TipoConta']['id'] ))?>" title="SubTipos" class="btn">
								<div class="icon-list-alt"></div>
							</a>-->
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
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>