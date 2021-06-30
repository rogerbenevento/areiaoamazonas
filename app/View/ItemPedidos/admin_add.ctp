<?php if ($retorno): ?>
<tr class="<?php echo $item['indice'] ?>">
	<td><?php echo $item['produto'] ?></td>
	<td class="itemQuantidade"><?php echo $item['quantidade'].' '. $item['unidade'] ?></td>
	<td><?php echo $item['unidade']?></td>
	<td><?php echo $this->Number->format( $item['valor'], array('before'=>'R$ ','decimals'=>',','thousands'=>'.') ) ?></td>
	<td class="itemTotal"><?php echo $this->Number->format( $item['valor'] * $item['quantidade'], array('before'=>'R$ ','decimals'=>',','thousands'=>'.') ) ?></td>
	<td>
		<a href="#" class="remover"><i class="icon-trash"></i></a>
	</td>
</tr>
<?php else: ?>
	<?php echo $retorno; ?>
<?php endif; ?>