<!-- CLIENTES -->
<li <?php if ($this->params['controller'] == 'clientes') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Clientes", array('controller' => 'clientes', 'action' => 'index')); ?>		
</li>
<!-- PEDIDOS -->
<li <?php if ($this->params['controller'] == 'pedidos') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Pedidos", array('controller' => 'pedidos', 'action' => 'index')); ?>		
</li>
<li <?php if ( $this->params['controller'] == 'programcao' ) echo 'class="active"' ?>>
	<?php echo $this->Html->link( "Programação", array( 'controller' => 'programacao', 'action' => 'index' ) ); ?>
</li>