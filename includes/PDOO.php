<?php
if(!extension_loaded('pdo')) {
	throw new Exception('Brak rozszerzenia PDO. Skrypt nie będzie działał.');
}

if(!extension_loaded('pdo_mysql')) {
	throw new Exception('Brak rozszerzenia PDO MySQL. Skrypt nie będzie działał.');
}

class PDOO {
	private static $PDO;
	
	static function Singleton() {
		if(self::$PDO === NULL) {
			self::$PDO = new PDO('mysql:dbname='.config::$db_base.';host='.config::$db_host,
				config::$db_user, config::$db_pass);
			self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$PDO->query('SET NAMES utf8');
		}
		
		return self::$PDO;
	}
}
?>