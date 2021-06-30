<?php echo $this->Html->script( array( 'jquery.price_format.min','jquery.maskedinput.min', 'obras/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php 
	$string = utf8_encode($cliente['Cliente']['nome']);
	echo $this->Html->link( "Obras ( ".substr($string,0,25).')', array( 'controller' => 'obras', 'action' => 'index',$cliente['Cliente']['id'], $this->Session->read('Auth.User.nivel') => true ) )
		   ?>
	:: Cadastro
</p>
<?php

echo $this->Form->create( 'Obra' );
echo $this->Form->hidden( 'cliente_id',array('value'=>$cliente['Cliente']['id']));
echo $this->Form->input( 'vendedor_id', array( 'class' => 'span6','options'=>$vendedores,'empty'=>'[ Selecione um Vendedor ]' ) ); 
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) ); 
echo $this->Form->input( 'cnpj', array( 'class' => 'span6' ) ); 
echo $this->Form->input( 'custo_extra', array( 'label'=>'Custo Extra', 'class' => 'span6','type'=>'text' ) ); 
echo $this->Form->input( 'comissao', array( 'label'=>'Comissão(%)','class' => 'span6','type'=>'text','value'=>@($this->data['Obra']['comissao']?:0)*100 ) );
echo $this->Form->input( 'freteiro', array( 'label'=>'Freteiro(%)','class' => 'span6','type'=>'text','value'=>@($this->data['Obra']['freteiro']?:0)*100 ) );
echo $this->Form->input( 'endereco', array( 'label' => 'Endereço', 'class' => 'span6' ) ); 
echo $this->Form->input( 'cep', array( 'class' => 'span6' ) ); 
echo $this->Form->input( 'complemento', array( 'class' => 'span6' ) );
echo $this->Form->input( 'bairro', array( 'class' => 'span6' ) ); 
echo $this->Form->input( 'estado_id', array( 'options' => $estados, 'class' => 'span6', 'empty' => '[Escolha o estado]' ) ); 
echo $this->Form->input( 'cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6' ) ); 

?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'obras', 'action' => 'index',$cliente['Cliente']['id'], $this->Session->read('Auth.User.nivel') => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>