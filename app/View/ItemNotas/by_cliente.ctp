<?php if ( count( $pedidos ) > 0 ): ?>
	<table style="width: 100%;" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Cliente</th>
				<th>Obra</th>
				<th>Pedido</th>
				<th>Data</th>
				<th>Material</th>
				<th>Sit. Trib.</th>
				<th class="span2">Quantidade</th>
				<th class="span1">Valor Unitario</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $pedidos as $pedido ): ?>
					<tr class="<?php echo $pedido['Pedido']['id'] ?>">
						<td style="text-align: center">
							<input type="button" name="btnPedido<?php echo $pedido['Pedido']['id'] ?>" rel="<?php echo $pedido['Pedido']['id'] ?>" id="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="btn btn-primary btn-pedido-add" value="Inserir"/>
						</td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="cliente"><?php echo $pedido['Cliente']['nome'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="obra"><?php echo $pedido['Obra']['nome'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="pedido"><?php echo $pedido['Pedido']['id'] ?></label></td>
						<td><label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" class="data_entrega"><?php echo $pedido['Pedido']['data_entrega'] ?></label></td>
						
						<td>
							<label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" >
								<?php 
									$bl='';
									foreach($pedido['ItemPedido'] as $item_id=>$item):
										echo $bl;
								?>
								<select class="material">
									<?php 
									foreach($produtos as $i=>$p):
										$selected = ($p==$item['Produto']['nome'])?' selected ' :'';
										echo "<option {$selected} value='{$i}'>{$p}</option>";
									endforeach; 
									?>
									<?php //echo $pedido['ItemPedido'][0]['Produto']['nome'] ?>
								</select>
								<?php 
									$bl='<br><br>';
								endforeach; 
								?>
							</label>
						</td>
						<td>
							<?php 
								$bl='';
								foreach($pedido['ItemPedido'] as $item_id=>$item):
									echo $bl;
							?>
							<!--<label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" >-->
							<?php 
							echo "<input type='text' class='span1 situacao_tributaria' maxlength='3' value=''/>"; 
							
							?>
							<!--</label>-->
							<?php 
								$bl='<br><br>';
							endforeach; 
							?>
						</td>
						<td style="width: 150px">
							<?php 
								$bl='';
								foreach($pedido['ItemPedido'] as $item_id=>$item):
									echo $bl;
							?>
							<!--<label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" >-->
							<?php 
							echo "<input type='text' class='span1 quantidade' value='{$item['quantidade']}'/>"; 
							echo $this->Form->input('unidade',array(
								'label'=>false,
								'div'=>false,
								'style'=>'width:60px',
								'class'=>'unidade',
								'options'=>$unidades,
								'selected'=>$item['unidade']
							));
							//echo @$unidades[$item['unidade']];
							?>
							<!--</label>-->
							<?php 
								$bl='<br><br>';
							endforeach; 
							?>
						</td>
						<td>
							<?php 
								$bl='';
								foreach($pedido['ItemPedido'] as $item_id=>$item):
									echo $bl;
							?>
							<!--<label for="btnPedido<?php echo $pedido['Pedido']['id'] ?>" >-->
							<?php 
							echo "<input type='text' class='span1 valor_unitario' value='".  ($item['quantidade']>0?number_format($item['pago']/$item['quantidade'],2):'')."'/>"; 
							
							?>
							<!--</label>-->
							<?php 
								$bl='<br><br>';
							endforeach; 
							?>
						</td>
					</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>