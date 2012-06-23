<?php
include('./includes/std.php');

validate::KOD($_POST['kod']);

list(,$szukaj1) = ksiazki::szukaj_info($_POST);
$ibd = new ibd;
$szukaj2 = $ibd->szukaj_info($_POST['tytul'], $_POST['autor'], $_POST['wydawnictwo']);

$i = 0;

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Krok 2 - wybierz żądaną książkę </h3>

<?php
gotowe::dodaj_lista($_POST['kod'], $szukaj1, $szukaj2);
?>

<?php
include('./design/bottom.php');
?>
