<?php echo $this->Html->script(array( 'jquery.price_format.min', 'compras/emitir'), array('inline' => false)) ?>
<p class="lead">
	<?php echo $this->Html->link('COMPRAS', array('controller' => 'compras', 'action' => 'index', $this->params['prefix'] => true)) ?>
	:: Emitir
</p>

<?php #echo $this->Form->create( 'Compra', array( 'id'=>'CompraEmitirForm','inputDefaults' => array( 'div' => false, 'label' => false ) ) ) ?>
<?php echo $this->Form->create( 'Compra' ) ?>
<?php 
	echo $this->Form->input( 'fornecedor_id', array( 'options' => $fornecedores, 'class' => 'span6', 'empty' => '[Escolha o fornecedor]' ) );
	echo $this->Form->input( 'valor', array( 'label' => 'Valor:','class'=>'span6'));
?>	
	<div class="input">
		<label for="CompraObservacao">Observacao:</label>
		<?php echo $this->Form->textarea( 'observacao',array('class'=>'span6'));?>	
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Finalizar compra</button>
	</div>
<?php echo $this->Form->end() ?>