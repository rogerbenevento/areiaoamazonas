<?php echo $this->Html->css(array('simpleAutoComplete2','jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('simpleAutoComplete', 'jquery.price_format.min', 'jquery-ui.min','jquery.maskedinput.min'), array( 'inline' => false ) ) ?>
<script>
	$(function(){
		$('#ValeDataEntrega').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
		$('#ItemPedidoPago').priceFormat({
			prefix: "",
			centsSeparator: ",",
			thousandsSeparator: ""
		});
		$('#ItemPedidoQuantidade').priceFormat({
			prefix: "",
			centsSeparator: ".",
			thousandsSeparator: ""
		});
		$('#PedidoCliente').keypress(function(e){
		if ( e.which == 13 ) {
			return false;
		}
		}).simpleAutoComplete(APP +'/'+$('#role').val()+'/clientes/all', {}, callbackCliente);
		function callbackCliente( par ) { 
			$("#ValeClienteId").val( par[0] );
			$('#ObraId').load(APP +'/'+$('#role').val()+'/obras/options/'+par[0]);
			$('#boxObra').fadeIn();
		}
		
		$('#PedidoCliente').change(function(){
			if($(this).val()==''){
				$("#ValeClienteId").val('');
				$("#ValeObraId").val('');
				$('#boxObra').slideUp();
			}
		});

		$('#ObraId').change(function(){
			$('#ValeObraId').val($(this).val());
		});
		
	});
</script>
<p class="lead">
	Vale Avulso
</p>
	<?php
		echo $this->Form->create( 'Vale',array('action'=>'imprimir_avulso') );
	?>
	<!-- Div do Cliente -->
	<div class="well form-inline boxCliente">
		<input type="text" id="PedidoCliente" class='span6' placeholder="Selecione um Cliente" value="<?php if (isset($this->data['Cliente']['nome'])) echo $this->data['Cliente']['nome'] ?>" placeholder="Pesquise por um Cliente"/>
		<div id="boxObra" style="display: none;margin-top: 20px;">
			<div class="input" >
				<select id="ObraId" class="span5">
					<option value="">[Obras]</option>
				</select>
			</div>
		</div>
	</div>
	<?php
		echo $this->Form->hidden('cliente_id');
		echo $this->Form->hidden('obra_id');
		echo $this->Form->input( 'motorista_id', array( 'class' => 'span6','options'=>$motoristas,'empty'=>'[ Selecione um Motorista ]','required'=>false) );
		echo $this->Form->input( 'produto_id', array( 'class' => 'span6','empty'=>'[ Selecione um Produto ]','label'=>"Produtos",'options'=>$produtos ) );		
		echo $this->Form->input( 'nota_fiscal', array( 'label' => 'Número da Nota Fiscal', 'class' => 'span6','type'=>'text') );
		echo $this->Form->input( 'ItemPedido.quantidade', array( 'label' => 'Quantidade Entregue', 'class' => 'span6','type'=>'text','required'=>false) );
		echo $this->Form->input( 'ItemPedido.unidade', array( 'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span6'));
		echo $this->Form->input( 'periodo_id', array('label'=>'Periodo:','options'=>$periodos,'class'=>'span6') );
		echo $this->Form->input( 'data_entrega', array( 'label' => 'Data', 'class' => 'span6','type'=>'text','value'=>'','placeholder'=>'31/12/2000') );
		echo $this->Form->input( 'layout', array( 'class' => 'span6','options'=>array(1=>'Areião Amazonas',2=>'Scorpions')) );
		
		echo $this->Form->submit('IMPRIMIR',array('class'=>'btn btn-primary')) ;
		echo $this->Form->end() ;
		if (Configure::read('debug') == 2) echo $this->element('sql_dump') 
?>