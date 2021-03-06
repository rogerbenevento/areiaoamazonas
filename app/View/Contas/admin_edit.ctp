<?php echo $this->Html->css(array('simpleAutoComplete2', 'jqueryui/jquery-ui'), null, array('inline' => false)) ?>
<?php echo $this->Html->script(array('simpleAutoComplete','jquery-ui.min','jquery.mask','jquery.price_format.min', 'contas/edit'), array('block' => 'scriptBottom')) ?>
<p class="lead">
	<?php echo $this->Html->link('Gerenciar Contas a Pagar / Receber', array('controller' => 'contas', 'action' => 'index')) ?>
	:: Editar
</p>
<style>
	#boxPedidos{
		max-height: 200px;
		background: white;
		overflow-y: scroll;
		border: 1px solid #ccc;
		display: none;
	}
	.pull-left{
		margin-right: 20px;
	}
</style>
<?php echo $this->Form->create( 'Conta', array(  ) ); ?>
	<?php echo $this->Form->input('conta_id', array('type' => 'hidden')); ?>
	<?php echo $this->Form->input('pedido_id', array('type' => 'hidden')); ?>
	<?php echo $this->Form->input('compra_id', array('type' => 'hidden')); ?>
	<?php if (!empty($this->request->data)): ?>
		<?php echo $this->Form->input('editar', array('options' => array('uma'=>'Somente esta', 'todas'=>'A partir desta'), 'default' => 'uma', 'type'=>'radio', 'legend'=>'Alterar conta')) ?>
		<br />
	<?php endif; ?>

	<?php echo $this->Form->input( 'tipo', array( 'options' => $tipos, 'class' => 'span2', 'empty' => '(Escolha o tipo de conta)', 'label' => 'Tipo de Conta', 'div' => array('class' => 'pull-left'))); ?>
	<?php echo $this->Form->input( 'empresa_id', array( 'options' => $empresas, 'class' => 'span2', 'empty' => '[ Escolha a Empresa ]', 'label' => 'Empresa' , 'div' => array('class' => 'pull-left'))); ?>
	<?php #echo $this->Form->input( 'conta_corrente_id', array( 'options' => $contas, 'class' => 'span6', 'empty' => '[ Escolha a conta destino ]', 'label' => 'Destino' ) ); ?>
	<?php #echo $this->Form->input( 'tipo_pagamento_id', array( 'options' => $tipos_pagamentos, 'class' => 'span6', 'empty' => '[ Escolha o tipo de pagamento ]', 'label' => 'Pagamento' ) ); ?>
	<?php echo $this->Form->input( 'tipo_conta_id', array( 'options'=>$tipos_contas,'class' => 'span2', 'empty' => '[ Escolha o Tipo de Conta ]', 'label' => 'Tipo Conta' ) ); ?>
	<?php #echo $this->Form->input( 'subtipo_conta_id', array( 'options'=>$subtipos_contas,'class' => 'span6', 'empty' => '[ Escolha o SubTipo de Conta ]', 'label' => 'SubTipo Conta' ) ); ?>
	<?php #echo $this->Form->input( 'empresa_id', array( 'class' => 'span6', 'empty' => '[ Escolha uma Empresa ]' ) ); ?>
	<?php echo $this->Form->input( 'tipo_pagamento_id', array( 'options' => $tipos_pagamentos, 'class' => 'span3', 'empty' => '[ Escolha o tipo de pagamento ]', 'label' => 'Tipo de Pagamento','div'=>array('class'=>'pull-left pagto_fornecedor hide')) ); ?>
	<?php echo $this->Form->input( 'fornecedor_id', array( 'options' => $fornecedores, 'class' => 'span3', 'empty' => '[ Escolha o fornecedor ]', 'div'=>array('class'=>'pagto_fornecedor hide')) ); ?>

	<?php echo $this->Form->input( 'descricao', array( 'label' => 'Descri????o', 'class' => 'span6' ) ); ?>
	<?php echo $this->Form->input( 'numero_documento', array( 'label' => 'Numero Documento', 'class' => 'span6' ) ); ?>
	
	<?php $data = ( !empty( $this->request->data ) ) ? $this->request->data['Conta']['data_vencimento'] : '';?>
	<?php echo $this->Form->input( 'data_vencimento', array( 'class' => 'span6', 'type' => 'text', 'value' => $data ) ); ?>

	<?php $valor = !empty($this->request->data['Conta']['valor']) ? $this->Number->format($this->request->data['Conta']['valor'], array('before'=>'', 'decimals'=>',', 'thousands'=>'.')) : ''; ?>
	<?php echo $this->Form->input( 'valor', array( 'class' => 'span6', 'type' => 'text', 'value' => $valor ) ); ?>
	
	<?php 
		if(!empty($this->request->data['Conta']['pago'])){
			$pago = !empty($this->request->data['Conta']['pago'])? moedaBr($this->request->data['Conta']['pago']) : '';
			echo $this->Form->input( 'pago', array( 'class' => 'span6', 'type' => 'text', 'value' => $pago ) ); 
		}
	?>

	<?php echo $this->Form->input( 'parcela', array( 'class' => 'span6', 'default' => 1 ) ); ?>
	<?php echo $this->Form->input( 'parcelas', array( 'class' => 'span6', 'default' => 1 ) ); ?>
	<?php echo $this->Form->hidden( 'intervalos'); ?>
	<div class=" well boxIntervaloParcelas" style="display: <?php echo (empty($IntervalosParcelas)?'none':'block');?>;">
		<p class="lead">Vencimento das pr??ximas parcelas: : </p>
		<?php 
			// Salva-se qual ?? a primeira parcela
			$parcela = $this->request->data['Conta']['parcela'];
			//pr($IntervalosParcelas);
			if(!empty($IntervalosParcelas) and is_array($IntervalosParcelas))
			foreach ($IntervalosParcelas as $key=>$value){
//				if($value['hidden']){
//					// Parcela anterior a atual, esconder o input para o javascript funcionar sem problemas
//					echo $this->Form->hidden( 'IntervaloParcela.'.$key,array('class'=>'intervalos','value'=>$value['dias']));
//				}else{
//					// Parcela atual ou posterior, imprimir o input e incrementar a variavel da parcela
//					echo $this->Form->input( 'IntervaloParcela.'.$key,array('class'=>'span1 intervalos','label'=>'Parcela '.$parcela.' e Parcela '.++$parcela,'value'=>$value['dias'],'type'=>'number'));
//				}
				echo $this->Form->input( 'IntervaloParcela.'.$key ,array('class'=>'span2 intervalos','label'=>'Parcela '.++$parcela,'value'=>$value['intervalo'],'type'=>'text','div'=>array('class'=>'input number pull-left')));
				echo $this->Form->input( 'IntervaloParcelaValor.'.$key ,array('class'=>'span2 money','label'=>'Valor '.++$parcela,'value'=>$value['valor'],'type'=>'text'));
//				'<div class="input number pull-left">'
//							+'<label for="IntervaloParcela' + (i) + '">Parcela ' + (1*i+2) + '</label>'
//							+'<input id="IntervaloParcela' + (i) + '" class="span2 intervalos" type="text" name="data[IntervaloParcela][intervalo][' + (i) + ']">'
//						+'</div>'
//						+'<div class="input number">'
//							+'<label for="IntervaloParcelaValor' + (i) + '">Valor</label>'
//							+'<input id="IntervaloParcelaValor' + (i) + '" class="span2 money" type="text" name="data[IntervaloParcela][valor][' + (i) + ']" value="'+vlparcela+'">'
//						+'</div>'
			}
		?>		
	</div>
	<?php echo $this->Form->input( 'observacao', array( 'type' => 'textarea', 'class' => 'span6', 'rows' => 5, 'label' => 'Observa????o' ) ); ?>
	
	<?php echo $this->Form->submit( 'Gravar', array( 'class' => 'btn btn-primary' ) ); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->link('Voltar', array('action' => 'index')); ?>