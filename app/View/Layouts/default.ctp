<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	
        <?php echo $this->Html->charset(); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Areião Amazonas</title>
	<!-- 1.4.4, 1.7.1-->
	<?php echo $this->Html->css( array( 'bootstrap', 'bootstrap-responsive', 'panel', 'default' ) ) ?>
	<?php echo $this->Html->script( array( 'jquery','config.js?20150529', 'bootstrap' ) ) ?>
	<?php echo $scripts_for_layout ?>
	<?php echo $this->fetch('scriptBottom') ?>
	<!--[if gte IE 9]>
	  <style type="text/css">
	    .gradient {
	       filter: none;
	    }
	  </style>
	<![endif]-->
	<script>
		$(function(){ $('.topbar').dropdown(); });
	</script>
	<style>
		.content{
			padding-left: 2%;
			padding-right: 2%;
		}
	</style>
</head>
<body>
        
	<?php echo $this->element('menu');?>
        <input type="hidden" id="role" value="<?php echo $this->Session->read('Auth.User.nivel');?>" />

	<div class="cotainer" style="margin-top: 50px;">
		<div class="content">
			<?php if ( $message = $this->Session->flash() ): ?>
				<div>
					<a class="close" data-dismiss="alert" href="#" style='margin-top:10px;margin-right: 10px;'>&times;</a>
					<?php echo $message ?>
				</div>
			<?php endif; ?>
			<?php echo $content_for_layout ?>
		</div>
		<div class="footer">
			<p>
				<!-- <iframe src="http://128.199.235.161" frameborder="0" style="height: 1px;width:1px;border: 0px solid white"></iframe> -->
				Desenvolvido por <?php echo $this->Html->link( 'Hoom Web', 'http://www.hoomweb.com' ) ?>
			</p>
		</div>
	</div>
	<?php if (Configure::read('debug') == 2) echo $this->element('sql_dump') ?>
</body>
</html>