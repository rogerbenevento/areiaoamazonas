<?php echo $this->Html->script( array( 'jquery.mask', 'empresas/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php echo $this->Html->link( 'EMPRESAS', array( 'controller' => 'empresas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: Cadastro de Empresa
</p>
<?php
echo $this->Form->create( 'Empresa' );
echo $this->Form->input( 'razao', array('label'=>'Razão Social', 'class' => 'span6' ) );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
echo $this->Form->input( 'cnpj', array( 'label' => 'CNPJ', 'class' => 'span6' ) );
echo $this->Form->input( 'ie', array( 'label' => 'IE', 'class' => 'span6' ) );
echo $this->Form->input( 'im', array( 'label' => 'Inscrição Municipal', 'class' => 'span6' ) );
echo $this->Form->input( 'cnae', array( 'label' => 'CNAE Fiscal', 'class' => 'span6' ) );
echo $this->Form->input( 'inicio_nota_fiscal', array( 'label' => 'Nº inícial da nota', 'class' => 'span6', 'type'=>'number' ) );
echo $this->Form->input( 'cep', array( 'label' => 'CEP', 'class' => 'span6' ) ); 
echo $this->Form->input( 'telefone', array( 'label' => 'Telefone', 'class' => 'span6' ) );
echo $this->Form->input( 'crt', array(
	'empty' => '[ Regime ]',
	'label' => 'Crt',
	'class' => 'span6'
	) );
echo $this->Form->input( 'endereco', array( 'label' => 'Endereço', 'class' => 'span6' ) );
echo $this->Form->input( 'numero', array( 'label' => 'Nº', 'class' => 'span6' ) );
echo $this->Form->input( 'complemento', array( 'label' => 'Complemento', 'class' => 'span6' ) );
echo $this->Form->input( 'bairro', array( 'label' => 'Bairro', 'class' => 'span6' ) );
?>
<?php echo $this->Form->input( 'estado_id', array( 'options' => $estados, 'class' => 'span6', 'empty' => '[Escolha o estado]' ) ); ?>
<?php echo $this->Form->input( 'cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6' ) ); ?>
	
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary btn-large' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'empresas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>