<?php echo $this->Html->css('contato', null, array('block' => 'scriptBottom')); ?>
<section class="content topspc brdr shdw conteudos fale">
	<h1 class="topspc btmspc"><span class="hidden">Fale Conosco</span></h1>
	<div id="form_container">
		<h3>Preencha corretamente os campos abaixo.</h3>
		<p>Entraremos em contato o mais breve possível!</p>
		<?php echo $this->Form->create('Contato', array( 'inputDefaults' => array( 'label' => false, 'div' => false ) )) ?>
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tbody>
					<tr>
						<td>
							<label for="ContatoNome">Nome:</label>
							<?php echo $this->Form->input('nome'); ?>
						</td>
						<td>
							<label for="ContatoSobre">Sobrenome:</label>
							<?php echo $this->Form->input('sobre'); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label for="ContatoEmail">Email:</label>
							<?php echo $this->Form->input('email'); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="ddd">
								<label for="ContatoDdd">DDD:</label>
								<?php echo $this->Form->input('ddd'); ?>
							</div>
							<div class="fone">
								<label for="ContatoFone">Telfone:</label>
								<?php echo $this->Form->input('fone'); ?>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label for="ContatoMsg">Mensagem:</label>
							<?php echo $this->Form->input('msg', array('type' => 'textarea')); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->Form->submit('Enviar', array('id' => 'sbmt')) ?>
						</td>
					</tr>
				</tbody>
			</table>
		<?php echo $this->Form->end(); ?>
		<div class="signature">Cenário Gourmet - Confeitaria & Gastronomia &nbsp;</div>
	</div>
	
</section>

<?php if (!empty($retorno)): ?>
	<?php echo "<script type='text/javascript'>$(function() { alert('".$retorno."') });</script>" ?>
<?php endif ?>