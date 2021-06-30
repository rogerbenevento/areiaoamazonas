<?php echo $this->Html->script(array('notas/index'), array('inline' => false)) ?>
<p class="lead">NOTAS</p>

<style>
	.file{
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
	}
	.labelfile{
		background-color: #ffffff;
		border: 1px solid #cccccc;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
		-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
		-o-transition: border linear 0.2s, box-shadow linear 0.2s;
		transition: border linear 0.2s, box-shadow linear 0.2s;
		height: 20px;
		padding: 4px 6px;
		margin-bottom: 10px;
		font-size: 14px;
		line-height: 20px;
		color: #555555;
		vertical-align: middle;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		width: 90%;
		margin-left: 2.5%;
	}
	.selectfile{
		width: 90%;
		margin-left: 2.5%;
	}
</style>
<div class="actions">
	<br />
	<a href="<?php echo Router::url(array('controller' => 'notas', 'action' => 'add')) ?>" class="btn btn-primary">
		<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar
	</a>
	<br /><br />
</div>
<?php
//$i= number_format(9.9560,4);
//$icm = substr($i,  strlen($i)-2)*1;
//pr($icm);
//if($icm >= 50 && $icm < 60){
//	$i-=0.0010;
//}
//pr($i);
//$i=round($i,2);
//pr($i);
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th class="span1"><?php echo $this->Paginator->sort('Nota.numero', 'Nota') ?></th>
			<th><?php echo $this->Paginator->sort('Empresa.nome', 'Empresa') ?></th>
			<th ><?php echo $this->Paginator->sort('Cliente.nome', 'Cliente') ?></th>
			<th class="span3"><?php echo $this->Paginator->sort('Nota.observacao', 'Observacao') ?></th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<?php
			$er = '/page:[0-9]*[\/]?/i';
			echo preg_replace($er, '', $this->Form->create(null, array('type' => 'get')));
			?>
			<td><input type="text" class="span1" name="Nota.numero" value="<?php echo (!empty($this->params->named['Nota_numero']) ? $this->params->named['Nota_numero'] : '' ) ?>" /></td>
			<td><input type="text" name="Empresa.nome" value="<?php echo (!empty($this->params->named['Empresa_nome']) ? $this->params->named['Empresa_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Cliente.nome" value="<?php echo (!empty($this->params->named['Cliente_nome']) ? $this->params->named['Cliente_nome'] : '' ) ?>" /></td>
			<td><input type="text" name="Nota.observacao"  value="<?php echo (!empty($this->params->named['Nota.observacao']) ? $this->params->named['observacao'] : '' ) ?>" /></td>
			<td style="text-align: center;"><button type="submit" class="btn"><i class="icon-search"></i></button></td>
			<?php echo $this->Form->end() ?>
		</tr>
	</thead>
	<tbody>
		<?php if ($total): ?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?php echo $row['Nota']['numero'] ?></td>
					<td title="<?php echo $row['Empresa']['nome']; ?>"><?php echo substr($row['Empresa']['nome'],0,20) ?></td>
					<td>
						<?php
						if ($row['Cliente']['inadimplente']) {
							?>
							<a class="btn btn-danger"
							   href="<? echo $this->Html->url(['action' => 'inadimplente_off', $row['Cliente']['id']]); ?>"
							   title='Remover marcação deste cliente'>
								<i class="icon icon-flag icon-white"></i>
							</a>
						<? } else { ?>
							<a class="btn"
							   href="<? echo $this->Html->url(['action' => 'inadimplente_on', $row['Cliente']['id']]); ?>"
							   title="Marcar cliente como inadimplente">
								<i class="icon icon-flag"></i>
							</a>
							<?php
						}
						echo $row['Cliente']['nome'];
						?>
						<div style="float: right;font-size: 12px">
						<?		
						if(!empty($row['Nota']['path'])){
							$n = explode('/',$row['Nota']['path']);
							end($n);         // move the internal pointer to the end of the array
							$nome = current($n);
							?>
							<span title="<?=($row['Nota']['anexo1_aberto']==1)?'Arquivo visualizado':'Arquivo NÃO visualizado'?>">
								<i class="icon <?=($row['Nota']['anexo1_aberto']==1)?'icon-eye-open':'icon-eye-close'?>"></i>
								<?=$nome?>
							</span>
						<? } ?>
						<? if(!empty($row['Nota']['path2'])){
							$n = explode('/',$row['Nota']['path2']);
							end($n);         // move the internal pointer to the end of the array
							$nome = current($n);
							?>
							<br>
							<span title="<?=($row['Nota']['anexo2_aberto']==1)?'Arquivo visualizado':'Arquivo NÃO visualizado'?>">
								<i class="icon <?=($row['Nota']['anexo2_aberto']==1)?'icon-eye-open':'icon-eye-close'?>"></i>
								<?=$nome?>
							</span>
						<? }?>
						</div>
					</td>
					<td><?php echo substr($row['Nota']['observacao'], 0, 25) ?></td>
					<td style="text-align: center;">
						<div class="btn-group">
							<? if(!empty($row['Nota']['path'])){ ?>
								<a href="<?php echo $this->Html->url(array('action' => 'nota_email', $row['Nota']['id'])) ?>"
								   class="btn"
								   title="Enviar Email <? if($row['Nota']['email_enviado'])echo" ( Já enviado )" ?>">
									<i class="icon <?=(!$row['Nota']['email_enviado'])? " icon-envelope":'icon-check'; ?>"></i>
								</a>
							<? } ?>
							<li class="dropdown mega-dropdown btn " style="padding: 0px 0px 0px 0px;">
								<a href="#" 
								   class="dropdown-toggle btnfile"
								   title="Armazenar XML"								   
								   style="line-height: 28px;padding: 4px 12px 4px 12px;">
									<i class="icon icon-arrow-down"></i>

								</a>
								<ul class="dropdown-menu">
									<li>
										<form id="frmArquivoseguro"
											  action="<?php echo $this->Html->url(array('action' => 'addnota',$row['Nota']['id'])) ?>"
											  method="POST"
											  enctype="multipart/form-data">
											<div class="input">
												<input type="file"
													   name="data[File][image]"
													   id="NotaPath<?= $row['Nota']['id'] ?>"
													   class='file inputfile'
													   data-multiple-caption="{count} files selected"
													   placeholder="Selecione um Arquivoseguro" />
												<label class="labelfile" for="NotaPath<?= $row['Nota']['id'] ?>">
													<i class="icon icon-file"></i>
													<span>Selecionar Arquivo</span>
												</label>
											</div>
											<div class="input">
												<input type="file"
													   name="data[File][image2]"
													   id="NotaPath<?= $row['Nota']['id'] ?>f2"
													   class='file inputfile'
													   data-multiple-caption="{count} files selected"
													   placeholder="Selecione um Arquivoseguro" />
												<label class="labelfile" for="NotaPath<?= $row['Nota']['id'] ?>f2">
													<i class="icon icon-file"></i>
													<span>Selecionar Arquivo</span>
												</label>
											</div>
											<?php
											echo $this->Form->input('disparar', array(
												'options' => [0 => 'Não', 1 => 'Sim'],
//												'div' => false,
												'class'=>'selectfile',
												'label' => 'Disparar email para cliente?',
												'required' => true
											))
											?>
											<input type="submit"
												   id="btn-add-ramo"
												   class="btn btn-primary btn-block btn-large"
												   value='Enviar'/>
										</form>
									</li>
								</ul>
							</li>
							<ul class="dropdown-menu">
								<li>
									<a target="_blank" href="<?php echo $this->Html->url(array('action' => 'imprimir/' . $row['Nota']['id'], 1)); ?>" title='IMPRIMIR NOTA FISCAL'>
										<i class="icon icon-print"></i> OKI 320 Turbo
									</a>	
								</li>
								<li>
									<a target="_blank" href="<?php echo $this->Html->url(array('action' => 'imprimir', $row['Nota']['id'])); ?>" title='IMPRIMIR NOTA FISCAL'>
										<i class="icon icon-print"></i> OKI 321 Turbo
									</a>	
								</li>
							</ul>
							<a href="<?php echo $this->Html->url(array('action' => 'exportar', $row['Nota']['id'])) ?>"
							   class="btn"
							   title="Exportar NFe">
								<i class="icon icon-folder-open"></i>
							</a>
							<a href="<?php echo $this->Html->url(array('controller' => 'notas', 'action' => 'edit', $row['Nota']['id'])) ?>" class="btn" title="Editar Nota">
								<?php echo $this->Html->image('icones/edit.png', array('border' => '0', 'title' => 'EDITAR NOTA')) ?>
							</a>
							<a id="<?php echo $row['Nota']['cliente_id'] ?>"
							   href="#"
							   title="IMPRIMIR ENDERECO DO CLIENTE"
							   class="btn endereco-print">
								   <?php echo $this->Html->image('icones/print.png', array('border' => '0')) ?>
							</a>
							<li class="dropdown dropdown-toggle btn " style="padding: 0px 0px 0px 0px;">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="line-height: 28px;padding: 4px 12px 4px 12px;">
									<i class="icon icon-file"></i>Imprimir <b class="caret" style="margin-top: 11px"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a target="_blank" href="<?php echo $this->Html->url(array('action' => 'imprimir/' . $row['Nota']['id'], 1)); ?>" title='IMPRIMIR NOTA FISCAL'>
											<i class="icon icon-print"></i> OKI 320 Turbo
										</a>	
									</li>
									<li>
										<a target="_blank" href="<?php echo $this->Html->url(array('action' => 'imprimir', $row['Nota']['id'])); ?>" title='IMPRIMIR NOTA FISCAL'>
											<i class="icon icon-print"></i> OKI 321 Turbo
										</a>	
									</li>
								</ul>
							</li>
							<a href="<?php echo $this->Html->url(array('controller' => 'notas', 'action' => 'del', $row['Nota']['id'])) ?>" class="btn" title="Deletar Nota" onclick="return confirm('Deseja realmente remover este registro?');">
								<?php echo $this->Html->image('icones/delete.png', array('border' => '0')) ?>
							</a>
						</div>

					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="5"><strong>Não há registros cadastrados no momento.</strong></td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong>Total de registros <?php echo $total ?>.</strong></td>
		</tr>
	</tfoot>
</table>
<?php echo $this->element('paginacao', array('url' => $this->params->query)) ?>

<script>

	$(function () {
		$( '.inputfile' ).each( function()
	{
		var $input	 = $( this ),
			$label	 = $input.next( 'label' ),
			labelVal = $label.html();
		console.log(labelVal);
		$input.on( 'change', function( e )
		{
			var fileName = '';
			
			if( this.files && this.files.length > 1 ){
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			}else if( e.target.value ){
				fileName = e.target.value.split( '\\' ).pop();
				
			}
			if( fileName )
				$label.find( 'span' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
		
		
		$('.btnfile').on('click', function (event) {
			$(this).parent().toggleClass('open');
		});
		$('body').on('click', function (e) {
			if (!$('.dropdown.mega-dropdown').is(e.target)
					&& $('.dropdown.mega-dropdown').has(e.target).length === 0
					&& $('.open').has(e.target).length === 0
					) {
				$('.dropdown.mega-dropdown').removeClass('open');
			}
		});
	})
</script>