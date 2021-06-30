<option value="">[Escolha uma SubCategoria]</option>
<?php foreach ( $subcategorias as $id => $nome ): ?>
<option value="<?php echo $id ?>"><?php echo $nome ?></option>
<?php endforeach; ?>