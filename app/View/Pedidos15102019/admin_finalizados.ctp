<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui','relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui.min','jquery.price_format.min', 'pedidos/index','vales/index'), array('inline' => false)) ?>
<p class="lead">PEDIDOS FINALIZADOS</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'id', 'Nº' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Cliente.nome', 'Cliente' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'data_entrega', 'Data' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'status' ) ?></th>

			<th style="text-align: center !important; width: 8% !important;"></th>
		</tr>

		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input class="span1" type="text" name="Pedido.id" value="<?php echo ( !empty( $this->params->named['Pedido_id'] ) ? $this->params->named['Pedido_id'] : '' ) ?>" /></td>
			<td></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td></td>
			<td>
				<select class="" name="Pedido.status"> 
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
					<td><?php echo $this->Time->format('d/m/Y', $row['Pedido']['created']) ?></td>
                                        <td>
                                            <?php echo $status[$row['Pedido']['status']] ?></td>
					<td style="text-align: center !important;">
                                            <div class="span2 btn-group">
                                                <?php echo $this->Html->image('icones/print.png', array( 'url'=>array('action'=>'#'),'border' => '0', 'title' => 'Imprimir Cupom','id'=> $row['Pedido']['id'], 'width'=>16,'alt' => 'Imprimir','class'=>'btn pedido-print' )) ?>
                                                <?php echo $this->Html->image('icones/view.png', array('url'=>array('action'=>'#'),'id'=> $row['Pedido']['id'], 'width'=>16, 'title'=>'Detalhes do Pedido','class'=>'btn pedido-view')) ?>


                                                <?php if ( $this->Session->read('Auth.User.nivel') == 'admin' ): ?>
                                                    <?php if ($row['Pedido']['status']!= 2 && $row['Pedido']['status']!= 1): ?>
                                                        <?php echo $this->Html->image('icones/delete.png', array('url'=>array('action'=>'#'),'id'=> $row['Pedido']['id'], 'title'=>'Cancelar Pedido', 'width'=>16, 'class'=>"btn cancelarPedido")) ?>
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