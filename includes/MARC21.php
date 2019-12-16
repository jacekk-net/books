<?php
define('RECORD_SEPERATOR', chr(0x1e));
define('UNIT_SEPERATOR', chr(0x1f));

class MARC21 {
	static function from_string($data) {
		$lead_len = 24;
		$lead = substr($data, 0, $lead_len);
		
		$file_length = substr($lead, 0, 5);
		$head_len = substr($lead, 12, 5);
		
		$cat_record_lof = substr($lead, 20, 1);
		$cat_record_scp = substr($lead, 21, 1);
		$cat_record_imp = substr($lead, 22, 1);
		$cat_record_len = 3 + $cat_record_lof + $cat_record_scp + $cat_record_imp;
		$cat_len = $head_len-$lead_len-1;
		
		$cat = substr($data, $lead_len, $cat_len);
		$info = substr($data, $head_len);
		
		$unit = FALSE;
		for($i=0; $i<$cat_len; $i += $cat_record_len) {
			$rec_num = substr($cat, $i, 3);
			
			if($rec_num>899) {
				continue;
			}
			
			$rec_len = substr($cat, $i+3, $cat_record_lof);
			$rec_start = substr($cat, $i+3+$cat_record_lof, $cat_record_scp);
			$rec = substr($info, $rec_start, $rec_len-1);
			
			$temp = array();
			$unit = FALSE;
			$unit_letter = 'a';
			
			for($j=0; $j<$rec_len; $j++) {
				$char = substr($rec, $j, 1);
				
				if(($j==0 || $j==1) && $rec_num>9 && $char!=' ') {
					if($j==0) {
						$temp['f0'] = $char;
					}
					elseif($j==1) {
						$temp['f1'] = $char;
					}
				}
				elseif($char == UNIT_SEPERATOR) {
					$unit = TRUE;
				}
				elseif($unit === TRUE) {
					$temp[$unit_letter] = trim($collect, '	 :;,/');
					$unit = FALSE;
					$unit_letter = $char;
					$collect = '';
				}
				else
				{
					$collect .= $char;
				}
			}
			
			$temp[$unit_letter] = trim($collect, '	 :;,/');
			$collect = '';
			
			$return[$rec_num][] = $temp;
		}
		
		return $return;
	}
	
	static function to_array($MARC) {
		if(!$MARC['020']) {
			$MARC['020'] = array();
		}
		foreach($MARC['020'] as $value) {
			$value = (string)$value['a'];
			if(strlen($value)==9 AND strlen($value)!=13) {
				$value .= checksum::ISBN($value);
			}
			if( strlen($value) > strlen($ISBN) ) {
				$ISBN = $value;
			}
		}
		
		if(!$MARC['022']) {
			$MARC['022'] = array();
		}
		foreach($MARC['022'] as $value) {
			$value = (int)$value['a'];
			if( strlen($value) > strlen($ISSN) ) {
				$ISSN = $value;
			}
		}
		
		if($MARC['100'][0]['f0']==1) {
			$autor = explode(', ', $MARC['100'][0]['a'], 2);
			$MARC['100'][0]['a'] = str_replace('.', '', $autor[1]).' '.$autor[0];
		}
		
		if(empty($MARC['100'][0]['a'])) {
			$MARC['100'][0]['a'] = 'Praca zbiorowa';
		}
		
		$MARC['260'][0]['b'] = str_replace(
			array(
				'Wydaw.',
				'Państ.',
				'Państw.',
				'PK',
				'Min.',
			),
			array(
				'Wydawnictwo',
				'Państwowy',
				'Państwowe',
				'Politechnika Krakowska',
				'Ministerstwa',
			),
		$MARC['260'][0]['b']);
		
		if($MARC['041'][0]['a'] == 'pol') {
			$MARC['041'][0]['a'] = 'polski';
		}
		
		
		if(empty($MARC['041'][0]['a'])) {
			$MARC['041'][0]['a'] = 'polski';
		}
		
		if(!empty($MARC['245'][0]['b'])) {
			$MARC['245'][0]['a'] = trim($MARC['245'][0]['a'], '().,\\/"\' ').'. '.ucfirst(trim($MARC['245'][0]['b'], '().,\\/"\' '));
		}
		
		return array(
			'tytul' => trim($MARC['245'][0]['a'], '().,\\/"\' '),
			'autor' => trim($MARC['100'][0]['a'], '().,\\/"\' '),
			'rok' => trim($MARC['260'][0]['c'], '[]().,:\\/"\' '),
			'miejsce' => trim($MARC['260'][0]['a'], '[]().,:\\/"\' '),
			'wydawnictwo' => trim($MARC['260'][0]['b'], '[]().,:\\/"\' '),
			'wydanie' => trim($MARC['250'][0]['a'], '[]().,:\\/"\' '),
			'jezyk' => $MARC['041'][0]['a'],
			'ISBN' => $ISBN,
			'ISSN' => $ISSN,
			/* stan */
		);
	}
	
	static function to_database($kod, $MARC) {
		if(!$MARC['020']) {
			$MARC['020'] = array();
		}
		foreach($MARC['020'] as $value) {
			$value = (int)$value['a'];
			if( strlen($value) > strlen($ISBN) ) {
				$ISBN = $value;
			}
		}
		
		if(!$MARC['022']) {
			$MARC['022'] = array();
		}
		foreach($MARC['022'] as $value) {
			$value = (int)$value['a'];
			if( strlen($value) > strlen($ISSN) ) {
				$ISSN = $value;
			}
		}
		
		db2::add('ksiazki', array(
			'id' => $kod,
			'tytul' => $MARC['245'][0]['a'],
			'autor' => $MARC['100'][0]['a'],
			'rok' => $MARC['260'][0]['c'],
			'miejsce' => $MARC['260'][0]['a'],
			'wydawnictwo' => $MARC['260'][0]['b'],
			'wydanie' => $MARC['250'][0]['a'],
			'jezyk' => $MARC['041'][0]['a'],
			'ISBN' => $ISBN,
			'ISSN' => $ISSN,
			/* stan */
		));
	}
}
?>
