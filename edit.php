<?php
include('./includes/std.php');

$ksiazka = ksiazki::szukaj_KOD($_GET['kod']);

$cover = ksiazki::okladka($ksiazka['id'], $ksiazka['ISBN']);

$title = 'Edytowanie książki';
include('./design/top.php');
?>

<table cellspacing="10" class="main">
<tr>
<td>

<form action="edit_book.php" method="post" onsubmit="return ffalse('step4')" enctype="multipart/form-data">
<p>KOD: <input type="text" name="id" id="step41" value="<?php echo $ksiazka['id']; ?>" readonly="readonly" required="required" /><br />
Nowy KOD: <input type="text" name="nid" /><br />
Tytuł: <input type="text" name="tytul" id="step42" value="<?php echo $ksiazka['tytul']; ?>" required="required" /><br />
Autor: <input type="text" name="autor" id="step43" value="<?php echo $ksiazka['autor']; ?>" required="required" /><br />
Język: <input type="text" name="jezyk" id="step44" value="<?php echo $ksiazka['jezyk']; ?>" required="required" /><br />
Wydanie: <input type="text" name="wydanie" value="<?php echo $ksiazka['wydanie']; ?>" /></p>
<p>Miejsce: <input type="text" name="miejsce" value="<?php echo $ksiazka['miejsce']; ?>" /><br />
Rok: <input type="text" name="rok" value="<?php echo $ksiazka['rok']; ?>" /><br />
Wydawnictwo: <input type="text" name="wydawnictwo" value="<?php echo $ksiazka['wydawnictwo']; ?>" /></p>
<p>ISBN: <input type="text" name="ISBN" value="<?php echo $ksiazka['ISBN']; ?>" /><br />
ISSN: <input type="text" name="ISSN" value="<?php echo $ksiazka['ISSN']; ?>" /></p>
<p>Miejsce (regał/półka/rząd): <br />
   <input type="text" name="regal" value="<?php echo $ksiazka['regal']; ?>" size="5" maxsize="5" />
 / <input type="text" name="polka" value="<?php echo $ksiazka['polka']; ?>" size="3" maxsize="3" />
 / <input type="text" name="rzad" value="<?php echo $ksiazka['rzad']; ?>" size="3" maxsize="3" /></p>
<p>Wycofana? <input type="checkbox" name="wycofana" value="1" <?php if($ksiazka['wycofana']) {echo 'checked="checked" ';} ?>/><br />
Powód: <input type="text" name="powod" value="<?php echo $ksiazka['powod']; ?>" /></p>

<p>Okładka: <br />
<?php echo ($cover ? '<a href="cover.php?KOD='.$ksiazka['id'].'&amp;ISBN='.$ksiazka['ISBN'].'"><img src="'.$cover.'" alt="Okładka" /></a> <br /> <label><input type="checkbox" name="okladka_del" value="1" /> Usuń</label>' : 'brak'); ?> <br />
<input type="file" name="okladka" /></p>
<p><input type="submit" value="Zapisz" /></p>
</form>

</td>
</tr>
</table>

<?php
include('./design/bottom.php');
?>
