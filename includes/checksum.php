<?php
class checksum {
	static function EAN($kod) {
		$kod = str_split($kod);
		
		$now = 1;
		foreach($kod as $v) {
			if($now==1) {
				$sum += $v;
				$now = 3;
			}
			else
			{
				$sum += $v*3;
				$now = 1;
			}
		}
		
		return (10 - ($sum%10)) % 10;
	}
	
	static function ISBN($kod) {
		$kod = str_split($kod);
		
		foreach($kod as $k => $v) {
			$sum += (10-$k)*$v;
		}
		
		$sum = (11 - ($sum % 11)) % 11;
		if($sum == 10) {
			$sum = 'X';
		}
		
		return $sum;
	}
	
	static function ISSN($kod) {
		$kod = str_split($kod);
		
		foreach($kod as $k => $v) {
			$sum += (8-$k)*$v;
		}
		
		$sum = (11 - ($sum % 11)) % 11;
		if($sum == 10) {
			$sum = 'X';
		}
		
		return $sum;
	}
}
?>
