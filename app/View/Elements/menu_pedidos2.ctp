<!-- PEDIDOS -->
<li <?php if ($this->params['controller'] == 'pedidos') echo 'class="active"' ?>>
	<?php echo $this->Html->link("Pedidos", array('controller' => 'pedidos', 'action' => 'index')); ?>		
</li>
<!-- PROGRAMACAO -->
<li <?php if ($this->params['controller'] == 'programacao') echo 'class="active"' ?>>
<?php echo $this->Html->link('Prog.', array('controller' => 'programacao', 'action' => 'index')); ?>
</li>
<!-- RELATORIOS -->
<li class="dropdown <?php if (substr_count($this->params['action'], 'gerar') > 0 || substr_count($this->params['action'], 'planilhao')) echo 'active' ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ($this->params['controller'] == 'relatorios' and substr_count($this->params['action'], 'vendas') > 0) echo 'class="active"' ?>>
			<?php echo $this->Html->link('Vendas', "/{$this->params['prefix']}/relatorios/gerar_vendas"); ?>
		</li>

	</ul>
</li>

