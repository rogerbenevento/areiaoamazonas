<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui','relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui.min', 'pedidos/index'), array('inline' => false)) ?>

<p class="lead">PEDIDOS</p>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'id', 'Nº' ) ?></th>			
			<th><?php echo $this->Paginator->sort( 'Cliente.nome', 'Cliente' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'Cliente.nome', 'CPF/CNPJ' ) ?></th>
			<th><?php echo $this->Paginator->sort( 'created', 'Data' ) ?></th>
			

			<th style="text-align: center !important; width: 8% !important;"></th>
		</tr>

		<tr>
			<?php echo $this->Form->create( null, array( 'type' => 'get' ) ) ?>
			<td><input class="span1" type="text" name="Pedido.id" value="<?php echo ( !empty( $this->params->named['Pedido_id'] ) ? $this->params->named['Pedido_id'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo ( !empty( $this->params->named['Cliente_nome'] ) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
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
					<td><?php echo $row['Pedido']['id'] ?></td>
					<td><?php echo $row['Cliente']['nome'] ?></td>
					<td><?php echo $row['Cliente']['cpf_cnpj'] ?></td>
					<td><?php echo $this->Time->format('d/m/Y', $row['Pedido']['created']) ?></td>
                                        <td style="text-align: center !important;">
                                            <div class="btn-group">
                                                <?php echo $this->Html->image('icones/check.png', array('url'=>array('action'=>'finalizar', $row['Pedido']['id']), 'width'=>18, 'title'=>'FINALIZAR PEDIDO','class'=>'pedido-view' ,'class'=> 'btn btn-small',"width" =>13)) ?>
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
<div id="frmPedido" class="dialog-content"></div>
<div id="frmLoja" class="dialog-content"></div>