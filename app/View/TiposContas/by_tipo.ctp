<option value="">[Escolha um Tipo Conta]</option>
<?php foreach ( $tipocontas as $id => $nome ): ?>
<option value="<?php echo $id ?>"><?php echo $nome ?></option>
<?php endforeach; ?>