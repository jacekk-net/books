<?php
class ksiazki_cache {
	static $cache = array();
	
	static function cache_get($kod) {
		$kod = (int)$kod;
		
		if(!isset(self::$cache[(int)$kod])) {
			self::cache_update($kod);
		}
		
		return self::$cache[(int)$kod];
	}
	
	static function cache_add($kod, &$dane) {
		if($dane['od2']) {
			$dane['od'] = $dane['od2'];
			unset($dane['od2']);
		}
		self::$cache[(int)$kod] = $dane;
	}
	
	static function cache_addarray(&$dane) {
		foreach($dane as &$r) {
			self::cache_add($r['id'], $r);
		}
	}
	
	static function cache_clear($id=NULL) {
		if(is_null($id)) {
			self::$cache = array();
		}
		else
		{
			unset(self::$cache[(int)$id]);
		}
	}
	
	static function cache_update($kod) {
		$dane = db2::escape_data(sql::fetchone(sql::query('SELECT *, (SELECT MAX(`od`) FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id`) as `od2`, (SELECT `do` FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id` AND `od`=`od2`) as `do`, (SELECT `kto` FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id` AND `od`=`od2`) as `kto` FROM `ksiazki` WHERE `id`=\''.sql::escape($kod).'\'')));
		self::cache_add($kod, $dane);
	}
}

class ksiazki extends ksiazki_cache {
	static $LT_API = '';
	
	static function okladka($KOD, $ISBN) {
		return okladki::znajdz($KOD, $ISBN, 'covers');
	}
	
	static function okladka_big($KOD, $ISBN) {
		return okladki::znajdz($KOD, $ISBN, 'covers_big');
	}
	
	static function dodaj(&$dane) {
		validate::KOD($dane['id'], FALSE);
		
		if($dane['ISBN']) {
			$t = validate::type($dane['ISBN']);
			if($t!='ISBN') {
				error::add('W polu ISBN znajduje się '.$t);
			}
		}
		if($dane['ISSN']) {
			$t = validate::type($dane['ISSN']);
			if($t!='ISSN') {
				error::add('W polu ISSN znajduje się '.$t);
			}
		}
		
		if($dane['jezyk']=='pol') {
			$dane['jezyk'] = 'polski';
		}
		
		unset($_POST['okladka']);
		
		okladki::upload($_FILES['okladka'], $dane['id'], $dane['ISBN']);
		
		db2::add('ksiazki', $dane);
		self::cache_update($dane['id']);
	}
	
	static function exists($kod) {
		$info = self::cache_get($kod);
		if(isset($info['id'])) {
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	static function edytuj(&$dane) {
		validate::KOD($dane['id']);
		$kod = $dane['id'];
		
		$old = self::szukaj_KOD($kod);
		
		if($dane['id']=='' OR empty($dane['autor']) OR empty($dane['tytul']) OR empty($dane['jezyk'])) {
			error::add('Brak wymaganych danych o książce (kod, autor, tytuł, język)');
		}
		
		if($dane['nid']!='') {
			validate::KOD($dane['nid']);
			$dane['id'] = $dane['nid'];
		}
		
		unset($dane['nid']);
		
		if($dane['ISBN']) {
			$t = validate::type($dane['ISBN']);
			if($t!='ISBN') {
				error::add('W polu ISBN znajduje się '.$t);
			}
		}
		if($dane['ISSN']) {
			$t = validate::type($dane['ISSN']);
			if($t!='ISSN') {
				error::add('W polu ISSN znajduje się '.$t);
			}
		}
		
		if(!$dane['wycofana']) {
			$dane['wycofana'] = 0;
			$dane['powod'] = NULL;
		}
		
		okladki::przenies($old['id'], $old['ISBN'], $dane['id'], $dane['ISBN']);
		
		unset($_POST['okladka']);
		
		// Nowa okładka
		if(isset($_POST['okladka_del']) || (isset($_FILES['okladka']) && is_uploaded_file($_FILES['okladka']['tmp_name']))) {
			okladki::usun($dane['id'], $dane['ISBN']);
			unset($_POST['okladka_del']);
		}
		
		okladki::upload($_FILES['okladka'], $dane['id'], $dane['ISBN']);
		
		db2::edit('ksiazki', $dane, array('id' => $kod));
		self::cache_update($kod);
		if($dane['id']!=$kod) {
			self::cache_update($dane['id']);
		}
	}
	
	static function miejsce($regal, $polka, $rzad, $where) {
		db2::edit('ksiazki', array('regal' => strtoupper($regal), 'polka' => $polka, 'rzad' => $rzad), $where);
		self::cache_clear();
	}
	
	static function usun(&$kod) {
		validate::KOD($kod);
		
		$dane = self::szukaj_KOD($kod);
		
		okladki::usun($dane['id'], $dane['ISBN']);
		
		db2::del('ksiazki', array('id' => $kod));
		self::cache_clear($kod);
	}
	
	static function szukaj_KOD($kod) {
		validate::KOD($kod, TRUE);
		
		return self::cache_get($kod);
	}
	
	static function szukaj_ISBN($ISBN) {
		validate::EAN($ISBN);
		
		return db2::get('ksiazki', '*', array('ISBN' => $ISBN), NULL, 10);
	}
	
	static function szukaj_ISSN($ISSN) {
		validate::EAN($ISSN);
		
		return db2::get('ksiazki', '*', array('ISSN' => $ISSN), NULL, 10);
	}
	
	static function szukaj_info($dane, $order=NULL, $start=NULL, $limit=30) {
		$allow = array('id', 'tytul', 'autor', 'wydawnictwo', 'miejsce', 'rok', 'wydanie', 'wycofana');
		$replace = array('tytul' => 'tytul~~', 'autor' => 'autor~~', 'wydawnictwo' => 'wydawnictwo~~');
		
		$where = array();
		
		foreach($dane as $key => $value) {
			if(!in_array($key, $allow) OR $value==='') {
				continue;
			}
			
			if($replace[$key]) {
				$key = $replace[$key];
			}
			
			$where[$key] = $value;
		}
		
		if($where['id']) {
			validate::$kod = TRUE;
			switch(validate::type($where['id'])) {
				case 'ISBN':
					$where['ISBN'] = $where['id'];
					unset($where['id']);
				break;
				case 'ISSN':
					$where['ISSN'] = $where['id'];
					unset($where['id']);
				break;
				case 'MSC':
					$where['regal'] = $where['id'];
					if($dane['polka']) {
						$where['polka'] = $dane['polka'];
					}
					if($dane['rzad']) {
						$where['rzad'] = $dane['rzad'];
					}
					unset($where['id']);
				break;
			}
			validate::$kod = FALSE;
		}
		
		if(!$where['regal']) {
			unset($where['polka']);
			unset($where['rzad']);
		}
		
		if($where['id']) {
			$ret[] = self::szukaj_KOD($where['id']);
			$num = count($ret);
		}
		else
		{
			if($dane['do']) {
				$num = db2::num('pozycz', 'id', array('do' => NULL));
				if($num==0) {
					$ret = array();
				}
				else
				{
					$ret = db2::get(array('pozycz', array('J', 'ksiazki', 'USING', 'id')), '*', array('do' => NULL), $order, $start, $limit);
				}
			}
			else
			{
				$num = db2::num('ksiazki', 'id', $where);
				if($num==0) {
					$ret = array();
				}
				else
				{
					$where = db2::__combine_where($where, TRUE);
					$ret = db2::escape_data(sql::fetch(sql::query('SELECT *, (SELECT MAX(`od`) FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id`) as `od2`, (SELECT `do` FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id` AND `od`=`od2`) as `do`, (SELECT `kto` FROM `pozycz` WHERE `pozycz`.`id`=`ksiazki`.`id` AND `od`=`od2`) as `kto`'.(db2::revelance() ? ', '.db2::$revelance : '').' FROM `ksiazki`'.$where.db2::__combine_order($order, TRUE).db2::__combine_limit($start, $limit))));
				}
			}
			
			self::cache_addarray($ret);
		}
		
		return array($num, $ret, db2::revelance());
	}
}
?>
