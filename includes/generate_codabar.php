<?php
if(!extension_loaded('gd')) {
	error::add('Brak rozszerzenia GD/GD2. Generowanie kodów kreskowych jest niemożliwe.');
}

$code = array(
	'SS' => '1011110000100001',
	'BT' => '0',
	'0' => '1010100001111',
	'1' => '1010111100001',
	'2' => '1010000101111',
	'3' => '1111000010101',
	'4' => '1011110100001',
	'5' => '1111010100001',
	'6' => '1000010101111',
	'7' => '1000010111101',
	'8' => '1000011110101',
	'9' => '1111010000101',
	'-' => '1010000111101',
	'$' => '1011110000101',
	':' => '1111010111101111',
	'/' => '1111011110101111',
	'.' => '1111011110111101',
	'+' => '1011110111101111'
);

function gen_binary($kod) {
	global $code;

	$kod = str_split($kod);
	$ret = '';
	foreach($kod as $key => $val) {
		$ret .= $code[$val].$code['BT'];
	}

	return $ret;
}
function print_code($kod, $img, $b, $w) {
	$kod = str_split($kod);
	foreach($kod as $val) {
		if($val==1) {
			imageline($img, $now, 0, $now, 40, $b);
			$now++;
		}
		elseif($val==0) {
			$now++;
		}
	}
}

function kod($kod) {
	global $code;
	$kod = $code['SS'].$code['BT'].gen_binary($kod).$code['SS'];
	
	$i = imagecreate(strlen($kod), 40);
	$w = imagecolorallocate($i, 255, 255, 255);
	$b = imagecolorallocate($i, 0, 0, 0);
	
	print_code($kod, $i, $b, $w);
	
	ob_start();
	imagegif($i);
	$img = ob_get_contents();
	ob_end_clean();
	
	return $img;
}
?>
