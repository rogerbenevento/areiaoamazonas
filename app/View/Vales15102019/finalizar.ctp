<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array( 'jquery.price_format.min', 'jquery-ui.min','jquery.maskedinput.min','pedidos/finalizar'), array( 'inline' => false ) ) ?>
<style type="text/css">
	.boxCliente,.boxProduto, .data_entrega { position: relative !important;}
	.pull-left{
		margin-right: 10px;
	}
</style>
<script>
	var FRETEIROS = [<?php 
			$virgula='';
			foreach($freteiros as $id=>$freteiro) {
				echo $virgula.$id;
				$virgula=',';
			}
		?>];

</script>
<script>
//	$(function(){
//		$('#ValeDataEntrega').mask('99/99/9999').datepicker(DATE_PICKER_CONFIG);
//		$('#ItemPedidoPago').priceFormat({
//			prefix: "",
//			centsSeparator: ",",
//			thousandsSeparator: ""
//		});
//		$('##ItemPedidoQuantidade').priceFormat({
//			prefix: "",
//			centsSeparator: ".",
//			thousandsSeparator: ""
//		});
//	})
</script>
<p class="lead">
	<?php echo (!empty($pedido))?
		$this->Html->link( 'FINALIZAR PEDIDO ('.$pedido.')', array( 'controller' => 'pedidos', 'action' => 'finalizar',$pedido, $this->params['prefix'] => true ) )
		:$this->Html->link( 'VALES', array( 'controller' => 'vales', 'action' => 'index', $this->params['prefix'] => true ) ) ;
	?>
	 :: Finalizar Vale</p>
	<?php 
		echo $this->Form->create( 'Vale' );
		echo $this->Form->hidden( 'Vale.id');
		echo $this->Form->hidden( 'ItemPedido.id');
		//echo $this->Form->input( 'fornecedor_id', array( 'class' => 'span6','options'=>$fornecedores ,'empty'=>'[Fornecedor]','required'=>true) );
		//echo $this->Form->input( 'motorista_id', array( 'class' => 'span6','options'=>$motoristas,'empty'=>'[Motorista]','required'=>true) );
		//echo $this->Form->input( 'Vale.codigo', array( 'label' => 'Número do Vale', 'class' => 'span6','type'=>'text') );
		//echo $this->Form->input( 'Vale.nota_fiscal', array( 'label' => 'Número da Nota Fiscal', 'class' => 'span6','type'=>'text') );
		//echo $this->Form->input( 'ItemPedido.quantidade', array( 'label' => 'Quantidade Entregue', 'class' => 'span6','type'=>'text','required'=>true) );
		//echo $this->Form->input( 'ItemPedido.unidade', array( 'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span6'));
		//echo $this->Form->input( 'periodo_id', array('label'=>'Periodo:','options'=>$periodos,'class'=>'span6','required'=>true) );
		//echo $this->Form->input( 'data_entrega', array( 'label' => 'Data', 'class' => 'span6','type'=>'text','value'=>'','placeholder'=>'00/00/0000','required'=>true ) );
		//echo $this->Form->input( 'ItemPedido.pago', array( 'label' => 'Valor Pago', 'class' => 'span6','type'=>'text','placeholder'=>'0,00','required'=>true ) );
		echo $this->Form->input( 'Vale.fornecedor_id', array( 'class' => 'span2','options'=>$fornecedores ,'empty'=>'[Fornecedor]','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.empresa_id', array( 'class' => 'span2','options'=>$empresas,'empty'=>'[Empresa]','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.motorista_id', array( 'class' => 'span2','options'=>$motoristas,'empty'=>'[Motorista]','required'=>true) );
		
		echo $this->Form->input( 'Vale.codigo', array( 'label' => 'Número do Vale', 'class' => 'span2','type'=>'text','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.nota_fiscal_emissao', array( 'label' => 'Dt. Emissão da Nota Fiscal', 'class' => 'span2','type'=>'text','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.nota_fiscal', array( 'label' => 'Número da Nota Fiscal', 'class' => 'span2','type'=>'text') );
		
		//Carregado
		echo $this->Form->input( 'ItemPedido.unidade_original', array( 'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span1 unidade_original','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.quantidade_original', array( 'label' => 'Quantidade Carregada', 'class' => 'span2','type'=>'text','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.valor_total', array( 'label' => 'Valor Carregado', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true ) );
		
		//Entregue
		echo $this->Form->input( 'ItemPedido.unidade', array( 'label' => 'Unidade', 'options'=>$unidades,'empty'=>'[Unidade]','class'=>'span1 unidade','div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.quantidade', array( 'label' => 'Quantidade Entregue', 'class' => 'span2','type'=>'text','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.valor_unitario', array( 'label' => 'Valor Unitário', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->hidden( 'ItemPedido.pago');
		echo $this->Form->input( 'ItemPedido.pago_display', array( 'disabled'=>true,'label' => 'Valor Pago', 'class' => 'span2','type'=>'text','placeholder'=>'0,00','required'=>true ) );
		
		echo $this->Form->input( 'Vale.periodo_id', array('label'=>'Periodo:','options'=>$periodos,'class'=>'span2','required'=>true,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'Vale.data_entrega', array( 'label' => 'Data', 'class' => 'span2','type'=>'text','placeholder'=>'00/00/0000','required'=>true ,'div'=>array('class'=>'pull-left')) );
		echo $this->Form->input( 'ItemPedido.frete', array( 'label' => 'Valor Frete', 'class' => 'span2','type'=>'text') );
	?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Finalizar', array( 'class' => 'btn btn-primary' ) ) ?><br />
	ou
	<?php echo $this->Html->link( 'Voltar', array( 'controller' => 'taxas', 'action' => 'index', $this->params['prefix'] => true ) ) ?>
</div>
<?php echo $this->Form->end() ?>
<?php if (Configure::read('debug') == 2) echo $this->element('sql_dump') ?>