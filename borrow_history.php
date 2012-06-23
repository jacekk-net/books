<?php
include('./includes/std.php');

validate::KOD($_GET['kod']);

$title = 'Historia wypożyczeń książki';
include('./design/top.php');

$nastrone = 5;

if(ctype_digit($_GET['strona'])) {
	$strona = $nastrone*($strona-1);
}
else
{
	$strona = 0;
}

gotowe::informacje($_GET['kod']);

$num = db2::num('pozycz', '*', array('id' => $_GET['kod']));
$dane = db2::get('pozycz', '*', array('id' => $_GET['kod']), array('do' => 'DESC'), $strona, $nastrone);

echo '

<table id="bhist">
<tr> <th>Pożyczający</th> <th>Od</th> <th>Do</th> </tr>
';

foreach($dane as $o) {
	echo '<tr> <td>'.$o['kto'].'</td> <td>'.date('Y-m-d H:i:s', $o['od']).'</td> <td>'.($o['do'] ? date('Y-m-d H:i:s', $o['do']) : '').'</td> </tr>'."\n";
}

echo '</table>

<p>';

for($i=0; $i<$num; $i+=$nastrone) {
	$str = ($i/$nastrone)+1;
	if($i>=$strona && $i<$strona+$nastrone)
		echo '<b> [ '.$str.' ] </b> ';
	else
		echo '<a href="borrow_history.php?kod='.$_GET['kod'].'&amp;strona='.$str.'"> [ '.$str.' ] </a> ';
}


include('./design/bottom.php');
?>
