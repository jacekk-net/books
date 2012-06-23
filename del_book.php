<?php
include('./includes/std.php');

ksiazki::usun($_POST['kod']);

$title = 'Usuwanie książki';
include('./design/top.php');
?>

<h3> Książka została usunięta </h3>

<?php
include('./design/bottom.php');
?>
