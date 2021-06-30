<?php
echo $this->Html->script( array( "jquery", "jquery.validate", "frmUser" ), array(), true, true );
?>
<h1>
	Alterar senha do usuário<br />
	<span class="label label-success"><?php echo $user['User']['nome'] ?></span>
</h1>
<?php
echo $this->Form->create( 'User' );
echo $this->Form->input( 'password', array( 'type' => 'password', 'label' => 'Nova senha' ) );
echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-success btn-large' ) );
echo $this->Form->end();
?>