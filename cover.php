<?php
include('./includes/std.php');

header('Content-type: text/html; charset=utf-8');

$cover = ksiazki::okladka_big($_GET['KOD'], $_GET['ISBN']);

if($cover) {
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Okładka</title>
<script type="text/javascript">
function fit() {
	window_width = (window.innerWidth) ? window.innerWidth : document.body.clientWidth;
	window_height = (window.innerHeight) ? window.innerHeight : document.body.clientHeight;
	resize_width = document.getElementsByTagName(\'img\').item(0).width - window_width;
	resize_height = document.getElementsByTagName(\'img\').item(0).height - window_height;
	window.resizeBy(resize_width + 100, resize_height + 100);
}
</script>
</head>
<body '.(isset($_GET['pop']) ? 'onclick="window.close();" ' : '').'onload="fit();" style="text-align:center;">
<p><img src="'.$cover.'" alt="Okładka"></p>'.(isset($_GET['pop']) ? '
<p>Kliknij, aby zamknąć.</p>' : '').'
</body>
</html>';
}
else
{
	echo 'Brak okładki!';
}
?>