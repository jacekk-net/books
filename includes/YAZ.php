<?php
if(!extension_loaded('yaz')) {
	error::add('Brak rozszerzenia YAZ. Wyszukiwanie w bazach Biblioteki Narodowej niemożliwe.');
}

class YAZ {
	private static $connection;
	static $timeout = 10;
	
	static function connect($host) {
		self::$connection = yaz_connect($host, array('charset' => 'UTF-8'));
		yaz_syntax(self::$connection, 'marc21');
	}
	
	static function search($query, $start=1, $num=10) {
		yaz_search(self::$connection, 'rpn', $query);
		yaz_range(self::$connection, $start, $num);
		yaz_wait();
		self::is_error();
	}
	
	static function scan($query) {
		yaz_scan(self::$connection, 'rpn', $query);
		yaz_wait();
		self::is_error();
	}
	
	static function scan_result() {
		return yaz_scan_result(self::$connection);
	}
	
	static function scan_get($start=1, $num=10) {
		yaz_range(self::$connection, $start, $num);
		yaz_present(self::$connection);
		yaz_wait();
		self::is_error();
	}
	
	static function hits() {
		return yaz_hits(self::$connection);
	}
	
	static function return_MARCs() {
		$hits = self::hits();
		
		$records = array();
		
		$time = time();
		
		for($i=1; $i<=$hits; $i++) {
			if($time+self::$timeout <= time()) {
				break;
			}
			$record = yaz_record(self::$connection, $i, 'raw');
			$records[] = MARC21::from_string($record);
		}
		
		return $records;
	}
	
	static function return_arrays() {
		$return = array();
		
		$MARCs = self::return_MARCs();

		foreach($MARCs as $MARC) {
			$return[] = MARC21::to_array( $MARC );
		}
		
		return $return;
	}
	
	static function is_error() {
		if($e = yaz_error(self::$connection)) {
			error::add('Błąd YAZ: '.$e);
		}
		else
		{
			return FALSE;
		}
	}
}
?>
