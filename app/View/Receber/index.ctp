<p class="lead">CONTAS A RECEBER</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'receber', 'action' => 'add', $this->params['prefix'] => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Receber.titulo', 'Titulo' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Receber.valor', 'Valor' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Receber.data_vencimento', 'Nome' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Receber.cpf_cnpj" value="<?php echo ( !empty( $this->params->named['Receber_cpf_cnpj'] ) ? $this->params->named['Receber_cpf_cnpj'] : '' ) ?>" /></td>
			<td><input type="text" name="Receber.nome" value="<?php echo ( !empty( $this->params->named['Receber_nome'] ) ? $this->params->named['Receber_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Receber.data_vencimento" value="<?php echo ( !empty( $this->params->named['Receber_data_vencimento'] ) ? $this->params->named['Receber_data_vencimento'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Receber']['titulo'] ?></td>
					<td><?php echo moedaBr($row['Receber']['valor']) ?></td>					
					<td><?php echo $row['Receber']['data_vencimento'] ?></td>
					<td style="text-align: center;">
						<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'receber', 'action' => 'edit', $row['Receber']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16,'class'=>'btn' ) ) ?>
						<?php echo $this->Html->image( 'icones/delete.png', array( 'url' => array( 'controller' => 'receber', 'action' => 'edit', $row['Receber']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16,'class'=>'btn' ) ) ?>
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