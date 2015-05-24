<?php
header("Content-type: text/html;charset=utf-8");
?>
<html>
<head>
</head>
<body>


<?php
function addbook($link, $isbn10, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$listprice, $ourprice,$shiptime,$currency, $pages)
{
        if ($shiptime == "")
            $shiptime = 'Out Of Stock';
	$quantity=1;
        if ($shiptime == "Out Of Stock")
          $quantity = 0;
	$isbn13 = converttoutf8($isbn13);
        $isbn13 = trim($isbn13);
        $isbn10 = trim($isbn10);
	echo $title; echo "<br>";
	$title = converttoutf8($title);
	echo $title; echo "<br>";
	$edition = converttoutf8($edition);
	$author1 = converttoutf8($author1);
	$author2 = converttoutf8($author2);
	$author3 = converttoutf8($author3);
	$author4 = converttoutf8($author4);
	$publisher = converttoutf8($publisher);
	$blurb = converttoutf8($blurb);
	$blurb = str_replace("\"","'", $blurb);
	$blurb = trim($blurb);
	$title = str_replace("\"","'", $title);
	$booklink = createbooklink($title);
        $booklink = rawurlencode($booklink);
	$db_query = "select bookid from book_inventory where isbn13 like '$isbn13%'";
	$db_result = mysqli_query($link,$db_query);
	if ($db_result == TRUE)
	{
		if (mysqli_num_rows($db_result) > 0)
		{
			echo "ISBN $isbn13 is already in Database. Try Modify.";
                        /*$db_query = "update book_inventory set isbn13=\"$isbn13\", isbn10=\"$isbn10\", blurb=\"$blurb\",booklink=\"$booklink\",title=\"$title\",author1=\"$author1\", author2=\"$author2\", author3=\"$author3\", author4=\"$author4\" , yearofprint=\"$yearofprint\",currency=\"$currency\", quantity=$quantity,publisher=\"$publisher\",nlistprice=$listprice,nourprice=$ourprice, edition=\"$edition\",shiptime=\"$shiptime\", pages=\"$pages\",textbook=\"no\"   where isbn13 like '$isbn13%'";
                        $db_result = mysqli_query($link, $db_query);
                        if ($db_result == TRUE)
                              return ("modified");
                        else */
                     /*   $db_query = "update book_inventory set currency=\"$currency\" where isbn13 like '$isbn13'";
                        $db_result = mysqli_query($link, $db_query);*/
                              return ("notmodified");
		}
	}
                  /* yes means need to update */
                  /* no means its a good one its also the default*/
        $db_query = "insert into book_inventory (isbn10, isbn13, title, author1, author2,author3,author4, nlistprice, nourprice, quantity, publisher, edition, yearofprint, blurb,shiptime,booklink,currency,pages) values ('$isbn10', '$isbn13', \"$title\", \"$author1\", \"$author2\", \"$author3\", \"$author4\", $listprice, $ourprice, 1, \"$publisher\", \"$edition\", \"$yearofprint\", \"$blurb\",'$shiptime','$booklink','$currency',\"$pages\")"; 
	 /* $db_query = "insert into book_inventory (isbn10, isbn13, title, author1, author2,author3,author4, nlistprice, nourprice, quantity, publisher, edition, yearofprint, blurb,shiptime,booklink,currency,pages,textbook) values ('$isbn10', '$isbn13', \"$title\", \"$author1\", \"$author2\", \"$author3\", \"$author4\", $listprice, $ourprice, 1, \"$publisher\", \"$edition\", \"$yearofprint\", \"$blurb\",'$shiptime','$booklink','$currency',\"$pages\",'yes')"; */
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		echo "<br>Added Book $title  and $isbn13 <br>";
	}
	else
	{
		echo "failed $db_query"; echo "<br>";
	            return("not added");
	}
	return("added");
}

function buildindex($link, $isbn10, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$listprice, $ourprice,$vipkeys,$shiptime)
{
	$index = 0;
	$weight = 25;
        if (strstr($yearofprint, "2010") != null)
             $weight = 30;
	$isbn13 = converttoutf8($isbn13);
	$title = converttoutf8($title);
	$edition = converttoutf8($edition);
	$author1 = converttoutf8($author1);
	$author2 = converttoutf8($author2);
	$author3 = converttoutf8($author3);
	$author4 = converttoutf8($author4);
	$publisher = converttoutf8($publisher);
	$vipkeys = removeduplicates($vipkeys);
	$publisher = removeduplicates($publisher);
	$edition = removeduplicates($edition);
         $booklink = createbooklink($title);
	$value = $isbn10 . " " . $isbn13 . " " . $booklink . " " . $author1 . " " . $author2 . " " . $author3 . " " . $author3 . " " . $author4;

			if ($value != "")
			{
				$lowerline = strtolower($value);
				$escape1 = ", ;:.\/\"'\n\r-()?&!*$\t";
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
			$value = $edition . " " . $publisher . " " . $vipkeys;
			$weight = 2;
			if ($value != "")
			{
				$lowerline = strtolower($value);
				$escape1 = ", ;:.\/\"'\n\r-()?&!*$\t";
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

$keylines = file("2-2-2011-jaico-books.data");
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
			$blurb = $value;
			$currency = $value;
			$listprice = $value;
			$ourprice = $value;
			$shiptime  = $value;
			$vipkeys = $value;
			$pages = $value;
foreach($keylines as $line)
{
		$escape = "\r\n=";
		$param = strtok ($line, $escape);
		if ($param == "###")
		{
			$lp = $listprice;
                        $op = $lp;

                         $op = $lp; 
                         if ($listprice == "")
                         {
                                $lp = 0; $op = 0; $listprice = 0;
                         }
                         $op = $lp;
                         if ($lp >= 1000)
                                $op = $lp * 0.97;
                        else if ($lp >= 500)
                                $op = $lp * 0.93;
                        else if ($lp >= 200)
                                $op = $lp * 0.89;
                        else
                                $op = $lp;

			echo "isbn=$isbn13 currency=$currency $listprice listprice = $lp <br> ourprice = $op <br>";

			if ($isbn13 == "")
				$isbn13 = $isbn10;
			      
			if ($isbn13 != "")
			{
		       	
			$result = addbook($link, $isbn10, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$lp, $op,$shiptime,$currency, $pages);
			if ($result == "added" || $result == "modified")
			buildindex($link, $isbn10, $isbn13, $title, $edition , $yearofprint, $author1, $author2, $author3, $author4, $publisher,$blurb,$lp, $op,$vipkeys,$shiptime); 
                        flush();
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
			$publisher = $value;
			$edition = $value;
			$yearofprint = $value;
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
		else if ($param == "pages")
			$pages = $value;
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
			$publisher = $value;
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
}
$db_query = "update book_inventory set boolblurb=0 where blurb like ''";
mysqli_query($link, $db_query);
function removenonalphanum($str)
{
        $str = iconv("UTF-8", "ASCII", $str);
        $str = str_replace("?", " ",$str);
        return $str;
}


function createbooklink($str)
{
	$ostr = $str;
	$str = str_replace("’","'",$str);
        $str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
        $str = iconv("UTF-8", "ASCII", $str);
        $str = str_replace("?", " ", $str);
        echo "<br> remove non <br> $str <br>";
        $title = $str;
$title = str_replace("&amp;", " ", $title);
        $escape = ", ;:.\/\"'\n\r-()!*$%?&\t";
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
function converttoutf8($str)
{
	/*if(mb_detect_encoding($str,"UTF-8, ISO-8859-1, ASCII")!="UTF-8" )*/
	$str = str_replace("’","'",$str);
        $str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
        $enc = mb_detect_encoding($str,"UTF-8, ISO-8859-1, ASCII");
        if ($enc == "UTF-8" )
        {
                return $str;
        }
        else if ($enc == "ASCII")
        {
                return iconv("ASCII", "UTF-8", $str);
        }
        else if ($enc == "ISO-8859-1")
        {
                return iconv("ISO-8859-1", "UTF-8", $str);
        }
}
function removeduplicates($vipkeys)
{
	$tmparray = array();
	$index = 0;
	$escape = ", ;:.\/\"'\n!*$\r-()?&\t";
	$tmp = strtok($vipkeys, $escape);

	while ($tmp != null)
	{
		$tindex = 0;
		while ($tindex < $index)
		{
			if ($tmparray[$tindex] == $tmp)
				break;
			$tindex = $tindex + 1;
		}
		if ($tindex == $index)
		{
			$tmparray[$index] = $tmp;
			$index = $index + 1;
		}
		$tmp = strtok($escape);
	}
	$tindex = 0;
	$vipkeys = "";
	while ($tindex < $index)
	{
		$vipkeys = $vipkeys . " " . $tmparray[$tindex];
		$tindex = $tindex + 1;
	}
	return $vipkeys;

}
function removetags ($string)
	{
		$ret_str = "";
		$tmp = strtok($string, "<");
		while ($tmp != null)
		{
                        $tmp = trim($tmp);
			if ($tmp != "###")
			$ret_str = $ret_str . " " . $tmp;
			$string = strstr($string, "<");
			if (substr($string,0,4) == "<br>")
			{
				$ret_str = $ret_str . " " . "<br>";
			}
			if (substr($string,0,4) == "<BR>")
			{
				$ret_str = $ret_str . " " . "<br>";
			}
			if (substr($string,0,4) == "<li>")
			{
				$ret_str = $ret_str . " " . "<br>&middot;";
			}
			if (substr($string,0,4) == "<LI>")
			{
				$ret_str = $ret_str . " " . "<br>&middot;";
			}

			$string = strstr($string, ">");
			$string = substr($string, 1);
		

			$ring = substr($string,0, 1);
			if ($ring == "<")
	                       $tmp = "###";
			else			
				$tmp = strtok($string, "<");

			
		}
		return $ret_str;

	}



?>
