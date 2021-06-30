
<!-- CONTAS -->
<?php
$contas_class = ( in_array($this->params['controller'], array('compras','contas','caixa','notas','empresas'))) ? 'active' : '';
?>
<li class="dropdown <?php echo $contas_class ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Contas <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ( $this->params['controller'] == 'compras') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Compras", "/{$this->params['prefix']}/compras/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'contas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Contas", "/{$this->params['prefix']}/contas/" ); ?>
		</li>		
		<li class="divider"></li>		
		<li <?php if ( $this->params['controller'] == 'tipos_pagamentos') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Formas de Pagamentos", "/{$this->params['prefix']}/tipos_pagamentos/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'tipos_contas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Tipos de Contas", "/{$this->params['prefix']}/tipos_contas/" ); ?>
		</li>
			
	</ul>
</li>