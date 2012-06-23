<?php
include('./includes/std.php');

pozycz::wypozyczenie($_POST['kod'], $_POST['kto']);

$title = 'Wypożyczanie książki';
include('./design/top.php');

gotowe::informacje($_POST['kod']);

echo '<form action="search.php" method="get" onsubmit="return ffalse(\'step2\')">
<p>KOD/IS*N: <input type="text" name="id" id="step21" required="required" /></p>
<p><input type="submit" value="Znajdź" /></p>

<script type="text/javascript">
document.getElementById(\'step21\').focus();
</script>
</form>';

include('./design/bottom.php');
?>
