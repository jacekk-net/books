<?php
include('./includes/std.php');

validate::KOD($_POST['kod'], FALSE);

$szukaj = ksiazki::szukaj_KOD($_POST['kod2']);

$i = 0;

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Krok 2 - wybierz żądaną książkę </h3>

<?php
gotowe::dodaj_lista($_POST['kod'], array($szukaj) );
?>

<?php
include('./design/bottom.php');
?>
