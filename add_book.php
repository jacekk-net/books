<?php
include('./includes/std.php');

if($_POST['id']=='' OR empty($_POST['autor']) OR empty($_POST['tytul']) OR empty($_POST['jezyk'])) {
	error::add('Brak wymaganych danych o książce (kod, autor, tytuł, język)');
}

ksiazki::dodaj($_POST);

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Książka została dodana! </h3>

<p>Informacje o dodanym egzemplarzu:</p>

<?php
gotowe::informacje($_POST['id']);

include('./design/bottom.php');
?>
