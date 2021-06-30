<?php

class Vendedor extends AppModel {
	public $useTable = 'vendedores';
	public $order = array('Vendedor.nome' => 'ASC');
	public $displayField = 'nome';
	public $recursive = 1;
	public $validate = array(
	    'nome' => array('rule' => 'notEmpty', 'message' => 'Informe o Nome'),
	    'comissao' => array('rule' => 'notEmpty', 'message' => 'Informe a Comissao'),
	);
	public $belongsTo = array('Cidade', 'User');

	
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		//pr($results);
		foreach ($results as &$resultado)
			foreach ($resultado as &$Vendedor)
				foreach ($Vendedor as $campo => &$value){
					if (($campo == 'data_nascimento' || $campo == 'data_admissao' || $campo == 'datasaida' || $campo == 'afastamento') && $value != '0000-00-00')
						$value = dateMysqlToPhp($value);
					if ($campo == 'comissao')
						$value = number_format ($value,2);
				}


		return $results;
	}

}