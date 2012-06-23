<?php
$title = 'Etykiety';
include('./design/top.php');
?>

<table class="main">
<tr>
<td>

<form action="generate_page.php" method="post" onsubmit="return ffalse('step1')">
<p>Kody od numeru: <input type="text" name="from" id="step11" required="required" /></p>

<p>Zaznacz brakujące etykiety (<a href="javascript:uc_all('no_a', true)">wszystkie</a> <a href="javascript:uc_all('no_a', false)">żadna</a>):</p>
<table class="no" id="no_a">
<tr>
<?php
for($i=0; $i<11; $i++) {
	for($j=0; $j<4; $j++) {
		echo '	<td><input type="checkbox" name="no_'.$i.'_'.$j.'" /></td>'."\n";
	}
	if($i<10) {
		echo '
</tr>
<tr>
';
	}
}
?>
</tr>
</table>
<p><input type="submit" value="Utwórz" /></p>
</form>

</td>
<td>

<form action="generate_list.php" method="post" onsubmit="return ffalse('step2')">
<p>Lista potrzebnych kodów:<br />
<textarea name="kody" id="step21" cols="18" rows="4" required="required"></textarea></p>

<p>Zaznacz brakujące etykiety (<a href="javascript:uc_all('no_b', true)">wszystkie</a> <a href="javascript:uc_all('no_b', false)">żadna</a>):</p>
<table class="no" id="no_b">
<tr>
<?php
for($i=0; $i<11; $i++) {
	for($j=0; $j<4; $j++) {
		echo '	<td><input type="checkbox" name="no_'.$i.'_'.$j.'" /></td>'."\n";
	}
	if($i<10) {
		echo '
</tr>
<tr>
';
	}
}
?>
</tr>
</table>

<p><input type="submit" value="Utwórz" /></p>
</form>

</td>
<td>

<form action="generate39_list.php" method="post" onsubmit="return ffalse('step3')">
<p>Lista potrzebnych identyfikatorów: <br />
Format: REGAŁ/<i>PÓŁKA</i>/<i>RZĄD</i> <br />
<textarea name="kody" id="step31" cols="14" rows="3" required="required"></textarea></p>

<p>Zaznacz brakujące etykiety (<a href="javascript:uc_all('no_c', true)">wszystkie</a> <a href="javascript:uc_all('no_c', false)">żadna</a>):</p>
<table class="no" id="no_c">
<tr>
<?php
for($i=0; $i<11; $i++) {
	for($j=0; $j<4; $j++) {
		echo '	<td><input type="checkbox" name="no_'.$i.'_'.$j.'" /></td>'."\n";
	}
	if($i<10) {
		echo '
</tr>
<tr>
';
	}
}
?>
</tr>
</table>


<p><input type="submit" value="Utwórz" /></p>
</form>

</td>
</tr>
</table>

<?php
include('./design/bottom.php');
?>
