<?php echo $this->Html->css( array( 'simpleAutoComplete2','jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script( array( 'simpleAutoComplete','jquery-ui.min','jquery.maskedinput.min','relatorios/gerar_vendas'), array( 'inline' => false ) ) ?>
<p class="lead">Relatório de Venda</p>
<div id="filtro">
    <?php
        echo $this->Form->create( 'Relatorio',array('action'=>'vendas') );        
    ?>
    <div id="field-datas">
    <?php
        echo $this->Form->input( 'inicio', array('label'=>'Data Inicial','type' => 'text') );    
        echo $this->Form->input( 'fim', array('label'=>'Data Final','type' => 'text') );    
    ?>
    </div>
    <?php
        echo $this->Form->input( 'user_id', array( 'options'=>$vendedores,'label' => 'Vendedores','empty'=>'[Selecione um Vendedor]','class'=>'span5' ) );
        echo $this->Form->input( 'motorista_id', array( 'options'=>$motoristas,'label' => 'Motorista','empty'=>'[Selecione um Motorista]','class'=>'span5' ) );
        echo $this->Form->hidden( 'cliente_id' );
		?>
	<div class="input select">
		<label for="Cliente">Cliente</label>
		<input type="text" id="Cliente" class='span5' placeholder="Selecione um Cliente" value=""/>
    </div>
	<?php
        echo $this->Form->input( 'obra_id', array( 'options'=>array(),'label' => 'Obra','empty'=>'[Selecione uma Obra]','class'=>'span5' ) );
		echo $this->Form->input( 'layout', array('label'=>'Exibir relatorio em ...','options'=>$layouts,'required'=>true) );    
		echo $this->Form->submit( 'Gerar Relatório', array( 'class' => 'btn-large btn-primary' ) );
        echo $this->Form->end();
    ?>
</div>