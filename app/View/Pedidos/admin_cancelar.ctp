<?php echo $this->Html->script(array('jquery-ui.min','jquery.price_format.min', 'pedidos/index'), array('inline' => false)) ?>

<?php 
	echo $this->Form->create('Pedido');
	echo $this->Form->input('motivo',array('class'=>'span3','required'=>true));
?>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th style="width:50px">Frete</th>
			<th>Status</th>
			<th>Vale</th>
			<th>Nome</th>
			<th>Qtde.</th>
			<th>Valor</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<?php $valorTotal = 0; #pr($pedido); ?>
		<?php foreach ($pedido['ItemPedido'] as $key=>$item): ?>
			<tr>
				<td>
					<?php
						if($item['Vale']['status']!=0){
							//
							// CALCULO DO FRETE
							//
							$porcentagem_freteiro=($pedido['Pedido']['freteiro']>0)?
									$pedido['Pedido']['freteiro']/100 
									: $porcentagem_frete;

							$total = $item['valor'] * $item['quantidade'];
							$lucro = $total - $item['pago'];	
							if($item['Vale']['motorista_tipo'] == 1){
								// motorista da Casa
								if($item['Vale']['periodo_id'] == 1)
									$frete=$preco_diurno;
								else
									$frete=$preco_noturno;
							}else{
								// freteiro
								$frete= $lucro * $porcentagem_freteiro;
							}
							$frete = number_format($frete,2);
						}else{
							$frete=null;
						}
					?>
					<input type="hidden" name="data[ItemPedido][<?php echo $key ?>][id]" id="PedidoItemPedidoId" value="<?php echo $item['id'] ?>"/>
					<input type="hidden" name="data[ItemPedido][<?php echo $key ?>][pedido_id]" id="PedidoItemPedidoId" value="<?php echo $item['pedido_id'] ?>"/>
					<input type="text" class="span1 frete" name="data[ItemPedido][<?php echo $key ?>][frete]" id="PedidoItemPedidoFrete" value="<?php echo $frete ?>" required="required"/>

				</td>
				<td><?php echo $status[$item['Vale']['status']]; ?></td>
				<td><?php echo $item['Vale']['id']; ?></td>
				<td><?php echo $item['Produto']['nome']; ?></td>
				<td><?php echo $item['quantidade']; ?></td>
				<td><?php echo moedaBr($item['valor']); ?></td>					
				<td><?php echo $item['valor_total']; ?></td>
			</tr>
			<?php $valorTotal += ($item['valor_total']); ?>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><strong>Valor total: </strong></td>
			<td><strong><?php echo moedaBr($valorTotal ); ?></strong></td>
		</tr>
	</tfoot>
</table>
<?php
	echo $this->Form->submit('CANCELAR PEDIDO',array('class'=>'btn btn-primary'));
	echo $this->Form->end() ;
?>