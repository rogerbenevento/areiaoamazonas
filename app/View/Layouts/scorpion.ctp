<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>SCORPION</title>
		<?php echo $this->Html->css(array("print.css")); ?>
		
	</head>

	<body id='scorpion'>
		<div id="print">
			<a href="javascript:window.print();">Imprimir</a>
		</div>
		<div id="header">
			<div class='img'>
				<?php echo $this->Html->image('scorpion.png',array('class'=>'scorpion-logo')) ?>
				<div>
					<?php echo $this->Html->image('scorpion_text.png',array('class'=>'scorpion-logo-text')) ?>
				</div>
			</div>			
			<p>
				Fone: ( 11 ) 3754-0445 - I.D. 86*133072
				<br>
				scorpion.areiaepedra@terra.com.br
			</p>

		</div>
		<div id="conteudo">
			<?php echo $content_for_layout; ?>			
			<p class='obs'><b>*N&Atilde;O ACEITAMOS RECLAMA&Ccedil;&Otilde;ES POSTERIORES REFERENTE A METRAGEM</b></p>
		</div>
		<div id="footer">
			<p>Desenvolvido pela Hoom Web Design</p>
		</div>
	</body>
</html>