<?php if( count( $vendedores ) ): ?>
	<ul>
	<?php foreach ( $vendedores as $p ): ?>
		<?php 
                    echo "<li id='autocomplete_{$p['Vendedor']['id']}' rel='{$p['Vendedor']['id']}_{$p['Vendedor']['nome']}'>"
                            ."{$p['Vendedor']['cpf_cnpj']} - {$p['Vendedor']['nome']}"
                        ."</li>";
                ?>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
	<font color='red'>N&atilde;o h&aacute; sugest&otilde;es</font>
<?php endif; ?>