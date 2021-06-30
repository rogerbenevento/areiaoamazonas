<?php $page = basename($_SERVER['SCRIPT_NAME']); ?>
<!DOCTYPE html><!-- G5Framework -->

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<head>
<meta content="text/html" charset="UTF-8" http-equiv="Content-Type"/>

<title>
	Eletroja | 
	<?php echo $title_for_layout?>
</title>

<!--[if lt IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,chrome=1" />
<![endif]-->
<!--[if IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<![endif]-->
<?php echo $this->Html->meta(array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'chrome=1, IE=edge' )); ?>
<?php echo $this->Html->meta(array( 'http-equiv' => 'imagetoolbar', 'content' => 'no' )); ?>
<?php echo $this->Html->meta(array( 'name' => 'author', 'content' => 'Arthur Fanti - HoomWeb' )); ?>
<?php echo $this->Html->meta(array( 'name' => 'copyright', 'content' => '&copy; 2011 Arthur Fanti' )); ?>

<link rel="canonical" href="http://www.cenariogourmet.com.br/<?php echo $page; ?>" />

<?php echo $this->Html->css( array('base', 'handheld', 'jquery.lightbox-0.5') ) ?>
<?php echo $this->Html->script( array('jquery','modernizr-2.0.custom', 'jquery.kwicks-1.5.1.pack', 'jquery.easing.1.3', 'script', 'config') ); ?>
<?php echo $this->fetch('scriptBottom'); ?>

</head>
<body>
	<div id="wrapper">
		<header id="top">
			<a href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'index') ) ?>" title="Home">
				<?php echo $this->Html->image('logo.png', array('class' => 'lft-img')) ?>
			</a>
				<nav class="brdr shdw">
					<ul>
						<li>
							<a href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'index') ) ?>" class="<?php if ($this->params->action == 'display' || $this->params->action == 'index') { ?>active<?php } ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'quem_somos') ) ?>" class="<?php if ($this->params->action == 'quem_somos') { ?>active<?php } ?>">Sobre Nós</a>

						<div class="dropdown_2columns">
							<div class="col_2 btmspc divisorh dwnarrw">
								<em>Eletroja</em>
							</div>
							<div class="col_2 btmspc divisorh">
								<a href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'quem_somos') ) ?>" class="no-content linkint">
									<p>Conheça a trajetória desta iniciativa da amante<br/>de gastronomia - e arquiteta - com vasta vivência<br/>em eventos, <strong>Claudia Matarazzo</strong>.</p>
								</a>
							</div>
							<div class="col_1 divisorv">
								<?php echo $this->Html->image('minis.png') ?>
							</div>
							<div class="col_1 dstq btmspc"><cite>Muita criatividade e<br/>ingredientes de qualidade,<br/>combinados para criar<br/>os mais deliciosos<br/>cardápios!</cite></div>
							<div class="col_2 signature">Cenário Gourmet - Confeitaria & Gastronomia &nbsp;</div>
						</div>
					</li>
					<li>
						<a>Produtos</a>
						<div class="dropdown_5columns">
							<div class="col_5 btmspc divisorh dwnarrw">
								<em>Conheça e delicie-se com nossas linhas de produtos!</em>
							</div>
							<div class="col_half divisorv">
								<?php echo $this->Html->image('acucar.png') ?><br/>
								<?php echo $this->Html->link('Bolos', array('controller' => 'pages', 'action' => 'bolos') ) ?><br/>
								<?php echo $this->Html->link('Cupcakes', array('controller' => 'pages', 'action' => 'cupcakes') ) ?><br/>
								<?php echo $this->Html->link('Sobremesas', array('controller' => 'pages', 'action' => 'sobremesas') ) ?><br />
								<?php echo $this->Html->link('Doces', array('controller' => 'pages', 'action' => 'docinhos') ) ?><br/>
								<?php echo $this->Html->link('Cafeteria', array('controller' => 'pages', 'action' => 'cafeteria') ) ?> <br>
								<?php echo $this->Html->link('Light & Diet', array('controller' => 'pages', 'action' => 'light_diet') ) ?>
							</div>
							<div class="col_half btmspc">
								<?php echo $this->Html->image('sal.png') ?><br/>
								<?php echo $this->Html->link('Tortas', array('controller' => 'pages', 'action' => 'tortas') ) ?><br/>
								<?php echo $this->Html->link('Tortas Light', array('controller' => 'pages', 'action' => 'tortas_light') ) ?><br/>
								<?php echo $this->Html->link('Salgados', array('controller' => 'pages', 'action' => 'salgados') ) ?><br/>
								<?php echo $this->Html->link('Canapés', array('controller' => 'pages', 'action' => 'canapes') ) ?><br/>
								<?php echo $this->Html->link('Terrines', array('controller' => 'pages', 'action' => 'terrines') ) ?><br/>
								<?php echo $this->Html->link('Finger Food', array('controller' => 'pages', 'action' => 'finger_food') ) ?><br/>
							</div>
							<div class="col_5 btmspc divisorh dwnarrw">&nbsp;</div>
							<div class="col_third dstq">
								<?php echo $this->Html->link('Eventos Corporativos', array('controller' => 'pages', 'action' => 'corporativos') ) ?><br/>
								Conheça nossos cardápios especiais<br/>para eventos corporativos.
							</div>
							<div class="col_third dstq btmspc">
								<?php echo $this->Html->link('Eventos Temáticos', array('controller' => 'pages', 'action' => 'tematicos') ) ?><br/>
								Cardápios inusitados para datas<br/>e temas comemorativos.
							</div>
							<div class="col_third dstq btmspc">
								<?php echo $this->Html->link('Eventos Infantis', array('controller' => 'pages', 'action' => 'infantis') ) ?><br/>
								Cardápios e decorações lúdicas<br/>para eventos infantis.
							</div>
							<div class="col_5 signature">Cenário Gourmet - Confeitaria & Gastronomia &nbsp;</div>
							
						</div>
					</li>
					<li>
						<?php echo $this->Html->link('Novidades', array('controller' => 'pages', 'action' => 'novidades') ) ?>
					</li>
					<li>
						<?php echo $this->Html->link('Eventos', array('controller' => 'pages', 'action' => 'eventos') ) ?>
					</li>
					<li>
						<?php echo $this->Html->link('Contato', array('controller' => 'contatos', 'action' => 'index') ) ?>
					</li>
				</ul>
			</nav>
		</header><!--end #top-->

		<?php echo $this->fetch('content'); ?>

		<footer id="bottom">
			<section class="cols cols2 footer">
				<article class="col first">
					<?php echo $this->Html->image('blogNovo.png', array('class' => 'lft-img')); ?>
				</article>
				<article class="col">
					<?php echo $this->Html->image('fonesNovo.png', array('class' => 'rgt-img')); ?>
				</article>
			</section>
            
			<section class="copyright">
				<p style="padding:2px 8px !important; background:#51381f;" class="brdr shdw">
					R. Bogotá, 16 – 1 andar Salão Studio 3, Jd. Rincão (próximo ao Hotel Arujá)<br>
					<small>Cenário Gourmet | Copyright &copy; <?php echo date("Y"); ?>. Todos os direitos reservados.</small>
				</p>
				<a href="http://www.hoomweb.com/" target="_blank" title="desenvolvido por">
					<?php echo $this->Html->image('logo-hoom.png', array('width' => '120', 'height' => '46', 'class' => 'rgt-img')) ?>
				</a>
			</section>
		</footer><!--end #bottom-->
	</div><!--end #wrapper-->