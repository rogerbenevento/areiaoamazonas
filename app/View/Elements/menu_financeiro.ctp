<li <?php if ( $this->params['controller'] == 'pedidos' and  substr_count($this->params['action'],'index')==1) echo 'class="active"' ?>>
    <?php echo $this->Html->link( "Pedidos", "/{$this->params['prefix']}/pedidos/index" ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'pedidos' and  substr_count($this->params['action'],'aberto')==1) echo 'class="active"' ?>>
    <?php echo $this->Html->link( "Finalizar Pedidos", "/{$this->params['prefix']}/pedidos/abertos" ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'pedidos' and  substr_count($this->params['action'],'finalizados')==1) echo 'class="active"' ?>>
    <?php echo $this->Html->link( "Pedidos Finalizados", "/{$this->params['prefix']}/pedidos/finalizados" ); ?>
</li>
<!-- FINANCEIRO -->
<?php
$financeiro_class = ( in_array($this->params['controller'], array('compras','contas','caixa','notas','empresas'))) ? 'active' : '';
?>
<li class="dropdown <?php echo $financeiro_class ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Financeiro <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ( $this->params['controller'] == 'empresas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Empresas", "/{$this->params['prefix']}/empresas/" ); ?>
		</li>	
		<li <?php if ( $this->params['controller'] == 'notas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Notas Fiscais", "/{$this->params['prefix']}/notas/" ); ?>
		</li>
			
	</ul>
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