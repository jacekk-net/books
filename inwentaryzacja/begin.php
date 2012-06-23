<?php
$title = 'Inwentaryzacja - rozpoczęcie';
include('design/top.php');
?>

<h3>Krok 1 z 3: Przygotowanie listy książek</h3>

<p>Ten krok służy do przygotowania listy książek, które zostaną objęte inwentaryzacją.</p>

<p>W trakcie inwentaryzacji zaleca się nie zmieniać położenia książek za pomocą interfejsu, gdyż dane te zostaną po zakończeniu procesu (krok 3) nadpisane.</p>

<?php
if(file_exists('list.txt')) {
?>
<p><strong>Niezapisane dane z poprzedniej inwentaryzacji zostaną usunięte!</strong></p>

<?php
}
?>
<form action="make.php">
<p><input type="submit" value="Utwórz listę" /></p>
</form>

<?php
include('design/bottom.php');
?>