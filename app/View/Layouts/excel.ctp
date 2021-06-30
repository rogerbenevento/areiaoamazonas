 <?php
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.(empty($nome_arquivo)? 'relatorio'.date('YmdHi') : $nome_arquivo).'.xls"');
	header('Cache-Control: max-age=0');
	echo $content_for_layout;
?> 