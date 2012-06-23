<?php
include('./includes/std.php');

$title = 'Książki wycofane';
include('./design/top.php');

$_GET['wycofana'] = 1;
gotowe::lista();

include('./design/bottom.php');
?>
