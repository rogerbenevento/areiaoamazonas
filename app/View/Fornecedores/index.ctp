<p class="lead">FORNECEDORES</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'fornecedores', 'action' => 'add', $this->params['prefix'] => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'Fornecedor.cnpj', 'Cnpj' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Fornecedor.nome', 'Nome' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Fornecedor.telefone', 'Telefone' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Fornecedor.email', 'Email' ) ?></th>
			<th><?php if($this->Session->read('Auth.User.nivel')=='admin')echo $this->Paginator->sort( 'Fornecedor.saldo', 'Saldo' ) ?>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="Fornecedor.cnpj" value="<?php echo ( !empty( $this->params->named['Fornecedor_cnpj'] ) ? $this->params->named['Fornecedor_cnpj'] : '' ) ?>" /></td>
			<td><input type="text" name="Fornecedor.nome" value="<?php echo ( !empty( $this->params->named['Fornecedor_nome'] ) ? $this->params->named['Fornecedor_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Fornecedor.telefone" class="span1" value="<?php echo ( !empty( $this->params->named['Fornecedor_telefone'] ) ? $this->params->named['Fornecedor_telefone'] : '' ) ?>" /></td>
			<td><input type="text" name="Fornecedor.email" class="span1" value="<?php echo ( !empty( $this->params->named['Fornecedor_email'] ) ? $this->params->named['Fornecedor_email'] : '' ) ?>" /></td>
			<td></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Fornecedor']['cnpj'] ?></td>
					<td><?php echo $row['Fornecedor']['nome'] ?></td>
					<td><?php echo $row['Fornecedor']['telefone'] ?></td>
					<td><?php echo $row['Fornecedor']['email'] ?></td>
					<td><?php if($this->Session->read('Auth.User.nivel')=='admin') echo moedaBr($row['Fornecedor']['saldo'])?></td>
					<td style="text-align: center;">
						<?php if($this->Session->read('Auth.User.nivel')=='admin'){
						
						 echo $this->Html->image( 'icones/desconto.png', array( 'url' => array( 'controller' => 'fornecedores', 'action' => 'precos', $row['Fornecedor']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ,'class'=>'btn') );
						}?>
						<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'fornecedores', 'action' => 'edit', $row['Fornecedor']['id'], $this->params['prefix'] => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ,'class'=>'btn') ) ?>
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