<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>IMPERIO</title>
		<?php echo $this->Html->css(array("print.css")); ?>
		
	</head>

	<body id='areiao'>
		<div id="print">
			<a href="javascript:window.print();">Imprimir</a>
		</div>
		<div id="header">
			<?php echo $this->Html->image('imperio.png') ?>
			<div >IMP&Eacute;RIO COM&Eacute;RCIO DE AREIA E PEDRA EIRELI - ME</div>
			<p>
				e-mail: imperio.areiaepedra@uol.com.br<br />
				<b>Tel.: (11) 4655-1649</b>				
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