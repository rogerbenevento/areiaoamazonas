<table class="table table-striped table-bordered table-condensed grid">
	<thead>
		<tr>
			<th></th>
			<th>Cliente</th>
			<th>Obra</th>
			<th>Pedido</th>
			<th>Data</th>
			<th>Material</th>
			<th>SituacaoTributaria</th>
			<th>Quantidade</th>
			<th>Valor Unitario</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($this->Session->check('pedidos')):
				$total = 0;
				foreach ($this->Session->read('pedidos') as $pedido): ?>
				<tr class="<?php echo $pedido['indice'] ?>">
					<td class="span2">
						<div  style="text-align: center">
							<a href="#" class="btn remover" title="REMOVER"><i class="icon-trash"></i></a>
							<a href="#" class="btn <?php if($pedido['imprimir']==1) echo 'hide'; ?> habilitar" title="HABILITAR IMPRESSAO"><i class="icon-eye-close"></i></a>
							<a href="#" class="btn <?php if($pedido['imprimir']!=1) echo 'hide'; ?> desabilitar" title="DESABILITAR IMPRESSAO"><i class="icon-eye-open"></i></a>
						</div>
					</td>
					<td><?php echo $pedido['cliente'] ?></td>
					<td >
						<span class="qtde"><?php echo $pedido['obra']; ?></span>                                            
					</td>
					<td><?php echo $pedido['pedido_id'] ?></td>
					<td><?php echo $pedido['data_entrega'] ?></td>
					<td><?php echo $pedido['material'] ?></td>
					<td><?php echo $pedido['situacao_tributaria'] ?></td>
					<td><?php echo $pedido['quantidade'].' '.$unidades[$pedido['unidade']] ?></td>
					<td><?php echo "R$ ". @number_format($pedido['valor_unitario'],2,",","."); ?></td>
					<td><?php
							$itemTotal = 0;
							if(isset($pedido['valor_unitario'])){
								$itemTotal = round($pedido['quantidade'] * $pedido['valor_unitario'], 2);
								//$itemTotal = $pedido['quantidade'] * $pedido['valor_unitario'];
							} 

							$total += $itemTotal;

							$itemTotal = number_format($itemTotal,2,",",".");

							echo "R$ ".$itemTotal;


						?>
					</td>
				</tr>				
			<?php endforeach; ?>
			<tr>
				<td colspan="9"> <h4>Total </h4> </td>
				<td><?php echo "R$ ". number_format($total,2,",",".");?></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>