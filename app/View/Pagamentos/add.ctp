<tr class="<?php echo $p['item'] ?>">
        <td><?php echo $p['nome']?></td>
        <td class="valorPagto"><?php echo $this->Number->format($p['valor'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
        <td><?php echo $this->Number->format($p['entrada'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
        <td><?php echo $p['parcelas'] ?> parcelas - <?php echo $this->Number->format($p['valor'] / $p['parcelas'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
        <td><?php echo $this->Number->format($p['tac'] , array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
        <td style="text-align: center;">
                <a href="#!remover" class="removerPagto"><i class="icon-trash"></i></a>
        </td>
</tr>