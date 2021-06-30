<p class="lead">MOTORISTAS INATIVOS</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Motorista.cpf_cnpj', 'Cnpj' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.nome', 'Nome' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.cnh', 'Nome' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.telefone', 'Telefone' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.email', 'Email' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Motorista.cpf_cnpj" value="<?php echo ( !empty( $this->params->named['Motorista_cpf_cnpj'] ) ? $this->params->named['Motorista_cpf_cnpj'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.nome" value="<?php echo ( !empty( $this->params->named['Motorista_nome'] ) ? $this->params->named['Motorista_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.cnh" value="<?php echo ( !empty( $this->params->named['Motorista_cnh'] ) ? $this->params->named['Motorista_cnh'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.telefone" class="span1" value="<?php echo ( !empty( $this->params->named['Motorista_telefone'] ) ? $this->params->named['Motorista_telefone'] : '' ) ?>" /></td>
			<td><input type="text" name="Motorista.email" class="span1" value="<?php echo ( !empty( $this->params->named['Motorista_email'] ) ? $this->params->named['Motorista_email'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Motorista']['cpf_cnpj'] ?></td>
					<td><?php echo $row['Motorista']['nome'] ?></td>
					<td><?php echo $row['Motorista']['cnh'] ?></td>
					<td><?php echo $row['Motorista']['telefone'] ?></td>
					<td><?php echo $row['Motorista']['email'] ?></td>
					<td style="text-align: center;">
						<?php echo $this->Html->image( 'icones/like.png', array( 'url' => array( 'controller' => 'motoristas', 'action' => 'ativar', $row['Motorista']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Ativar registro', 'width' => 16 ) ) ?>
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