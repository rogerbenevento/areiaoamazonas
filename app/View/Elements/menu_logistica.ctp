<li <?php if ( $this->params['controller'] == 'compras' ) echo 'class="active"' ?>>
    <?php echo $this->Html->link( 'Compras', array( 'controller' => 'compras', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'entregas' and $this->params['action']!='logistica_gerar_relacao') echo 'class="active"' ?>>
    <?php echo $this->Html->link( 'Entregas', array( 'controller' => 'entregas', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'fornecedores' ) echo 'class="active"' ?>>
    <?php echo $this->Html->link( 'Fornecedores', array( 'controller' => 'fornecedores', 'action' => 'index', $this->params['prefix'] => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'entregas' and $this->params['action']=='logistica_gerar_relacao' ) echo 'class="active"' ?>>
    <?php echo $this->Html->link( "Relação de Entregas", array( 'controller' => 'entregas', 'action' => 'gerar_relacao', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'produtoslojas' and $this->params['action']=='logistica_gerar_relacao' ) echo 'class="active"' ?>>
    <?php echo $this->Html->link( 'Relação de Estoque', array( 'controller' => 'produtoslojas', 'action' => 'gerar_relacao', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>
<li <?php if ( $this->params['controller'] == 'transferencias' ) echo 'class="active"' ?>>
    <?php echo $this->Html->link( 'Transferências', array( 'controller' => 'transferencias', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>