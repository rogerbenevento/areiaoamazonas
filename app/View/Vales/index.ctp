<?php echo $this->Html->script(array('vales/index'), array('inline' => false)) ?>
<p class="lead">
	<?php echo $this->Html->link( 'PEDIDOS', array( 'controller' => 'pedidos', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: VALES ( Pedido <?php echo ($this->request->pass[0])?> )
</p>
<?php #pr($rows);?>
<table class="table table-striped">
	<thead>
		<tr>
			<!--<th><?php echo $this->Paginator->sort( 'Vale.id', 'Código' ) ?></th>-->
			<th><?php echo $this->Paginator->sort( 'ItemPedido.pedido_id', 'Pedido' ) ?></th>			
			<th><?php echo $this->Paginator->sort( 'ItemPedido.Pedido.Cliente.nome', 'Cliente' ) ?></th>			
			<th><?php echo $this->Paginator->sort( 'ItemPedido.id', 'Item do Pedido' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'ItemPedido.Pedido.data_entrega', 'Data Entrega' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Motorista.nome', 'Motorista' ) ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<!--<td><input class="span1" type="text" name="Vale.id" value="<?php echo ( !empty( $this->params->named['Vale_id'] ) ? $this->params->named['Vale_id'] : '' ) ?>" /></td>-->
			<td><input class="span1" type="text" name="ItemPedido.pedido_id" value="<?php echo ( !empty( $this->params->named['ItemPedido_pedido_id'] ) ? $this->params->named['ItemPedido_pedido_id'] : '' ) ?>" /></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<!--<td><?php echo $row['Vale']['id'] ?></td>-->					
					<td><?php echo $row['ItemPedido']['pedido_id']?></td>
					<td><?php echo $row['ItemPedido']['Pedido']['Cliente']['nome']?></td>
					<td><?php echo $row['ItemPedido']['Produto']['nome']?></td>
					<td><?php echo $row['ItemPedido']['Pedido']['data_entrega']?></td>
					<td><?php echo $row['Motorista']['nome']?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<?php if($row['Vale']['status']==0)echo $this->Html->image( 'icones/check.png', array( 'url' => array( 'controller' => 'vales', 'action' => 'finalizar', $row['Vale']['id'] ),'onclick'=>'if(!confirm("Deseja realmente finalizar este vale?"))return false;', 'border' => '0', 'title' => strtoupper('Confirmar Vale'), 'width' => 18,'class'=>'btn' ) ) ?>
							<?php 
//							switch( $row['ItemPedido']['Pedido']['Cliente']['empresa_id']):
//								case 1:
//									$layout='a';
//									break;
//								default:
//									$layout='s';
//									break;
//							endswitch;
							//$layout=$row['ItemPedido']['Pedido']['Cliente']['empresa_id'];

							$layout=$row['ItemPedido']['Pedido']['Cliente']['empresa_id'];
							if($row['Vale']['status']<2)
								echo $this->Html->image( 'icones/print.png', array( 'url' => '','class'=>'btn btnimprimir-modal','vale_id'=>$row['Vale']['id'],'layout'=>$layout, 'border' => '0', 'title' => strtoupper('Imprimir registro'), 'width' => 18 ) )
							?>
						</div>
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
<!-- Modal -->
<?php echo $this->element('modal-imprimir-vale');?>