<!-- CADASTROS -->
<?php
	$financeiro_class = ( in_array($this->params['controller'], array('produtos','clientes','fornecedores','motoristas','vendedores'))) ? 'active' : '';
?>
<li class="dropdown <?php echo $financeiro_class ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<!-- PRODUTO -->
		<li <?php if ($this->params['controller'] == 'produtos') echo 'class="active"' ?>>
			<?php echo $this->Html->link("Produtos", array('controller' => 'produtos', 'action' => 'index')); ?>
		</li>
		<!-- CLIENTES -->
		<li <?php if ($this->params['controller'] == 'clientes') echo 'class="active"' ?>>
			<?php echo $this->Html->link("Clientes", array('controller' => 'clientes', 'action' => 'index')); ?>		
		</li>
		<!-- FORNECEDORES -->
		<li <?php if ($this->params['controller'] == 'fornecedores') echo 'class="active"' ?>>
			<?php echo $this->Html->link("Fornecedores", array('controller' => 'fornecedores', 'action' => 'index')); ?>
		</li>
		<!-- MOTORISTAS -->
		<li <?php if ($this->params['controller'] == 'motoristas') echo 'class="active"' ?>>
			<?php echo $this->Html->link("Motoristas", array('controller' => 'motoristas', 'action' => 'index')); ?>
		</li>
		<!-- VENDEDORES -->
		<li <?php if ($this->params['controller'] == 'vendedores') echo 'class="active"' ?>>
			<?php echo $this->Html->link("Vendedores", array('controller' => 'vendedores', 'action' => 'index')); ?>
		</li>
	</ul>
</li>
<!-- VEICULOS -->
<li class="dropdown <?php if (in_array($this->params['controller'], array('multas','abastecimentos'))) echo 'active' ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Multas&Abast. <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ( $this->params['controller'] == 'multas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Multas", "/{$this->params['prefix']}/multas/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'abastecimentos') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Abastecimentos", "/{$this->params['prefix']}/abastecimentos/" ); ?>
		</li>	
	</ul>
</li>