<?php echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('simpleAutoComplete2', 'jquery.price_format.min', 'jquery-ui.min','jquery.maskedinput.min', 'pedidos/edit','vales/index','pedidos/finalizar'), array('inline' => false)) ?>
<input type="hidden" id="pedido_id" value="<?php echo $pedido['Pedido']['id'] ?>"/>
<style type="text/css">
	.boxCliente,.boxProduto, .data_entrega { position: relative !important;}
	.pull-left{
		margin-right: 10px;
	}
</style>
<script>
	var FRETEIROS = [<?php 
			$virgula='';
			foreach($freteiros as $id=>$freteiro) {
				echo $virgula.$id;
				$virgula=',';
			}
		?>];

</script>
<p class="lead"><?php echo $this->Html->link('PEDIDOS', array('controller' => 'pedidos', 'action' => 'index')) . " :: Pedido {$pedido['Pedido']['id']}" ?> </p>
<?php
echo $this->Form->create('Pedido',array('inputDefaults'=>array('required'=>true)));
?>
<!-- Div do Cliente -->

<input type="hidden" name="data[Pedido][id]" id="id" value="<?php echo $pedido['Pedido']['id']; ?>" />
<!--	<input type="hidden" name="valor" id="valor" value="<?php echo $pedido['valor']; ?>" />
<input type="hidden" name="desconto" id="desconto" value="<?php echo $pedido['desconto']; ?>" />-->
<table class="table-condensed">
	<tr>
		<td><b>N&ordm; do pedido:</b></td>
		<td><?php echo $pedido['Pedido']['id']; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Vendedor: </b></td>
		<td><?php echo $pedido['User']['nome']; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Cliente: </b></td>
		<td><?php echo $pedido['Cliente']['nome']; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><b>CPF / CNPJ:</b> </td>
		<td><?php echo $pedido['Cliente']['cpf_cnpj']; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Telefone:</b> </td>
		<td><?php echo $pedido['Cliente']['telefone']; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Endere&ccedil;o:</b> </td>
		<td>
			<?php
			echo @$pedido['Obra']['cep'] .' - '. @$pedido['Obra']['endereco']
			.' - '. @$pedido['Obra']['complemento'] .' - '. @$pedido['Obra']['bairro']
			.' - '. @$pedido['Obra']['Cidade']['nome'];
			?>
		</td>
	</tr>
	<tr>
		<td><b>Valor total:</b> </td>
		<td>
			<?php
			$valorTotal = 0;
			foreach ($pedido['ItemPedido'] as $item):
				$valor = $item['valor'] * $item['quantidade'];
				$valorTotal += $valor;
			endforeach;

			echo moedaBr($valorTotal);
			?>&nbsp;
		</td>
	</tr>
</table>

<label class="well form-inline">
	<span><b>Observa&ccedil;&atilde;o: </b></span>
	<?php echo $pedido['Pedido']['observacao']; ?>&nbsp;
</label>
<div id="itens">
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th style="width:100px"></th>
				<th style="width:50px">Frete</th>
				<th>Status</th>
				<th>Nome</th>
				<th>Qtde.</th>
				<th>Valor</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$valorTotal = 0; #pr($pedido); 
				$imprimir_form=false;
				$item_id=$vale_id=null;
				foreach ($pedido['ItemPedido'] as $key=>$item):
			?>
				<tr>
					<td style="text-align: center">
						<div class="btn-group">
						<?php 
							if($item['Vale']['status']==0){
								$imprimir_form =(!$vale_id)? true : false;
								$produto_form = $item['Produto']['nome'];
								$quantidade_form = $item['quantidade'];
								$unidade_form = $item['unidade'];
								$vale_id=$item['Vale']['id'];
								$item_id=$item['id'];
						?>
								<a href="<?php echo $this->Html->url(array('controller'=>'vales','action'=>'finalizar/'.$item['Vale']['id'],$item['pedido_id'])) ?>" class="btn" title="FINALIZAR VALE">
									<?php echo $this->Html->image('icones/check.png', array('width'=>16));?>
								</a>
						<?php } ?>
							<a href="#" class="btn btnimprimir-modal" vale_id='<?php echo $item['Vale']['id']?>' title="IMPRIMIR VALE">
								<?php echo $this->Html->image( 'icones/print.png', array( 'border' => '0', 'width' => 16 ) ) ;?>
							</a>
						</div>
					</td>
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
								?>
								<input type="hidden" name="data[ItemPedido][<?php echo $key ?>][id]" id="PedidoItemPedidoId" value="<?php echo $item['id'] ?>"/>
								<input type="hidden" name="data[ItemPedido][<?php echo $key ?>][pedido_id]" id="PedidoItemPedidoId" value="<?php echo $item['pedido_id'] ?>"/>
								<input type="text" class="span1 frete" name="data[ItemPedido][<?php echo $key ?>][frete]" id="PedidoItemPedidoFrete" value="<?php echo $frete ?>"/>
								<?php
							}
						?>
					</td>
					<td><?php echo $status[$item['Vale']['status']]; ?></td>
					<!--<td><?php echo $item['Vale']['id']; ?></td>-->
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
</div>
<div>
	<?php
	if($imprimir_form){
		echo "<h4>Finalizar: {$produto_form} (Qtde:{$quantidade_form})</h4>";
		echo $this->Form->hidden( 'Vale.id',array('value'=>$vale_id));
		echo $this->Form->hidden( 'ItemPedido.id',array('value'=>$item_id));
		echo $this->Form->input( 'Vale.fornecedor_id', array( 'class' => 'span2','options'=>$fornecedores ,'empty'=>'[Fornecedor]','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.empresa_id', array( 'class' => 'span2','options'=>$empresas,'empty'=>'[Empresa]','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.motorista_id', array( 'class' => 'span2','options'=>$motoristas,'empty'=>'[Motorista]','required'=>true) );
		
		echo $this->Form->input( 'Vale.codigo', array( 'label' => 'N??mero do Vale', 'class' => 'span2','type'=>'text','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.nota_fiscal_emissao', array( 'label' => 'Dt. Emiss??o da Nota Fiscal', 'class' => 'span2','type'=>'text','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.nota_fiscal', array( 'label' => 'N??mero da Nota Fiscal', 'class' => 'span2','type'=>'text') );
		
		//Carregado
		echo $this->Form->input( 'ItemPedido.unidade_original', array('selected'=>$unidade_form,'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span1 unidade_original','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.quantidade_original', array( 'value'=>$quantidade_form,'label' => 'Quantidade Carregada', 'class' => 'span2','type'=>'text','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.valor_total', array( 'label' => 'Valor Carregado', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true ) );
		
		//Entregue
		echo $this->Form->input( 'ItemPedido.unidade', array( 'selected'=>$unidade_form,'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span1 unidade','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.quantidade', array( 'value'=>$quantidade_form,'label' => 'Quantidade Entregue', 'class' => 'span2','type'=>'text','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.valor_unitario', array( 'label' => 'Valor Unit??rio', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->hidden( 'ItemPedido.pago');
		echo $this->Form->input( 'ItemPedido.pago_display', array( 'disabled'=>true,'label' => 'Valor Pago', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true ) );
		
		echo $this->Form->input( 'Vale.periodo_id', array('label'=>'Periodo:','options'=>$periodos,'class'=>'span2','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.data_entrega', array( 'label' => 'Data', 'class' => 'span2','type'=>'text','value'=>'','placeholder'=>'00/00/0000','required'=>true ,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.frete', array( 'label' => 'Valor Frete', 'class' => 'span2','type'=>'text') );
		
	}
	?>
</div>
<div class="form-actions">
	<input tabindex="2" type="submit" value="Finalizar" class="btn btn-primary" />
</div>

<?php echo $this->Form->end(); #pr($_SESSION) ?>

<?php echo $this->Html->link('Voltar', array('action' => 'abertos')) ?>
<?php echo $this->element('modal-imprimir-vale');?>