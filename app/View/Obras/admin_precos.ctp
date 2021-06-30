<?php echo $this->Html->script( array( 'jquery.price_format.min','jquery.maskedinput.min', 
'obras/preco' ), array( 'inline' => false ) ) ?>
<p class="lead">
	<?php 
	$string = utf8_encode($cliente['Cliente']['nome']);
	echo $this->Html->link( "Obras ( ".substr($string,0,25).')', array( 'controller' => 'obras', 'action' => 'index',$cliente['Cliente']['id'], $this->Session->read('Auth.User.nivel') => true ) )
		   ?>
	:: Cadastro
</p>

<div class="row">
    <?php echo $this->Form->hidden( 'cliente_id',array('value'=>$cliente['Cliente']['id'])); ?>
    <?php echo $this->Form->hidden( 'obra_id',array('value'=>$obra)); ?>
    <div class="coluna">
        <?php  echo $this->Form->input( 'produto_id', array( 'class' => 'span3','options'=>$produtos,'empty'=>'[ Selecione Produto ]' ) ); ?>
    </div>
    <div class="coluna"><?php echo $this->Form->input( 'preco', array('type'=>'text','label'=>'Preço', 'class' => 'span3' ) ); ?></div>
    <div class="coluna">
        <button class="btn btn-primary" id="addPreco"><i class="icon-plus-sign icon-white"></i>&nbsp;</button>
    </div>
</div>
<div class="row" id="listPrecos">
   
</div>
<style>
.row{
    padding: 20px;
   
}
.row .coluna{
    float:left;
    width: 300px;
}
.coluna .btn{
    margin-top: 24px;
}
</style>