<?php
include('./includes/std.php');

validate::KOD($_POST['kod'], FALSE);

if(!is_uploaded_file($_FILES['marc']['tmp_name'])) {
	errorclass::add('Nie wysłano pliku!');
}

$szukaj = array( MARC21::to_array( MARC21::from_string( file_get_contents( $_FILES['marc']['tmp_name'] ) ) ) );
$i = 0;

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Krok 2 - wybierz żądaną książkę </h3>

<?php
gotowe::dodaj_lista($_POST['kod'], $szukaj);
?>

<?php
include('./design/bottom.php');
?>
