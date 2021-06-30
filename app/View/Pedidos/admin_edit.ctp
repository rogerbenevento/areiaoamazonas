<?php
$tabindex = 1;
echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false))
?>
<?php echo $this->Html->script(array('simpleAutoComplete2', 'jquery.price_format.min', 'jquery-ui.min', 'pedidos/edit'), array('inline' => false)) ?>
<input type="hidden" id="pedido_id" value="<?php if (isset($pedido)) echo $pedido['Pedido']['id'] ?>"/>
<style type="text/css">
    .boxCliente,.boxProduto, .data_entrega { position: relative !important;}
</style>
<p class="lead">
    <?php
    echo $this->Html->link('PEDIDOS', array('controller' => 'pedidos', 'action' => 'index')) . " :: ";
    echo (isset($pedido)) ? "Editar" : "Emitir";
    echo" Pedido"
    ?>
</p>

<!-- Div do Cliente -->
<div class="well form-inline boxCliente">
    <input type="text" id="PedidoCliente" tabindex="<?php echo $tabindex++ ?>" class='span6' placeholder="Selecione um Cliente" value="<?php if (isset($this->data['Cliente']['nome'])) echo $this->data['Cliente']['nome'] ?>"/>
    &nbsp;<button type="button" class="btn btn-add-cliente"><i class="icon-plus-sign"></i>&nbsp;Cadastrar novo Cliente</button>
   

   
    <div id="boxObra" style="display: none;margin-top: 20px;">
        <div class="input" >
            Pesquisa:<input id="developer">
            <select id="ObraId" class="span5" tabindex="<?php echo $tabindex++ ?>" >
                <option value="">[Obras]</option>
            </select>
        </div>
    </div>
</div>




<!-- Fim Div do Cliente -->
<!-- Div do Produto -->
<div class="well form-inline boxProduto">
    <form id="frmProduto">
<!--		<input type="hidden" name="produto_id" id="produto_id" />
            <input type="text" name="produto" tabindex="<?php echo $tabindex++ ?>"  id="produto" class="span4" placeholder="Produto..." />&nbsp;-->
        <?php echo $this->Form->input('produto_id', array('label' => "", 'options' => $produtos, 'tabindex' => $tabindex++, 'div' => array('style' => "float:left;"))); ?>
        <div class="input-append">
            <input type="text" name="quantidade" tabindex="<?php echo $tabindex++ ?>"  id="quantidade" class="span1" placeholder="Quantidade..."  />
            <?php echo $this->Form->input('unidade', array('label' => '', 'div' => array(), 'options' => $unidades, 'empty' => '[Unidade]', 'tabindex' => $tabindex++, 'class' => 'span1')) ?>
        </div><!--
        <div class="input-prepend input-append">
                <div class="add-on" style="margin-right: -5px;">$</div>
                <input type="text" tabindex="<?php echo $tabindex++ ?>" name="valor" id="valor" class="span1 input" placeholder="0,00"/>			
                <span class="unidadeMedida add-on" style="margin-left: -5px;"></span>
        </div>-->
        &nbsp;<button type="submit" tabindex="<?php echo $tabindex++ ?>"  class="btn btn-success"><i class="icon-search icon-white"></i>&nbsp;Inserir</button>
        <!-- Mostra os produtos disponíveis -->
        <!--		<div id="produtos" style="display: none;" class="well">
                                <form>
                                        <div id="conatiner_produtos"></div>
                                </form>
                        </div>-->
        <!-- Fim da lista de produtos -->
    </form>
</div>
<!-- Fim Div do Produto -->
<!-- tabela de itens do pedido -->
<p class="lead">Listagem de itens:</p>
<div id="tableItensPedidos">
    <?php echo $this->element('pedidos/itenspedido'); ?>
</div>
<!-- Fim da tabela de itens do pedido -->
<?php if (count($this->data['ItemPedido']) > 0) { ?>
<p class="lead">Carregamento(Vales):</p>
<div id="itens">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width:100px"></th>
                    <th>Status</th>
                    <th>Nome</th>
                    <th>Qtde.</th>
                    <th>Valor</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $valorTotal = 0; #pr($pedido); 
                $imprimir_form = false;
                $item_id = $vale_id = null;
                foreach ($this->data['ItemPedido'] as $key => $item):

                    $total = $item['quantidade'] * $precos[$item['produto_id']];
                    ?>
                    <tr>
                        <td style="text-align: center">
                            <div class="btn-group">
                                <?php
                                #if ($item['Vale']['status'] == 0) {
                                    $imprimir_form = (!$vale_id) ? true : false;
                                    $produto_form = $item['Produto']['nome'];
                                    $quantidade_form = $item['quantidade'];
                                    $unidade_form = $item['unidade'];
                                    $vale_id = $item['Vale']['id'];
                                    $item_id = $item['id'];
                                    ?>
                                    <a href="<?php echo $this->Html->url(array('controller' => 'vales', 'action' => 'finalizar/' . $item['Vale']['id'], $item['pedido_id'])) ?>" class="btn" title="FINALIZAR VALE">
                                        <?php echo $this->Html->image('icones/edit.png', array('width' => 16)); ?>
                                    </a>
                                <?php
                                #} 
                                ?>
                            </div>
                        </td>
                        <td><?php echo $status[$item['Vale']['status']]; ?></td>
                        <!--<td><?php echo $item['Vale']['id']; ?></td>-->
                        <td><?php echo $item['Produto']['nome']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <!-- <td><?php echo moedaBr($precos[$item['produto_id']]);?></td> -->	
                         <td><?php echo moedaBr($item['valor']);?></td>   				
                        <td><?php echo $item['valor_total']; ?></td>
                    </tr>
                    <?php $valorTotal += ($item['valor_total']); ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><strong>Valor total: </strong></td>
                    <td><strong><?php echo moedaBr($valorTotal); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
    } 
    ?>

    <!-- Fim da Aplicação de Desconto por item -->

    <?php echo $this->Form->create('Pedido', array('inputDefaults' => array('div' => false, 'label' => false), 'action' => 'edit')) ?>
    <?php echo $this->Form->hidden('cliente_id') ?>
    <?php echo $this->Form->hidden('obra_id') ?>
    <?php echo $this->Form->input('data_entrega', array('label' => 'Data Entrega:', 'class' => 'span6', 'type' => 'text', 'required' => true, 'tabindex' => $tabindex++)) ?>
    <?php echo $this->Form->input('periodo_id', array('label' => 'Periodo:', 'options' => $periodos, 'class' => 'span6', 'tabindex' => $tabindex++)) ?>
    <?php echo $this->Form->input('user_id', array('label' => "Vendedor:", 'options' => $vendedores, 'tabindex' => $tabindex++, 'class' => 'span6', 'empty' => '[ Selecione um Vendedor ]', 'required' => true)); ?>
    <?php echo '<label for="PedidoObservacao">Observação:</label>' . $this->Form->input('observacao', array('style' => 'width:99%', 'tabindex' => $tabindex++)) ?>

    <div class="form-actions">
        <button type="submit" tabindex="<?php echo $tabindex++ ?>" class="btn btn-primary btn-large btn-block">Salvar Pedido</button>
    </div>
    <?php echo $this->Form->end(); #pr($_SESSION)?>

    <?php echo $this->Html->link('Voltar', array('action' => 'index')) ?>