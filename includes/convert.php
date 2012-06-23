<?php
class convert {
	static function ISBN13_to_ISBN10($kod) {
		validate::EAN($kod);
		
		if(substr($kod, 0, 3)=='978') {
			$kod = substr($kod, 3, 9);
			
			return strtoupper( $kod . checksum::ISBN($kod) );
		}
		else
		{
			error::add('Kodu ISBN-13 '.$kod.' nie można zamienić na ISBN-10!');
		}
	}
	
	static function ISBN10_to_ISBN13($kod) {
		validate::ISBN($kod);
		
		$kod = '978' . substr($kod, 0, -1);
		
		return $kod . checksum::EAN($kod);
	}
	
	static function ISSN13_to_ISSN8($kod) {
		validate::EAN($kod);
		
		$kod = substr($kod, 3, 7);
		
		return strtoupper( $kod . checksum::ISSN($kod) );
	}
	
	static function ISSN8_to_ISSN13($kod) {
		validate::ISSN($kod);
		
		$kod = '977' . substr($kod, 0, -1).'00';
		
		return $kod . checksum::EAN($kod);
	}
}
?>
