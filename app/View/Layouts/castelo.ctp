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
			<?php echo $this->Html->image('icone-castelo.png', array('style'=>'width: 82px; margin: -10px 0 0 0;')) ?>
		<center>
			<?php echo $this->Html->image('castelo.png', array('style'=>'float:none;      margin-left: -50px;  width: 152px;')) ?>
		</center>
			<!-- <div>Karen Juliana Felix Julio Comercio de Areia e Pedra</div> -->
			<p style="margin-top: -30px; text-align: right;">
				 e-mail: distribuidoracastelo@uol.com.br<br />
				<b>Tel.: (11) 94757-0283</b>
				
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