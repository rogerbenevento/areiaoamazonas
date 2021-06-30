 <?php
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment;filename="'.(empty($nome_arquivo)? 'arquivo' : $nome_arquivo));
	header('Cache-Control: max-age=0');
	echo $content_for_layout;
?> 