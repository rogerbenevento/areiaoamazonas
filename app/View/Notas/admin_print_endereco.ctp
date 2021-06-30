<fieldset>
	<label>
		<div><b>End: </b>&nbsp; <?php echo $row['Endereco']['endereco']; ?>&nbsp;&nbsp;&nbsp;<b>CEP: </b> <?php echo $row['Endereco']['cep']; ?></div>
		<div><b>Bairro:&nbsp; </b> <?php echo $row['Endereco']['bairro']; ?>&nbsp;&nbsp;&nbsp;<b>Cidade:&nbsp; </b> <?php echo $row['Cidade']['nome'].' / '.$row['Estado']['nome']; ?></div>		
	</label>
</fieldset>