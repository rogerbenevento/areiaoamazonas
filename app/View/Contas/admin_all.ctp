<form action="#" id="filtroContas" style="margin: 0px;">
			
<table class="table table-striped tbcontas">
	<thead>
		<tr>
			<th>Data</th>
			<th>Descrição</th>
			<th>Tipo</th>
			<th></th>
			<th>Status</th>
			<th>Valor</th>
			<th>Parcela</th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<th>
	
				<div class="input-append">
				<input id="data" type="text" style="width: 70px"/>
				<a class="">.00</span>
				</div>
			</th>
			<th><input id="descricao" type="text" style="width: 100px"/></th>
			<th><input id="tipo" type="text" style="width: 50px"/></th>
			<th><input id="tipo_conta" type="text" style="width: 250px"/></th>
			<th><input id="status" type="text" style="width: 50px"/></th>
			<th><input id="valor" type="text" style="width: 70px"/></th>
			<th><input id="parcela" type="text" style="width: 50px"/></th>
			<th><button type="submit" class="btn"><i class="icon-search"></i>&nbsp;Filtrar</button></th>
			
		</tr>
	</thead>
	
	<tbody>

		<?php $dispesas = 0; $receitas = 0; $valorTotal = 0; ?>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr class="<?php echo $row['Conta']['tipo'] == 'R' ? 'receita' : 'dispesa' ?>">
					<td><?php echo dateMysqlToPhp($row['Conta']['data_vencimento'] ) ?></td>
					<td><?php echo $row['Conta']['descricao'] ?></td>
					<td><?php echo ( $row['Conta']['tipo'] == 0 ) ? '<span class="badge badge-error">Despesa</span>' : '<span class="badge badge-success">Receita</span>' ?></td>
					<td>
						<?php 
						
						echo ($row['TipoConta']['id']==1? 
								substr($row['Fornecedor']['nome'],0,25).(strlen($row['Fornecedor']['nome'])>18?'...':'')." <small>({$row['TipoPagamento']['nome']})</small>" 
								: $row['TipoConta']['nome']) ;
							
						?>
					</td>
					<td>
						<?php if ( !$row['Conta']['status'] ): ?>
							Em aberto
						<?php elseif ( $row['Conta']['status'] == 1 ): ?>
							Pago
						<?php elseif ( $row['Conta']['status'] == 2 ): ?>
							Cancelado
						<?php endif; ?>
					</td>
					<td>
						<?php if ( $row['Conta']['tipo'] == 0 ): ?>
							<span style="color: red;">-<?php echo $this->Number->format( $row['Conta']['valor'], array( 'places' => 2, 'before' => 'R$ ', 'decimals' => ',', 'thousands' => '.' ) ) ?></span>
						<?php else: ?>
							<span style="color: #666;">+<?php echo $this->Number->format( $row['Conta']['valor'], array( 'places' => 2, 'before' => 'R$ ', 'decimals' => ',', 'thousands' => '.' ) ) ?></span>
						<?php endif; ?>
					</td>
					<td><?php echo $row['Conta']['parcela'] .'/'. $row['Conta']['parcelas'] ?></td>
					<td style="text-align: center;">
						<?php
							
						//if($_SERVER['REMOTE_ADDR']=='168.227.12.232'){
							
							echo $this->Html->image(
								'icones/search.png',
								array(
									'width'=>16,
									'class'=>'btn searchBtn',
									'data-id'=> $row['Conta']['id']
								)
							);
						//}
						?>
						<div class="btn-group">
						<?php 







						if ( !$row['Conta']['status'] ): ?>
							<?php echo $this->Html->image( 'icones/check.png', array( 'url' => array( 'controller' => 'contas', 'action' => 'consolidar', $row['Conta']['id'], $this->Session->read('Auth.User.role') => true ), 'border' => 0, 'title' => 'Consolidar conta', 'width' => 16,'class'=>'btn') ) ?>
							<?php echo $this->Html->image( 'icones/edit.png', array( 'url' => array( 'controller' => 'contas', 'action' => 'edit', $row['Conta']['id'], $this->Session->read('Auth.User.role') => true ), 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ,'class'=>'btn') ) ?>
						<?php endif; ?>
						</div>
					</td>
				</tr>
				<?php
					if ( $row['Conta']['tipo'] == 'D' ): $dispesas += $row['Conta']['valor'];
					elseif( $row['Conta']['tipo'] == 'R' ): $receitas += $row['Conta']['valor']; endif;
				?>
			<?php endforeach; ?>
			<?php $valorTotal = $receitas - $dispesas ?>
		<?php else: ?>
			<tr>
				<td colspan="7"><strong>Não há registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7"><strong>Total de registros cadastrados: <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
</form>
<!--
<div class="well">
	<br />
	<div class="row-fluid">
		<div class="span3">
			<h3 style="text-align: center;"><strong><span style="color: green;">+<?php echo $this->Number->format($receitas, array('places'=>2, 'before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></span></strong></h3>
		</div>
		<div class="span1" style="text-align: center !important;"><h3>-</h3></div>
		<div class="span3">
			<h3 style="text-align: center;"><strong><span style="color: red;">-<?php echo $this->Number->format($dispesas, array('places'=>2, 'before'=>'R$ ', 'decimals'=>',', 'thousands'=>'.')) ?></span></strong></h3>
		</div>
		<div class="span1" style="text-align: center !important;"><h3>=</h3></div>
		<div class="span4">
			<h3 style="text-align: center;">
				<strong>
					<?php if ($valorTotal > '0'): ?>
						<span style="color: green;">+<?php echo $this->Number->format( $receitas - $dispesas, array( 'places' => 2, 'before' => 'R$ ', 'decimals' => ',', 'thousands' => '.' ) ) ?></span>
					<?php else: ?>
						<span style="color: red;">-<?php echo $this->Number->format( $receitas - $dispesas, array( 'places' => 2, 'before' => 'R$ ', 'decimals' => ',', 'thousands' => '.' ) ) ?></span>
					<?php endif; ?>
				</strong>
			</h3>
		</div>
	</div>
</div>-->
<?php pr($sql_debug); ?>
<script type="text/javascript">
	$('.searchBtn').die('click').live('click',function(e){
		var id = $(this).data('id');



		 window.open("<?=$this->webroot.$this->Session->read('Auth.User.nivel')?>/contas/dados/"+id, "", "width=800,height=600,left=100,top=100,resizable=yes,scrollbars=yes");


	});
</script>