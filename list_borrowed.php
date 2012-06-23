<?php
include('./includes/std.php');

$title = 'Książki wypożyczone';
include('./design/top.php');

$_GET['do'] = TRUE;
gotowe::lista();

include('./design/bottom.php');
?>
