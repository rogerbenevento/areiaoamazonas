<?php if( count( $ramos )>0 ): ?>
	<ul>
	<?php foreach ( $ramos as $p ): ?>
		<?php 
                    echo "<li id='autocomplete_{$p['Ramo']['id']}' rel='{$p['Ramo']['id']}_{$p['Ramo']['nome']}'>"
                            ."{$p['Ramo']['susep']} - {$p['Ramo']['nome']}"
                        ."</li>";
                ?>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
	<font color='red'>N&atilde;o h&aacute; sugest&otilde;es</font>
<?php endif; ?>