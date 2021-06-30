<?php echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/jquery-ui')) ?>
<?php echo $this->Html->script(array('jquery.price_format.min', 'simpleAutoComplete', 'jquery-ui.min', 'jquery.maskedinput.min', 'notas/add'), array('inline' => false)) ?>
<p class="lead"><?php echo $this->Html->link('NOTAS', array('controller' => 'notas', 'action' => 'index', $this->params['prefix'] => true)) ?>
	:: Adicionar</p>
<div class="well form-inline">
	<form id="frmBuscarPedido">
		<input type="text" class="span6" id="NotaCliente" placeholder="Selecione um Cliente" value="<?php if (!empty($this->data['Cliente']['nome'])) echo $this->data['Cliente']['cpf_cnpj'] . ' - ' . $this->data['Cliente']['nome']; ?>"/>
		<?php
		//if($_SERVER['REMOTE_ADDR']=='138.94.23.196'){
		?>
		<input type="text" id="dateStart" placeholder="Data Início">
		<input type="text" id="dateEnd" placeholder="Data Final">

		<?php
		//}
		?>
<!--		<button type="submit" class="btn btn-info buscarPedido"><i class="icon-search icon-white"></i>&nbsp;Buscar Pedidos</button>-->
	</form>
	<div id="boxPedidos" style="max-height: 400px;overflow-y: scroll">

	</div>
</div>   
<?php


echo $this->Form->create('Nota');
echo $this->Form->hidden('cliente_id');
echo $this->Form->hidden('pedido_id'); 



?>
<!-- tabela de itens do pedido -->


<div id="tbPedidos" class="well">
	<?php echo $this->element('notas/tbpedidos'); ?>
</div>
<!-- Fim da tabela de itens do pedido -->
<div class="row-fluid">


	<?php
	echo $this->Form->input('natureza_operacao', array(
		'label' => 'Natureza Operacao',
		'div' => array('class' => 'span3'),
		'class' => 'span12'));
	echo $this->Form->input('cfop', array(
		'label' => 'Cfop',
		'div' => array('class' => 'span2'),
		'class' => 'span12'));
	?>
</div>
<div class="row-fluid">
	<?php echo $this->Form->input('imposto', array(
		'label' => 'Possui imposto', 
		'checked'=>(!empty($this->data['Nota']['imposto']))?$this->data['Nota']['imposto']:true
		));?>
</div>
<?php
echo $this->Form->input('empresa_id', array('label' => 'Empresa:', 'class' => 'span6'));


if($num_nota){
	echo $this->Form->input('numero', array('class' => 'span6','value'=>$num_nota));
}else{
	echo $this->Form->input('numero', array('class' => 'span6'));
}


echo $this->Form->input('indpres', array('class' => 'span6'));
echo $this->Form->input('procemi', array('class' => 'span6', 'options' => $procemi));



?>
<div class="row-fluid">
	<?php
	echo $this->Form->input('emissao', array(
		'div' => array('class' => 'span2'),
		'class' => 'span12',
		'type' => 'text'));
	echo $this->Form->input('vencimento', array(
		'div' => array('class' => 'span2'),
		'class' => 'span12',
		'type' => 'text'));
	?>
</div>
<?php
echo "<label for='NotaObservacao'>Observação:</label>" . $this->Form->textarea('observacao', array('class' => 'span6'));
?>
<div class="clearfix"></div>

<table class="table table-striped span6">
	<tr>
		<td>Nr.Ordem</td>
		<td><?php echo $this->Form->input('fatura_ordem1', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_ordem2', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_ordem3', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>		
	</tr>
	<tr>
		<td>Vencimento</td>
		<td><?php echo $this->Form->input('fatura_vencimento1', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_vencimento2', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_vencimento3', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>		
	</tr>
	<tr>
		<td>Valor</td>
		<td><?php echo $this->Form->input('fatura_valor1', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_valor2', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>
		<td><?php echo $this->Form->input('fatura_valor3', array('class' => 'span2', 'type' => 'text', 'div' => false, 'label' => false)); ?></td>		
	</tr>
</table>
<div class="clearfix"></div>
<?php
echo $this->Form->input('tPag', array(
	'label' => 'Forma Pagamento (tPag)',
	'options' => [
		1=>'dinheiro',
		2=>'cheque',
		3=>'cartão de credito',
		4=>'cartão de debito',
		5=>'credito loja',
		10=>'vale alimentação',
		11=>'vale refeição',
		12=>'vale presente',
		13=>'vale combustível',
		15=>'boleto bancário',
		90=>'Sem pagamento',
		99=>'Outros'
	],
	//'after'=>'<small>'. 'Obs.: Em NFe de Ajuste ou Devolução, a forma de pagamento deve ser preenchida com o valor = Sem Pagamento.'.'</small>',
	'class' => 'span2'));
echo '<div class="clearfix"></div>';
echo $this->Form->input('vPag', array('label'=>'vPag','class' => 'span2 price', 'type' => 'text'));
echo $this->Form->input('vDesc', array('label'=>'vDesc','class' => 'span2 price', 'type' => 'text'));
// echo $this->Form->input('vFCP', array('label'=>'vFCP','class' => 'span2 price', 'type' => 'text'));
// echo $this->Form->input('vFCPST', array('label'=>'vFCPST','class' => 'span2 price', 'type' => 'text'));
// echo $this->Form->input('vFCPSTRet', array('label'=>'vFCPSTRet','class' => 'span2 price', 'type' => 'text'));
// echo $this->Form->input('vIPIDevol', array('label'=>'vIPIDevol','class' => 'span2 price', 'type' => 'text'));
echo $this->Form->input('vTroco', array('label'=>'vTroco','class' => 'span2 price', 'type' => 'text'));


echo $this->Form->input('base_calculo_icms', array('class' => 'span2', 'type' => 'text'));
echo $this->Form->input('dados_adicionais', array('class' => 'span6', 'type' => 'text'));

echo $this->Form->submit('Gravar', array('class' => 'btn btn-primary btn-large btn-block'));
?><br />
ou
<?php echo $this->Html->link('Voltar', array('controller' => 'notas', 'action' => 'index', $this->params['prefix'] => true)) ?>

<?php echo $this->Form->end() ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#NotaEmpresaId').change(function(){
			var n = $(this).val();
			//alert(n);
			 $.get(APP +'/'+$('#role').val()+'/notas/ultima/'+n,
			 	function(data){
			 		//alert(data);
			 		$("#NotaNumero").val(data);
			 	});


	

		});
	});
</script> 