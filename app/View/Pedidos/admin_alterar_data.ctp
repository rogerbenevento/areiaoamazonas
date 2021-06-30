<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui.min','jquery.maskedinput.min' ), array( 'inline' => false ) ) ?>

<script type="text/javascript">
$(function(){ 
	$('#PedidoDataEntrega').mask('99/99/9999').datepicker({ dateFormat: "dd/mm/yy" });; 
	$('form').validate();
});
</script>
<p class="lead">
	<?php
		echo $this->Html->link('PEDIDOS', array('controller' => 'pedidos', 'action' => 'index', $this->params['prefix'] => true)) . " :: ";
		echo "Editar a data do Pedido (".$pedido['Pedido']['id'] .")";
	?>
</p>
<?php echo $this->Form->create('Pedido', array("action"=> "alterar_data/".$pedido['Pedido']['id'] )) ?>
	<?php echo $this->Form->input('data_entrega', array('label'=>'Data Entrega:','class'=>'required dateBR','type'=>'text')) ?>
<!--	<label for="created">Data: </label>				
	<input type="text" name="created" id="created" value="<?php echo $pedido['Pedido']['created']; ?>" class="required dateBR" />-->
	<input type="submit" value="Gravar" class="btn btn-primary" />
	ou <?php echo $this->Html->link( 'Voltar', "/{$this->params['prefix']}/pedidos" ); ?>
<?php echo $this->Form->end() ?>