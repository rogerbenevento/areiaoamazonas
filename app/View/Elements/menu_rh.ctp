<li <?php if ( $this->params['controller'] == 'Vendedores' ) echo 'class="active"' ?>>
        <?php echo $this->Html->link( "Funcionários", array( 'controller' => 'vendedores', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) ); ?>
</li>