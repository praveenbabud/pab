<?php
$datetime = new DateTime();
$link = mysqli_connect("localhost","root","","zoco");
$db_result = mysqli_query($link, "select now()");

$db_row = mysqli_fetch_row($db_result);

echo $db_row[0];
echo "<br>";
echo	date_format($datetime, DATE_ATOM);

$gola = $_GET['1isbn'];

echo $gola;
echo "<br>";

$stri = "1" . "isbn";

$gola = $_GET[$stri];
echo $gola;
?>
