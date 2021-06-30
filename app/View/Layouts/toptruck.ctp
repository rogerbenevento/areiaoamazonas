<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>AREIÃO AMAZONAS</title>
		<?php echo $this->Html->css(array("print.css")); ?>
	</head>

	<body id='areiao'>
		<div id="print">
			<a href="javascript:window.print();">Imprimir</a>
		</div>
		<div id="header">
			<?php echo $this->Html->image('toptruck.png') ?>
			<div >Top Truck Comercio de Areia, Pedra e Transportes Eireli -ME</div>
			<p>
				 e-mail: toptruckareiaepedra@uol.com.br<br />
				<!--Rua Rosa Mafei,294 - Guarulhos - SP&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp;--> 
				<b>Tel.: (11) 2436-4700</b>
				&nbsp;
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