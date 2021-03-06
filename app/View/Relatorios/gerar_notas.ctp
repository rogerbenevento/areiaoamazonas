<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui.min','jquery.maskedinput.min','relatorios/gerar_vendas'), array( 'inline' => false ) ) ?>
<p class="lead">Relatório de Notas</p>
<div id="filtro">
    <?php
        // echo $this->Form->create( 'Relatorio',array('action' => 'notas') );        
        echo $this->Form->create( 'Relatorio',array('url' => 'notas') );        
    ?>
    <div id="field-datas">
    <?php
        echo $this->Form->input( 'inicio', array('label'=>'Data Inicial','type' => 'text') );    
        echo $this->Form->input( 'fim', array('label'=>'Data Final','type' => 'text') );    
    ?>
    </div>
    <?php
        echo $this->Form->input( 'empresa_id', array( 'options'=>$empresas,'empty'=>'[Selecione uma Empresa]','class'=>'span3' ) );
        echo $this->Form->input( 'vendedor_id', array( 'options'=>$vendedores,'empty'=>'[Selecione um Vendedor]','class'=>'span3' ) );
        echo $this->Form->input( 'cliente_id', array( 'options'=>$clientes,'empty'=>'[Selecione um Cliente]','class'=>'span3' ) );
        echo $this->Form->input( 'layout', array('label'=>'Exibir relatorio em ...','options'=>$layouts,'required'=>true) );    
		echo $this->Form->submit( 'Gerar Relatório', array( 'class' => 'btn-large btn-primary' ) );
		
        echo $this->Form->end();
    ?>
</div>