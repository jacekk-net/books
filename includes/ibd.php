<?php
interface ibd_module {
	//static $name;
	function szukaj_info($tytul=NULL, $autor=NULL, $wydawnictwo=NULL);
	function szukaj_ISBN($ISBN);
	function szukaj_ISSN($ISSN);
}

class ibd implements Countable {
	static $providers = array(
		'ibd_BN',
	);
	
	static $timelimit = 25;
	
	function __call($function, $args) {
		$stop = time() + self::$timelimit;
		$return = array();
		
		foreach(self::$providers as $provider) {
			if(time() >= $stop) break;
			
			$name = new $provider;
			if(!method_exists($name, $function)) {
				continue;
			}
			
			$results = call_user_func_array(array($name, $function), $args);
			
			if(!empty($results)) {
				$return[$name->name] = $results;
			}
		}
		
		return $return;
	}
	
	function count() {
		return count(self::$providers);
	}
}
?>