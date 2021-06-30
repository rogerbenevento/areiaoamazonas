<?php if(!empty($conta)){ ?>
<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui-1.8.21.custom.min','jquery.maskedinput-1.3.min', 'jquery.price_format.1.6.min' ), array( 'inline' => false ) ) ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#ContaDataPagamento').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
	$('#ContaTaxaAdiantamento').priceFormat({
		prefix: '',
		centsSeparator: ',',
		thousandsSeparator: '',
		limit: 4,
		centsLimit: 2
	 });
});
</script>
<p class="lead">
	<?php echo $this->Html->link('Gerenciar Contas', array('controller' => 'contas', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true)) ?> 
	:: Consolidar Conta <small><?php echo $conta['Conta']['descricao'] ?></small>
</p>
<?php 
	echo $this->Form->create( 'Conta', array( 'class' => 'well' ) ); 
	echo $this->Form->input( 'data_pagamento', array( 'class' => 'span2', 'label' => 'Data de pagamento&nbsp;&nbsp;', 'type' => 'text' ) );
	if($conta['Conta']['credito']==1)
		echo $this->Form->input( 'taxa_adiantamento', array( 'class' => 'span2', 'label' => 'Taxa Adiantamento&nbsp;&nbsp;', 'type' => 'text' ) );
	echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) );
	echo $this->Form->end();

	echo $this->Html->link('Voltar', array('action' => 'index'));
?>
<?php } ?>
