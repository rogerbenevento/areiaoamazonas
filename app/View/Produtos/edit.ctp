<?php echo $this->Html->script( array( 'jquery.maskedinput.min','jquery.price_format.min', 'produtos/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
<?php echo $this->Html->link( 'PRODUTOS', array( 'controller' => 'produtos', 'action' => 'index', $this->params['prefix'] => true ) ).":: ";
          echo (!empty($this->request->data))?"Editar":"Cadastrar";
          echo" Produto"
    ?>
</p>
<?php
	echo $this->Form->create( 'Produto' );
	echo $this->Form->input( 'codigo', array( 'class' => 'span6' ) );
	echo $this->Form->input( 'ncm', array( 'class' => 'span6' ) );
	echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
	echo $this->Form->input( 'descricao', array( 'class' => 'span6', 'label' => 'Descrição', 'type' => 'textarea' ) );
?>
<div class="form-actions">
    <?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
    ou <?php echo $this->Html->link( 'Voltar', array( 'controller' => 'produtos', 'action' => 'index', 'admin' => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>