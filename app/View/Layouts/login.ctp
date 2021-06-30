<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>AREIÃO AMAZONAS</title>
	<?php echo $this->Html->css( array( 'login', 'bootstrap', 'bootstrap-responsive', 'panel' ) ) ?>
	<?php echo $this->Html->script( array( 'jquery','config', 'bootstrap' ) ) ?>
	<?php echo $scripts_for_layout ?>
	<link rel="shortcut icon" href="<?php echo Router::url( '/img/favicon.ico' ) ?>" />
</head>
<body class="login">
	<div class="container">
		<div class="content">
			<div class="hero-unit">
				<div class="page-header"><h1>AREIÃO AMAZONAS</h1></div>
				<?php if ( $message = $this->Session->flash() ): ?>
					<div class="alert alert-danger"><?php echo $message ?></div>
				<?php endif; ?>
				<?php echo $content_for_layout ?>
			</div>
		</div>
		<footer>
			<p>Desenvolvido por <?php echo $this->Html->link( 'Hoom Web', 'http://www.hoomweb.com' ) ?></p>
		</footer>
	</div>
	<?php if (Configure::read('debug') == 2) echo $this->element('sql_dump') ?>
</body>
</html>