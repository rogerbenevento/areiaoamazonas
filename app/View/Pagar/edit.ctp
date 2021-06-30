<?php echo $this->Html->script( array( 'jquery.price_format.min','jquery.maskedinput.min', 'pagar/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php echo $this->Html->link( 'CONTAS A PAGAR', array( 'controller' => 'motoristas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: Cadastro de Conta a Pagar</p>
	<?php 
		echo $this->Form->create( 'Pagar' );
		echo $this->Form->input( 'titulo', array( 'class' => 'span6' ) );
		echo $this->Form->input( 'data_vencimento', array( 'label' => 'Data Vencimento','type'=>'text', 'class' => 'span6' ) );
		echo $this->Form->input( 'valor', array( 'label' => 'Valor', 'class' => 'span6','type'=>'text' ) );
	?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'motoristas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>