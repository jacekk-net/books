<?php
__autoload('ibd');

class YAZ_ibd implements ibd_module {
	var $name, $yaz_server;
	
	function __construct($name, $server) {
		$this->name = $name;
		$this->yaz_server = $server;
	}
	
	function zapytanie_info($ISBN=NULL, $ISSN=NULL, $tytul=NULL, $autor=NULL, $wydawnictwo=NULL) {
		if(!empty($ISBN)) {
			$attrs[] = '@attr 1=7 "'.$ISBN.'"';
		}
		if(!empty($ISSN)) {
			$attrs[] = '@attr 1=8 "'.$ISSN.'"';
		}
		if(!empty($tytul)) {
			$attrs[] = '@attr 1=4 "'.$tytul.'"';
		}
		if(!empty($autor)) {
			$attrs[] = '@attr 1=1003 "'.$autor.'"';
		}
		if(!empty($wydawnictwo)) {
			$attrs[] = '@attr 1=1018 "'.$wydawnictwo.'"';
		}
		
		if(count($attrs)==1) {
			return $attrs[0];
		}
		elseif(count($attrs)>1) {
			$return = '@and '.array_pop($attrs).' '.array_pop($attrs);
		}
		
		if(count($attrs)>0) {
			foreach($attrs as $value) {
				$return = '@and '.$value.' '.$return;
			}
		}
		
		return $return;
	}
	
	function szukaj_info($tytul=NULL, $autor=NULL, $wydawnictwo=NULL) {
		YAZ::connect( $this->yaz_server );
		YAZ::search( self::zapytanie_info( NULL, NULL, $tytul, $autor, $wydawnictwo ) );
		
		return YAZ::return_arrays();
	}
	
	function szukaj_ISBN($kod) {
		YAZ::connect( $this->yaz_server );
		YAZ::search( self::zapytanie_info( $kod ) );
		if(substr($kod, 0, 3)=='978') {
			YAZ::search( self::zapytanie_info( convert::ISBN13_to_ISBN10( $kod ) ) );
		}
		
		return YAZ::return_arrays();
	}
	
	function szukaj_ISSN($kod) {
		YAZ::connect( $this->yaz_server );
		YAZ::search( self::zapytanie_info( convert::ISSN13_to_ISSN8( $kod ) ) );
		
		return YAZ::return_arrays();
	}
}
?>