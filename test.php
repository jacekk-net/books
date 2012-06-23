<?php
include('./includes/std.php');

$title = 'Test systemu';
include('./design/top.php');

define('OK', '<b style="color:green">OK</b>');
define('NT', '<b style="color:blue">NIE SPRAWDZANE</b>');
define('FAIL', '<b style="color:red">BRAK</b>');
define('SFAIL', '<b style="color:lightcoral">BRAK<br />NIE WYMAGANE</b>');
?>

<style type="text/css">
th, td {border: 1px solid black; padding: 5px;}
th {text-align: left;}
th.head {text-align: center; background: lightblue;}
</style>

<table>


<tr> <th class="head" colspan="2">PHP</th> </tr>
<tr> <th>PHP 5.2 lub nowsze</th> <td><?php
if(version_compare(phpversion(), '5.2') >= 0) echo OK;
else echo FAIL;
?></td> </tr>
<tr> <th>magic_quotes_gpc = Off</th> <td><?php
if(get_magic_quotes_gpc()) echo FAIL;
else echo OK;
?></td> </tr>


<tr> <th class="head" colspan="2">Baza danych</th> </tr>
<tr> <th>Ustawienia bazy<br />(/includes/db2.php)</th> <td><?php echo NT; ?></td> </tr>
<tr> <th>Rozszerzenie MySQL</th> <td><?php
if(!extension_loaded('mysql')) {
	echo FAIL;
	$mysql = FALSE;
}
else echo OK;
?></td> </tr>
<tr> <th>Tabela książek</th> <td><?php
$num = db2::num('ksiazki', '*', NULL);
if($mysql===FALSE) echo NT;
elseif($num===FALSE) echo FAIL;
else echo OK; ?></td> </tr>
<tr> <th>Tabela wypożyczeń</th> <td><?php
$num = db2::num('pozycz', '*', NULL);
if($mysql===FALSE) echo NT;
elseif($num===FALSE) echo FAIL;
else echo OK; ?></td> </tr>


<tr> <th class="head" colspan="2">Zewnętrzne bazy danych</th> </tr>
<tr> <th>Rozszerzenie YAZ</th> <td><?php
if(!extension_loaded('yaz')) {
	echo SFAIL;
	$yaz = FALSE;
}
else
	echo OK;
?></td> </tr>
<tr> <th>Próba pobrania</th> <td><?php
if($yaz===FALSE)
	echo NT;
else {
	$ibd = new ibd_BN;
	if($ibd->szukaj_ISBN('9788301121365'))
		echo OK;
	else
		echo SFAIL;
}
?></td> </tr>


<tr> <th class="head" colspan="2"> Okładki LibraryThing </th> </tr>
<tr> <th>Rozszerzenie cURL</th> <td><?php
if(!extension_loaded('curl')) {
	echo SFAIL;
	$curl = FALSE;
} else echo OK; ?></td> </tr>
<tr> <th>LibraryThing API key<br />(/includes/ksiazki.php)</th> <td><?php
if($curl === FALSE)
	echo NT;
elseif(!empty(ksiazki::$LT_API))
	echo OK;
else
	echo FAIL;
?></td> </tr>
<tr> <th>Uprawnienia dla katalogu<br />
/covers</th> <td><?php
if($curl === FALSE) echo NT;
elseif(is_readable('./covers') && is_writable('./covers')) echo OK;
else echo FAIL;
?></td> </tr>
<tr> <th>Uprawnienia dla katalogu<br />
/covers_big</th> <td><?php
if($curl === FALSE) echo NT;
elseif(is_readable('./covers_big') && is_writable('./covers_big')) echo OK;
else echo FAIL;
?></td> </tr>


<tr> <th class="head" colspan="2"> Okładki własne </th> </tr>
<tr> <th>file_uploads = On</th> <td><?php
if(strtolower(ini_get('file_uploads')) == 'on' || ini_get('file_uploads') == 1) {
	echo OK;
}
else
{
	echo FAIL;
	$upload = FALSE;
}
?></td> </tr>
<tr> <th>Rozszerzenie GD2</th> <td><?php
if($upload === FALSE) echo NT;
elseif(!extension_loaded('gd')) {
	echo SFAIL;
	$gd = FALSE;
}
else echo OK;
?></td> </tr>
<tr> <th>Uprawnienia dla katalogu<br />
/covers/own</th> <td><?php
if($gd === FALSE || $upload === FALSE) echo NT;
elseif(is_readable('./covers/own') && is_writable('./covers/own')) echo OK;
else echo FAIL;
?></td> </tr>
<tr> <th>Uprawnienia dla katalogu<br />
/covers_big/own</th> <td><?php
if($gd === FALSE || $upload === FALSE) echo NT;
elseif(is_readable('./covers_big/own') && is_writable('./covers_big/own')) echo OK;
else echo FAIL;
?></td> </tr>
</table>

<?
include('./design/bottom.php');
?>