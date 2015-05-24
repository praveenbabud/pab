<?php
$session_id = session_id();
require('dbconnect.php');
$name = array ();
$index = 0;
$id = array ();
$link = wrap_mysqli_connect();
$tname = array();
$tid = array();
$tindex = 0;
$stname = array();
$stid = array();
$stindex = 0;



$db_result = mysqli_query($link,"select name, productid from products");

$db_row = mysqli_fetch_row($db_result);
while ($db_row != null)
{
	$tname[$tindex] = $db_row[0];
	$tid[$tindex] = $db_row[1];
	$tindex = $tindex + 1;
	$db_row = mysqli_fetch_row($db_result);
}
$tv = 0;
for ($tv = 0; $tv < $tindex; $tv = $tv + 1)
{
	$db_result = mysqli_query($link,"select name, subproductid from subproducts where productid=$tid[$tv]");
	$db_row = mysqli_fetch_row($db_result);

	$name[$index] = $tname[$tv];
	$id[$index] = 0;
	$index = $index + 1;
	while ($db_row != null)
	{
		if (strstr($db_row[0], "Select") == FALSE)
		{
		$stname[$stindex] = $db_row[0];
		$stid[$stindex] = $db_row[1];
		$stindex = $stindex + 1;
		$name[$index] = $db_row[0];
		$id[$index] = 1;
		$index = $index + 1;
		}
		$db_row = mysqli_fetch_row($db_result);
	}
}
$tv = 0;
for ($tv = 0; $tv < $stindex; $tv = $tv + 1)
{
	$db_result = mysqli_query($link,"select name, subsubproductid from subsubproducts where subproductid=$stid[$tv]");
	$db_row = mysqli_fetch_row($db_result);
        if ($db_row != null)
	{
	$name[$index] = $stname[$tv];
	$id[$index] = 0;
	$index = $index + 1;
	}
	while ($db_row != null)
	{
		if (strstr($db_row[0], "Select") == FALSE)
		{
		$name[$index] = $db_row[0];
		$id[$index] = 1;
		$index = $index + 1;
		}
		$db_row = mysqli_fetch_row($db_result);
	}
}
$avglen = intval($index/3);

$tindex = 0;
$tid = $id[$tindex];
echo "<table cellspacing=\"10\"><tr>";
$tindex = 0;
$i = 0;
for ($i = 0 ; $i < 4; $i = $i + 1)
{
	$kc = 1;
echo "<td valign=\"top\">";
while ($tindex < $index)
{
	$tid = $id[$tindex];
	if ($tid == 0)
	{
		$value = getnextzero($tindex,$id,$index);
		if (($kc + $value) > $avglen)
		{
			echo "</td>"; break;
		}
		$bs = str_replace(" ","-",$name[$tindex]);
		echo "<br><br><span class=\"boldatp\"><a style=\"color:#000000;\" href=\"https://www.popabook.com/$bs\">$name[$tindex]</a></span><br><br>";
	}
	else if ($tid == 1)
	{
		$bs = str_replace(" ","-",$name[$tindex]);
		echo "<span class=\"atp\"><a href=\"https://www.popabook.com/$bs\">$name[$tindex]</a></span><br>";
	}
	$tindex = $tindex + 1;
	$kc = $kc + 1;
}
}
echo "</td>";
echo "</tr></table>";
function getnextzero($toindex, $id,$index)
{
	$c = 0;
	$toindex = $toindex + 1;
	while ($toindex < $index)
	{
		$c = $c + 1;
		if($id[$toindex] == 0)
			return $c;
		$toindex = $toindex + 1;
	}
	return 0;
}
?>
