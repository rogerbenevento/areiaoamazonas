<table class="table table-striped table-bordered table-condensed grid">
	<thead>
		<tr>
			<th></th>
			<th>Descrição</th>
			<th>Quantidade</th>
			<th>Pago</th>
			<th>Motivo</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($this->Session->check('carrinho')): ?>
			<?php $valorTotal = 0;?>
			<?php foreach ($this->Session->read('carrinho') as $item): ?>
				<?php if(!isset($item['desconto'])) $item['desconto']=0; ?>
				<tr class="<?php echo $item['indice'] ?>">
					<td class="span2">
						<div  style="text-align: center">
							<a href="<?php echo $this->Html->url(array('controller'=>'pedidos','action'=>'edit_finalizado',$item['pedido_id'],$item['id'])); ?>" class="btn" title="EDITAR"><i class="icon-pencil"></i></a>
						</div>
					</td>
					<td><?php echo $item['produto'] ?></td>
					<td class="itemQuantidade">
						<span class="qtde"><?php echo $item['quantidade'].' '.@$unidades[$item['unidade']]; ?></span>
					</td>
					<td><?php echo moedaBr($item['pago'])?></td>
					<td><?php echo $item['motivo']?></td>
					<!--<td class="itemTotal"><?php echo moedaBr($item['valor_total']) ?></td>-->
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
<!--	<tfoot>

		<tr>
			<td colspan="3">&nbsp;</td>
			<td ><strong>Valor Total:</strong></td>
			<td colspan="2"><strong><span class="valorTotal"><?php echo isset($valorTotal) ? moedaBr($valorTotal) : '' ?></span></strong></td>
		</tr>
	</tfoot>-->
</table>