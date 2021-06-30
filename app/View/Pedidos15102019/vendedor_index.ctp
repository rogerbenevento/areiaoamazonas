<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui','relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui.min','jquery.price_format.min', 'pedidos/index','vales/index'), array('inline' => false)) ?>
<p class="lead">Pedidos</p>
<p>
	<a href="<?php echo Router::url(array('controller' => 'pedidos', 'action' => 'add', $this->Session->read('Auth.User.nivel') => true)) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('id', 'Nº') ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Data') ?></th>
			<th><?php echo $this->Paginator->sort('Cliente.nome', 'Cliente') ?></th>
			<th><?php echo $this->Paginator->sort('data_entrega') ?></th>
			<th><?php echo $this->Paginator->sort('status') ?></th>

			<th style="text-align: center !important; width: 12% !important;"></th>
		</tr>

		<tr>
			<?php echo $this->Form->create(null, array('type' => 'get')) ?>
			<td><input type="text" name="Pedido.id" value="<?php echo (!empty($this->params->named['Pedido_id']) ? $this->params->named['Pedido_id'] : '' ) ?>" /></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<select class="span1" name="Pedido.status"> 
					<option value="" >Todos</option>
					<option value="0" >Aberto</option>
					<option value="1" >Finalizado</option>
					<option value="2" >Cancelado</option>
				</select>
			</td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>


	</thead>
	<tbody>
		<?php if ($total): ?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?php echo $row['Pedido']['id'] ?></td>
					<td><?php echo $this->Time->format('d/m/Y', $row['Pedido']['created']) ?></td>
					<td><?php echo $row['Cliente']['nome'] ?></td>
					<td><?php echo!empty($row['Pedido']['data_entrega']) ? $this->Time->format('d/m/Y H:i', $row['Pedido']['data_entrega']) : 'Não' ?></td>
					<td><?php echo $status[$row['Pedido']['status']] ?></td>
					<td style="text-align: center !important;">
						<div class="btn-group">
						<?php echo $this->Html->image('icones/view.png', array('url' => array('action' => '#'), 'id' => $row['Pedido']['id'], 'width' => 18, 'title' => 'Detalhes do Pedido', 'class' => 'btn pedido-view')) ?>
						<a href="<?php echo $this->Html->url(array('controller'=>'vales','action'=>'index',$row['Pedido']['id']));?>" title="Vales" class="btn">
							<?php echo $this->Html->image('icones/quantidades.png', array( 'width'=>16)) ?>
						</a>
						<?php if (!$row['Pedido']['status']): ?>
							<?php echo $this->Html->image('icones/edit.png', array('url' => array('action' => 'edit', $row['Pedido']['id']), 'width' => 18,'class'=>'btn', 'title' => 'Editar pedido')) ?>
						<?php endif; ?>
							</div>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><strong>No momento não há registros cadastrados.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><strong>Total de registros cadastrados: <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element('paginacao', array('url' => $this->params->query)) ?>
<div id="frmPedido" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Detalhes do Pedido</h3>
	</div>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>
<div id="modalCopiar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Copiar Pedido</h3>
	</div>
	<div class="modal-body">
		<?php 
			echo $this->Form->create('Pedido',array('id'=>'frmCopiar','url'=>array('action'=>'copiar') ));
			echo $this->Form->input( 'id' );		
			echo $this->Form->input( 'quantidade', array('label'=>'Nº de Cópias', "class" => "span4",'value'=>1 ) );		
			echo $this->Form->submit( 'COPIAR',array('class'=>'btn btn-primary btn-block btn-large') );
			echo $this->Form->end();
		?>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>
<div id="frmLoja" class="dialog-content" data-toggle="modal"></div>
<!-- Modal -->
<?php 
	echo $this->element('modal_cancelar_pedido');
	
	if(!empty($_SESSION['imprimir_vale'])){
		echo $this->element('modal-imprimir-vale');
		
//		switch( $_SESSION['imprimir_vale']['ItemPedido']['Pedido']['Cliente']['empresa_id']):
//			case 1:
//				$layout='a';
//				break;
//			default:
//				$layout='s';
//				break;
//		endswitch;
		$layout = $_SESSION['imprimir_vale']['ItemPedido']['Pedido']['Cliente']['empresa_id'];
		echo "<script>$(function(){
				$('#print_id').val('{$_SESSION['imprimir_vale']['Vale']['id']}');
				$('#layout').val('{$layout}');
				$('#myModal').modal('show');					
				
			})</script>";
		//pr($_SESSION['imprimir_vale']);
		unset($_SESSION['imprimir_vale']);
	}
	
?>