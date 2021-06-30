<?php if( count( $produtos ) ): ?>
	<ul>
	<?php foreach ( $produtos as $p ): ?>
		<?php 
                    echo "<li id='autocomplete_{$p['Produto']['id']}' rel='{$p['Produto']['id']}_{$p['Produto']['nome']}'>"
                            ."{$p['Produto']['nome']}"
                        ."</li>";
                ?>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
	<font color='red'>N&atilde;o h&aacute; sugest&otilde;es</font>
<?php endif; ?>