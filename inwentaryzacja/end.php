<?php
$title = 'Inwentaryzacja - rozpoczęcie';
include('design/top.php');
?>

<h3>Krok 3 z 3: Zakończenie inwentaryzacji</h3>

<?php
if(!is_file('list.xml')) {
	echo '<p>Wystąpił błąd: plik z danymi nie istnieje!</p>';
	
	include('design/bottom.php');
	die();
}
?>

<p>Poniżej znajduje się lista zmian do wykonania. Aby zatrzymać dane zmiany należy odznaczyć odpowiednie pole wyboru.</p>

<form action="finish.php">
<table class="width">
<tr> <th>Okł.</th> <th> <b>Kod</b> <br /> Wyd. </th> <th> Autor <br /> <b>Tytuł</b> </th> <th> Miejsce, rok <br /> Wydawnictwo </th> <th> Akcje </th> </tr>
<?php
$doc = new DOMDocument;
if(!$doc->load('list.xml')) {
	echo '<p>Wystąpił błąd: plik z danymi nie jest poprawnym arkuszem XML!</p>';
	
	include('design/bottom.php');
	die();
}

foreach($doc->documentElement->childNodes as $loc) {
	if(!($loc instanceof DOMElement) || $loc->tagName != 'lokalizacja') {
		continue;
	}
	
	foreach($loc->childNodes as $node) {
		if(!($node instanceof DOMElement) || $node->tagName != 'ksiazka') {
			continue;
		}
		if($node->hasAttribute('status') && $node->getAttribute('status') == 'ok') {
			continue;
		}
		
		$dane = array(
			'id' => substr($node->getAttribute('id'), 1),
			'status' => $node->getAttribute('status'),
		);
		
		foreach($node->childNodes as $attr) {
			if($node instanceof DOMElement) {
				$dane[$attr->tagName] = htmlspecialchars($attr->textContent);
			}
		}
		
		echo '<tr> <td></td> <td> <b>'.$dane['id'].'</b> <br /> </td> <td>'.$dane['autor'].' <br /> <b>'.$dane['tytul'].'</b></td> <td>'.$dane['miejsce'].' '.$dane['rok'].' <br /> '.$dane['wydawnictwo'].'</td> <td></td> </tr>'."\n";
	}
}
?>
</table>

<p><input type="submit" value="Wykonaj wybrane operacje" /></p>
</form>

<?php
include('design/bottom.php');
?>