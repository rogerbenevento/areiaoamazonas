<?php

if ($this->Session->check('enderecos')) {
	foreach ($this->Session->read('enderecos') as $key => $value){
		echo "<tr class='{$value['indice']}'>"
		. ($this->action=='view'?'':
			"<td><a href='#' class='delete btn btn-del-endereco' id='{$value['indice']}' title='REMOVER'><div class='icon icon-trash'></div></a></td>")
		. "<td>{$tipos[$value['tipo_id']]}</td>"
		. "<td>{$value['endereco']}</td>"
		. "<td>{$value['numero']}</td>"
		. "<td>{$value['cep']}</td>"
		. "<td>{$value['cidade_nome']}</td>"
		. "</tr>";
	}
}
?>