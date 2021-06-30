<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array( 'jquery-ui.min','jquery.maskedinput.min','jquery.price_format.min',  'multas/edit'), array('inline' => false)) ?>
<p class="lead">
	<?php echo $this->Html->link('MULTAS', array('controller' => 'multas', 'action' => 'index', $this->params['prefix'] => true)) ?>
	:: Emitir
</p>

<?php #echo $this->Form->create( 'Multa', array( 'id'=>'MultaEmitirForm','inputDefaults' => array( 'div' => false, 'label' => false ) ) ) ?>
<?php echo $this->Form->create( 'Multa' ) ;
	echo $this->Form->input( 'motorista_id', array( 'options' => $motoristas, 'class' => 'span6', 'empty' => '[ Selecione o Motorista ]' ) );
	echo $this->Form->input( 'tipo', array( 'label' => 'Tipo:','class'=>'span6'));
	echo $this->Form->input( 'valor', array( 'label' => 'Valor:','class'=>'span6'));
	echo $this->Form->input( 'indicacao', array( 'label' => 'Data Limite p/ Indicação:','class'=>'span2','type'=>'text'));
	echo $this->Form->input( 'numero_auto', array( 'label' => 'Num. Auto','class'=>'span6','type'=>'text'));
	echo $this->Form->input( 'data', array( 'label' => 'Data:','class'=>'span2','type'=>'text'));
	echo $this->Form->input( 'hora', array('class'=>'span2'));
	echo $this->Form->input( 'local', array('class'=>'span6','type'=>'text'));
	echo $this->Form->input( 'pontuacao', array( 'label' => 'Pontuação:','class'=>'span6','type'=>'text'));
?>	
	<div class="input">
		<label for="MultaObservacao">Observacao:</label>
		<?php echo $this->Form->textarea( 'observacao',array('class'=>'span6'));?>	
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Cadastrar Multa</button>
	</div>
<?php echo $this->Form->end() ?>