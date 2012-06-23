<?php
include('./includes/std.php');

require_once('includes/generate_html.php');
require_once('includes/generate_codabar.php');

echo GENERATE_STYLE;

echo '<table cellspacing="0">
<tr>';

$column = 0;
$row = 0;
for($i=$_POST['from']; $i<$_POST['from']+44; $i++) {
	$i = trim($i);
	
	if(!ctype_digit($i) OR empty($i) OR strlen($i)>8) {
		continue;
	}
	
	validate::KOD($i);
	
	while(true) {
		if(!$_POST['no_'.$row.'_'.$column]) {
			break;
		}
		else
		{
			echo '<td></td>';
			column($column, $row);
			continue;
		}
	}
	
	$kod = str_pad($i, 8, '0', STR_PAD_LEFT);
	
	echo '<td style="padding-left: '.margin($column%4).'mm;">
<img src="data:image/gif;base64,'.base64_encode(kod($kod)).'" alt=""><br>'.$kod.'
</td>
';
	
	column($column, $row);
}

while(true) {
	column($column, $row);
}

echo GENERATE_END;
?>
