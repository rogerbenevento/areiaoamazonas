<style>
	span{
		font-weight: bold;
	}
</style>
<fieldset>
	<legend>Dados do cliente</legend>
	<div class="row-fluid">
		<div class="span8"><label><span>Nome: </span><?php echo $pedido['Cliente']['nome']; ?></label></div>
		<div class="span4">
			<label><span>Telefone: </span><?php echo $pedido['Cliente']['telefone']; ?></label>
		</div>
	</div>
	
	<label><span>Email: </span><?php echo $pedido['Cliente']['email']; ?></label>
	
</fieldset>
<fieldset>
	<legend>Endereco da obra</legend>
	<div class="row-fluid">
		<div class="row-fluid">
			<div class="span8">
				<label><span>Endereço: </span><?php echo $pedido['Obra']['endereco']; ?></label>
			</div>
			<div class="span4">
				<label><span>CEP: </span><?php echo $pedido['Obra']['cep']; ?></label>
			</div>			
		</div>		
		<div class="row-fluid">
			<div class="span12">
				<label><span>Cidade / Estado: </span><?php echo @$pedido['Obra']['Cidade']['nome'].' / '.@$pedido['Obra']['Cidade']['Estado']['nome']; ?></label>			
			</div>
		</div>		
		
	</div>
</fieldset>
<fieldset>
	<legend>Dados do Pedido</legend>
	<div class="row-fluid">
		<div class="span4">
			<label><span>N&ordm;: </span><?php echo $pedido['Pedido']['id']; ?></label>
		</div>
		<div class="span4">
			<label><span>Data: </span><?php echo $this->Time->format('d/m/Y H:i', $pedido['Pedido']['created']); ?></label>		
		</div>
		<div class="span4">
			<label>
				<span>Status: </span>
				<?php
				switch ($pedido['Pedido']['status']):
					case 0: echo "Em aberto";
						break;
					case 1: echo "Finalizado";
						break;
					case 2: echo "Cancelado";
						break;
				endswitch;
				?>
			</label>
		</div>
	</div>
	<label><span>Vendedor: </span><?php echo $pedido['User']['nome']; ?></label>
	
	<label>
		<span>Observações: </span>
		<?php echo $pedido['Pedido']['observacao']; ?>
		<br><b><?php if (!empty($pedido['Pedido']['motivo'])) echo $pedido['Pedido']['motivo']; ?></b>
	</label>
</fieldset>

<fieldset>
	<?php #pr($pedido);exit();?>
	<legend>Itens do Pedido</legend>
	<table style="width: 100%">
		<thead>
			<tr>
				<th>Descrição</th>
				<th>Quant.</th>
				<th>Valor</th>
				<th>Frete</th>
				<th>Pago</th>
			</tr>
		</thead>
		<tbody>
			<?php $valorTotal = 0; ?>
			<?php foreach ($pedido['ItemPedido'] as $item): ?>
				<tr>
					<td style="min-width: 200px;font-size: 11px;color: #008ee8">
						<?php echo $item['Produto']['nome']; ?>							
					</td>
					<td><?php echo number_format($item['quantidade'],($item['unidade']==2? 2 : 3)); ?></td>
					<td><?php echo moedaBr($item['valor_total']); ?></td>
					<td><?php echo moedaBr($item['frete']); ?></td>
					<td><?php echo moedaBr($item['pago']); ?></td>
				</tr>
				<?php $valorTotal += ($item['pago'] ); ?>
			<?php 
			endforeach;
			
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"><strong>Valor Total</strong></td>
				<td colspan="1"><strong><?php echo moedaBr($valorTotal); ?></strong></td>
			</tr>
		</tfoot>
	</table>
</fieldset>