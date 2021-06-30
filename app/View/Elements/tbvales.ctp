<table class="table table-striped table-bordered table-condensed grid">
	<thead>
		<tr>
			<th></th>
			<th>Nota</th>
			<th>Emissão</th>
			<th>Quantidade</th>
			<th>Valor</th>
			<th>Valor M²</th>
			<th>Pedido</th>
		</tr>
	</thead>
	<tbody>
		<?php 

		$contaValor = 0;
		if ($this->Session->check('vales')):

				foreach ($this->Session->read('vales') as $vale): ?>
				<tr class="<?php echo $vale['indice'] ?>" >
					<td class="span2">
						<div  style="text-align: center">
							<a href="#" data-valor="<?= $vale['valor_total'] ?>" class="btn remover" title="REMOVER"><i class="icon-trash"></i></a>
						</div>
					</td>
					<td><?php echo $vale['nota_fiscal'] ?></td>
					<td><?php echo dateMysqlToPhp($vale['nota_fiscal_emissao']) ?></td>
					<td><?php echo $vale['quantidade'] ?></td>
					<td><?php echo $vale['valor_total'] ?></td>
					<td><?php echo number_format($vale['valor_total'] / $vale['quantidade'],2) ?></td>
					<td><?php echo $vale['pedido'] ?></td>
				</tr>				
			<?php
			$contaValor += $vale['valor_total'];
			 endforeach; ?>

		<?php endif; 

			
		?>
		<label>TOTAL</label>
		<input type="text" name="TOTAL" id="totalCorreto"  value="<?=$contaValor?>" disabled>
		<span style="margin: -10px 0 0 5px;" class="btn btn-success" onclick="totalReal()" title="Sincronizar">
			<img src="<?=$this->webroot?>img/icones/sincronizar_valor.png" width="16">
		</span>
	</tbody>
</table>

<?php 
if($this->Session->check('vales')):
	$array_view = $this->Session->read('vales'); 
	// if($_SERVER['REMOTE_ADDR']=='179.99.92.56'){
	// 	echo $contaValor;
	// 	echo '<pre>';
	// 	print_r($array_view);
	// }
	$array_itens = '';

	foreach ($array_view as $key => $value) {
		$array_itens[]=$key;
	}

	$json_itens = json_encode($array_itens,true);
	echo '<input type="hidden" id="Vales" value="'.$json_itens.'">';

	echo '<input type="hidden" id="total" value="'.$contaValor.'">';
	//echo "<pre>";
	//print_r($array_view);
endif;
?>