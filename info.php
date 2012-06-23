<?php
include('./includes/std.php');

$title = 'Wypożyczanie książki';
include('./design/top.php');

gotowe::informacje($_GET['kod']);

include('./design/bottom.php');
?>
