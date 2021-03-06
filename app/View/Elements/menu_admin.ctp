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
<!-- PEDIDOS -->
<li <?php if ($this->params['controller'] == 'pedidos') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Pedidos", array('controller' => 'pedidos', 'action' => 'index')); ?>		
</li>
<!-- VALE EXTERNO -->
<!--
<li <?php if ($this->params['controller'] == 'vales') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Vale Ext.", array('controller' => 'vales', 'action' => 'avulso')); ?>		
</li>
-->
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
<!-- FINANCEIRO -->
<?php
$financeiro_class = ( in_array($this->params['controller'], array('compras','contas','caixa','notas','empresas'))) ? 'active' : '';
?>
<li class="dropdown <?php echo $financeiro_class ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Financeiro">Finan. <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ( $this->params['controller'] == 'empresas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Empresas", "/{$this->params['prefix']}/empresas/" ); ?>
		</li>	
		<li <?php if ( $this->params['controller'] == 'notas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Notas Fiscais", "/{$this->params['prefix']}/notas/" ); ?>
		</li>					
	</ul>
</li>
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
<!-- PROGRAMACAO -->
<li <?php if ($this->params['controller'] == 'programacao') echo 'class="active"' ?>>
<?php echo $this->Html->link('Prog.', array('controller' => 'programacao', 'action' => 'index')); ?>
</li>
<!-- RELATORIOS -->
<li class="dropdown <?php if (substr_count($this->params['action'], 'gerar') > 0 || substr_count($this->params['action'], 'planilhao')) echo 'active' ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Relat??rios <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ($this->params['controller'] == 'relatorios' and substr_count($this->params['action'], 'vendas') > 0) echo 'class="active"' ?>>
			<?php echo $this->Html->link('Vendas', "/{$this->params['prefix']}/relatorios/gerar_vendas"); ?>
		</li>
		<li <?php if ($this->params['controller'] == 'relatorios' and substr_count($this->params['action'], 'gerar_notas') > 0) echo 'class="active"' ?>>
			<?php echo $this->Html->link('Notas', "/{$this->params['prefix']}/relatorios/gerar_notas"); ?>
		</li>
		<li <?php if ($this->params['controller'] == 'relatorios' and substr_count($this->params['action'], 'frete') > 0) echo 'class="active"' ?>>
			<?php echo $this->Html->link('Pagamento Frete', "/{$this->params['prefix']}/relatorios/gerar_frete"); ?>
		</li>
	</ul>
</li>
<!-- USUARIOS -->
<li <?php if ($this->params['controller'] == 'users') echo 'class="active"' ?>>
<?php echo $this->Html->link('Usu??rios', array('controller' => 'users', 'action' => 'index')); ?>
</li>
