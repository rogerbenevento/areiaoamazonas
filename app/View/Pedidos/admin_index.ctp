<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui','relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui.min','jquery.price_format.min', 'pedidos/index','vales/index'), array('inline' => false)) ?>
<p class="lead">PEDIDOS</p>
<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'pedidos', 'action' => 'add', $this->Session->read('Auth.User.nivel') => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'id', 'Nº' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Cliente.nome', 'Cliente' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'data_entrega', 'Data' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'status' ) ?></th>
			<th style="text-align: center !important; width: 15% !important;"></th>
		</tr>

		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input class="span1" type="text" name="Pedido.id" value="<?php echo ( !empty( $this->params->named['Pedido_id'] ) ? $this->params->named['Pedido_id'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td></td>
			<td>
				<select style="width: 100px;" name="Pedido.status"> 
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
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['Pedido']['id'] ?></td>
					<td><?php echo $row['Cliente']['nome'] ?></td>
					<td><?php echo $row['Pedido']['data_entrega']; ?></td>
					<td><?php echo $status[$row['Pedido']['status']] ?></td>
					<td style="text-align: center !important; min-width:300px">
						<div class="btn-group">
							<?php if (!$row['Pedido']['status']): ?>
								<a href="<?php echo $this->Html->url(array('controller'=>'pedidos','action'=>'finalizar',$row['Pedido']['id']))?>" title='Finalizar Pedido' class='btn' >
									<?php echo $this->Html->image('icones/check.png', array('width'=>16)) ?>
								</a>
								<a href="<?php echo $this->Html->url(array('controller'=>'pedidos','action'=>'edit',$row['Pedido']['id']))?>" title='Editar' class="btn">
										<?php echo $this->Html->image('icones/edit.png', array('width'=>16)) ?>
								</a>
							<?php else: ?>
								<a href="<?php echo $this->Html->url(array('controller'=>'pedidos','action'=>'edit_finalizado',$row['Pedido']['id']))?>" title='Editar' class="btn">
									<i class="icon-pencil"></i>
								</a>							
							<?php endif; ?>
							
							<a href="#" id="<?php echo $row['Pedido']['id'];?>"  title='Gerar Copia do Pedido' class='btn pedido-copiar' >
								<i class="icon-share"></i>
							</a>
							<a href="<?php echo $this->Html->url(array('controller'=>'vales','action'=>'index',$row['Pedido']['id']));?>" title="Vales" class="btn">
								<?php echo $this->Html->image('icones/quantidades.png', array( 'width'=>16)) ?>
							</a>
							<a href="#" id="<?php echo $row['Pedido']['id'];?>" title="Detalhes do Pedido" class="btn pedido-view">
								<?php echo $this->Html->image('icones/view.png', array( 'width'=>16)) ?>
							</a>
							<?php if ( $this->Session->read('Auth.User.nivel') == 'admin' ): ?>
							    <?php if($row['Pedido']['status']!=2){ ?>
									<a href="<?php echo $this->Html->url(array('action'=>'alterar_data', $row['Pedido']['id']));?>" title="Editar a data do pedido" class="btn">
										<?php echo $this->Html->image('icones/data.png', array( 'width'=>16)) ?>
									</a>
								<?php } ?>
							    <?php if (!in_array($row['Pedido']['status'],array(2,1))): ?>
									<a data-target="#myModal" href="<?php echo $this->Html->url(array('action'=>'cancelar',$row['Pedido']['id']));?>" data-toggle="modal" id="<?php echo $row['Pedido']['id']; ?>"  title='Cancelar Pedido' class="btn cancelarPedido">
										<?php echo $this->Html->image('icones/delete.png', array('width'=>16)) ?>
									</a>
							    <?php endif; ?>
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
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>
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
		#pr($_SESSION['imprimir_vale']);
		
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
		unset($_SESSION['imprimir_vale']);
	}
	
?>