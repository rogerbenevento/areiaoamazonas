<?php if (count($obras) > 0):
		echo '<option value="">[Obras]</option>';
		foreach ($obras as $p):
			echo "<option value='{$p['Obra']['id']}' ".($selected==$p['Obra']['id'] ? 'selected="selected"':'').">"
			. "{$p['Obra']['nome']} - {$p['Obra']['endereco']}"
			. "</option>";
		endforeach; 
	else:
		echo '<option value="">[Obras]</option>';
	endif; 
?>