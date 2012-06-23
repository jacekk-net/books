<?php
include('./includes/std.php');

validate::KOD($_POST['kod'], FALSE);

$ibd = new ibd;

switch( validate::type($_POST['isn']) ) {
	case 'ISBN':
		$szukaj1 = ksiazki::szukaj_ISBN($_POST['isn']);
		$szukaj2 = $ibd->szukaj_ISBN($_POST['isn']);
	break;
	case 'ISSN':
		$szukaj1 = ksiazki::szukaj_ISSN($_POST['isn']);
		$szukaj2 = $ibd->szukaj_ISSN($_POST['isn']);
	break;
}

$i = 0;

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Krok 2 - wybierz żądaną książkę </h3>

<?php
gotowe::dodaj_lista($_POST['kod'], $szukaj1, $szukaj2);

include('./design/bottom.php');
?>
