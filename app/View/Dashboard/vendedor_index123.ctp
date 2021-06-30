<?php echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('jquery.price_format.1.7.min', 'jquery-ui-1.8.21.custom.min', 'simpleAutoComplete', 'pedidos/emitir'), array('inline' => false)) ?>

<script>
    $(function(){
	$('#btn-limpar').click(function(){
	    location.href=APP+'/<?php echo $this->Session->read('Auth.User.nivel');?>/dashboard/index';
	});
	$('#btn-enviarpedido').click(function(){
	    location.href=APP+'/<?php echo $this->Session->read('Auth.User.nivel');?>/pedidos/edit';
	});
    })
</script>

<p class="lead">Dashboard</p>

<!-- Div do Produto -->
	<div class="well form-inline boxProduto">
            <form id="frmBuscarProduto">
		<input type="hidden" name="produto_id" id="produto_id" />
		<input type="text" name="produto" id="produto" class="span4" placeholder="Produto..." />&nbsp;
		<div class="input-append">
			<input type="number" name="quantidade" id="quantidade" class="span1" placeholder="Quantidade..." value="1" />
		</div>
		&nbsp;<button type="submit" class="btn btn-success"><i class="icon-search icon-white"></i>&nbsp;Buscar</button>
            
                <!-- Mostra os produtos disponíveis -->
                    <div id="produtos" style="display: none;" class="well">
                        <form>
                        <div id="conatiner_produtos"></div>
                        </form>
                    </div>
                <!-- Fim da lista de produtos -->
	    </form>
	</div>
        <!-- Fim Div do Produto -->
	<!-- tabela de itens do pedido -->
	<p class="lead">Listagem de itens:</p>
        <div id="tableItensPedidos">
            <?php echo $this->element('_table_itens_pedidos');?>
        </div>
        <!-- Fim da tabela de itens do pedido -->
	<!-- Box para adicionar valor de garantia extendida ao produto -->
        <div id="garantiaExtendida" class="well form-inline" style="display:none">
            <form>
                <input type="hidden" name="item_garantia" id="item_garantia" />
                <label for="periodo_garantia">Garantia de </label>
                <div class="input-append">
                        <input type="number" name="periodo_garantia" id="periodo_garantia" class="span1" min="1" max="10" />
                        <span class="add-on">ano(s)</span>
                </div>
                &nbsp;&nbsp;&nbsp;
                <label for="valor_garantia">Valor</label>
                <div class="input-prepend">
                        <span class="add-on">R$</span>
                        <input type="text" name="valor_garantia" id="valor_garantia" class="span1" />
                </div>
                <input type="submit" class="btn btn-success aplicarGarantia" value="Aplicar garantia"/>
            </form>
        </div>
        <!-- Fim do box de garantia extendida -->
	<!--  Aplicação de Desconto por item -->		
        <div id="aplicarDesconto" class="well form-inline" style="display: none;">
            <form>
                <input type="hidden" name="item_carrinho" id="item_carrinho" />
                <label for="valor_desconto">Aplicar desconto: </label>
                <div class="input-append">
                        <input type="text" name="valor_desconto" id="valor_desconto" class="span1" />
                        <span class="add-on">%</span>
                </div>

                <!-- área para autenticação do gerente -->
                <div class="autenticarDesconto" style="display: none;">
                        <p>
                                <label for="desconto_user">Login: </label>
                                <input type="text" name="desconto_user" id="desconto_user" maxlenth="50" />
                        </p>
                        <p>
                                <label for="desconto_pass">Senha: </label>
                                <input type="password" name="desconto_pass" id="desconto_pass" maxlenth="50" />
                        </p>
                </div>

                <!-- área para informar o token de administrador -->
                <div class="tokenDesconto" style="display: none;">
                        <div id="resultToken"></div>
                        <p>
                                <label for="token">Token: </label>
                                <input type="text" name="token" id="token" maxlength="10" />
                                <input type="button" value="Gerar Token" id="gerarToken" />
                        </p>
                </div>
                <input type="submit" value="Aplicar Desconto" id="aplicar" class="btn btn-success" />
            </form>
        </div>
        <!-- Fim da Aplicação de Desconto por item -->
	<!-- Aplicação de Desconto ao Pedido -->
	<div class="well form-inline">
            <form>
		<div class="pull-left">
			<label for="desconto">Cupom de Desconto: </label>
			<div class="input-append">
                            <input type="text" name="desconto" id="cupom_desconto" class="span3" placeholder="Cupom de Desconto" value="<?php if($this->Session->check('desconto.cupom')) echo $this->Session->read('desconto.cupom');?>"/>
				
			</div>
			<input type="submit" id="calcularCupomDesconto" class="btn btn-success" value="Calcular">
		</div>
            </form>
	</div>
	<!-- Fim da parte de Desconto -->
<!-- Fim da parte de Desconto -->
<div class="form-actions">
    <button type="button" id="btn-limpar" class="btn btn-primary">Limpar</button>
    <button type="button" id="btn-enviarpedido" class="btn btn-success">Enviar para Pedido</button>
</div>