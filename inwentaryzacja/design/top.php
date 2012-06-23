<?php
if(!defined('TOP_SEND')) {
	header('Content-type: text/html; charset=utf-8');
	header('Pragma: no-cache');
	header('Cache-control: private, no-cache, must-revalidate');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<title>System biblioteczny - Inwentaryzacja</title>
<link rel="stylesheet" type="text/css" href="design/style.css" />
</head>
<body>

<h1> System biblioteczny </h1>

<ul id="menu">
<li><a href="../index.php">Wypożyczanie/wyszukiwanie</a></li>

<li> </li>

<li><a href="../add.php">Dodaj książkę</a></li>
<li><a href="../place.php">Ustaw położenie</a></li>

<li> </li>

<li><a href="../generate.php">Etykiety</a></li>
</ul>

<h2> Inwentaryzacja </h2>

<?php
	define('TOP_SEND', TRUE);
}
?>