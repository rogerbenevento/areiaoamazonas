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

<!--<li <?php if ( $this->params['controller'] == 'clientes' ) echo 'class="active"' ?>>
        <?php echo $this->Html->link( "Clientes", array( 'controller' => 'clientes', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>-->