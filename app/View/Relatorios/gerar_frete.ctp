<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui.min','jquery.maskedinput.min','relatorios/gerar_vendas'), array( 'inline' => false ) ) ?>
<p class="lead">Relatório de Frete</p>
<div id="filtro">
    <?php
        echo $this->Form->create( 'Relatorio',array('action'=>'frete') );        
    ?>
    <div id="field-datas">
    <?php
        echo $this->Form->input( 'inicio', array('label'=>'Data Inicial','type' => 'text') );    
        echo $this->Form->input( 'fim', array('label'=>'Data Final','type' => 'text') );    
    ?>
    </div>
    <?php
        echo $this->Form->input( 'motorista_id', array( 'options'=>$motoristas,'label' => 'Motorista','empty'=>'[Selecione um Motorista]','class'=>'span5' ) );
        echo $this->Form->input( 'layout', array('label'=>'Exibir relatorio em ...','options'=>$layouts,'required'=>true) );    
		echo $this->Form->submit( 'Gerar Relatório', array( 'class' => 'btn-large btn-primary' ) );
		
        echo $this->Form->end();
    ?>
</div>