<script>
$(function(){
	$('.cliente-view').click(function(e){
		e.preventDefault();
		$.post(APP+'/'+$('#role').val()+'/clientes/view/'+$(this).attr('id'),{
			},function(resp){
				$('#frmCliente div.modal-body').html(resp);		  
				$('#frmCliente').modal("show");
			});
	});
})
</script>
<p class="lead">CLIENTES</p>
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
			<?php 
			$er = '/page:[0-9]*[\/]?/i';
			echo preg_replace($er, '', $this->Form->create(null, array('type' => 'get')) ); 
			?>
			<td><input type="text" name="Cliente.cpf_cnpj" value="<?php echo ( !empty( $this->params->named['Cliente_cpf_cnpj'] ) ? $this->params->named['Cliente_cpf_cnpj'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.telefone" value="<?php echo ( !empty( $this->params->named['Cliente_telefone'] ) ? $this->params->named['Cliente_telefone'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.email" class="span1" value="<?php echo ( !empty( $this->params->named['Cliente_email'] ) ? $this->params->named['Cliente_email'] : '' ) ?>" /></td>
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
					<td style="text-align: center;width: 15%">
						<div class="btn-group">
							<a href="<?php echo $this->Html->url(array('action'=>'#'))?>" id="<?php echo $row['Cliente']['id']?>" class="btn cliente-view">
								<?php echo $this->Html->image('icones/view.png', array('width'=>16, 'title'=>'Detalhes do Cliente')) ?>
							</a>
							<?php if($this->Session->read('Auth.User.nivel')!='programacao1'){ ?>
							<a href="<?php echo $this->Html->url(array( 'controller' => 'obras', 'action' => 'index', $row['Cliente']['id']));?>" class="btn" title='Ver Obras'>
								<?php echo $this->Html->image( 'icones/quantidades.png', array( 'border' => '0', 'width' => 16 ) ) ?>
							</a>
							<a href="<?php echo $this->Html->url(array( 'controller' => 'clientes', 'action' => 'edit', $row['Cliente']['id']));?>" class="btn" title='Editar registro'>
								<?php echo $this->Html->image( 'icones/edit.png', array( 'border' => '0', 'width' => 16 )); ?>
							</a>
							<?php }?>
							<?php if($this->Session->read('Auth.User.nivel')=='admin'){ ?>
								<a href="<?php echo $this->Html->url(array( 'controller' => 'clientes', 'action' => 'precos', $row['Cliente']['id']));?>" class="btn" title='Editar Preços'>
								<?php echo $this->Html->image( 'icones/desconto.png', array( 'border' => '0', 'width' => 16 )); ?>
							</a>
							<a href="<?php echo $this->Html->url(array( 'controller' => 'clientes', 'action' => 'del', $row['Cliente']['id']));?>" class="btn" title='Editar registro'>
								<?php echo $this->Html->image( 'icones/delete.png', array( 'border' => '0', 'width' => 16 )); ?>
							</a>
							<?php } ?>
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
<div id="frmCliente" class="dialog-content modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Cadastro do Cliente</h3>
	</div>
	<div class="modal-body"  style="width: 530px;"></div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fechar</button>
	</div>
</div>