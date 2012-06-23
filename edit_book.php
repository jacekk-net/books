<?php
include('./includes/std.php');

ksiazki::edytuj($_POST);

$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Książka została zmieniona! </h3>

<p>Informacje o zmienionym egzemplarzu:</p>

<?php
gotowe::informacje($_POST['id']);

include('./design/bottom.php');
?>
