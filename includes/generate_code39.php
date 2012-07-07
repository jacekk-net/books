<?php
if(!extension_loaded('gd')) {
	error::add('Brak rozszerzenia GD/GD2. Generowanie kodów kreskowych jest niemożliwe.');
}

$code = array(
	'SS' => '100101101101',
	'BT' => '0',
	'0' => '101001101101',
	'1' => '110100101011',
	'2' => '101100101011',
	'3' => '110110010101',
	'4' => '101001101011',
	'5' => '110100110101',
	'6' => '101100110101',
	'7' => '101001011011',
	'8' => '110100101101',
	'9' => '101100101101',
	'A' => '110101001011',
	'B' => '101101001011',
	'C' => '110110100101',
	'D' => '101011001011',
	'E' => '110101100101',
	'F' => '101101100101',
	'G' => '101010011011',
	'H' => '110101001101',
	'I' => '101101001101',
	'J' => '101011001101',
	'K' => '110101010011',
	'L' => '101101010011',
	'M' => '110110101001',
	'N' => '101011010011',
	'O' => '110101101001',
	'P' => '101101101001',
	'Q' => '101010110011',
	'R' => '110101011001',
	'S' => '101101011001',
	'T' => '101011011001',
	'U' => '110010101011',
	'V' => '100110101011',
	'W' => '110011010101',
	'X' => '100101101011',
	'Y' => '110010110101',
	'Z' => '100110110101',
	'-' => '100101011011',
	'.' => '110010101101',
	' ' => '100110101101',
	'$' => '100100100101',
	'/' => '100100101001',
	'+' => '100101001001',
	'%' => '101001001001',
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
	
	if(trim($kod, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $')!='') {
		error::add('Znaki inne niż cyfry, litery, pauza, kropka, spacja, ukośnik');
	}
	
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