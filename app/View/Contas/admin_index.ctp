<?php echo $this->Html->css(array('jqueryui/jquery-ui')) ?>

<?php echo $this->Html->script(array('jquery-ui.min','jquery.price_format.min','jquery.mask', 'contas/index'), array('block' => 'scriptBottom')) ?>
<p class="lead">Gerenciar Contas a Pagar / Receber</p>

<div class="row">
	<div class="span12">
		<p>
			<a href="<?php echo Router::url( array( 'controller' => 'contas', 'action' => 'add', $this->Session->read('Auth.User.role') => true ) ) ?>" class="btn btn-primary">
				<i class="icon-plus-sign icon-white"></i>&nbsp;Adicionar Conta
			</a>
		</p>
	</div>
</div><br />

<!-- Formulário para filtrar -->
<!--
<?php echo $this->Form->create( null, array( 'type' => 'get', 'class' => 'well form-inline', 'inputDefaults' => array( 'div' => false, 'label' => false ) ) ) ?>
	<?php echo $this->Form->month( 'mes', array( 'name' => 'mes', 'value' => !empty( $this->params->query['mes'] ) ? $this->params->query['mes'] : date('m'), 'class' => 'span1', 'monthNames' => false ) ) ?>
	<?php echo $this->Form->year('ano', date('Y') - 5, date('Y') + 5, array( 'name' => 'ano', 'class' => 'span1', 'value' => !empty( $this->request->query['ano'] ) ? $this->request->query['ano'] : date('Y') )) ?>
	<button type="submit" class="btn"><i class="icon-search"></i>&nbsp;Filtrar</button>
<?php echo $this->Form->end() ?>
-->

<script type="text/javascript">

</script>

<div class="well">
	<input type="hidden" name="ano" id="ano" value="<?php echo date('Y') ?>" />
	<input type="hidden" name="mes" id="mes" value="<?php echo date('m') ?>" />
	
	<div class="row-fluid">
		<div class="span2" style="text-align: center !important;">
			<h2>
				<button class="btn btn-primary menosUm"><i class="icon-chevron-left icon-white"></i></button>
				<small><span class="ano"><?php echo date('Y') ?></span></small>
				<button class="btn btn-primary maisUm"><i class="icon-chevron-right icon-white"></i></button>
			</h2>
		</div>		
		<div class="span10" >
			<?php $meses = array(
				1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
				9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
			) ?>
			<!-- Add data-toggle="buttons-radio" for radio style toggling on btn-group -->
			<div class="btn-group meses" data-toggle="buttons-radio" style="padding-top: 20px;">
				
				<?php foreach ($meses as $key => $value): ?>
					<button class="btn  mes <?php echo ($key == date('m')) ? 'active' : '' ?>" rel="<?php echo $key ?>">
						&nbsp;&nbsp;<?php echo $value ?>&nbsp;&nbsp;
					</button>
				<?php endforeach; ?>
					
			</div>
		</div>
		
	</div>
</div>

<div class="grid">
	<div style="text-align: center">
		<?php echo $this->Html->image('icones/load.gif') ?>&nbsp;Carregando Contas
	</div>
</div>
<?php #echo $this->element( 'paginacao', array( 'url' => $this->params->query ) ) ?>