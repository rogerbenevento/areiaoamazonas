<p class="lead">FORMAS DE PAGAMENTOS</p>
<!--<div class="actions">
	<br />
	<a href="<?php echo Router::url( array( 'controller' => 'tipospagamentos', 'action' => 'add', 'admin' => true ) ) ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar</a>
	<br /><br />
</div>-->
<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort( 'TipoPagamento.nome', 'Descrição' ) ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php if ( $total ): ?>
			<?php foreach ( $rows as $row ): ?>
				<tr>
					<td><?php echo $row['TipoPagamento']['nome'] ?></td>
					<td style="text-align: center;">
						<div class="btn-group">	
							<a href="<?php echo $this->Html->url(array( 'controller' => 'tipospagamentos', 'action' => 'edit', $row['TipoPagamento']['id'])) ?>" class="btn" title="Editar Registro">
								<?php echo $this->Html->image( 'icones/edit.png', array( 'border' => '0', 'title' => 'Editar registro', 'width' => 16 ) ) ?>
							</a>
							<a href="<?php echo $this->Html->url(array( 'controller' => 'tipospagamentos', 'action' => 'del', $row['TipoPagamento']['id'])) ?>" class="btn" title="Editar Registro">
								<?php echo $this->Html->image( 'icones/delete.png', array( 'border' => '0', 'title' => 'Apagar registro', 'width' => 16 ) ) ?>
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
<?php echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>