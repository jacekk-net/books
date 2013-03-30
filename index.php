<?php
$title = 'Wyszukiwanie i wypożyczanie';
include('design/top.php');
?>

<table class="main">
<tr>
<td>

<form action="search.php" method="get" onsubmit="return ffalse('step1')">
<p>KOD/IS*N: <input type="text" name="id" id="step11" required="required" /></p>
<p><input type="submit" value="Znajdź" /></p>

<p><i>Przy ręcznym wpisywaniu ośmiocyfrowego <br /> numeru ISSN, poprzedź go zerem</i></p>
</form>

<script type="text/javascript">
document.getElementById('step11').focus();
</script>

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
