<?php if( count( $fornecedores ) ): ?>
	<ul>
	<?php foreach ( $fornecedores as $p ): ?>
		<?php 
                    echo "<li id='autocomplete_{$p['Fornecedor']['id']}' rel='{$p['Fornecedor']['id']}_{$p['Fornecedor']['nome']}'>"
                            ."{$p['Fornecedor']['nome']}"
                        ."</li>";
                ?>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
	<font color='red'>N&atilde;o h&aacute; sugest&otilde;es</font>
<?php endif; ?>