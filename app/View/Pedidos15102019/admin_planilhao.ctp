<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui','relatorio'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery-ui-1.8.21.custom.min', 'pedidos/index'), array('inline' => false)) ?>
<p class="lead">Planilhão</p>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th><?php echo ( 'NF' ) ?></th>			
			<th><?php echo ( 'NP' ) ?></th>
			<th><?php echo ( 'Data Venda' ) ?></th>
			<th><?php echo ( 'Vendedor' ) ?></th>
			<th><?php echo ( 'Comissão' ) ?></th>
			<th><?php echo ( 'Valor Venda' ) ?></th>
			<th><?php echo ( 'Pagamento' ) ?></th>
			<th><?php echo ( 'Parcelas' ) ?></th>
			<th><?php echo ( 'Descrição' ) ?></th>
			<th><?php echo ( 'Cliente' ) ?></th>
			<th><?php echo ( 'Telefone' ) ?></th>
			<th><?php echo ( 'CPF/CPNJ' ) ?></th>

			<th style="text-align: center !important; width: 8% !important;"></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( count($rows)>0 ): ?>
			<?php foreach ( $rows as $row ): ?>
				<?php
                                    $comissao=$valor_total=$valor_garantia=$venda=0;
                                    foreach ($row['ItemPedido'] as $item) {
                                            if (isset($item['desconto']) && !empty($item['desconto'])) {
                                                    $valor_total +=  ($item['valor_unitario'] * (1 - ($item['desconto'] / 100))) * $item['quantidade'];
                                            }else{
                                                    $valor_total += ($item['valor_unitario'] * $item['quantidade']);
                                            }
                                            $valor_garantia += $item['valor_garantia'];
                                    }//foreach 
                                   
                                    $venda=$valor_total  - $row['Pedido']['arredondamento'] -$row['Pedido']['valor_cupom'];
                                    $comissao =($venda* 0.04)+($valor_garantia*0.07);
                                    $venda+= $valor_garantia;
                                    //pr($row);
                                ?>
                                <tr>
                                        <td><?php echo $row['Pedido']['nota_fiscal'] ?></td>			
                                        <td><?php echo $row['Pedido']['id'] ?></td>
                                        <td><?php echo $this->Time->format('d/m/Y', $row['Pedido']['created']) ?></td>
                                        <td><?php echo $row['User']['nome'] ?></td>
                                        <td><?php echo $model->moedaBr($comissao) ?></td>
                                        <td><?php echo $model->moedaBr($venda) ?></td>
                                        <td><?php echo (isset($row['Pagamento'][0]['TipoPagamento']['nome']))?$row['Pagamento'][0]['TipoPagamento']['nome']:''; ?></td>
                                        <td><?php echo $row['Pagamento'][0]['parcelas'] ?></td>
                                        <td><?php echo (isset($row['ItemPedido'][0]['Produto']['nome']))?$row['ItemPedido'][0]['Produto']['nome']:'' ?></td>
                                        <td><?php echo $row['Cliente']['nome'] ?></td>
                                        <td><?php echo $row['Cliente']['telefone'] ?></td>
                                        <td><?php echo $row['Cliente']['cpf_cnpj'] ?></td>
					
					<td style="text-align: center !important;">
                                            <?php echo $this->Html->image('icones/view.png', array('url'=>array('action'=>'#'),'id'=> $row['Pedido']['id'], 'width'=>18, 'title'=>'Detalhes do Pedido','class'=>'pedido-view')) ?>
                                        </td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6"><strong>No momento não há registros cadastrados.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div id="frmPedido" class="dialog-content"></div>
<div id="frmLoja" class="dialog-content"></div>