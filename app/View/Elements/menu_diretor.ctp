<!-- FINANCEIRO -->
<?php
$financeiro_class = ( in_array($this->params['controller'], array('compras','contas','caixa','notas','empresas'))) ? 'active' : '';
?>
<li class="dropdown <?php echo $financeiro_class ?>">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Financeiro <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li <?php if ( $this->params['controller'] == 'compras') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Compras", "/{$this->params['prefix']}/compras/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'contas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Contas", "/{$this->params['prefix']}/contas/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'caixa') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Fluxo de Caixa", "/{$this->params['prefix']}/contas/gerar_fluxo" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'taxas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Taxas&Impostos", "/{$this->params['prefix']}/taxas" ); ?>
		</li>
		<li class="divider"></li>
		<li <?php if ( $this->params['controller'] == 'notas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Notas Fiscais", "/{$this->params['prefix']}/notas/" ); ?>
		</li>
		<li <?php if ( $this->params['controller'] == 'empresas') echo 'class="active"' ?>>
			<?php echo $this->Html->link( "Empresas", "/{$this->params['prefix']}/empresas/" ); ?>
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
		<li <?php if ($this->params['controller'] == 'relatorios' and substr_count($this->params['action'], 'frete') > 0) echo 'class="active"' ?>>
			<?php echo $this->Html->link('Pagamento Frete', "/{$this->params['prefix']}/relatorios/gerar_frete"); ?>
		</li>
	</ul>
</li>