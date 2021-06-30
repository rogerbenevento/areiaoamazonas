<?php echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('simpleAutoComplete', 'jquery.mask', 'jquery-ui.min', 'jquery.price_format.min', 'contas/edit'), array('block' => 'scriptBottom')) ?>
<p class="lead">
	<?php echo $this->Html->link('Gerenciar Contas a Pagar / Receber', array('controller' => 'contas', 'action' => 'index')) ?>
	:: Nova Conta
</p>
<style>
	#boxPedidos{
		max-height: 200px;
		background: white;
		overflow-y: scroll;
		border: 1px solid #ccc;
		display: none;
	}
	.pull-left{
		margin-right: 20px;
	}
</style>



<?php echo $this->Form->create('Conta', array()); ?>
<?php echo $this->Form->input('tipo', array('options' => $tipos, 'class' => 'span2', 'empty' => '[ Escolha o tipo de operação ]','default'=>0,'disabled'=>'disabled','label' => 'Tipo de Operação', 'div' => array('class' => 'pull-left', ))); ?>

<?php echo $this->Form->input('tipo_conta_id', array('options'=>$tipos_contas, 'default'=>1,'disabled'=>'disabled', 'class' => ' span2', 'empty' => '[ Escolha o Tipo de Conta ]', 'label' => 'Tipo Conta', 'div' => array('class' => 'pull-left'))); ?>

<?php echo $this->Form->input('empresa_id', array('options' => $empresas, 'class' => 'span2', 'empty' => '[ Escolha a Empresa ]', 'label' => 'Empresa')); ?>


<?php echo $this->Form->input( 'tipo_pagamento_id', array( 'options' => $tipos_pagamentos, 'class' => 'span3', 'empty' => '[ Escolha o tipo de pagamento ]', 'label' => 'Tipo de Pagamento','div'=>array('class'=>'pull-left pagto_fornecedor hide')) ); ?>
<?php echo $this->Form->input( 'fornecedor_id', array( 'options' => $fornecedores, 'class' => 'span3', 'empty' => '[ Escolha o fornecedor ]', 'div'=>array('class'=>'pagto_fornecedor ')) ); ?>
<div id="vincular_vale" class="well well-small " >
	<h5>Vincular Vale</h5>


	<table>
			<tr>
				<td><input type="text" id="inicio" placeholder="dd/mm/YYYY"></td>
				<td><input type="text" id="fim" placeholder="dd/mm/YYYY"></td>
				<td><input type="text" id="nota_fiscal" placeholder="nº"></td>
				<td><button value="buscar" id="btnBuscarData" style="margin: -11px 0px 0 4px" class="btn btn-primary">Buscar</button ></td>
				<td>
					<?php
		
			echo "<button 
					id=\"addAllVales\" 
					class=\"btn btn-success\" 
					style=\"float:right; margin: -11px 0px 0 4px;\">
						Inserir Todos
					</button>";
		
		?>

				</td>
			</tr>
		</table>
		<script type="text/javascript">
		$(document).ready(function(){
			$('#inicio').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
			$('#fim').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
		});
		</script>
<!--	<form id="frmBuscarVale">
		<input type="hidden" id="FornecedorId" />
		<input type="text" class="span6" id="Fornecedor" placeholder="Selecione um Cliente"/>
		<button type="submit" class="btn btn-info buscarPedido"><i class="icon-search icon-white"></i>&nbsp;Buscar Pedidos</button>
	</form>-->
	<div id="boxVales" style="max-height: 150px;overflow-y: scroll">

	</div>
	<!-- tabela de itens do pedido -->
	<div id="tbVales" class="well">
		<?php echo $this->element('tbvales'); ?>
	</div>
	<!-- Fim da tabela de itens do pedido -->
</div>
<?php echo $this->Form->input('descricao', array('label' => 'Descrição', 'class' => 'span6')); ?>
<?php echo $this->Form->input('numero_documento', array('label' => 'Numero Documento', 'class' => 'span2', 'div' => array('class' => 'pull-left'))); ?>
<?php echo $this->Form->input('fatura', array('label' => 'Fatura', 'class' => 'span2', 'div' => array('class' => 'pull-left'))); ?>
<?php echo $this->Form->input('data_vencimento', array('label' => 'Data/Vencimento', 'class' => 'span2', 'type' => 'text', 'div' => array('class' => 'pull-left'))); ?>
<?php echo $this->Form->input('valor', array('class' => 'span2', 'type' => 'text','value'=>$contaValor)); ?>
<?php echo $this->Form->input('parcela', array('default' => 1, 'class' => 'span1', 'div' => array('class' => 'pull-left'))); ?>
<?php echo $this->Form->input('parcelas', array('default' => 1, 'class' => 'span1')); ?>



<?php echo $this->Form->hidden('intervalos'); ?>
<div class=" well boxIntervaloParcelas" style="display: none;">

</div>
<?php echo $this->Form->input('observacao', array('type' => 'textarea', 'class' => 'span6', 'rows' => 5, 'label' => 'Observação')); ?>
<?php 
echo "<a href=\"#\" id=\"btnImprimir\" onclick=\"imprimirConta()\" class=\"btn btn-success\" style=\"float:left; margin:0 10px;\">Imprimir</a>"; 

echo $this->Form->submit('Gravar', array('class' => 'btn btn-primary')); ?>
<?php echo $this->Form->end(); ?>


<?php 
	//print_r();
?>
<script type="text/javascript">
function imprimirConta(){
	var vales = $("#Vales").val();
	var conta_empresa = $("#ContaEmpresaId").val();
	var conta_fornecedor = $("#ContaFornecedorId").val();	
	var conta_descricao = $("#ContaDescricao").val();
	var conta_numero_documento =  $("#ContaNumeroDocumento").val();
	var conta_fatura = $("#ContaFatura").val();
	var conta_vencimento = $("#ContaDataVencimento").val();
	var conta_valor =  $("#ContaValor").val();
	var conta_obs = $("#ContaObservacao").val();
	var conta_parcela = $("#ContaParcela").val();
	var conta_parcelas = $("#ContaParcelas").val();

	var j = conta_parcelas - 2;
	var asparcelas = [];
	for(i=0;i<=j;i++){
		var itemValor = $("#IntervaloParcelaValor"+i).val();
		var item = $("#IntervaloParcela"+i).val();

		asparcelas[i] = [{text:item,value:itemValor}];
	}

	var json_dados = {
			empresa:conta_empresa,
			fornecedor:conta_fornecedor,
			descricao:conta_descricao,
			numero_documento:conta_numero_documento,
			fatura:conta_fatura,
			vencimento:conta_vencimento,
			valor:conta_valor,
			obs:conta_obs,
			parcela:conta_parcela,
			parcelas:conta_parcelas,
			asparcelas:asparcelas,
			vales:vales
	};
	console.log("Dados",json_dados);

	 window.open("<?=$this->webroot.$this->Session->read('Auth.User.nivel')?>/contas/print/?"+decodeURIComponent($.param(json_dados)), "", "width=800,height=600,left=100,top=100,resizable=yes,scrollbars=yes");
}	

</script>
<?php // echo $this->Html->link('voltar', array('controller' => 'contas', 'action' => 'index', 'admin' => true)); ?>