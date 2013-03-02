<?php
$title = 'Inwentaryzacja - rozpoczęcie';
include('design/top.php');
?>

<h3>Krok 1 z 3: Przygotowanie listy książek</h3>

<p>Ten krok służy do przygotowania listy książek, które zostaną objęte inwentaryzacją. Na liście nie znajdą się książki wycofane. W przypadku książki wypożyczonej, informacja o zwrocie (w przypadku jej zainwentaryzowania) nie zostanie zapisana w bazie!</p>

<p>W trakcie inwentaryzacji zaleca się nie zmieniać położenia książek za pomocą interfejsu, gdyż dane te mogą zostać nadpisane po zakończeniu procesu (krok 3).</p>

<p>Z interfejsu inwentaryzacji w danej chwili powinna korzystać tylko jedna osoba, w przeciwnym wypadku osoby inwentaryzujące mogą wzajemnie nadpisywać zgromadzone dane.</p>

<?php
if(file_exists('list.xml')) {
?>
<p><strong>Dane z poprzedniej (niedokończonej) inwentaryzacji zostaną usunięte!</strong></p>

<?php
}
?>
<form action="make.php">
<p><input type="submit" value="Utwórz listę" /></p>
</form>

<?php
include('design/bottom.php');
?>