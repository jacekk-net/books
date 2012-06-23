<?php
ini_set('zlib.output_compression', TRUE);

include('./includes/std.php');

$query = sql::query('SELECT * FROM ksiazki');

$row = sql::fetchonea($query);

echo implode("\0", array_keys($row))."\n";

do {
	echo implode("\0", $row)."\n";
	
	$row = sql::fetchonea($query);
} while($row !== FALSE);
?>
