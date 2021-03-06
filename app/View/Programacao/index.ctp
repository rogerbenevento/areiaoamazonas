

<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui', 'relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui.min', 'jquery.price_format.min', 'jquery.maskedinput.min'), array('inline' => false)) ?>
<p class="lead">
	<a href="<?php echo Router::url(array('controller' => 'programacao', 'action' => 'index')); ?>">PROGRAMA&Ccedil;&Atilde;O</a>
	:: 
	<input type="text" class='seletor' id='data' class='span2' value='<?php echo dateMysqlToPhp($data); ?>' style="font-size: 20px;">
	<?php echo $this->Form->input('periodo', array("style" => "font-size: 19px;", 'div' => false, 'label' => false, 'options' => $periodos, 'empty' => '[ Periodo ]', 'selected' => $periodo)); ?>

	<a href="#" class="btn btn-info pull-right btn-print">
		<i class="icon-print icon-white"></i>&nbsp;Imprimir
	</a>
</p>
<style>
	.seletor{		
		height: 26px;
		margin-top: 3px;
		width: 110px;
	}
	.boxHistorico{
		font-size: 11px;
	}
	.table .table{
		margin-bottom: 0px;
		margin-left: 20px;
	}
	.success{
		color: #000;
	}
	@media print{
		body{
			margin-top: 0px;
		}
		a[href]:after {
			content: "";
		}
		div.navbar,
		.btn-print,
		#footer{ display: none; }
	}
</style>
<script>
	$(function() {
		$('#data').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);

		$('#data').change(function() {
			var d = $(this).val();	//12/08/2013
			var p = $('#periodo').val();	//12/08/2013
			var date = d.substr(-4, 4) + '-' + d.substr(3, 2) + '-' + d.substr(0, 2);
			location.href = APP + '/' + $('#role').val() + '/programacao/index/' + date + '/' + p;
		});
		$('#periodo').change(function() {
			var d = $('#data').val();	//12/08/2013
			var p = $(this).val();	//12/08/2013
			var date = d.substr(-4, 4) + '-' + d.substr(3, 2) + '-' + d.substr(0, 2);
			location.href = APP + '/' + $('#role').val() + '/programacao/index/' + date + '/' + p;
		});

		$('.item').change(function(e) {
			e.preventDefault();
			var item = $(this);
			if ($(this).prop('checked') == true) {
				//Foi ativado
				$.post(APP + '/' + $('#role').val() + '/programacao/active', {
					data: {
						Programacao: {
							item_pedido_id: $(this).val(),
							data: $('#data').val()
						}
					}
				}, function(response) {
					if (response == true) {
						item.parent('td').parent('tr').toggleClass('success');
					} else {
						alert('N??o foi possivel salvar a Altera????o!');
					}
				}, 'json');
			} else {
				//Foi Desativado
				$.post(APP + '/' + $('#role').val() + '/programacao/desactive', {
					data: {
						Programacao: {
							item_pedido_id: $(this).val(),
							data: $('#data').val()
						}
					}
				}, function(response) {
					if (response == true) {
						item.parent('td').parent('tr').toggleClass('success');
					} else {
						alert('N??o foi possivel salvar a Altera????o!');
					}
				}, 'json');
			}
		});

		$('.btn-print').click(function(e) {
			e.preventDefault();
			javascript:window.print();
		});
	});
</script>
<div class="boxHistorico">
	<table class="table">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th><?php echo $this->Paginator->sort('User.nome', 'Vendedor') ?></th>
				<th><?php echo $this->Paginator->sort('Cliente.nome', 'Cliente') ?></th>
				<th><?php echo $this->Paginator->sort('Viagens') ?></th>
				<th><?php echo $this->Paginator->sort('ItemPedido.produto_id', 'Material') ?></th>
				<th><?php echo $this->Paginator->sort('Obra.cidade_id', 'Cidade') ?></th>			
				<th><?php echo $this->Paginator->sort('Obra.endereco', 'Endere??o') ?></th>			
				<th><?php echo $this->Paginator->sort('Obra.bairro', 'Bairro') ?></th>			
				<th><?php echo $this->Paginator->sort('Pedido.observacao', 'Obs') ?></th>
			</tr>
			<?php
			$er = '/page:[0-9]*[\/]/i';
			echo preg_replace($er, '', $this->Form->create(null, array('type' => 'get')));
			?>
		<td></td>
		<td><input type="text" class="span1" name="User.nome" value="<?php echo (!empty($this->params->named['User_nome']) ? $this->params->named['User_nome'] : '' ) ?>" /></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
		<?php echo $this->Form->end() ?>
		</thead>
		<tbody>
			<?php
			//$total=count($rows);
			if ($total > 0):
				//Assumindo que todo pedido com mais de um produto significa apenas 1 viagem
				// ?? organizado o array para que seja exibido apenas 1 vale por pedido
				while (key($rows) !== null) {
					$i = key($rows);
					$row = &current($rows);
					$row['Produto']['nome']='coco';
					
					$id = array();
					$rows2 = $rows;
					unset($rows2[$i]);
					reset($rows2);
					//Loop para verificar se existe mais itens do mesmo pedido
					while (key($rows2) !== null) {
						$i2 = key($rows2);
						$row2 = &current($rows2);
						if (
							$row['ItemPedido']['id'] != $row2['ItemPedido']['id'] && $row['ItemPedido']['pedido_id'] == $row2['ItemPedido']['pedido_id']
						) {
							if(empty($rows[$i]['ItemPedido']['quantidade']))
								$rows[$i]['ItemPedido']['quantidade']=1;
							$rows[$i]['ItemPedido']['quantidade'].='<br>'.(empty($row2['ItemPedido']['quantidade'])?1:$row2['ItemPedido']['quantidade']);
							$rows[$i]['ItemPedido']['produto_id'].='<br>'.$row2['ItemPedido']['produto_id'];
							$rows[$i]['Produto']['nome'].='<br>'.$row2['Produto']['nome'];
							$id[] = $i2;
						}
						next($rows2);
					}
					
					if (!empty($id)) {
						foreach ($id as $d) {
							unset($rows[$d]);
						}
						reset($rows);
					} else {

						next($rows);
					}
				}


				//Ordena o array por produto_id
				reset($rows);
				$tr_open = false;
				while (key($rows) !== null):
					$i = key($rows);
					$row = &current($rows);
					$class = $checked = '';

					if (!isset($imprimir_total[$row['Cliente']['id']][$row['Obra']['id']][$row['ItemPedido']['produto_id']])) {
						//Veririco se existe um linha aberta
						if ($tr_open) {
							echo "</table></td></tr>";
							$tr_open = false;
						}

						$rows2 = $rows;
						unset($rows2[$i]);
						reset($rows2);
						$contador = 0;

						while (key($rows2) !== null):
							// Verifico por outras viagens com mesmo produto
							$row2 = &current($rows2);
							if (
								$row['ItemPedido']['id'] != $row2['ItemPedido']['id'] && $row['Cliente']['id'] == $row2['Cliente']['id'] && $row['Obra']['id'] == $row2['Obra']['id'] && $row['ItemPedido']['produto_id'] == $row2['ItemPedido']['produto_id']
							) {
								$imprimir_total[$row['Cliente']['id']][$row['Obra']['id']][$row['ItemPedido']['produto_id']] = true;
								$contador++;
							}
							next($rows2);
						endwhile;
						if (isset($imprimir_total[$row['Cliente']['id']][$row['Obra']['id']][$row['ItemPedido']['produto_id']])) {
							// Encontrou mais de uma linha dessa "configuracao"
							echo "<tr class='info'>"
							. "<td></td>"
							. '<td>' . substr($row['User']['nome'], 0, ( strpos($row['User']['nome'], ' ') ? strpos($row['User']['nome'], ' ') : 30)) . '</td>'
							. "<td>{$row['Cliente']['nome']}</td>"
							. "<td>" . ($contador + 1) . "</td>"
							. "<td>{$row['Produto']['nome']}</td>"
							. "<td>" . (@$row['Cidade']['nome']? : '') . "</td>"
							. "<td>{$row['Obra']['endereco']}</td>"
							. "<td>{$row['Obra']['bairro']}</td>"
							. "<td></td>"
							. "</tr>"
							. "<tr><td colspan='7'><table class='table'>";
							$tr_open = true;
							$contador = 0;
						}
					}
					foreach ($programacoes as $programacao) {
						$checked = '';
						if ($programacao['Programacao']['item_pedido_id'] == $row['ItemPedido']['id']) {
							$checked = 'checked="checked"';
							$class = 'success';
							break;
						}
					}
					?>			
					<tr class="<?php echo $class; ?>">
						<td style='width: 30px;'>
							&nbsp;&nbsp;<input class='item' name='data[Promocao][item_pedido_id]' <?php echo $checked; ?> type="checkbox" value='<?php echo $row['ItemPedido']['id']; ?>'>	
						</td>
					<?php if (empty($tr_open)) { ?>
							<td><?php echo substr($row['User']['nome'], 0, ( strpos($row['User']['nome'], ' ') ? strpos($row['User']['nome'], ' ') : 30)); ?></td>
							<td><?php echo $row['Cliente']['nome']; ?></td>
							<td><?php echo (empty($row['ItemPedido']['quantidade'])) ? 1 : $row['ItemPedido']['quantidade']; ?></td>
							<td><?php echo $row['Produto']['nome']; ?></td>					
							<td><?php echo @$row['Cidade']['nome']? : ''; ?></td>
							<td><?php echo $row['Obra']['endereco']; ?></td>
							<td><?php echo $row['Obra']['bairro']; ?></td>
							<td><?php echo $row['Pedido']['observacao']; ?></td>
		<?php } else { ?>
							<td><?php echo $row['ItemPedido']['quantidade'] ?></td>
							<td><?php echo $row['Produto']['nome']; ?></td>					
							<td><?php echo $row['Pedido']['observacao']; ?></td>
		<?php } ?>
					</tr>
						<?php
						next($rows);
						unset($rows[$i]);
					endwhile;
					if ($tr_open) {
						echo "</table></td></tr>";
						$tr_open = false;
					}
					?>
			<?php else: ?>
				<tr>
					<td colspan="5"><strong>N??o h?? registros cadastrados no momento.</strong></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5"><strong>Total de registros <?php echo $total ?>.</strong></td>
			</tr>
		</tfoot>
	</table>
	<?php //echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>
</div>