<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui.min','jquery.maskedinput.min' ), array( 'inline' => false ) ) ?>
<script>
	$(function(){
		$( "#ContaInicio,#ContaFim" ).mask('99/99/9999')
			 .datepicker({ dateFormat: "dd/mm/yy" });
	 })
</script>
<p class="lead">Fluxo de Caixa</p>
<div id="filtro">
    <?php
        echo $this->Form->create( 'Conta',array('action'=>'fluxo') );
        
    ?>
    <div id="field-datas">
    <?php
        echo $this->Form->input( 'inicio', array('label'=>'Data Inicial','type' => 'text') );    
        echo $this->Form->input( 'fim', array('label'=>'Data Final','type' => 'text') );    
    ?>
    </div>
    <?php
        echo $this->Form->submit( 'Gerar Relatório', array( 'class' => 'btn-large btn-primary' ) );
        echo $this->Form->end();
    ?>
</div>