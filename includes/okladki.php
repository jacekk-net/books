<?php
class okladki {
	static function znajdz($KOD, $ISBN, $dir = 'covers') {
		if(strlen($KOD)<=8 && ctype_digit($KOD)) {
			validate::KOD($KOD);
			
			if(file_exists('./'.$dir.'/own/'.$KOD)) {
				return './'.$dir.'/own/'.$KOD;
			}
		}
		
		if(strlen($ISBN)==13) {
			validate::EAN($ISBN);
			
			if(substr($ISBN, 0, 3)=='978') {
				$ISBN10 = convert::ISBN13_to_ISBN10($ISBN);
			}
			else
			{
				$ISBN10 = $ISBN;
			}
			
			if(file_exists('./'.$dir.'/own/'.$ISBN)) {
				return './'.$dir.'/own/'.$ISBN;
			}
			
			if(self::librarything($ISBN, $dir)!==FALSE) {
				return './'.$dir.'/'.$ISBN;
			}
		}
		
		return FALSE;
	}
	
	static function librarything($ISBN, $dir = 'covers') {
		if(!function_exists('curl_init') || !config::$lt_api) {
			return FALSE;
		}
		
		// Okładkę już mamy
		if(file_exists('./'.$dir.'/'.$ISBN)) {
			if(filesize('./'.$dir.'/'.$ISBN)>0) {
				return NULL;
			}
			
			// Negatywne cache'owanie
			if(filesize('./'.$dir.'/'.$ISBN)==0 AND filemtime('./'.$dir.'/'.$ISBN)+(30*24*60*60) > time()) {
				return FALSE;
			}
		}
		
		$get = 'http://www.librarything.com/devkey/'.config::$lt_api.'/'.($dir=='covers_big' ? 'large' : 'small').'/isbn/'.$ISBN10;
		$curl = curl_init($get);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		
		$img = @curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		
		if($code != 200 || substr($type, 0, 6) != 'image/' || $img == FALSE || strlen($img) < 100) {
			// Negatywne cache'owanie
			touch('./'.$dir.'/'.$ISBN);
			return FALSE;
		}
		else
		{
			file_put_contents('./'.$dir.'/'.$ISBN, $img);
			return TRUE;
		}
	}
	
	static function przenies($SKOD, $SISBN, $KOD, $ISBN) {
		foreach(array('covers', 'covers_big') as $where) {
			$nowaokl = self::znajdz($KOD, $ISBN, $where);
			if(!$nowaokl) {
				$staraokl = self::znajdz($SKOD, $SISBN, $where);
				if(!$staraokl) {
					continue;
				}
				if($nowaokl == $staraokl) {
					continue;
				}
					
				if(!empty($ISBN)) {
					if(strpos($staraokl, '/own/')) {
						rename($staraokl, './'.$where.'/own/'.$ISBN);
					}
					else
					{
						rename($staraokl, './'.$where.'/'.$ISBN);
					}
				}
				else
				{
					rename($staraokl, './'.$where.'/own/'.$KOD);
				}
			}
		}
		
		if($KOD != $SKOD) {
			self::usun($SKOD, '');
		}
	}
	
	static function usun($KOD, $ISBN) {
		if($ISBN) {
			@unlink('./covers/'.$ISBN);
			@unlink('./covers_big/'.$ISBN);
			@unlink('./covers/own/'.$ISBN);
			@unlink('./covers_big/own/'.$ISBN);
		}
		else
		{
			@unlink('./covers/own/'.$KOD);
			@unlink('./covers_big/own/'.$KOD);
		}
	}
	
	static function upload($files, $KOD, $ISBN) {
		if($ISBN) {
			$DANE = $ISBN;
		}
		else
		{
			$DANE = $KOD;
		}
		
		if(is_uploaded_file($files['tmp_name'])) {
			okladki::skaluj($files['tmp_name'], 500, 500, './covers_big/own/'.$DANE);
			okladki::skaluj($files['tmp_name'], 53, 80, './covers/own/'.$DANE);
			if(file_exists('./covers/'.$DANE)) {
				unlink('./covers/'.$DANE);
			}
		}
	}
	
	static function skaluj($file, $max_width, $max_height, $outfile) {
		list($width, $height, $type) = getimagesize($file);
		
		switch($type) {
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($file);
			break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($file);
			break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($file);
			break;
			case IMAGETYPE_XBM:
				$image = imagecreatefromxpm($file);
			break;
			default:
				errorclass::add('Nieznany format obrazka: '.$type.'!');
				return FALSE;
			break;
		}
		
		if ($width > $max_width OR $height > $max_height) {
			if($width*$max_height > $height*$max_width) {
				$new_width = $max_width;
				$new_height = round( ($new_width / $width) * $height );
			}
			else
			{
				$new_height = $max_height;
				$new_width = round( ($new_height / $height) * $width );
			}
			
			$new_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			return imagejpeg($new_image, $outfile, 100);
		}
		else
		{
			return imagejpeg($image, $outfile, 100);
		}
	}
}
?>