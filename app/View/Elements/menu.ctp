<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo Router::url(array('controller' => 'dashboard', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true)) ?>">
				AREIÃO AMAZONAS
			</a>
			<div class="nav-collapse">
				<ul class="nav">
					<?php echo $this->element( 'menu_'.$this->Session->read('Auth.User.nivel') ); ?> 
				</ul>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->Session->read('Auth.User.nome') ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
						    <li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout', $this->Session->read('Auth.User.nivel') => true)) ?></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>