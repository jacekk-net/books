<?php
class pozycz {
	static function wypozyczenie($kod, $kto) {
		if(self::pozyczona($kod)!==FALSE) {
			errorclass::add('Książka jest już wypożyczona!');
		}
		
		db2::add('pozycz', array('id' => $kod, 'kto' => $kto, 'od' => time()));
		ksiazki::cache_clear($kod);
	}
	
	static function zwrot($kod) {
		if(self::pozyczona($kod)===FALSE) {
			errorclass::add('Książka nie jest wypożyczona!');
		}
		
		$st = PDOO::Singleton()->prepare('INSERT INTO pozycz_historia (id, kto, od, do)
			SELECT id, kto, od, ? FROM pozycz WHERE id=?');
		$st->execute(array(time(), $kod));
		
		$st = PDOO::Singleton()->prepare('DELETE FROM pozycz WHERE id=?');
		$st->execute(array($kod));
		
		ksiazki::cache_update($kod);
	}
	
	static function pozyczona($kod) {
		$ksiazka = ksiazki::szukaj_KOD($kod);
		return $ksiazka['od'] != NULL;
	}
}
?>