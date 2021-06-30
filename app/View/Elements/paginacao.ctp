<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()): ?>
	
    <?php if ( !empty( $url ) ): ?>
		<?php #print_r( $url ) ?>
		<?php $this->Paginator->options( array( 'url' => $url ) ); ?>
	<?php endif; ?>
	<div class="pagination">
		<ul>
			<?php if ($this->Paginator->hasPrev()): ?>
				<li class="prev"><?php echo $this->Paginator->prev('&larr; Anterior', array('escape' => false)); ?></li>
			<?php else: ?>
				<li class="prev disabled"><a>&larr; Anterior</a></li>
			<?php endif; ?>
			<?php echo preg_replace('/<li class="current">(.*)<\/li>/i', '<li class="active"><a>$1</a></li>', $this->Paginator->numbers(array('tag' => 'li', 'separator' => ''))) ?>
		
			<?php if ($this->Paginator->hasNext()): ?>
				<li class="next"><?php echo $this->Paginator->next('Próxima &rarr;', array('escape' => false)); ?></li>
			<?php else: ?>
				<li class="next disabled"><a>Próxima &rarr;</a></li>
			<?php endif; ?>
		</ul>
	</div>
<?php endif; ?>