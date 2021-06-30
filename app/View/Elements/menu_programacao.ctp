<li <?php if ( $this->params['controller'] == 'programcao' ) echo 'class="active"' ?>>
	<?php echo $this->Html->link( "Programação", array( 'controller' => 'programacao', 'action' => 'index' ) ); ?>
</li>