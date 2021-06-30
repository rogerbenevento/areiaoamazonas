<p class="lead">
	<?php echo $this->Html->link( 'Tipos de Contas', array( 'controller' => 'tipos_contas', 'action' => 'index') ) ?>
	:: <?php if(empty($this->data['TipoConta'])) echo "Cadastro"; else echo "Edição"; ?>
</p>
<?php
echo $this->Form->create( 'TipoConta' );
echo $this->Form->input( 'tipo',  array('label'=>'Tipo','empty' => '[Escolha um Tipo]'));
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );

?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'tipos_contas', 'action' => 'index') ) ?>
</div>
<?php echo $this->Form->end() ?>