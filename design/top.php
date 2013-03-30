<?php
if(!defined('TOP_SEND')) {
	header('Content-type: text/html; charset=utf-8');
	header('Pragma: no-cache');
	header('Cache-control: private, no-cache, must-revalidate');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<title>System biblioteczny - <?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="design/style.css" />
</head>
<body>

<h1> System biblioteczny </h1>

<ul id="menu">
<li><a href="index.php">Wyszukiwanie</a></li>
<li><a href="list_all.php">Pełna lista książek</a></li>
</ul>

<h2> <?php echo $title; ?> </h2>

<?php
	define('TOP_SEND', TRUE);
}
?>