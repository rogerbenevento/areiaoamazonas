<li 
    <?php 
        if ( $this->params['controller'] == 'pedidos' 
            and substr_count($this->params['action'],'gerar')==0
            and substr_count($this->params['action'],'aberto')==0
            and substr_count($this->params['action'],'finaliza')==0
            )echo 'class="active"' 
    ?>
    >
        <?php echo $this->Html->link( "Pedidos", array( 'controller' => 'pedidos', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'programcao' ) echo 'class="active"' ?>>
	<?php echo $this->Html->link( "Programação", array( 'controller' => 'programacao', 'action' => 'index' ) ); ?>
</li>

<!--<li <?php if ( $this->params['controller'] == 'clientes' ) echo 'class="active"' ?>>
        <?php echo $this->Html->link( "Clientes", array( 'controller' => 'clientes', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>-->