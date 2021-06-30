<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array('jquery-ui.min','jquery.price_format.min', 'jquery.mask', 'vendedores/edit'), array( 'inline' => false ) ) ?>

<p class="lead">
    <?php 
        echo $this->Html->link( 'VENDEDORES', array( 'controller' => 'vendedores', 'action' => 'index', $this->params['prefix'] => true ) );
    ?> 
    :: Cadastro
</p>

<?php echo $this->Form->create('Vendedor'); ?>
	<fieldset>
		<?php echo $this->Form->input( 'nome', array( "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'User.username', array( "class" => "span6" ) ); ?>
		<?php if(empty($this->request->data['User']['password']))echo $this->Form->input( 'User.password', array( "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'cpf_cnpj', array( 'label' => 'CPF', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'comissao', array( 'label' => 'Comissão(%)', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'data_nascimento', array("type"=>'text', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'email', array( 'label' => 'Email', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'telefone', array( 'label' => 'Telefone', "class" => "span6" ) ); ?>
	</fieldset>
	<fieldset>
		<legend>Endereço</legend>
		<?php echo $this->Form->input('cep', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input('endereco', array( 'label' => 'Endereço', 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input('complemento', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input('bairro', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input('estado_id', array( 'options' => $estados, 'class' => 'span6', 'empty' => '[Escolha o estado]' ) ); ?>
		<?php echo $this->Form->input('cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6' ) ); ?>
	</fieldset>
     <?php echo "Observações<br>".$this->Form->textarea( 'observacoes', array( 'class' => 'span6' ) ); ?>
<?php // echo $this->Form->input( 'observacao', array( 'type' => 'textarea', 'class' => 'span6', 'rows' => 8, 'label' => 'Observações' ) ); ?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ); ?>
	ou
	<?php /*echo $this->Html->link( 'voltar', array( 'controller' => 'Vendedores', 'action' => 'index', $this->params['prefix'] => true ) );*/ ?>
	<?php echo $this->Html->link( 'Voltar',  'index'); ?>
</div>
<?php echo $this->Form->end(); ?>