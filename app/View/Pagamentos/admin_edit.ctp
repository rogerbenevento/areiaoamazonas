<?php echo $this->Html->script(array('jquery.price_format.1.7.min', 'jquery-ui-1.8.21.custom.min', 'pagamentos/edit'), array('inline' => false)) ?>

<p class="lead"><?php echo $this->Html->link( 'Finalizar Pedido '.$pedido['Pedido']['id'], array( 'controller' => 'pedidos', 'action' => 'finalizar',$pedido['Pedido']['id'], $this->params['prefix'] => true ) ) ?> :: Editar Forma Pagamento</p>
<!-- Gestão da forma de pagamento -->
<div class="">
    <table >
        <tr>
            <td><b>Pedido:</b></td>
            <td><?php echo $pedido['Pedido']['id']?></td>
        </tr>
        <tr>
            <td ><b>Valor:</b></td>
            <td class="valorTotal"><?php echo $model->moedaBr($model->valorTotal()); ?></td>
        </tr>
        <tr>
            <td ><b>Arredondamento:</b></td>
            <td class="valorArredondamento"><?php echo $model->moedaBr($pedido['Pedido']['arredondamento']); ?></td>
        </tr>
    </table>
</div>
<br>
<div class="well">
    <form id="frmPagamento">
        <p><button type="button" class="btn btn-success addFormaPagto">Nova forma de pagamento</button></p>
        <div class="control-group addFormaPagtoBox" style="display: none;">
                <p class="lead">Adição de nova forma de pagamento</p>
                <div class="input">
                        VALOR:<input type="text" name="valor" id="valor" class="span1" placeholder="Valor" />
                        <input type="hidden" name="forma_pagto_validate" id="forma_pagto_validate"/>
                        &nbsp;<?php echo $this->Form->select('forma_pagto', $tpPagamentos, array('id'=>'forma_pagto', 'class'=>'span2','empty'=>'[Forma de Pagamento]',"title"=>'FORMA PAGAMENTO')) ?>
                        &nbsp;ENT.:<input type="text" name="entrada" id="entrada" class="span1" placeholder="Entrada" title="ENTRADA"/>
                        &nbsp;PARC.:<select name="parcelas" id="parcelas" class="span3" title="PARCELAS"></select>
                        &nbsp;TAC:<input type="text" name="tac" id="tac" class="span1" placeholder="TAC" title="TAC" />
                        &nbsp;<button type="submit" class="btn btn-success novaFormaPagto"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</button>
                </div>
        </div>
    </form>
    <table class="table table-striped table-bordered table-condensed pagamentos">
            <thead>
                    <tr>
                            <th>Forma Pagto.</th>
                            <th>Valor</th>
                            <th>Entrada</th>
                            <th>Parcelas</th>
                            <th>TAC</th>
                            <th></th>
                    </tr>
            </thead>
            <tbody>
                    <?php
                        if ($this->Session->check('pagamentos_pedidos')): ?>
                            <?php foreach ($this->Session->read('pagamentos_pedidos') as $p): ?>
                                    <tr class="<?php echo $p['item'] ?>">
                                            <td><?php echo $p['nome'] ?></td>
                                            <td class="valorPagto"><?php echo $this->Number->format($p['valor'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
                                            <td><?php echo $this->Number->format($p['entrada'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
                                            <td><?php echo $p['parcelas'] ?> parcelas - <?php echo $this->Number->format($p['valor'] / $p['parcelas'], array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
                                            <td><?php echo $this->Number->format($p['tac'] , array('before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></td>
                                            <td style="text-align: center;">
                                                    <a href="#!remover" class="removerPagto"><i class="icon-trash"></i></a>
                                            </td>
                                    </tr>
                            <?php endforeach; ?>
                    <?php endif; ?>
            </tbody>
    </table>

</div>
<!-- FIM Gestão da forma de pagamento -->
<?php
echo $this->Form->create('Pagamento');
echo $this->Form->hidden('pedidos_id',array('value'=>$pedido['Pedido']['id']));

?>
<div class="form-actions">
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ) ?><br />
</div>
<?php echo $this->Form->end() ?>

<?php echo $this->Html->link('Voltar', array('controller' => 'pedidos', 'action' => 'finalizar',$pedido['Pedido']['id'], $this->params['prefix'] => true)) ?>