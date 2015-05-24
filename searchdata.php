<?php
session_start();
header("Content-type: text/html;charset=utf-8");
require_once("browsecatalog.php");
$session_id = session_id();
require('dbconnect.php');
if (isset($_POST['search_string']) == FALSE)
      exit;
$storeurl = "";
if (isset($_POST['storeurl']) == TRUE)
$storeurl = $_POST['storeurl'];
$search_string = $_POST['search_string'];
$books = array ();
$bookindex = 0;
$ranks = array ();
$escape = ", ;:.\/\"'\n\r-()!*?&\t";
$reqpage = 1;
$search_string=strtolower($search_string);
$link = wrap_mysqli_connect();
$numtok = 0;
$dbstr = rawurlencode($search_string);
mysqli_query($link,"insert into sessions_searches (session,search) values ('$session_id','$dbstr')");
$pabcache = "__PAB_" . $storeurl . "_" . $search_string;
if (isset($_SESSION[$pabcache]) == TRUE)
{
	if (isset($_POST['reqpage']) == TRUE)
	{
		$reqpage = $_POST['reqpage'];
	}
	else
	{
		$reqpage = 1;
	}

	$bookstring = $_SESSION[$pabcache][$reqpage];
	$tok = strtok($bookstring,"#");
	while ($tok != null)
	{
		$books[$bookindex] = $tok;
		$bookindex = $bookindex + 1;
		$tok = strtok("#");
	}
}
else 
{
	$books = searchbooks($link, $search_string, $escape, $storeurl,1);
	$bookindex = count($books);
        if ($bookindex != 0)
        {
	$_SESSION[$pabcache]['numitems'] = $bookindex;
	if (($bookindex%10) != 0)
		$numpages = $bookindex/10 - ($bookindex%10)/10 + 1;
	else
		$numpages = $bookindex/10;
	$page = 1;
	$tmp = 0;
	$tnumpages = $numpages;
	while ($tnumpages > 0)
	{
		$bstring = "";
		for ($i = 0 ; $i < 10; $i = $i + 1)
		{
			if ($tmp < $bookindex)
			{
				$bstring = $bstring . $books[$tmp] . "#";
			}
			$tmp = $tmp + 1;
		}
		$_SESSION[$pabcache][$page] = $bstring;
		$tnumpages = $tnumpages - 1;
		$page = $page + 1;
	}
	if ($bookindex > 10)
		$bookindex = 10;
        }
}
if ($bookindex != 0)
{
$numitems = $_SESSION[$pabcache]['numitems'];
if (($numitems%10) != 0)
	$numpages = $numitems/10 - ($numitems%10)/10 + 1;
else
	$numpages = $numitems/10;
$output = "";
}
if ($bookindex == 0)
{
	echo "<div class=\"atp\" style=\"padding-left:5px;\"> <p> We could not find any matches for the search string <span class=\"blueatp\">" . $search_string . ".</span><br>Please try using different search string. <br>We are constantly trying to add to our inventory,<br> incase you could not find a match now please try later. <br>Thank You for your support.</p></div>";
	exit;
}
showbooksonpage($link,$output, $reqpage, $numpages, $books, $bookindex,$search_string,0,$storeurl);
echo $output;
/*for ($ti=0; $ti < $bookindex; $ti = $ti + 1)
{
	echo "Rank:$ranks[$ti]" . " Book:$books[$ti]" . "<br>";
}
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?";
echo ">";
echo "<data>";
echo "<books>";
for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
{
	$db_query = "select title, author1, isbn13, listprice, ourprice,edition,author2,author3,author4,publisher,boolblurb, textbook,shiptime, usedbookcount from book_inventory where bookid = $books[$ti]";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		echo "<sbook>";
		echo "<title>";
			echo "$db_row[0]";
		echo "</title>";
		if ($db_row[1] != "")
		{
		  echo "<author>";
			echo "$db_row[1]";
		  echo "</author>";
		}
		echo "<isbn13>";
			echo "$db_row[2]";
		echo "</isbn13>";
                if (file_exists("/var/apache2/2.2/htdocs/optimage/". $db_row[2]. ".jpg"))
			echo "<image>$db_row[2].jpg</image>";
		else
			echo "<image>ina.jpg</image>";

		echo "<listprice>$db_row[3]</listprice>";
		echo "<ourprice>$db_row[4]</ourprice>";
		if ($db_row[5] != "")
		{
			echo "<edition>" . $db_row[5] . "</edition>";
		}
		if ($db_row[6] != "")
		{
			echo "<author2>" . $db_row[6] . "</author2>";
		}
		if ($db_row[7] != "")
		{
			echo "<author3>" . $db_row[7] . "</author3>";
		}
		if ($db_row[8] != "")
		{
			echo "<author4>" . $db_row[8]. "</author4>";
		}
		if ($db_row[9] !="")
		{
			echo "<publisher>" . $db_row[9] . "</publisher>";
		}
		if ($db_row[10] !="")
		{
			echo "<textbook>" . $db_row[11] . "</textbook>";
		}
		if ($db_row[11] !="")
		{
			echo "<shiptime>" . $db_row[12] . "</shiptime>";
		}
		echo "<boolblurb>" . $db_row[10] . "</boolblurb>";
		echo "<usedbookcount>" . $db_row[13] . "</usedbookcount>";
		echo "</sbook>";
		
	}
}
echo "</books>";
$numitems = $_SESSION[$search_string]['numitems'];
if (($numitems%10) != 0)
	$numpages = $numitems/10 - ($numitems%10)/10 + 1;
else
	$numpages = $numitems/10;
echo "<pagination>";
if ($reqpage == $numpages)
{
	;	
}
else
{
	echo "<spage>LAST</spage>";
	echo "<spage>NEXT</spage>";
}
echo "<spage status=\"cur\">" . $reqpage . "</spage>";
if ($reqpage == 1)
{
	;
}
else
{
	echo "<spage>PREVIOUS</spage>";
	echo "<spage>FIRST</spage>";
}
echo "<totalpages>" . $numpages . "</totalpages>";
echo "</pagination>";


echo "</data>"; */

?>
