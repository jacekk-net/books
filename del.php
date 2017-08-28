<?php
include('./includes/std.php');

validate::KOD($_GET['kod']);

$ksiazka = ksiazki::szukaj_KOD($_GET['kod']);

if(empty($ksiazka)) {
	errorclass::add('Wybrana książka nie istnieje');
}

$title = 'Usuwanie książki';
include('./design/top.php');
?>

<h3>Czy na pewno chcesz usunąć poniższą książkę?</h3>

<form action="del_book.php" method="post" onsubmit="return ffalse('step4')">
<p>KOD: <input type="text" name="kod" id="step41" value="<?php echo $_GET['kod']; ?>" readonly="readonly" required="required" /><br />
Tytuł: <?php echo $ksiazka['tytul']; ?><br />
Autor: <?php echo $ksiazka['autor']; ?><br />
Wydanie: <?php echo $ksiazka['wydanie']; ?></p>

<p><?php echo $ksiazka['wydawnictwo']; ?></p>
<p><input type="submit" value="Usuń" /></p>
</form>

<?php
include('./design/bottom.php');
?>
