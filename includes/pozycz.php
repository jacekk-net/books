<?php
class pozycz {
	static function pozyczona($kod) {
		$ksiazka = ksiazki::szukaj_KOD($kod);
		return $ksiazka['od'] != NULL;
	}
}
?>