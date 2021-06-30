<?php echo $this->Html->script(array('jquery.price_format.min','users/edit')) ?>
<p class="lead">
    <?php 
        echo (isset($pedido))?$this->Html->link( 'USUÁRIOS', array( 'controller' => 'users', 'action' => 'edit',$pedido, $this->params['prefix'] => true ) ) :$this->Html->link( 'FUNCIONÁRIOS', array( 'controller' => 'Vendedores', 'action' => 'index', $this->params['prefix'] => true ) );
    ?> 
    :: Cadastro
</p>
<?php
echo $this->Form->create( 'User' );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
echo $this->Form->input( 'email', array( 'class' => 'span6' ) );
echo $this->Form->input( 'username', array( 'class' => 'span6' ) );
if(empty($this->data['User']['password']))echo $this->Form->input( 'password', array( 'class' => 'span6' ) );
echo $this->Form->input( 'nivel_id', array( 'class' => 'span6', 'options' => $niveis, 'empty' => '[Escolha o nivel]', 'label' => 'Nivel' ) );
?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'users', 'action' => 'index', 'admin' => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>