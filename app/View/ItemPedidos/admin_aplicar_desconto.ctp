<?php if (!empty($itens)): ?>
	<?php $valorTotal = 0; ?>
	<?php foreach ($itens as $item): ?>
		<tr class="<?= $item['indice'] ?>">
			<td><?php echo $item['produto'] ?></td>
			<td class="itemQuantidade"><?php echo $this->Number->format($item['quantidade'], array('before'=>'','decimals'=>',','thousands'=>'.')).' '.$item['unidade'] ?></td>
			<td><?php echo $this->Number->format( $item['preco'], array('before'=>'R$ ','decimals'=>',','thousands'=>'.') ) ?></td>
			<td class="itemTotal"><?php echo $this->Number->format($item['preco'] * $item['quantidade'] , array('before'=>'R$ ','decimals'=>',','thousands'=>'.')) ?></td>
			<td>
				<a href="#" class="remover"><i class="icon-trash"></i></a>
			</td>
		</tr>
		<?php $valorTotal += ($item['preco'] * $item['quantidade'])  ?>
	<?php endforeach; ?>
<?php endif; ?>