<?php
$tabindex = 1;
echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false))
?>
<?php echo $this->Html->script(array('simpleAutoComplete2', 'jquery.price_format.min', 'jquery-ui.min', 'pedidos/edit'), array('inline' => false)) ?>
<input type="hidden" id="pedido_id" value="<?php if (isset($pedido)) echo $pedido['Pedido']['id'] ?>"/>
<style type="text/css">
	.boxCliente,.boxProduto, .data_entrega { position: relative !important;}
</style>
<script>
	$(function() {
		$('#PedidoComissao').priceFormat({
			prefix: "",
			limit: 4,
			centslimit: 2,
			centsSeparator: "."
		});
	})
</script>
<p class="lead">
	<?php
	echo $this->Html->link('PEDIDOS', array('controller' => 'pedidos', 'action' => 'index')) . " :: ";
	echo" Editar Pedido Finalizado"
	?>
</p>

<!-- Div do Cliente -->

<label><strong>Cliente:</strong>&nbsp;<?php if (isset($pedido['Cliente']['nome'])) echo $pedido['Cliente']['nome'] ?></label>	
<br>
<label><strong>Obra:</strong>&nbsp;<?php if (isset($pedido['Obra']['endereco'])) echo $pedido['Obra']['endereco'] ?></label>	

<!-- Fim Div do Cliente -->
<?php if (!empty($this->data['ItemPedido'])) { ?>
	<div class="well well-small">
		<div><b>Item Pedido:</b></div>
		<?php
		echo $this->Form->create('ItemPedido', array('id' => 'frmItemPedido'));
		echo $this->Form->input('Produto.nome', array('class' => 'span3', 'label' => false, 'div' => null, 'placeholder' => 'NOME', 'title' => 'Produto', 'disabled' => 'true'));
		echo $this->Form->input('quantidade', array('class' => 'span2', 'type' => 'text', 'label' => false, 'div' => null, 'placeholder' => 'Quantidade', 'title' => 'Quantidade'));
		echo "&nbsp;" . $this->Form->input('unidade', array('class' => 'span2', 'label' => false, 'div' => null, 'options' => $unidades, 'empty' => '[Unidade]', 'placeholder' => 'Unidade', 'title' => 'Uniade'));
		echo "&nbsp;" . $this->Form->input('pago', array('class' => 'span2', 'type' => 'text', 'label' => false, 'div' => null, 'placeholder' => 'Pago', 'title' => 'Pago'));
		echo "&nbsp;" . $this->Form->input('motivo', array('class' => 'span2', 'label' => false, 'div' => null, 'placeholder' => 'MOTIVO', 'title' => 'MOTIVO', 'required' => true));
		?>
		<input type="submit" class="btn btn-primary" style="margin-top:-10px	" value="SALVAR">
		<a href="<?php echo $this->Html->url(array('action' => 'edit_finalizado', $pedido['Pedido']['id'])) ?>" class="btn btn-warning" style="margin-top:-10px	">Cancelar</a>
	</form>
	</div>
<?php } ?>
<!-- tabela de itens do pedido -->
<p class="lead">Listagem de itens:</p>
<div id="tableItensPedidos">
	<?php echo $this->element('pedidos/itenspedidofinalizado'); ?>
</div>
<!-- Fim da tabela de itens do pedido -->
<div class="well <?php echo (!empty($this->data['ItemPedido']) ? 'hide' : '' ) ?>">
	<?php echo $this->Form->create('Pedido', array('id'=>'EditFinalizado','inputDefaults' => array('div' => false, 'label' => false))) ?>
	<?php echo $this->Form->input('comissao', array('label' => 'Comissão(%):', 'class' => 'span6', 'type' => 'text', 'required' => true, 'tabindex' => $tabindex++)) ?>
	<?php echo '<label for="PedidoObservacao">Observação:</label>' . $this->Form->input('observacao', array('style' => 'width:99%', 'tabindex' => $tabindex++)) ?>
	<button type="submit" tabindex="<?php echo $tabindex++ ?>" class="btn btn-primary">Salvar Pedido</button>

	<?php echo $this->Form->end(); #pr($_SESSION)?>
</div>
<!-- Fim da Aplicação de Desconto por item -->
<?php echo $this->Html->link('Voltar', array('action' => 'index')) ?>