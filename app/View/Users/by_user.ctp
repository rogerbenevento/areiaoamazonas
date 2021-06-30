<option value="">[ Selecione um Vendedor ]</option>
<?php foreach ( $vendedores as $itens ): ?>
    <option value="<?php echo $itens['User']['id']; ?>"><?php echo $itens['User']['nome']; ?></option>
<?php endforeach; ?>