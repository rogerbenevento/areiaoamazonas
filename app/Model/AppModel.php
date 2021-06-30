<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public $components = array('Session', 'Cookie', 'Auth');
	
	public function SqlDump(){
		$view = new View($this, false);
		return $view->element('sql_dump');		
	}
	
	public function VerificaAfterFind($campo,&$value){
		if (($campo == 'data' 
			||$campo == 'data_vencimento' 
			||  $campo == 'data_entrega' 
			|| $campo == 'validade_cnh' 
			|| $campo == 'data_nascimento' 
			|| $campo == 'data_admissao' 
			|| $campo == 'datasaida' 
			|| $campo == 'afastamento'
			|| $campo == 'emissao' 
			|| $campo == 'vencimento'
			|| $campo == 'indicacao'
			) && $value != '0000-00-00' && $value != null)
			$value = dateMysqlToPhp($value);
		if($campo == 'placa')
			$value = PlacaBr ($value) ;
	}
	
	public function beforeSave($options=Array()) {
		foreach ($this->data as &$model) {
			
			foreach ($model as $key => &$value) {
				if(!is_array($value)){
					//Formata Datas
					if ($key == 'data' 
						|| $key == 'data_vencimento' 
						||  $key == 'data_entrega'
						|| $key == 'validade_cnh'
						|| $key == 'emisao'
						|| $key == 'emissao'
						|| $key == 'vencimento'
						|| $key == 'indicacao'
						)
						if(substr_count($value, '/') > 0 )$value = dateFormatBeforeSave($value);
					// Formata Valores
					if ($key=='quantidade'){
						if(!is_object($value))$value = moedaBD($value,3);
					}
					if ($key == 'valor' || $key == 'pago' || $key=='quilometragem'){
						if(!is_object($value))$value = moedaBD($value);
					}
					// Formata Placa
					if ($key == 'placa')
						if(is_string($value))$value = PlacaBD($value);
				}
			}
		}
		return true;
	}

	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		//pr($results);
		
		foreach ($results as $campo => &$resultado){
			if(is_array($resultado)){
				foreach ($resultado as &$model){
					if(is_array($model)){
						foreach ($model as $campo => &$value){
							if(!is_array($value))
							$this->VerificaAfterFind($campo,$value);

						}//foreach
					}else{
						if(!is_array($model))
							$this->VerificaAfterFind($campo,$model);
					}
				}//foreach
			}else{
				if(!is_array($resultado))
					$this->VerificaAfterFind($campo,$resultado);
			}
		}


		return $results;
	}

	
	/**
	 * Imprime um grafico de torta
	 * @param array[string => int] $dados: Dados do Grafico
	 * @param string $titulo: Titulo do grafico
	 * @param int $largura: Largura do grafico
	 * @param int $altura: Altura do grafico
	 * @return string Tag IMG com o caminho para o grafico
	 */
	function gerarGraficoPizza($dados, $titulo, $largura = 480, $altura = 200) {

		// Gerando a URL dinamicamente
		$legendas = array_keys($dados);
		$valores = array_values($dados);

		// Converter valores para porcentagens
		$soma = array_sum($valores);
		$percentual = array();
		foreach ($valores as $valor) {
			$percentual[] = round($valor * 100 / $soma);
		}

		$grafico = array(
		    'cht' => 'p', // Tipo do gráfico
		    'chs' => $largura . 'x' . $altura, // Largura e altura do gráfico
		    'chd' => 't:' . implode(',', $percentual),
		    'chl' => implode('|', $percentual), // Valores no gráfico
		    'chdl' => implode('|', $legendas), // Legendas
		    'chma' => '10,10,20,30|10,30', // Margens
		    'chco' => 'c60000,1da3f8' // Cores do gráfico (gradiente)
		);
		$url = 'https://chart.googleapis.com/chart?' . http_build_query($grafico, '', '&');

		// Imprimindo o gráfico
		return sprintf('<img src="%s" width="%d" height="%d" alt="%s" />', $url, $largura, $altura, htmlentities($titulo, ENT_COMPAT, 'UTF-8')
		);
	}

//gerarGraficoPizza
	
	public function PaginateCount($conditions = null, $recursive = 0, $extra = array()) {
		//pr($extra);exit();
		$rec = empty($extra['extra']['recursive']) ? $recursive : $extra['extra']['recursive'];
		$contain = empty($extra['extra']['contain']) ? null : $extra['extra']['contain'];
		$opt = array(
				'conditions' => $conditions,
				'recursive' => $rec,
				'contain'=>$contain
		);
		if(!empty($extra['extra']['joins']))
			$opt['joins']=$extra['extra']['joins'];
		return $this->find('count', $opt);
	}
	
	
	/**
	 * uploads files to the server
	 * @params:
	 * 		$folder 	= the folder to upload the files e.g. 'img/files'
	 * 		$formdata 	= the array containing the form files
	 * 		$itemId 	= id of the item (optional) will create a new sub folder
	 * @return:
	 * 		will return an array with the success of each file upload
	 */
	function Upload($formdata, $itemId = null,$folder='img/',$newname=null,$override=false) {
		// setup dir names absolute and relative
		$folder_url = WWW_ROOT . $folder;
		$rel_url = $folder;

		// create the folder if it does not exist
		if (!is_dir($folder_url)) {
			mkdir($folder_url);
		}

		// if itemId is set create an item folder
		if ($itemId) {
			// set new absolute folder
			$folder_url = WWW_ROOT . $folder . '/' . $itemId;
			// set new relative folder
			$rel_url = $folder . '/' . $itemId;
			// create directory
			if (!is_dir($folder_url)) {
				mkdir($folder_url);
			}
		}

		// list of permitted file types, this is only images but documents can be added
		$permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'application/pdf');

		// loop through and deal with the files
		foreach ($formdata as $file) {
			// replace spaces with underscores
			$filename = (empty($newname))? str_replace(' ', '_', $file['name']) : $newname.substr($file['name'], strrpos($file['name'], '.'));;
			// assume filetype is false
			$typeOK = false;
			// check filetype is ok
			foreach ($permitted as $type) {
//				if ($type == $file['type']) {
					$typeOK = true;
//					break;
//				}
			}

			// if file type ok upload the file
			if ($typeOK) {
				// switch based on error code
				switch ($file['error']) {
					case 0:
						// check filename already exists
						if (!file_exists($folder_url . '/' . $filename) || $override) {
							// create full filename
							$full_url = $folder_url . '/' . $filename;
							$url = $rel_url . '/' . $filename;
							// upload the file
							$success = move_uploaded_file($file['tmp_name'], $url);
						} else {
							// create unique filename and upload file
							//ini_set('date.timezone', 'Europe/London');
							$now = date('Y-m-d-His');
							$full_url = $folder_url . '/' . $now . $filename;
							$url = $rel_url . '/' . $now . utf8_encode($filename);
							$success = move_uploaded_file($file['tmp_name'], $url);
						}
						// if upload was successful
						if ($success) {
							// save the url of the file
							$result['urls'][] = $url;
						} else {
							$result['errors'][] = "Error uploaded $filename. Please try again.";
						}
						break;
					case 3:
						// an error occured
						$result['errors'][] = "Error uploading $filename. Please try again.";
						break;
					default:
						// an error occured
						$result['errors'][] = "System error uploading $filename. Contact webmaster.";
						break;
				}
			} elseif ($file['error'] == 4) {
				// no file was selected for upload
				$result['nofiles'][] = "No file Selected";
			} else {
				// unacceptable file type
				$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
			}
		}
		return $result;
	}//uploadFiles
}