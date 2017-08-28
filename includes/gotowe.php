<?php
class gotowe {
	static $pola = array('autor', 'tytul', 'wydanie', 'miejsce', 'rok', 'wydawnictwo', 'jezyk', 'ISBN', 'ISSN');
	static $nastrone = 25;
	static $add = '';
	static $default = FALSE;
	
	static function sort($by=NULL, $strona=NULL) {
		if($strona === NULL) {
			$strona = 0;
		}
		
		if($by === NULL) {
			if(!self::$default) {
				$by = $_GET['sort'];
			}
			
			$ord = $_GET['ord'];
		}
		elseif($by == 'default' && self::$default) {
			$ord = self::invert_sort($_GET['ord']);
		}
		elseif($_GET['sort'] == $by && $_GET['ord'] == 'asc') {
			$ord = 'desc';
		}
		else
		{
			$ord = 'asc';
		}
		
		return $_SERVER['PHP_SELF'].'?'.self::$add.'strona='.$strona.'&amp;sort='.$by.'&amp;ord='.$ord;
	}
	
	static function add($what, $size=50) {
		if($_GET[$what]!='') {
			self::$add .= $what.'='.urlencode(substr($_GET[$what], 0, $size)).'&';
		}
	}
	
	static function invert_sort($ord) {
		$ord = strtolower($ord);
		if($ord != 'asc') {
			$ord = 'asc';
		}
		else
		{
			$ord = 'desc';
		}
		return $ord;
	}
	
	static function informacje($kod, $dane=NULL) {
		if(is_null($dane)) {
			$dane = ksiazki::szukaj_KOD($kod);
		}
		
		if($dane['wycofana']) {
			$class = 'wyc';
			$info = '<p>Książka wycofana</p>';
		}
		else
		{
			if(!pozycz::pozyczona($dane['id'])) {
				$class = 'norm';
				$info = '

<p>Książka w dostępna</p>

';
			}
			else
			{
				$class = 'poz';
				$info = '

<p>Książka wypożyczona</p>

';
			}
		}
		
		// Okładka
		$cover = ksiazki::okladka($dane['id'], $dane['ISBN']);
		
		echo '<div class="'.$class.'" id="book">
'.($cover ? '<a href="cover.php?KOD='.$dane['id'].'&amp;ISBN='.$dane['ISBN'].'"><img src="'.$cover.'" alt="Okładka" /></a>
' : '').'<h4>'.$dane['tytul'].'</h4>
<h5>'.$dane['autor'].($dane['regal'] ? ' <span>('.$dane['regal'].($dane['polka'] ? '/'.$dane['polka'] : '').($dane['rzad'] ? '/'.$dane['rzad'] : '').')</span>' : '').'</h5>
'.($dane['wydanie'] ? '<p>Wydanie '.$dane['wydanie'].'</p>' : '').'
<p>'.($dane['wydawnictwo'] ? $dane['wydawnictwo'].'<br />
' : '').$dane['miejsce'].' '.$dane['rok'].($dane['ISBN'] ? '<br />
ISBN-13: '.$dane['ISBN'] : '').(substr($dane['ISBN'], 0, 3) == '978' ? '<br />
ISBN-10: '.convert::ISBN13_to_ISBN10($dane['ISBN']) : '').($dane['ISSN'] ? '<br />
ISSN-13: '.$dane['ISSN'].'<br />
ISSN-10: '.convert::ISSN13_to_ISSN8($dane['ISSN']) : '').'</p>
'.$info.'
</div>';
	}
	
	static function lista() {
		if(!in_array($_GET['sort'], array('id', 'autor', 'tytul', 'miejsce', 'rok', 'wydawnictwo'))) {
			$_GET['sort'] = 'tytul';
			self::$default = TRUE;
		}
		if($_GET['ord'] != 'desc') {
			$_GET['ord'] = 'asc';
		}
		if(!ctype_digit($_GET['strona'])) {
			$_GET['strona'] = 0;
		}
		
		if($_GET['id']) {
			self::add('id', 13);
		}
		else
		{
			self::add('tytul');
			self::add('autor');
			self::add('wydanie', 25);
			self::add('miejsce');
			self::add('rok', 4);
			self::add('wydawnictwo');
		}
		
		if(self::$default) {
			$sort = array('revelance' => self::invert_sort($_GET['ord']), $_GET['sort'] => $_GET['ord']);
		}
		else
		{
			$sort = array($_GET['sort'] => $_GET['ord']);
		}
		
		list($num, $ksiazki, $revelance) = ksiazki::szukaj_info($_GET, $sort, $_GET['strona']*self::$nastrone, self::$nastrone);
		
		if($num==0) {
			errorclass::add('Brak książek spełniających podane kryteria');
		}
		elseif($num==1 AND !$revelance) {
			self::informacje(NULL, $ksiazki[0]);
			return TRUE;
		}
		
		echo '<table class="width">
<tr> <th>Okł.</th> <th> <b><a href="'.self::sort('id').'">Kod</a></b> <br /> Wyd. </th> <th> <a href="'.self::sort('autor').'">Autor</a> <br /> <b><a href="'.self::sort('tytul').'">Tytuł</a></b> </th> <th> <a href="'.self::sort('miejsce').'">Miejsce</a>, <a href="'.self::sort('rok').'">rok</a> <br /> <a href="'.self::sort('wydawnictwo').'">Wydawnictwo</a> </th>'.($revelance ? ' <th><a href="'.self::sort('default').'">Trafność</a></th>' : '').' <th> Wypożyczenie <br /> Opcje </th> </tr>
';
		
		foreach($ksiazki as $ksiazka) {
			if($ksiazka['wycofana']) {
				$info = 'Książka wycofana';
				$class = 'wyc';
			}
			else
			{
				if($ksiazka['do']!==NULL OR $ksiazka['od']===NULL) {
					$class = 'norm';
					$info = 'Książka w bibliotece';
				}
				else
				{
					$class = 'poz';
					$info = 'Książka wypożyczona';
					$pozycz = TRUE;
				}
			}
			
			$cover = ksiazki::okladka($ksiazka['id'], $ksiazka['ISBN']);
			
			echo '<tr'.($class ? ' class="'.$class.'"' : '').'>
	<td>
 		'.($cover ? '<a href="cover.php?KOD='.$ksiazka['id'].'&amp;ISBN='.$ksiazka['ISBN'].'"><img src="'.$cover.'" alt="Okładka" /></a>' : '').'
	</td>
	<td>
		<b>'.$ksiazka['id'].'</b> <br />
		'.($ksiazka['wydanie'] ? 'W. '.$ksiazka['wydanie'] : '').'
	</td>
	<td>
		'.$ksiazka['autor'].
			($ksiazka['regal'] ? ' <span>('.$ksiazka['regal'].
			($ksiazka['polka'] ? '/'.$ksiazka['polka'] : '').
			($ksiazka['rzad'] ? '/'.$ksiazka['rzad'] : '').
			')</span>' : '').' <br />
		<b>'.$ksiazka['tytul'].'</b> </td>
	<td>
		'.$ksiazka['miejsce'].' '.$ksiazka['rok'].' <br />
		'.$ksiazka['wydawnictwo'].' 
	</td>'.($revelance ? '
	<td>'.min(100, (int)($ksiazka['revelance']*10)).'% </td>' : '').'
	<td class="n">
		'.$info.' <br />
		<a href="info.php?kod='.$ksiazka['id'].'">Więcej...</a>
	</td>
</tr>
';
		}
		
		echo '</table>

';
		self::strony($num);
	}
	
	static function strony($elementow) {
		$stron = ceil($elementow / self::$nastrone) - 1;
		
		echo '<p class="paginator"> ';
		for($strona=0; $strona<=$stron; $strona++) {
			if($strona == $_GET['strona']) {
				echo '<b>[ '.($strona+1).' ]</b> ';
			}
			else
			{
				echo '<a href="'.self::sort(NULL, $strona).'">[ '.($strona+1).' ]</a> ';
			}
		}
		
		echo '</p>';
	}
}
?>