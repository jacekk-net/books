<?php
define('GENERATE_STYLE', '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>System biblioteczny - Wygenerowane etykiety</title>
<style type="text/css">
html, body, table, * {
	margin:0;
	padding:0;
	font-size: 4mm;
}
table {
	margin-top:7mm; border:0;
	width: 100%;
}
td {
	border: 0;
	text-align: center;
	width: 25%;
	height: 25mm;
}
</style>
</head>
<body>
');
define('GENERATE_END', '
</body>
</html>
');

function column(&$column, &$row) {
	if($column == 3) {
		if($row == 10) {
			$row = 0;
			echo '</tr> </table> </body> </html>';
			die();
		}
		else
		{
			$row++;
			echo '</tr> <tr>';
		}
	}
	
	$column++;
	if($column == 4) {
		$column = 0;
	}
}

function margin($i) {
	switch($i) {
		default:
			return 0;
		break;
		case 1:
			return 0;
		break;
		case 2:
			return 0;
		break;
		case 3:
			return 0;
		break;
	}
}
?>