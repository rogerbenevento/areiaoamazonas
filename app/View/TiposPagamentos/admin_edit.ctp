<?php echo $this->Html->script(array('jquery.price_format.1.7.min')); ?>
<script>
	$(document).ready(function(){
		
		$('#TipoPagamentoTac,#TipoPagamentoEntrada').priceFormat({
			prefix: "",
			limit: 4,
			centsSeparator: ".",
			thousandsSeparator: ""
		});
	});
</script>
<p class="lead">
	<?php echo $this->Html->link( 'FORMAS DE PAGAMENTOS', array( 'controller' => 'tipospagamentos', 'action' => 'index' )) ?>
	:: Cadastro
</p>
<?php
echo $this->Form->create( 'TipoPagamento' );
echo $this->Form->input( 'nome', array( 'class' => 'span6','disabled'=>'disabled' ) );
echo $this->Form->input( 'parcelas', array( 'class' => 'span6' ) );
echo $this->Form->input( 'dias_credito', array( 'label'=>'Dias para recebimento','class' => 'span6' ) );
//echo $this->Form->input( 'credito' );
echo $this->Form->input( 'tac', array( 'label'=>'TAC','class' => 'span6','type'=>'text' ) );
echo $this->Form->input( 'entrada', array( 'label'=>'Entrada(%)','class' => 'span6','type'=>'text' ) );
?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'tipospagamentos', 'action' => 'index') ) ?>
</div>
<?php echo $this->Form->end() ?>