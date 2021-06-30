<?php echo $this->Html->script( array( 'jquery.price_format.min','taxas/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php echo $this->Html->link( 'TAXAS&IMPOSTOS', array( 'controller' => 'taxas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: Cadastro de Taxas&Impostos</p>
	<?php 
		echo $this->Form->create( 'Taxa' );
		echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
		echo $this->Form->input( 'valor', array( 'label' => 'Valor', 'class' => 'span6','type'=>'text' ) );
	?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'taxas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>
<?php if (Configure::read('debug') == 2) echo $this->element('sql_dump') ?>