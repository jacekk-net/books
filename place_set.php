<?php
include('./includes/std.php');

validate::MSC($_POST['regal'], $_POST['polka'], $_POST['rzad']);

$kody = explode("\n", $_POST['kody']);
$and = array('OR' => NULL);
foreach($kody as $kod) {
	$kod = trim($kod);
	if(($pos=strpos($kod, '-'))!==FALSE) {
		$from = trim(substr($kod, 0, $pos));
		$to = trim(substr($kod, $pos+1));
		
		validate::KOD($from);
		validate::KOD($to);
		
		for($from=(int)$from; $from<=$to; $from++) {
			$and['id'][] = $from;
		}
	}
	elseif($kod != '') {
		validate::KOD($kod);
		$and['id'][] = (int)$kod;
	}
}

ksiazki::miejsce($_POST['regal'], $_POST['polka'], $_POST['rzad'], $and);

$title = 'Położenie książki';
include('design/top.php');

echo '<p>Ustalono położenie następujących książek:</p>

<ul>
';

foreach($and['id'] as $kod) {
	echo '<li>'.$kod.'</li>'."\n";
}

echo '</ul>';

include('design/bottom.php');
?>
