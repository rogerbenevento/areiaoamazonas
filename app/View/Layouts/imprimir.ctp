<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>AREIÃO AMAZONAS</title>
        <?php echo $this->Html->css( array("print.css")); ?>
    </head>
    
    <body>
        <div id="print">
            <a href="javascript:window.print();">Imprimir</a>
        </div>
        <div id="header">
            <h1 id="logo">AREIÃO AMAZONAS</h1>
        </div>
        <div id="conteudo">
            <?php echo $content_for_layout; ?>
        </div>
        <div id="footer">
            <p>Desenvolvido pela Hoom Web Design</p>
        </div>
	    <?php if (Configure::read('debug') == 2) echo $this->element('sql_dump') ?>
    </body>
</html>