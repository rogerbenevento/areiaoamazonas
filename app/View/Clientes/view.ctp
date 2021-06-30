<style>
	table.table-cliente-view{
		width: 525px;
	}
	table.table-cliente-view tr td:first-child{
		font-weight: bold;
	}
</style>

<table class="table table-striped table-cliente-view">
	<thead>
		<th colspan="2" style="text-align: center;font-size: 16px;"><?php echo $cliente['Cliente']['nome']; ?></th>
	</thead>
	<tbody>
		<?php if ($cliente['Cliente']['tipo'] == 'F') { ?>
			<!--PESSOA FISICA-->
			<tr>
				<td style="width: 100px;">Tipo: </td><td><?php echo 'Pessoa Física'; ?></td>
			</tr>
			<tr>
				<td>CPF: </td><td><?php echo $cliente['Cliente']['cpf_cnpj']; ?></td>
			</tr>
			<tr>
				<td>RG: </td><td><?php echo $cliente['Cliente']['rg_ie']; ?></td>
			</tr>
			<tr>
				<td>Dt Nacimento: </td><td><?php if (!empty($cliente['Cliente']['data_nascimento'])) echo $this->Time->format('d/m/Y', $cliente['Cliente']['data_nascimento']); ?></td>
			</tr>		
		<?php }else { ?>
			<!--PESSOA JURIDICA-->
			<tr>
				<td>Tipo: </td><td><?php echo 'Pessoa Jurídica'; ?></td>
			</tr>
			<tr>
				<td>CNPJ: </td><td><?php echo $cliente['Cliente']['cpf_cnpj']; ?></td>
			</tr>
			<tr>
				<td>IE: </td><td><?php echo $cliente['Cliente']['rg_ie']; ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td>Telefone: </td><td><?php echo $cliente['Cliente']['telefone']; ?></td>
		</tr>
		<tr>
			<td>Email: </td><td><?php echo $cliente['Cliente']['email']; ?></td>
		</tr>
		<tr>
			<td>Contato: </td><td><?php echo $cliente['Cliente']['contato']; ?></td>
		</tr>
		<tr>
			<td>Aceita Notas para dia: </td><td><?php echo $dias[$cliente['Cliente']['dia_nota']]; ?></td>
		</tr>
		<tr>
			<td>Empresa: </td><td><?php echo $cliente['Empresa']['nome']; ?></td>
		</tr>
		<tr>
			<td>Observação: </td><td><?php echo $cliente['Cliente']['observacao']; ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="table table-condensed table-bordered tbenderecos">
					<thead>			
						<th>Tipo</th>
						<th>Endereco</th>
						<th>CEP</th>
						<th>Cidade</th>
					</thead>
					<tbody>
						<?php echo $this->element('clientes/tbenderecos');?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>