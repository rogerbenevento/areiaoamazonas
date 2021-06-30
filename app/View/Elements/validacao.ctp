<?php
if ( !empty( $erros ) ) {
	$campos = array();
	echo '<div id="erros">';
		echo "<h3>Foram encontrados erros nos seguintes campos:</h3>";
		echo "<ul>";
		foreach ( $erros as $key => $value ) {
			if ($helper) $key = 'Form'. Inflector::camelize($key);
			echo "<li><label for='{$key}'>{$value}</label></li>";
			$campos[] = '#'.$key;
		}
		echo "</ul>";
	echo '</div><br />';
}
if ( isset( $campos ) ) $campos = implode( ', ', $campos );
?>

<style type="text/css">
#erros{ margin: 10px auto; border: 1px solid red; width: 600px; padding: 10px 20px; background: #FFF0F5; text-align: left; }
#erros h3{ color: red; padding-bottom: 10px; }
#erros ul{ list-style-type: none; }
#erros ul li{ padding: 5px; letter-spacing: 1px; display: block; }
#erros ul li strong{ display: block; float: left; width: 100px; }
#erros ul li:hover label{ font-size: 14px; cursor: pointer; }
#erros ul li:hover label:before{ content: "→"; padding-right: 5px; }
<?php echo $campos ?>{ box-shadow: 0 0 10px rgba(255, 0, 0, 0.2) inset; }
</style>
