<?php
set_time_limit(0);

$link = mysqli_connect('localhost', 'root', '', 'zoco');
$db_query = "select isbn13 from book_inventory";
$db_result = mysqli_query($link,$db_query);
if ($db_result == TRUE)
{
	$db_row = mysqli_fetch_row($db_result);
	while ($db_row != null)
	{
		if (file_exists($db_row[0]."jpg"))
		{;}
		else
			echo "cp ina.jpg $db_row[0].jpg"; echo "<br>";
		$db_row = mysqli_fetch_row($db_result);
	}
}
?>		
