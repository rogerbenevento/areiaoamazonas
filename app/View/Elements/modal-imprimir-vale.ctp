<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Imprimir Vale</h3>
	</div>
	<div class="modal-body">
		<input type="hidden" id='print_id' value=''>
		<div class='btn-group' style="padding-left: 140px;">			
		<?php echo $this->Form->input('motorista',array('options'=>$motoristas,'empty'=>'[ Motoristas ]'));?>
		<?php #echo $this->Html->image('scorpion.png', array('class' => 'btn','style'=>'width:100px;height:100px;','rel'=>'s')) ?>
		<?php #echo $this->Html->image('areiao.png', array('class' => 'btn','style'=>'width:100px;height:100px;','rel'=>'a')) ?>
		<?php ##echo $this->Form->input('layout', array('options' => array('s'=>'SCORPION','a'=>'AREIÃO AMAZONAS'),'required'=>true)) ?>
		<?php echo $this->Form->hidden('layout') ?>
		<?php echo $this->Form->input('Imprimir', array('label'=>false,'class' => 'btn btn-large btn-block btn-primary btnimprimir','type'=>'button')) ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Voltar</button>
	</div>
</div>