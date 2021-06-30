<?php if( count( $clientes ) ): ?>
	<ul>
	<?php foreach ( $clientes as $p ): ?>
		<?php 
                    echo "<li id='autocomplete_{$p['Cliente']['id']}' rel='{$p['Cliente']['id']}_{$p['Cliente']['nome']}_{$p['Cliente']['vendedor_id']}'>"
                            ."{$p['Cliente']['cpf_cnpj']} - {$p['Cliente']['nome']}"
                        ."</li>";
                ?>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
	<font color='red'>N&atilde;o h&aacute; sugest&otilde;es</font>
<?php endif; ?>