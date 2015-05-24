<?php
function directoryofbooks($link,&$pagetitle)
{
$minbookid = 0;
    $maxbookid = 0;
$nbookids = 0;
$booksinleaves= 0;
$level1seed = 0;
    $db_query = "select min(bookid), max(bookid) from book_inventory";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
	    $db_row = mysqli_fetch_row($db_result);
	    if ($db_row != null)
	    {
	    $minbookid = $db_row[0];
	    $maxbookid = $db_row[1];
	    $nbookids = $maxbookid - $minbookid + 1;
	    }
    }
    if ($minbookid != 0 && $maxbookid != 0 && $nbookids != 0)
    {
	    $booksinleaves = ceil($nbookids/10000);
	    $level1seed = $booksinleaves * 100;
    }
    $links = array();
    $index = 0;
if (isset($_GET['books']) == FALSE)
{
	$i = 0;
	for ($i = 0; $i < 100; $i = $i + 1)
	{
		$from = $minbookid + ($i) * $level1seed;

		$to = $minbookid + ($i + 1) * $level1seed - 1;
		$fromtitle = "";
		$totitle = "";
		if ($from > $maxbookid)
		{
		      break;	
		}
		if ($to > $maxbookid)
		{
			$to = $maxbookid;
		}
		$db_query = "select title from book_inventory where bookid=$from";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			$fromtitle = $db_row[0];
		}
		$db_query = "select title from book_inventory where bookid=$to";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			$totitle = $db_row[0];
		}
		$tn = 0;
		if ($fromtitle == "")
		{
			$tn = $from - $minbookid;
			$fromtitle = "Book $tn";
		}
		if ($totitle == "")
		{
			$tn = $to - $minbookid;
			$totitle = "Book $tn";
		}
		$tn = $i + 1;
		$links[$index] = "<a href=\"directoryofbooks.php?books=2-$tn\">$fromtitle to $totitle</a>";
		$index = $index + 1;
	}
}
else
{
	$books = $_GET['books'];
	$arrex = explode("-", $books);
	if (count($arrex) == 2)
	{
		if ($arrex[0] == "2")
		{
			$page = intval($arrex[1]);
			$min = $minbookid + ($page - 1) * $level1seed;
			$max = $minbookid + ($page * $level1seed) - 1;
			if ($min > $maxbookid)
				;
			if ($max > $maxbookid)
				$max = $maxbookid;
			$links = displaylevel2page($link,$min,$max,$booksinleaves,$page,$pagetitle);
		}
		else if ($arrex[0] == "3")
		{
			$page = intval($arrex[1]);
			$min = $minbookid + ($page - 1) * $booksinleaves;
			$max = $min + $booksinleaves - 1;

			$links = displaylevel3page($link,$min,$max,$booksinleaves,$page,$pagetitle);
		}
	}
}
/*$index = count($links);
$half = ceil($index/2);
$i = 0;

$links[$index] = "";
$index = $index + 1;
echo "<div style=\"padding-left:25px;\">";
echo "<div class=\"logoatp\" style=\"text-align:center;\"><br><br>Directory of Books<br><br></div>";
echo "<table width=\"100%\">";
for ($i = 0; $i < $half; $i = $i + 1)
{
	$one = 2 * $i;
	$two = 2 * $i + 1;
	echo "<tr><td width=\"50%\" valign=\"top\">$links[$one]</td><td width=\"50%\" valign=\"top\">$links[$two]</td></tr>";
}
echo "</table>";
echo "</div>";*/
if ($pagetitle == "")
     $pagetitle = "PopAbooK.com : Directory of Books";
else
     $pagetitle = "PopAbooK.com : " . $pagetitle;
    
  
return $links;
//displaylinks($links);
}

function displaylevel2page($link, $min,$max,$booksinleaves,$page, &$pagetitle)

{
        $pagetitle = "";
	$index = 0;
	$links = array();
	$i = 0;
	$level1seed = $booksinleaves * 100;
	
	for ($i = 0; $i < 100; $i = $i + 1)
	{
	        $from = $min + ($i) * $booksinleaves;
		$to = $from + $booksinleaves - 1;
		$fromtitle = "";
		$totitle = "";
		if ($from > $max)
		{
		      break;	
		}
		if ($to > $max)
		{
			$to = $max;
		}
		$db_query = "select title from book_inventory where bookid=$from";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			$fromtitle = $db_row[0];
                        if ($from == $min)
                            $pagetitle = $fromtitle;
		}
		$db_query = "select title from book_inventory where bookid=$to";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			$totitle = $db_row[0];
		}
		$tn = 0;
		if ($fromtitle == "")
		{
			$tn = ($page - 1) * $level1seed + ($i) * $booksinleaves;
			$fromtitle = "Book $tn";
		}
		if ($totitle == "")
		{
			$tn = $page * $level1seed + ($i + 1) * $booksinleaves - 1;
			$totitle = "Book $tn";
		}
                if ($from == $min)
                            $pagetitle = $fromtitle;
                if ($to == $max)
                            $pagetitle = $pagetitle . " to " .  $totitle;
		$tn = $i + 1;
		$tn = $tn + (100 * ($page - 1));
		$links[$index] = "<a href=\"directoryofbooks.php?books=3-$tn\">$fromtitle to $totitle</a>";
		$index = $index + 1;
	}
	return $links;
}
function displaylevel3page($link, $min,$max,$booksinleaves,$page, &$pagetitle)
{
        $pagetitle = "";
	$links = array();
	$index = 0;
	$i = 0;
	for ($i = 0; $i < $booksinleaves; $i = $i + 1)
	{
	     $to = $min + $i;
             $db_query = "select  title,booklink,isbn13,author1 from book_inventory where bookid=$to";
	     $db_result = mysqli_query($link, $db_query);
	     if ($db_result == TRUE)
	     {
		     $db_row = mysqli_fetch_row($db_result);
		     if ($db_row != null)
		     {
			$links[$index] = "<a href=\"http://www.popabook.com/$db_row[1]/$db_row[2]\">$db_row[0] by $db_row[3]</a>";
			$index = $index + 1;
                if ($to == $min)
                            $pagetitle = $db_row[0];
                if ($to == $max)
                            $pagetitle = $pagetitle . " to " .  $db_row[0];
		     }
	     } 
	}
	return $links;
}
function displaylinks($links)
{
    $index = count($links);
$half = ceil($index/2);
$i = 0;

$links[$index] = "";
$index = $index + 1;
echo "<div style=\"padding-left:25px;\">";
echo "<div class=\"logoatp\" style=\"text-align:center;\"><br><br>Directory of Books<br><br></div>";
echo "<table width=\"100%\">";
for ($i = 0; $i < $half; $i = $i + 1)
{
        $one = 2 * $i;
        $two = 2 * $i + 1;
        echo "<tr><td width=\"50%\" valign=\"top\">$links[$one]</td><td width=\"50%\" valign=\"top\">$links[$two]</td></tr>";
}
echo "</table>";
echo "</div>";

}

?>
