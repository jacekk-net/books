<?php
$title = 'Wyszukiwanie i wypożyczanie';
include('design/top.php');
?>

<table class="main">
<tr>
<td>

<form action="search.php" method="get" onsubmit="return ffalse('step1')">
<p>KOD/IS*N: <input type="text" name="id" id="step11" required="required" /><br />
Półka, rząd: <input type="text" name="polka" size="3" maxlength="3" />
/ <input type="text" name="rzad" size="3" maxlength="3" /></p>
<p><input type="submit" value="Znajdź" /></p>

<p><i>Przy ręcznym wpisywaniu ośmiocyfrowego <br /> numeru ISSN, poprzedź go zerem</i></p>
</form>

<script type="text/javascript">
document.getElementById('step11').focus();
</script>

<hr />

<a href="list_all.php">Pełna lista książek</a> <br />
<a href="list_borrowed.php">Wypożyczone</a> <br />
<a href="list_repulsed.php">Wycofane</a>

</td>
<td>

<form action="search.php" method="get">
<p>Tytuł: <input type="text" name="tytul" /><br />
Autor: <input type="text" name="autor" /><br />
Wydanie: <input type="text" name="wydanie" /></p>
<p>Miejsce: <input type="text" name="miejsce" /><br />
Rok: <input type="text" name="rok" /><br />
Wydawnictwo: <input type="text" name="wydawnictwo" /></p>
<p><input type="submit" value="Szukaj" /></p>
</form>

</td>
</tr>
</table>

<?php
include('design/bottom.php');
?>
