<script>
	$(function(){
		$('#UserUsername').focus();
	})
</script>
<?php
    echo $this->Form->create( 'User' );
    echo $this->Form->input( 'username', array( 'label' => 'Usuário' ) );
    echo $this->Form->input( 'password', array('label'=>'Senha','type' => 'password') );    
    echo $this->Form->submit( 'Entrar', array( 'class' => 'btn-large btn-primary' ) );
    echo $this->Form->end();
?>