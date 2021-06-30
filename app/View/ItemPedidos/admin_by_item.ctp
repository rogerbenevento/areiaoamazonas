<?php echo $this->Html->css( array( 'bootstrap', 'bootstrap-responsive', 'panel', 'default' ) ) ?>
<?php echo $this->Html->script( array( 'jquery','config.js?20150529', 'bootstrap' ) ) ?>

<?php echo $this->Html->script(array('simpleAutoComplete', 'jquery.mask', 'jquery-ui.min', 'jquery.price_format.min'), array('block' => 'scriptBottom')) ?>
<form id="FormItemPedido">	
	<?php 

	//echo $vale;
//echo '-'.$item['ItemPedido']['id'];
	echo $this->Form->input('quantidade', array('label' => 'Quantidade', 'class' => 'span5','type'=>'number','value'=>$item['ItemPedido']['quantidade_original']));
	
	echo $this->Form->input('valor_total', array('label' => 'Valor Total', 'class' => 'span5','type'=>'number','value'=>$item['ItemPedido']['valor_total']));
	echo $this->Form->input('motivo', array('required'=>'required','label' => 'Motivo', 'class' => 'span5','value'=>$item['ItemPedido']['motivo']));




	echo $this->Form->hidden('id', array('label' => 'Valor Total', 'class' => 'span5','value'=>$item['ItemPedido']['id']));

	echo $this->Form->hidden('vale', array('label' => 'Valor Total', 'class' => 'span5','value'=>$vale));
	 ?>

	<a href="#" class="btn btn-primary atualizarItemPedido">Gravar</a>
</form>
<script type="text/javascript">
	$('.atualizarItemPedido').click(function(e){
		e.preventDefault();

			var str = $( "#FormItemPedido" ).serialize();

			
			$.post(APP+'/'+$('#role').val()+'/item_pedidos/by_item_atualizar/?'+str ,{
			},function(resp){
				$('#my-modal').modal('hide');

				$.post(APP +'/'+$('#role').val()+'/vales/by_fornecedor/'+$("#ContaFornecedorId").val(),{
				conta:1,
				selected:$('#ForenecedorId').val() 
			},function(data){
				$('#boxVales').html(data);

			});
		});	
	});
</script>
<?php 

?>