<?php
include('./includes/std.php');

validate::MSC($_POST['regal'], $_POST['polka'], $_POST['rzad']);
if(!empty($_POST['regal2']) || !empty($_POST['polka2']) || !empty($_POST['rzad2'])) {
	validate::MSC($_POST['regal2'], $_POST['polka2'], $_POST['rzad2']);
}

$arr = array();

if(!empty($_POST['regal'])) {
	$arr['regal'] = $_POST['regal'];
}
if(!empty($_POST['polka'])) {
	$arr['polka'] = $_POST['polka'];
}
if(!empty($_POST['rzad'])) {
	$arr['rzad'] = $_POST['rzad'];
}

$aff = ksiazki::miejsce($_POST['regal2'], $_POST['polka2'], $_POST['rzad2'], $arr);

$title = 'Położenie książki';
include('design/top.php');

echo '<p>Ustalono położenie '.$aff.' książek.</p>';

include('design/bottom.php');
?>
