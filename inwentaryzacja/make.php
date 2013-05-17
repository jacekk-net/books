<?php
$title = 'Inwentaryzacja - rozpoczęcie';
include('design/top.php');

require('../includes/config.php');
require('../includes/PDOO.php');

try {
	$fields = array('tytul', 'autor', 'miejsce', 'rok', 'wydawnictwo');

	$PDO = PDOO::Singleton();

	$st = $PDO->query('SELECT * FROM `ksiazki` WHERE `wycofana`=\'0\' ORDER BY `regal` ASC, `polka` ASC, `rzad` ASC');

	$fp = fopen('list.xml', 'w');
	if($fp === FALSE) {
		throw new Exception('Otwarcie pliku inwentaryzacja/list.xml nie powiodło się.');
	}

	fwrite($fp, '<?xml version="1.0" encoding="utf-8" ?>
	<!DOCTYPE inwentaryzacja [
	<!ENTITY % quot "&#34;">
	<!ENTITY % amp "&#38;">
	<!ENTITY % lt "&#60;">
	<!ENTITY % gt "&#62;">
	<!ELEMENT inwentaryzacja (lokalizacja)*>
	<!ELEMENT lokalizacja (ksiazka)*>
	<!ATTLIST lokalizacja
		id	ID	#IMPLIED
		regal	CDATA	#IMPLIED
		polka	CDATA	#IMPLIED
		rzad	CDATA	#IMPLIED>
	<!ELEMENT ksiazka (tytul | autor | miejsce | rok | wydawnictwo)*>
	<!ATTLIST ksiazka
		id	ID	#IMPLIED
		status	CDATA	#IMPLIED>
	<!ELEMENT tytul (#PCDATA)>
	<!ELEMENT autor (#PCDATA)>
	<!ELEMENT miejsce (#PCDATA)>
	<!ELEMENT rok (#PCDATA)>
	<!ELEMENT wydawnictwo (#PCDATA)>
	]>
	<inwentaryzacja>
	');

	$lastplace = NULL;

	while($entry = $st->fetch()) {
		if($lastplace != $entry['regal'].'/'.$entry['polka'].'/'.$entry['rzad']) {
			if($lastplace !== NULL) {
				fwrite($fp, '</lokalizacja>'."\n");
			}
			fwrite($fp, '<lokalizacja id="m_'.$entry['regal'].'_'.$entry['polka'].'_'.$entry['rzad'].'" regal="'.$entry['regal'].'" polka="'.$entry['polka'].'" rzad="'.$entry['rzad'].'">'."\n");
			$lastplace = $entry['regal'].'/'.$entry['polka'].'/'.$entry['rzad'];
		}
		
		fwrite($fp, '<ksiazka id="k'.$entry['id'].'">'."\n");
		foreach($fields as $key) {
			fwrite($fp, '<'.$key.'>'.htmlspecialchars($entry[$key]).'</'.$key.'>'."\n");
		}
		fwrite($fp, '</ksiazka>'."\n");
	}

	if($lastplace !== NULL) {
		fwrite($fp, '</lokalizacja>'."\n");
	}

	fwrite($fp, '</inwentaryzacja>'."\n");

?>

<h3>Krok 1 z 3: Przygotowanie listy książek</h3>

<p>Jeżeli nie widzisz błędów powyżej to krok pierwszy został pomyślnie zakończony.</p>

<form action="locate.htm">
<p>Możesz: <input type="submit" value="Rozpocząć inwentaryzację" /></p>
</form>

<?php
}
catch(Exception $e) {
	echo '<p style="color:red">Wystąpił błąd: '.$e->getMessage().'</p>';
}

include('design/bottom.php');
?>