<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array( 'jquery-ui.min','jquery.price_format.min',  'abastecimentos/edit'), array('inline' => false)) ?>
<p class="lead">
	<?php echo $this->Html->link('ABASTECIMENTOS', array('controller' => 'abastecimentos', 'action' => 'index', $this->params['prefix'] => true)) ?>
	:: Emitir
</p>

<?php #echo $this->Form->create( 'Abastecimento', array( 'id'=>'AbastecimentoEmitirForm','inputDefaults' => array( 'div' => false, 'label' => false ) ) ) ?>
<?php 
	echo $this->Form->create( 'Abastecimento' ) ;
	echo $this->Form->input( 'motorista_id', array( 'options' => $motoristas, 'class' => 'span6', 'empty' => '[Escolha o fornecedor]' ) );
	echo $this->Form->input( 'data', array( 'label' => 'Data:','class'=>'span6','type'=>'text'));
	echo $this->Form->input( 'valor', array( 'label' => 'Valor:','class'=>'span6'));
	echo $this->Form->input( 'quantidade', array( 'label' => 'Quantidade(L)','class'=>'span6'));
	echo $this->Form->input( 'quilometragem', array( 'class'=>'span6'));
?>	
	<div class="input">
		<label for="AbastecimentoObservacao">Observacao:</label>
		<?php echo $this->Form->textarea( 'observacao',array('class'=>'span6'));?>	
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Cadastrar Abastecimento</button>
	</div>
<?php echo $this->Form->end() ?>