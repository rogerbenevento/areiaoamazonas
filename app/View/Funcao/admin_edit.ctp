<p class="lead">
    <?php 
        echo $this->Html->link( 'Gerenciar Funções', array( 'controller' => 'funcao', 'action' => 'index', $this->params['prefix'] => true ) )."::";
        echo (isset($row['Funcao']['id']))?'Editar Função':'Cadastrar Função';
    ?>
</p>
<?php
echo $this->Form->create( 'Funcao' );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
echo "Descrição:<br>".$this->Form->textarea( 'descricao', array( 'class' => 'span6' ) );

?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'funcao', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>