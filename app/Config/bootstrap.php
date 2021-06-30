<?php
App::import('Vendor', array('file' => 'autoload'));

Cache::config('default', array('engine' => 'File'));
function alias($string, $find = '_') {
	$pos = strpos($string, $find);
	if ($pos) {
		$pos = ( strlen($string) - $pos );
		return substr_replace($string, '.', -$pos, 1);
	}
	return false;
}

/**
 * daysOfMonth() - Retorna o total de dias que o usuário utilizará o serviço no mes
 * para fazer o calculo proporcional aos dias que o usuário usou o serviço
 * @param date $date - Data do mes
 */
function daysOfMonth($date) {
	if (preg_match('/-/', $date))
		list( $day, $month, $year ) = explode('-', $date);
	else
		list( $day, $month, $year ) = explode('/', $date);

	$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	return array('dias_mes' => $days, 'dias_utilizacao' => $days - $day);
}

/**
 * função que soma dias a uma data
 * @param date $date (Formato Americano)
 * @param int $days Número de dias a adicionar
 */
function addDayIntoDate($date, $days) {
	if (preg_match('/-/', $date))
		list( $ano, $mes, $dia ) = explode('-', $date);
	else
		list( $dia, $mes, $ano ) = explode('/', $date);

	$proxima_data = mktime(0, 0, 0, $mes, ( $dia + $days), $ano);

	return strftime("%Y-%m-%d", $proxima_data);
}

/**
 * dateFormatBeforeSave() - Formata a data vinda do usuário para o padrão do banco de dados
 * @param string $dateString - Data no formato brasileiro (dd/mm/YYYY)
 * @return string (YYYY-mm-dd)
 */
function dateFormatBeforeSave($dateString) {
	if(substr_count($dateString, '/')>0){
		list( $dia, $mes, $ano ) = explode('/', $dateString);
		return "{$ano}-{$mes}-{$dia}";
	}else{
		return $dateString;
	}
}
function datePhpToMysql($date){
	return dateFormatBeforeSave($date);
}
/**
 * dateMysql2php() - Formata a data vinda do banco para o padrão do usuario
 * @param string $dateString - Data no formato brasileiro (YYYY-mm-dd)
 * @return string (dd/mm/YYYY)
 */
function dateMysqlToPhp($dateString) {
	if(substr_count($dateString, '-')>0){
		$arr =@date_parse($dateString);
		return date('d/m/Y', mktime($arr["hour"], $arr["minute"], $arr["second"], $arr["month"], $arr["day"], $arr["year"]));
	}else{
		return $dateString;
	}
}

function CidadeDB($str){
	$str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
    //$str = preg_replace('/[^a-z0-9]/i', '_', $str);
    //$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    return $str;
}
/**
 * TextoToUp() - Transforma o texto passado para maiscula
 * @param string $string - texto a ser transformado
 * @return string
 */
function TextoToUp($string) {
	$minusculos = Array('ã', 'õ', 'ê', 'ô', 'À', 'á', 'é', 'í', 'ó', 'ú', 'ç');
	$maiusculos = Array('Ã', 'Õ', 'Ê', 'Ô', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ç');

	return strtoupper(str_ireplace($minusculos, $maiusculos, $string));
}

/**
 * PlacaBD() - Formata a placa vinda do usuária
 * @param string $placa - Placa no formato brasileiro (AAA-9999)
 * @return string (AAA9999)
 */
function PlacaBD($placa) {
	if(substr_count($placa, '-')>0)
		   $placa=str_replace('-', '', $placa);
	$placa = TextoToUp($placa);
	return $placa;
}
/**
 * PlacaBr() - Formata a placa vinda do banco
 * @param string $placa - Placa (AAA9999)
 * @return string (AAA-9999)
 */
function PlacaBr($placa) {
	if(substr_count($placa, '-')==0)
		$placa=  substr($placa,0,3).'-'.substr($placa,3);
	return $placa;
}
/**
 * recebeFloatBeforeSave() - Trata os valores do tipo Float que estão no padrão brasileiro para o padrão do banco de dados
 * @param float $valor - Valor no formato brasileiro (decimais = ',', milhar = '.')
 * @return float (decimais = '.', milhar = ',')
 */
function moedaBD($valor,$decimais=2){
	return recebeFloatBeforeSave($valor,$decimais);
}
function recebeFloatBeforeSave($valor,$decimais=2) {
	@$valor=$valor.'';
	$valor = str_replace('R$', '', $valor);
	$valor = str_replace(' ', '', $valor);
	
	//$valor = str_replace('.', '', $valor);
	if(substr($valor,-3,1)==','){
		//Numero BR
		$valor = str_replace('.', '', $valor);
		$valor = str_replace(',', '.', $valor);
	}else{
		$valor = str_replace(',', '', $valor);
	}
	//pr($valor);
	$valor = @number_format($valor, $decimais, '.', '');
	//pr($valor);exit();
	return $valor;
}

/**
 * moedaBr() - Trata os valores do tipo Float para o padrão da moeda brasileira
 * @param float $valor - Valor no formato brasileiro (decimais = ',', milhar = '.')
 * @return string
 */
function moedaBr($valor,$round=true,$pre=null) {
	if(!$round){
		$valor = number_format($valor, 3 , ',','.');
		$valor = substr($valor, 0, strpos($valor, ',')+3);
	}else
		$valor=number_format($valor, 2, ',', '.');
	
	if($pre !== false){
		if($pre==null)
			$pre="R$ ";
	}else
		$pre="";
	return $pre . $valor;
}

/**
 * calculaIntervalo() - Faz um calculo entre duas datas e retornar o intervalo em meses
 * @param date $data1 - Primeira data
 * @param date $data2 - Segunda data
 * @return integer
 */
function calculaIntervalo($data1, $data2 = '') {
	// se data2 for omitida, o calculo sera feito ate a data atual
	$data2 = $data2 == '' ? date("d/m/Y", mktime()) : $data2;

	// separa as datas em dia,mes e ano
	list($dia1, $mes1, $ano1) = explode("/", $data1);
	list($dia2, $mes2, $ano2) = explode("/", $data2);

	// so lembrando que o padrao eh MM/DD/AAAA
	$timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
	$timestamp2 = mktime(0, 0, 0, $mes2, $dia2, $ano2);

	// calcula a diferenca em timestamp
	$diferenca = ($timestamp1 > $timestamp2) ? ($timestamp1 - $timestamp2) : ($timestamp2 - $timestamp1);

	$anos = ( date('Y', $diferenca) - 1970 ) * 12;
	$meses = date('m', $diferenca) - 1;
	$dias = date('d', $diferenca) - 1;

	#if ( $dias > 20 ) $meses++;

	return $anos + $meses;

	// retorna o calculo em anos, meses e dias
	#return (date("Y",$diferenca)-1970)." anos,".(date("m",$diferenca)-1)." meses e ".(date("d",$diferenca)-1)." dias";
}

/**
 * função que soma meses a uma data
 * @param date $date (Formato Americano)
 * @param int $months Número de meses a adicionar
 */
function addMonthIntoDate($date, $months) {
	if (preg_match('/-/', $date))
		list( $ano, $mes, $dia ) = explode('-', $date);
	else
		list( $dia, $mes, $ano ) = explode('/', $date);

	$date = mktime(0, 0, 0, ( $mes + $months), $dia, $ano);
	return preg_match('/-/', $date) ? strftime("%d/%m/%Y", $date) : strftime("%Y-%m-%d", $date);
}

function RemoverAcentuacoes($string){
	//$string=  TextoToUp($string);
	
	$string = str_replace("Ô","O",$string);
	$string = str_replace("ç","C",$string);
	$string = str_replace("Ç","C",$string);
	$string = str_replace("  "," ",$string);
	$string = eregi_replace("[ÁÀÂÃª]","A",$string);	
	$string = eregi_replace("[ÉÈÊ]","E",$string);	
	$string = eregi_replace("[ÓÒÔÕº]","O",$string);	
	$string = eregi_replace("[ÚÙÛ]","U",$string);	
	
	//$string= preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1',$string);
	return $string;
}

function valorPorExtenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
 
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
 
	$z=0;
 
	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];
 
	// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;) 
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}
 
	return($rt ? $rt : "zero");
}

function validaEmail($email) {
	$conta = "^[a-zA-Z0-9\._-]+@";
	$domino = "[a-zA-Z0-9\._-]+.";
	$extensao = "([a-zA-Z]{2,4})$";
	$pattern = $conta . $domino . $extensao;
	if (ereg($pattern, $email))
		return true;
	else
		return false;
}