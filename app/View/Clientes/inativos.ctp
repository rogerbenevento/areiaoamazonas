<p class="lead">CLIENTES INATIVOS</p>
<p>
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'clientes', 'action' => 'add', $this->Session->read('Auth.User.nivel') => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'cpf_cnpj','CPF/CNPJ' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'nome' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'telefone' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'email' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Cliente.cpf" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.contato" value="<?php echo ( !empty( $this->params->named['Cliente_contato'] ) ? $this->params->named['Cliente_contato'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.telefone" class="span1" value="<?php echo ( !empty( $this->params->named['Cliente_telefone'] ) ? $this->params->named['Cliente_telefone'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Cliente']['cpf_cnpj'] ?></td>
					<td><?php echo $row['Cliente']['nome'] ?></td>
					<td><?php echo $row['Cliente']['telefone'] ?></td>
					<td><?php echo $row['Cliente']['email'] ?></td>
					<td style="text-align: center;">
						//<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'clientes', 'action' => 'edit', $row['Cliente']['id'], $this->Session->read('Auth.User.nivel') => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ) ) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="4"><strong>N??o h?? registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><strong>Total de registros <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>