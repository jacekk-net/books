<?php
include('./includes/std.php');

$title = 'Wyszukiwanie książki';
include('./design/top.php');
?>

<h3> Znalezione elementy </h3>

<?php
gotowe::lista();

include('./design/bottom.php');
?>
