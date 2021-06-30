<?php echo $this->Html->script( array( 'jquery.mask', 'clientes/edit.js?t=20160512' ), array( 'inline' => false ) ) ?>
<style>
	div#div-endereco div{
		float:left;
		margin-right: 15px;
		margin-bottom: 15px;
	}
</style>
<p class="lead">
    <?php 
	   echo (isset($pedido))?$this->Html->link( 'PEDIDOS>>', array( 'controller' => 'clientes', 'action' => 'edit',$pedido, $this->Session->read('Auth.User.nivel') => true ) ) :$this->Html->link( 'CLIENTES', array( 'controller' => 'clientes', 'action' => 'index', $this->Session->read('Auth.User.nivel') => true ) );
    ?> 
    ::Cadastrar Cliente
</p>

<?php echo $this->Form->create('Cliente',array('id'=>'frmCliente')); ?>
	<fieldset>
		<legend>Básicos <small>Campos obrigatórios</small></legend>
		<?php echo $this->Form->input( 'nome', array( "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'tipo', array( 'type' => 'radio', 'class' => 'tipo', 'options' => array( 1 => 'Pessoa Física', 0 => 'Pessoa Jurídica' ) ) ); ?>
		<?php echo $this->Form->input( 'cpf_cnpj', array( 'label' => 'CPF/CNPJ', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'rg_ie', array( 'label' => 'RG/IE', "class" => "span6" ) ); ?>
		<?php echo $this->Form->input( 'telefone', array( "class" => "span6" ) ); ?>		
		<?php echo $this->Form->input( 'contato', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input( 'email', array(
			'label'=>'Email <small>( Separe os emails com ; (ponto e virgula) )</small>',
			'class' => 'span6'
			) ); ?>
		<?php echo $this->Form->input( 'dia_nota', array('label'=>'Aceita notas para dia:', 'class' => 'span6','options'=>$dias,'empty'=>'[ Dia da Nota ]' ) ); ?>
		<?php echo $this->Form->input( 'empresa_id', array( "class" => "span6",'empty'=>'[ Empresa ]' ) ); ?>
		<?php echo $this->Form->input( 'vendedor_id', array( "class" => "span6",'empty'=>'[ Vendedor ]','options'=>$vendedores ) ); ?>
		<?php echo $this->Form->textarea( 'observacao', array( "class" => "span6",'placeholder'=>'Observação' ) ); ?>
	</fieldset>
<!--	<fieldset>
		<legend>Endereço</legend>
		<?php echo $this->Form->input( 'cep', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input( 'endereco', array( 'label' => 'Endereço', 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input( 'complemento', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input( 'bairro', array( 'class' => 'span6' ) ); ?>
		<?php echo $this->Form->input( 'estado_id', array( 'options' => $estados, 'class' => 'span6', 'empty' => '[Escolha o estado]' ) ); ?>
		<?php echo $this->Form->input( 'cidade_id', array( 'options' => !empty( $cidades ) ? $cidades : '', 'class' => 'span6' ) ); ?>
	</fieldset>-->
	<?php echo $this->Form->end(); ?>
	<div id="div-tabela-horario" class="well well-small span8" style="float: none">
		
			<div id="div-endereco" class="form ">

				<?php echo $this->Form->create('Endereco',array('id'=>'frmEndereco','action'=>'adicionar')); ?>

				<?php echo $this->Form->input('tipo_id',array('div'=>array('style'=>'float:none'))); ?>
				<?php echo $this->Form->input('cep',array('class'=>'span2','div'=>array('style'=>'float:left'))); ?>
				<?php echo $this->Form->input('endereco'); ?>
				<?php echo $this->Form->input('numero',array('class'=>'span1','div'=>array('style'=>'float:left'))); ?>
				
				<?php echo $this->Form->input('bairro',array('div'=>array('style'=>''))); ?>
				<?php echo $this->Form->input('estado_id',array('class'=>'span1')); ?>
				<?php echo $this->Form->input('cidade_id'); ?>
				<?php echo $this->Form->input('complemento'); ?>
				<input id="btn-inserir-endereco" class="span6 btn btn-info" style="" title="INSERIR ENDERECO" type="submit" value="INSERIR" />
					<?php echo $this->Form->end(); ?>
			</div>
			
			<!-- <div class="endereco"></div> -->
		<table class="table table-condensed table-bordered tbenderecos">
			<thead>			
				<th></th>
				<th>Tipo</th>
				<th>Endereco</th>
				<th>N&ordm;</th>
				<th>CEP</th>
				<th>Cidade</th>
			</thead>
			<tbody>
				<?php echo $this->element('clientes/tbenderecos');?>
			</tbody>
		</table>
	</div>
<?php // echo $this->Form->input( 'observacao', array( 'type' => 'textarea', 'class' => 'span6', 'rows' => 8, 'label' => 'Observações' ) ); ?>
<div class="form-actions">
	<?php echo $this->Form->button( 'Gravar', array("id"=>'btnSubmit', 'class' => 'btn btn-primary' ) ); ?>
	ou
	<?php /*echo $this->Html->link( 'voltar', array( 'controller' => 'clientes', 'action' => 'index', 'admin' => true ) );*/ ?>
	<?php echo $this->Html->link( 'Voltar',  'index'); ?>
</div>
