<?php
if(is_file('list.xml')) {
	header('Location: locate.htm');
}
else
{
	header('Location: begin.php');
}
?>