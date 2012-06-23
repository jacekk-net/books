<?php
$title = 'Położenie książki';
include('design/top.php');
?>

<table class="main">
<tr>
<td>

<h3>Ustawianie</h3>

<form action="place_set.php" method="post" onsubmit="return ffalse('step1')">
<p>Miejsce (regał/półka/rząd): <br />
<input type="text" name="regal" id="step11" size="5" maxlength="5" required="required" />
/ <input type="text" name="polka" id="step12" class="focus" size="3" maxlength="3" />
/ <input type="text" name="rzad" id="step13" class="focus" size="3" maxlength="3" /></p>
<p>KODY książek: <br /> <textarea name="kody" id="step14" cols="18" rows="8" required="required"></textarea></p>
<p><input type="submit" value="Zastosuj" /></p>
</form>

<script type="text/javascript">
document.getElementById('step11').focus();
</script>

</td>
<td>

<h3>Zmiana</h3>

<form action="place_change.php" method="post" onsubmit="return ffalse('step2')">
<p>Miejsce (regał/półka/rząd): <br />
<input type="text" name="regal" id="step21" size="5" maxlength="5" required="required" />
/ <input type="text" name="polka" id="step22" class="focus" size="3" maxlength="3" />
/ <input type="text" name="rzad" id="step23" class="focus" size="3" maxlength="3" /></p>
<p>Nowe miejsce (zostaw puste, aby usunąć): <br />
<input type="text" name="regal2" id="step24" class="focus" size="5" maxlength="5"  />
/ <input type="text" name="polka2" id="step25" class="focus" size="3" maxlength="3" />
/ <input type="text" name="rzad2" id="step26" class="focus" size="3" maxlength="3" /></p>
<p><input type="submit" value="Zastosuj" /></p>
</form>

</td>
</tr>

<tr><td colspan="2"><p><i>Można pomijać argumenty półka i rząd, jeśli jest taka potrzeba.</i></p></td></tr>
</table>

<?php
include('design/bottom.php');
?>
