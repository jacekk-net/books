<?php
include('./includes/std.php');

require_once('includes/generate_html.php');
require_once('includes/generate_codabar.php');

foreach(explode("\n", $_POST['kody']) as $i) {
	$i = trim($i);
	
	if($i=='') {
		continue;
	}
	
	if(($pos=strpos($i, '-'))!==FALSE) {
		$from = trim(substr($i, 0, $pos));
		$to = trim(substr($i, $pos+1));
		
		validate::KOD($from);
		validate::KOD($to);
		
		for($from=(int)$from; $from<=$to; $from++) {
			$kody[] = $from;
		}
	}
	else
	{
		validate::KOD($i);
		
		$kody[] = $i;
	}
}

echo GENERATE_STYLE;

echo '<table cellspacing="0">
<tr>';

$column = 0;
$row = 0;
foreach($kody as $i) {
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

while($row != 10 OR $column != 3) {
	echo '<td></td>';
	column($column, $row);
}

column($column, $row);

echo GENERATE_END;
?>
