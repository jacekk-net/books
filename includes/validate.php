<?php
class validate {
	static $kod = FALSE;
	
	static function KOD(&$kod, $exists=NULL) {
		if(ltrim($kod, '0123456789')!='' OR strlen($kod)>8 OR strlen($kod)<1) {
			error::add('Błędny KOD - dozwolone tylko cyfry');
		}
		
		$kod = str_pad($kod, 8, '0', STR_PAD_LEFT);
		
		if(!is_null($exists)) {
			if($exists!==ksiazki::exists($kod)) {
				error::add('Wybrana książka '.($exists ? 'nie' : 'już').' istnieje!');
			}
		}
	}
	
	static function EAN($kod) {
		if( ltrim($kod, '0123456789') != '' OR strlen($kod) != 13 ) {
			error::add('Błędny ISN - dozwolone tylko cyfry');
		}
		
		if( substr($kod, -1) != checksum::EAN(substr($kod, 0, -1)) ) {
			error::add('Podany kod ISN jest błędny');
		}
	}
	
	static function ISBN(&$kod) {
		$kod = str_replace('-', '', strtoupper($kod));
		if( ltrim($kod, '0123456789X') != '' OR strlen($kod) != 10 ) {
			error::add('Błędny ISBN - dozwolone tylko cyfry i znak X');
		}
		
		if( substr($kod, -1) != checksum::ISBN(substr($kod, 0, -1)) ) {
			error::add('Podany ISBN jest błędny');
		}
	}
	
	static function ISSN(&$kod) {
		$kod = str_replace('-', '', strtoupper($kod));
		if( ltrim($kod, '0123456789X') != '' OR strlen($kod) != 8 ) {
			error::add('Błędny ISSN - dozwolone tylko cyfry');
		}
		
		if( substr($kod, -1) != checksum::ISSN(substr($kod, 0, -1)) ) {
			error::add('Podany ISSN jest błędny');
		}
	}
	
	static function MSC(&$kod, $polka=NULL, $rzad=NULL) {
		$kod = strtoupper($kod);
		
		if($polka===NULL) {
			$polka = $_GET['polka'];
		}
		if($rzad===NULL) {
			$rzad = $_GET['rzad'];
		}
		
		if(!ctype_alnum($kod) OR ctype_digit($kod) OR $kod=='' OR strlen($kod)>5) {
			error::add('Podany kod miejsca (regał) jest błędny');
		}
		if($polka!='' AND (!ctype_digit($polka) OR $polka>255)) {
			error::add('Podany kod miejsca (półka) jest błędny');
		}
		if($rzad!='' AND (!ctype_digit($rzad) OR $rzad>255)) {
			error::add('Podany kod miejsca (rząd) jest błędny');
		}
	}
	
	static function type(&$kod) {
		$kod = str_replace('-', '', $kod);
		
		switch(strlen($kod)) {
			case 13:
				self::EAN($kod);
				
				if(substr($kod, 0, 3)=='978' OR substr($kod, 0, 3)=='979') {
					return 'ISBN';
				}
				elseif(substr($kod, 0, 3)=='977') {
					return 'ISSN';
				}
			break;
			case 9:
				if(self::$kod) {
					$kod = substr($kod, 1);
					self::ISSN($kod);
					$kod = convert::ISSN8_to_ISSN13($kod);
					return 'ISSN';
				}
			break;
			case 8:
				if(self::$kod) {
					self::KOD($kod);
					return 'KOD';
				}
				else
				{
					self::ISSN($kod);
					$kod = convert::ISSN8_to_ISSN13($kod);
					return 'ISSN';
				}
			break;
			case 10:
				self::ISBN($kod);
				$kod = convert::ISBN10_to_ISBN13($kod);
				return 'ISBN';
			break;
			default:
				if(self::$kod AND ctype_digit($kod)) {
					self::KOD($kod);
					return 'KOD';
				}
			break;
		}
		
		if(self::$kod) {
			self::MSC($kod);
			return 'MSC';
		}
		
		error::add('Nieznany typ kodu');
	}
}
?>
