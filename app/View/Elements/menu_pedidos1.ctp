<!-- PEDIDOS -->
<li <?php if ($this->params['controller'] == 'pedidos') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Pedidos", array('controller' => 'pedidos', 'action' => 'index')); ?>		
</li>
