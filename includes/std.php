<?php
define('STANDARD', TRUE);

class errorclass {
	static $time;
	static function add($text) {
		$title = 'Błąd';
		include('design/top.php');
		
		echo '<p>'.$text.'</p>';
		
		include('design/bottom.php');
		die();
	}
}

errorclass::$time = microtime(TRUE);

function __autoload($class) {
	if($class == 'sql') {
		$class = 'db2';
	}
	require_once('./includes/'.$class.'.php');
}
?>
