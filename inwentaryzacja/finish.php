<?php
$title = 'Inwentaryzacja - zakończenie';
include('design/top.php');

require('../includes/config.php');
require('../includes/PDOO.php');
$PDO = PDOO::Singleton();
?>

<h3>Krok 3 z 3: Zakończenie inwentaryzacji</h3> 

<p>Wykonano następujące operacje:</p>

<ul>
<?php
$st = $PDO->prepare('UPDATE ksiazki SET regal=?, polka=?, rzad=? WHERE id=?');
foreach($_POST['move'] as $id => $placed) {
	if(strlen($placed) == 0) continue;
	
	$place = explode('/', $placed, 3);
	if($place[2] == '') {
		$place[2] = NULL;
		if($place[1] == '') {
			$place[1] = NULL;
			if($place[0] == '') {
				$place[0] = NULL;
			}
		}
	}
	
	echo '<li>Zmiana miejsca '.$id.' na '.htmlspecialchars($placed).'</li>'."\n";
	$st->execute(array($place[0], $place[1], $place[2], $id));
}

$date = date('d.m.Y H:i');
$st = $PDO->prepare('UPDATE ksiazki SET wycofana=\'1\', powod=\'Inwentaryzacja '.$date.'\' WHERE id=?');

foreach($_POST['repulse'] as $id => $placed) {
	if(!$placed) continue;
	
	echo '<li>Wycofanie '.$id.'</li>'."\n";
	$st->execute(array($id));
}

echo '<li>Usunięcie listy książek do inwentaryzacji</li>'."\n";
unlink('list.xml');
?>
</ul>

<?php
include('design/bottom.php');
?>