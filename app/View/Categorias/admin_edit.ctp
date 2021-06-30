<p class="lead">Gerenciar Categoria</p>
<?php
echo $this->Form->create( 'Categoria' );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );

?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'categorias', 'action' => 'index', 'admin' => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>