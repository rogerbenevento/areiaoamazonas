<?php echo $this->Html->script( array( 'jquery.mask', 'fornecedores/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php echo $this->Html->link( 'FORNECEDORES', array( 'controller' => 'fornecedores', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: Cadastro de Fornecedor
</p>
<?php //pr($estados); 
    // gambiarra para fazer aparecer estados/cidades com acentos
    foreach($estados as &$val){
        $val=  utf8_encode($val);
    }
    if(!empty($cidades))
    foreach($cidades as &$val){
        $val=  utf8_encode($val);
    }
echo $this->Form->create( 'Fornecedor' );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
echo $this->Form->input( 'cnpj', array( 'label' => 'CNPJ', 'class' => 'span6' ) );
echo $this->Form->input( 'contato', array( 'class' => 'span6' ) );
echo $this->Form->input( 'email', array( 'class' => 'span6' ) );
echo $this->Form->input( 'telefone', array( 'class' => 'span6' ) );
echo $this->Form->input( 'cep', array( 'class' => 'span6', 'label' => 'CEP' ) );
echo $this->Form->input( 'endereco', array( 'class' => 'span6', 'label' => 'Endereço' ) );
echo $this->Form->input( 'complemento', array( 'class' => 'span6' ) );
echo $this->Form->input( 'bairro', array( 'class' => 'span6' ) );
echo $this->Form->input( 'estado_id', array( 'type'=>'select','class' => 'span6', 'options' => $estados, 'empty' => '[Escolha o estado]', 'label' => 'Estado' ) );
echo $this->Form->input( 'cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6', 'label' => 'Cidade' ) );

//echo $this->Form->input( 'observacao', array( 'class' => 'span6', 'label' => 'Observações', 'type' => 'textarea' ) );
?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'fornecedores', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>
