<?php
include('./includes/std.php');

require_once('includes/generate_html.php');
require_once('includes/generate_code39.php');

echo GENERATE_STYLE;

echo '<table cellspacing="0">
<tr>';

$column = 0;
$row = 0;
foreach(explode("\n", $_POST['kody']) as $i) {
	$i = trim($i);
	
	if(empty($i)) {
		continue;
	}
	
	$i = explode('/', $i);
	validate::MSC($i[0], $i[1], $i[2]);
	$i = $i[0].'/'.$i[1].'/'.$i[2];
	
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
	};
	
	echo '<td style="padding-left: '.margin($column%4).'mm;">
<img src="data:image/gif;base64,'.base64_encode(kod(str_replace('/', '$I', $i))).'" alt=""><br>'.$i.'
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