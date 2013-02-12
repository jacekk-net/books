<?php
include('./includes/std.php');

validate::KOD($_GET['kod']);

$title = 'Historia wypożyczeń książki';
include('./design/top.php');

gotowe::informacje($_GET['kod']);
gotowe::historia($_GET['kod']);

include('./design/bottom.php');
?>