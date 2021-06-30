<?php //echo $this->Html->script(array('jquery.mask')); ?>

		
		<script type="text/javascript">
		//	$(document).ready(function(){



			//   $('#inicio').mask('99/99/9999')
		 //   .datepicker(DATE_PICKER_CONFIG);

		 //   		$('#fim').mask('99/99/9999')
		 //   .datepicker(DATE_PICKER_CONFIG);
			// });
		</script>
	
	<table style="width: 100%; " class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Pedido</th>
				<th>Nota</th>
				<th>Emissão</th>
				<th>Data Pedido</th>
				<th>Quantidade</th>
				<th>Valor</th>
				<th>Valor M²</th>
				<!-- <th>Cliente</th>
				<th>Obra</th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $vales as $vale ){  ?>
			<?php ?>
				<tr class="<?php echo $vale['Vale']['id'] ?>">
					<td style="text-align: center">

						<input type="button" name="btnVale<?php echo $vale['Vale']['id'] ?>" rel="<?php echo $vale['Vale']['id'] ?>" id="btnVale<?php echo $vale['Vale']['id'] ?>" 
						data-valor="<?php echo $vale['ItemPedido']['valor_total'] ?>"
						class="btn btn-primary btn-vale-add" value="Inserir"/>

						<?php
							//if($_SERVER['REMOTE_ADDR']=='168.227.12.21'){
								//echo $vale['Vale']['id'].'-'; 
								//echo $vale['Vale']['item_pedido_id']; 
								if($this->Session->read('Auth.User.nivel_id')==1){
									echo "<a  
											href=\"#\"
											data-controls-modal=\"my-modal\" 
											data-backdrop=\"static\"
											class='btn btn-info editarVale' 
											data-id='{$vale['Vale']['item_pedido_id']}'
											data-vale='{$vale['Vale']['id']}'
											>Editar</a>";
								}
							//}
						?>

					</td>
					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="pedido"><?php echo $vale['ItemPedido']['pedido_id'] ?></label></td>
					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="nota"><?php echo $vale['Vale']['nota_fiscal'] ?></label></td>

					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>"><?php echo dateMysqlToPhp($vale['Vale']['nota_fiscal_emissao']) ?></label></td>

					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="nota_fiscal_emissao"><?php echo dateMysqlToPhp($vale['Vale']['created']) ?></label></td>

					


					<td>
						<label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="quantidade">
						<?php echo $vale['ItemPedido']['quantidade_original'] ?>
							
						</label>
					</td>
					<td>
						<label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="valor_total">
							<?php echo $vale['ItemPedido']['valor_total'] ?>
						</label>
					</td>

					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="valor_total"><?php 
						$resultado =   $vale['ItemPedido']['valor_total']/$vale['ItemPedido']['quantidade_original'];

						echo number_format($resultado,2); 

					?></label></td>

					<!-- <td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="cliente"><?php echo $vale['ItemPedido']['Pedido']['Cliente']['nome'] ?></label></td>
					<td><label for="btnVale<?php echo $vale['Vale']['id'] ?>" class="obra"><?php echo $vale['ItemPedido']['Pedido']['Obra']['nome'] ?></label></td> -->
				</tr>
			<?php 
			
			}
			?>
		</tbody>
	</table>
<?php  ?>

<script type="text/javascript">
	$('.editarVale').click(function(e){
		e.preventDefault();
		var id = $(this).data('id');

		var vale = $(this).data('vale');

		$.post(APP+'/'+$('#role').val()+'/item_pedidos/by_item/'+id+'/'+vale ,{
		},function(resp){
			$('#my-modal div.modal-body').html(resp);
			$('#my-modal').modal('show');
		});		
	});
	$('.cancel').click(function(e){
		$('#my-modal').modal('hide');
	});

</script>


 <div class="modal hide fade in" id="my-modal" style="display: none;">
    <div class="modal-header">
      <!--   <a class="close" href="#">×</a> -->
        <h3>Editar</h3>
    </div>
    <div class="modal-body">
       <p>One fine body…</p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-danger cancel" href="#">Cancelar</a>
    </div>