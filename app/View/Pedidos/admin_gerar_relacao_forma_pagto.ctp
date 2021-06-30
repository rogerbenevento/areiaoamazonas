<?php echo $this->Html->css( array( 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'jquery-ui-1.8.21.custom.min','jquery.maskedinput-1.3.min','jquery.maskedinput-1.3.min', 'pedidos/gerar_relacao' ), array( 'inline' => false ) ) ?>
<p class="lead">Relatório de Vendas por Forma de Pagamento</p>
<div id="filtro">
    <?php
        echo $this->Form->create( 'Pedido',array('action'=>'relacao_forma_pagto') );
        
    ?>
    <div id="field-datas">
    <?php
        echo $this->Form->input( 'inicio', array('label'=>'Data Inicial','type' => 'text') );    
        echo $this->Form->input( 'fim', array('label'=>'Data Final','type' => 'text') );    
    ?>
    </div>
    <?php
        echo $this->Form->input( 'loja', array( 'options'=>$lojas,'label' => 'Loja','empty'=>'Todas','class'=>'span5' ) );
        echo $this->Form->input( 'users', array( 'options'=>$users,'label' => 'Vendedor','empty'=>'Todos','class'=>'span5' ) );
        echo $this->Form->submit( 'Gerar Relatório', array( 'class' => 'btn-large btn-primary' ) );
        echo $this->Form->end();
    ?>
</div>