<?php
$title = 'Dodawanie książki';
include('./design/top.php');
?>

<h3> Krok 1 - podaj podstawowe informacje o egzemplarzu </h3>

<table class="main">
<tr>
<th> Wyszukiwanie </th>
<th> Dodawanie wg. podanych informacji </th>
</tr>

<tr>
<td>

<form action="add_isbn.php" method="post" onsubmit="return ffalse('step1')">
<p>KOD: <input type="text" name="kod" id="step11" required="required" accesskey="1" /><br />
IS*N: <input type="text" name="isn" id="step12" required="required" /></p>
<p><input type="submit" value="Znajdź i zapisz" /></p>
</form>

<script type="text/javascript">
document.getElementById('step11').focus();
</script>

</td>
<td>

<form action="add_file.php" method="post" enctype="multipart/form-data" onsubmit="return ffalse('step3')">
<p>KOD: <input type="text" name="kod" id="step31" required="required" accesskey="3" /><br />
Plik MARC21: <input type="file" name="marc" id="step32" required="required" /></p>
<p><input type="submit" value="Wyślij i zapisz" /></p>
</form>

</td>
</tr>
<tr>
<td>

<form action="add_search.php" method="post" onsubmit="return ffalse('step2')">
<p>KOD: <input type="text" name="kod" id="step21" required="required" accesskey="2" /><br />
Tytuł: <input type="text" name="tytul" id="step22" required="required" /><br />
Autor: <input type="text" name="autor" /><br />
Wydawnictwo: <input type="text" name="wydawnictwo" /></p>
<p><input type="submit" value="Znajdź i zapisz" /></p>
</form>

</td>
<td rowspan="2">

<form action="add_book.php" method="post" onsubmit="return ffalse('step4')" enctype="multipart/form-data">
<p>KOD: <input type="text" name="id" id="step41" required="required" accesskey="4" /><br />
Tytuł: <input type="text" name="tytul" id="step42" required="required" /><br />
Autor: <input type="text" name="autor" id="step43" required="required" /><br />
Język: <input type="text" name="jezyk" id="step44" required="required" /><br />
Wydanie: <input type="text" name="wydanie" /></p>
<p>Miejsce: <input type="text" name="miejsce" /><br />
Rok: <input type="text" name="rok" /><br />
Wydawnictwo: <input type="text" name="wydawnictwo" /></p>
<p>ISBN: <input type="text" name="ISBN" /><br />
ISSN: <input type="text" name="ISSN" /></p>
<p>Okładka: <input type="file" name="okladka" /></p>
<p><input type="submit" value="Zapisz" /></p>
</form>

</td>
</tr>
<tr>
<td>

<form action="add_similar.php" method="post" onsubmit="return ffalse('step5')">
<p>KOD: <input type="text" name="kod" id="step51" required="required" accesskey="5" /><br />
KOD podobnej książki: <input type="text" name="kod2" id="step52" required="required" /></p>
<p><input type="submit" name="Znajdź i zapisz" value="Zapisz" /></p>
</form>

</td>
</tr>
</table>

<?php
include('./design/bottom.php');
?>
