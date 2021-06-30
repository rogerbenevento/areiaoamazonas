<?php echo $this->Html->script( array( 'jquery.mask', 'motoristas/edit' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php echo $this->Html->link( 'MOTORISTAS', array( 'controller' => 'motoristas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
	 :: Cadastro de Motorista</p>
<?php //pr($estados); 
    // gambiarra para fazer aparecer estados/cidades com acentos
    foreach($estados as &$val){
        $val=  utf8_encode($val);
    }
    if(!empty($cidades))
    foreach($cidades as &$val){
        $val=  utf8_encode($val);
    }
echo $this->Form->create( 'Motorista' );
echo $this->Form->input( 'nome', array( 'class' => 'span6' ) );
echo $this->Form->input( 'cpf_cnpj', array( 'label' => 'CNPJ', 'class' => 'span6' ) );
echo $this->Form->input( 'cnh', array( 'label' => 'CNH', 'class' => 'span6' ) );
echo $this->Form->input( 'validade_cnh', array( 'label' => 'Validade CNH', 'class' => 'span6','type'=>'text' ) );
echo $this->Form->input( 'tipo', array( 'label' => 'Tipo', 'class' => 'span6','options'=>$tipos,'empty'=>'[TIPO MOTORISTA]' ) );
echo $this->Form->input( 'placa', array( 'class' => 'span6','type'=>'text') );
echo $this->Form->input( 'contato', array( 'class' => 'span6' ) );
echo $this->Form->input( 'email', array( 'class' => 'span6' ) );
echo $this->Form->input( 'telefone', array( 'class' => 'span6' ) );
echo $this->Form->input( 'endereco', array( 'class' => 'span6', 'label' => 'Endereço' ) );
echo $this->Form->input( 'complemento', array( 'class' => 'span6' ) );
echo $this->Form->input( 'bairro', array( 'class' => 'span6' ) );
echo $this->Form->input( 'estado_id', array( 'type'=>'select','class' => 'span6', 'options' => $estados, 'empty' => '[Escolha o estado]', 'label' => 'Estado' ) );
echo $this->Form->input( 'cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6', 'label' => 'Cidade' ) );
echo $this->Form->input( 'cep', array( 'class' => 'span6', 'label' => 'CEP' ) );
//echo $this->Form->input( 'observacao', array( 'class' => 'span6', 'label' => 'Observações', 'type' => 'textarea' ) );
?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'motoristas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>