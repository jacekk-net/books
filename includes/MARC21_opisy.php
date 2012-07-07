<?php
$MARC21_opis = array(
	'001' => array(
		'Numer kontrolny',
		'a' => '^'
	),
	'003' => array(
		'Instytucja nadająca num. kontrolny',
		'a' => '^',
	),
	'005' => array(
		// rrmmddggmmss.0
		'Data ostatniej modyfikacji',
		'a' => '^',
	),
	'015' => array(
		'Numer bibliografii narodowej',
		'a' => '^'
	),
	'020' => array(
		'ISBN',
		'a' => 'ISBN',
		'z' => 'Błędny/unieważniony'
	),
	'100' => array(
		'Główny autor',
		
	)
);
?>