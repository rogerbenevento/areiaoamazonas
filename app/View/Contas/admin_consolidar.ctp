<?php if (!empty($conta)) { ?>
	<?php echo $this->Html->css(array('jqueryui/smoothness/jquery-ui'), null, array('inline' => false)) ?>
	<?php echo $this->Html->script(array('jquery.mask','jquery.price_format.min','jquery-ui.min','contas/consolidar'), array('inline' => false)) ?>

	<style>
		.dados{
			margin-left: 60px;
			font-size: 16px;
			width: 500px;
		}
		.dados dt{
			width: 100px;
			text-align: left;
		}
		.cheque {
			display: none;
		}
		.cheque > div{
			
			float: left;
			margin-right: 15px;		
		}
		.cheque > div:last-child{
			float: none;
		}
		
	</style>
	<p class="lead">
		<?php
		if (!empty($origem))
			switch ($origem) {
				case 'financeiro':
					echo $this->Html->link('Gerenciar Boletos', array('controller' => 'contas', 'action' => 'boletos'));
					break;
				default:
					echo $this->Html->link('Gerenciar Contas', array('controller' => 'contas', 'action' => 'index'));
			}
		else
			echo $this->Html->link('Gerenciar Contas', array('controller' => 'contas', 'action' => 'index'));
		?> 
		:: Consolidar Conta
	</p>
	<dl class="dl-horizontal dados">
		<dt><b>Descrição:</b></dt>
		<dd><?php echo $conta['Conta']['descricao'] ?></dd>
		<dt><b>Parcela:</b></dt>
		<dd><?php echo $conta['Conta']['parcela'] . " de " . $conta['Conta']['parcela'] ?></dd>
		<dt><b>Documento / Fatura:</b></dt>
		<dd><?php echo $conta['Conta']['numero_documento'] . " / " . $conta['Conta']['fatura'] ?></dd>
		<dt><b>Valor:</b></dt>
		<dd><?php echo moedaBr($conta['Conta']['valor_original']) ?></dd>
		<?php if(!empty($conta['ContaPedido'])){ ?>
		<dt>Pedidos:</dt>
		<dd>
			<?php 
			$virgula='';
			foreach($conta['ContaPedido'] as $con){
				echo $virgula.$con['pedido_id'] ;
				$virgula=', ';
			}
			?>
		</dd>
		<?php } ?>
		<dt><b>Observação:</b></dt>
		<dd><?php echo $conta['Conta']['observacao'] ?></dd>
	</dl>
	<?php
	echo $this->Form->create('Conta', array('class' => 'well frmConsolidar'));
	
	//if($conta['Conta']['valor_original']!=$conta['Conta']['valor'])
	//	echo $this->Form->input('data_pagamento_display', array('class' => 'span2', 'label' => 'Data de pagamento&nbsp;&nbsp;', 'type' => 'text','value'=>date('d/m/Y'),'disabled'=>true));
	//else 
		echo $this->Form->input('data_pagamento', array('class' => 'span2', 'label' => 'Data de pagamento&nbsp;&nbsp;', 'type' => 'text'));
	
	// Opcoes do campo pago
	$options = array('class' => 'span2', 'label' => 'Valor Pago', 'type' => 'text');
	
	$input_pago = 'pago';

	if(empty($conta['Conta']['tipo_pagamento_id']))
		echo $this->Form->input('tipo_pagamento_id', array('class' => 'span2', 'label' => 'Tipo Pagamento', 'required' => true, 'options' => $tipos_pagamentos, 'empty' => '[ Selecione um tipo de recebimento ]'));

	$options['required'] = true;
	if($conta['Conta']['valor_original']!=$conta['Conta']['valor']){

		$options['label']=$options['label'].'&nbsp;(<small class="error">Multa '.  moedaBr($conta['Conta']['valor']-$conta['Conta']['valor_original']).'</small>)';
		$options['name']='ValorPago';
		$options['disabled']=true;
		$options['value']=  number_format($conta['Conta']['valor'],2,',','.');

		#echo $this->Form->hidden('pago', array('value'=>moedaBr($conta['Conta']['valor'])));
		$input_pago='pago_display';
	}
	
	echo $this->Form->input($input_pago, $options);
//	echo '<div class="cheque">';
//	echo $this->Form->input('Cheque.banco_id', array('class' => 'span1', 'label' => 'Banco', 'empty' => '[ Selecione um Banco ]'));
//	echo $this->Form->input('Cheque.agencia', array('class' => 'span1', 'label' => 'Agencia', 'type' => 'text'));
//	echo $this->Form->input('Cheque.conta', array('class' => 'span2', 'label' => 'Conta', 'type' => 'text'));
//	echo $this->Form->input('Cheque.titular', array('class' => 'span2', 'label' => 'Titular', 'type' => 'text'));
//	echo $this->Form->input('Cheque.numero', array('class' => 'span2', 'label' => 'Numero', 'type' => 'text'));
//	echo $this->Form->input('Cheque.data_compensacao', array('class' => 'span2', 'label' => 'Data', 'type' => 'text'));
//	echo '</div>';
	echo $this->Form->end();
	echo $this->Form->submit('Consolidar', array('class' => 'btn btn-large btn-primary btnConsolidar','id'=>$conta['Conta']['id']));
	echo $this->Html->link('Voltar', array('action' => 'index'));
	?>
<?php } ?>
