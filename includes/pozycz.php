<?php
class pozycz {
	static function wypozyczenie($kod, $kto) {
		if(self::pozyczona($kod)!==FALSE) {
			error::add('Książka jest już wypożyczona!');
		}
		
		db2::add('pozycz', array('id' => $kod, 'kto' => $kto, 'od' => time()));
		ksiazki::cache_clear($kod);
	}
	
	static function zwrot($kod) {
		if(self::pozyczona($kod)===FALSE) {
			error::add('Książka nie jest wypożyczona!');
		}
		
		db2::edit('pozycz', array('do' => time()), array('id' => $kod, 'do' => NULL));
		ksiazki::cache_clear($kod);
	}
	
	static function pozyczona($kod) {
		$ksiazka = ksiazki::szukaj_KOD($kod);
		if($ksiazka['do']!==NULL OR $ksiazka['od']===NULL) {
			return FALSE;
		}
		else
		{
			return $ksiazka['kto'];
		}
	}
}
?>