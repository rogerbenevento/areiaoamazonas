<p class="lead">USUÁRIOS</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'users', 'action' => 'add', 'admin' => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'User.nome', 'Nome' ) ?></th>
                        <td><?php echo $this->Paginator->sort( 'User.username', 'Login' ) ?></td>
			<td><?php echo $this->Paginator->sort( 'User.email', 'Email' ) ?></td>
			<td><?php echo $this->Paginator->sort( 'User.nivel', 'Nivel' ) ?></td>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input type="text" name="User.nome" value="<?php echo ( !empty( $this->params->named['User_nome'] ) ? $this->params->named['User_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="User.username" class="span1" value="<?php echo ( !empty( $this->params->named['User_username'] ) ? $this->params->named['User_username'] : '' ) ?>" /></td>
			<td><input type="text" name="User.email" class="span1" value="<?php echo ( !empty( $this->params->named['User_email'] ) ? $this->params->named['User_email'] : '' ) ?>" /></td>
			<td>
			    <select name="User.nivel_id"> 
				<option value="" >Todos</option>
				<option value='1'>Administrador</option>
				<option value='2'>Vendedor</option>
			    </select>
			</td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): if(substr_count($row['User']['username'], 'hoomweb')>0)continue;?>
				<tr>
					<td><?php echo $row['User']['nome'] ?></td>
					<td><?php echo $row['User']['username'] ?></td>
					<td><?php echo $row['User']['email'] ?></td>
					<td><?php echo $niveis[$row['User']['nivel_id']] ?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<a class="btn" href="<?php  echo $this->Html->url(array( 'controller' => 'users', 'action' => 'alterar_senha', $row['User']['id'], 'admin' => true )); ?>">
								<?php echo $this->Html->image( 'icones/password.png', array(  'border' => '0', 'title' => 'Alterar Senha', 'width' => 16 ) ) ?>
							</a>
							<a class="btn" href="<?php  echo $this->Html->url(array( 'controller' => 'users', 'action' => 'edit', $row['User']['id'], 'admin' => true )); ?>">
								<?php echo $this->Html->image( 'icones/edit.png', array(  'border' => '0', 'title' => 'Editar registro', 'width' => 16 ) ) ?>
							</a>
							<a class="btn" href="<?php  echo $this->Html->url(array( 'controller' => 'users', 'action' => 'del', $row['User']['id'], 'admin' => true )); ?>">
								<?php echo $this->Html->image( 'icones/delete.png', array( 'border' => '0', 'title' => 'Apagar registro', 'width' => 16 ) ) ?>
							</a>
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