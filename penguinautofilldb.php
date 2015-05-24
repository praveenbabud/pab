<html>
<head>
</head>
<body>


<?php
function addbook($link, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$listprice, $ourprice,$shiptime,$pages)
{
	$quantity=1;
	$ourprice = $listprice;
	$db_query = "select bookid from book_inventory where isbn13 like '$isbn13'";
	$db_result = mysqli_query($link,$db_query);
	$booklink = createbooklink($title);
	if ($db_result == TRUE)
	{
		if (mysqli_num_rows($db_result) > 0)
		{
			echo "ISBN $isbn13 is already in Database. Trying Modify.";
			$db_query = "update book_inventory set title=\"$title\", edition=\"$edition\", year=\"$yearofprint\", author1=\"$author1\", author2=\"$author2\", author3=\"$author3\", author4=\"$author4\", publisher=\"$publisher\", listprice=\"$listprice\", ourprice=\"$ourprice\", pages=\"$pages\", shiptime=\"$shiptime\", blurb=\"$blurb\" where isbn13 like '$isbn13'";
			$db_result = mysqli_query($link, $db_query);
		        if ($db_result == TRUE)
			     echo "update success $isbn13 success <br>";
		        else
			     echo "update failed $isbn13 failed <br>";	
			return("updated");
		}
	}
	$db_query = "delete from book_inventory where isbn13 like '$isbn13'";
	mysqli_query($link,$db_query);

	$db_query = "insert into book_inventory (isbn10, isbn13, title, author1, author2,author3,author4, listprice, ourprice, quantity, publisher, edition, year, blurb,shiptime,pages,booklink) values ('$isbn10', '$isbn13', \"$title\", \"$author1\", \"$author2\", \"$author3\", \"$author4\", $listprice, $ourprice, 1, \"$publisher\", \"$edition\", \"$yearofprint\", \"$blurb\",'$shiptime','$pages','$booklink')";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		echo "<br>";

		echo "Added Book $title  and $isbn13";
		echo "<br>";
	}
	return("added");
}

function buildindex($link, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$listprice, $ourprice,$vipkeys,$shiptime)
{
	$index = 0;
	$weight = 25;
	if (strstr($yearofprint,"2010") != null)
		$weight = 30;
	if ($shiptime == "Out Of Stock")
		$weight = 7;
	$title = removenonalphanum($title);
	$author1 = removenonalphanum($author1);
	$author2 = removenonalphanum($author2);
	$author3 = removenonalphanum($author3);
	$author4 = removenonalphanum($author4);
	$vipkeys = removenonalphanum($vipkeys);
	$publisher = removenonalphanum($publisher);
	$value = $isbn13 . " " . $title . " " . $edition . " " . $author1 . " " . $author2 . " " . $author3 . " " . $author3 . " " . $author4 . " " . $publisher . " " . $vipkeys;

			if ($value != "")
			{
				$lowerline = strtolower($value);
				$escape1 = ", ;:.\/\"'\n\r-()?&\t";
	    			$tok = strtok ($lowerline, $escape1);
				while($tok != FALSE)
				{
					$tindex = 0;
					while ($index != $tindex)
					{
						if ($tok == $keywords[$tindex])
						break;
						$tindex = $tindex + 1;
					}
					if ($tindex == $index)
					{	
						$keywords[$index] = $tok;
						$score[$index] = $weight;
						$index = $index + 1;
					}
					else
					{
						$score[$tindex] = $score[$tindex] + $weight;
					}
					$tok = strtok($escape1);
				}
			}
	    
	$db_error = "";
	$bookid = 0;


	$db_query = "select bookid from book_inventory where isbn13 like '$isbn13'";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		echo "exeuted $db_query";
		echo "<br>";
		$db_row = mysqli_fetch_row($db_result);
		if ($db_row != null)
			$bookid = $db_row[0];
	}
	if ($bookid == 0)
	{
		echo "could not connect to DB or could not find ISBN $isbn13 in DB"; echo "<br>";
		return;
	}
	else
	       echo "<br> book id is $bookid <br>";
	$db_query = "delete from keyword_book_map where bookid = $bookid";
	mysqli_query($link, $db_query);

	$tindex = 0;	
	while ($tindex != $index)
	{
		$db_query = "select keywordid from keywords where keyword like '$keywords[$tindex]'";

		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			if (mysqli_num_rows($db_result) > 0)
			{
				$db_row = mysqli_fetch_row($db_result);
				$keywordid = $db_row[0];
				$db_query = "insert into keyword_book_map (keywordid,bookid,rank) values ($keywordid, $bookid, $score[$tindex])";
				$db_result = mysqli_query($link,$db_query);
			}
			else
			{
				$db_query = "insert into keywords (keyword) values ('$keywords[$tindex]')";
				$db_result = mysqli_query($link,$db_query);
				if ($db_result == TRUE)
				{
					$db_query = "select keywordid from keywords where keyword like '$keywords[$tindex]'";
					$db_result = mysqli_query($link,$db_query);
					$db_row = mysqli_fetch_row($db_result);
					$keywordid = $db_row[0];
    				$db_query = "insert into keyword_book_map (keywordid,bookid,rank) values ($keywordid, $bookid, $score[$tindex])";
				$db_result = mysqli_query($link,$db_query);
				}
			}
		}
		else
		{
			echo "Could not insert $keywords[$tindex]";
			return;
		}
		echo "$keywords[$tindex] = $score[$tindex]";
		echo "<br>";
		$tindex = $tindex + 1;
	}
}
set_time_limit(0);

$keylines = file("penguinbooks-5-sep-2010.data");
$link = mysqli_connect('localhost', 'root', 'zulu1PopAbooK', 'zoco');
$value = "";
	$isbn13 = $value;
			$title = $value;
			$isbn10 = $value;
			$author1 = $value;
			$author2 = $value;
			$author3 = $value;
			$author4 = $value;
			$listprice = $value;
			$ourprice = $value;
			$quantity = $value;
			$publisher = "";
			$edition = $value;
			$yearofprint = $value;
			$pages = $value;
			$blurb = $value;
			$currency = $value;
			$listprice = $value;
			$ourprice = $value;
			$shiptime  = $value;
			$vipkeys = $value;
foreach($keylines as $line)
{
		$escape = "\r\n=";
		$param = strtok ($line, $escape);
		if ($param == "###")
		{
			$t = strstr($listprice, "pound");
                        $nlp = "";
                        if ($t != null)
                        {
                                $currency = "pound";
                                $nlp = substr($listprice,5);
                                $listprice = $nlp;

			}
			else
			{
				$t = strstr($listprice, "Rs.");
				$nlp = "";
				if ($t != null)
				{
					$currency = "";
					$nlp = substr($listprice, 4);
					$listprice = $nlp;
				}
			}
			

			if ($currency == "pound")
				$lp = floatval($listprice) * 80;
			else if ($currency == "EURO")
				$lp = floatval($listprice) * 74;
			else if ($currency == "USD")
				$lp = floatval($listprice) * 49;
			else
				$lp = floatval($listprice) * 1;
                        $lp = intval($lp);
			$top = intval ($lp * 0.90);
			$op = $top;
			echo "isbn=$isbn13 currency=$currency $listprice listprice = $lp <br> ourprice = $op <br>";
			      
			if ($listprice != 0 && $isbn13 != "")
			{
		       	
			$result = addbook($link, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$lp, $op,$shiptime,$pages);
			
			if ($result == "added" || $result == "updated")
			buildindex($link, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$lp, $op,$vipkeys,$shiptime); 
			$value = "";
			$isbn13 = $value;
			$title = $value;
			$isbn10 = $value;
			$author1 = $value;
			$author2 = $value;
			$author3 = $value;
			$author4 = $value;
			$listprice = $value;
			$ourprice = $value;
			$quantity = $value;
			$publisher = "";
			$edition = $value;
			$yearofprint = $value;
			$pages = $value;
			$blurb = $value;
			$currency = $value;
			$listprice = $value;
			$ourprice = $value;
			}

		}
		if ($param == "blurb")
			$value = strtok("\n");
		else
			$value = strtok($escape);
		if ($param == "isbn13")
			$isbn13 = $value;
		else if ($param == "title")
			$title = $value;
		else if ($param == "isbn10")
			$isbn10 = $value;
		else if ($param == "author1")
			$author1 = $value;
		else if ($param == "author2")
			$author2 = $value;
		else if ($param == "author3")
			$author3 = $value;
		else if ($param == "author4")
			$author4 = $value;
		else if ($param == "listprice")
			$listprice = $value;
		else if ($param == "ourprice")
			$ourprice = $value;
		else if ($param == "quantity")
			$quantity = $value;
		else if ($param == "publisher")
		{
			$publisher = $value;
		}
		else if ($param == "edition")
			$edition = $value;
		else if ($param == "year")
			$yearofprint = $value;
		else if ($param == "blurb")
			$blurb = $value;
		else if ($param == "currency")
			$currency = $value;
		else if ($param == "shiptime")
			$shiptime = $value;
		else if ($param == "vipkeys")
			$vipkeys = $value;
		else if ($param == "pages")
			$pages = $value;
}
function removenonalphanum($str)
{
        $str = iconv("UTF-8", "ASCII", $str);
        $str = str_replace("?", " ",$str);
        return $str;
}


function createbooklink($str)
{
        $ostr = $str;
        $str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
        $str = iconv("UTF-8", "ASCII", $str);
        $str = str_replace("?", " ", $str);
        echo "<br> remove non <br> $str <br>";
        $title = $str;
$title = str_replace("&amp;", " ", $title);
        $escape = ", ;:.\/\"'\n\r-()%?&\t";
        $tooks = array();
        $in = 0;
        $tok = strtok($title,$escape);
        while ($tok != null)
        {
                        $tooks[$in] = $tok;
                        $tok = strtok($escape);
                        $in = $in + 1;
        }
        $link = "";
$it = 0;
while ($it < $in)
        {
                if ($it == 0)
                        $link = $link . $tooks[$it];
                else
                        $link = $link . "-" . $tooks[$it];
                $it = $it + 1;
        }
        echo "*** $ostr / $isbn ***<br>";
	echo "*** $link/$isbn ***<br>";
	return $link;

}





?>
