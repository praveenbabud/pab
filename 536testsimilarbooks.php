<?php
require('dbconnect.php');
$link = wrap_mysqli_connect();
set_time_limit(0);
$db_query = "select bookid, title,author1, author2, author3, author4 from book_inventory where bookid > 67555";
$db_result = mysqli_query($link, $db_query);

if ($db_result == TRUE)
{
	$db_row = mysqli_fetch_row($db_result);
	while($db_row != null)
	{
               $db_row[1] = removenonalphanum($db_row[1]);
               $db_row[2] = removenonalphanum($db_row[2]);
               $db_row[3] = removenonalphanum($db_row[3]);
               $db_row[4] = removenonalphanum($db_row[4]);
               $db_row[5] = removenonalphanum($db_row[5]);
		$books = similarbooksnew($link, $db_row[1] . " " . $db_row[2] . " " . $db_row[3] . " " . $db_row[4] . " " . $db_row[5],$db_row[0]);
                echo "Done $db_row[0] <br>";
                 flush();

		$tmp = 0;
		$bookstring = "";
		$bc = 0;
		while ( $tmp < count($books))
		{
			if ($books[$tmp] != $db_row[0])
			{
				if ($bc == 0)
				{
					$bookstring = $bookstring . $books[$tmp];
				}
				else
					$bookstring = $bookstring . "," . $books[$tmp];
				$bc = $bc + 1;

			}
			if ($bc == 6)
				break;
			$tmp = $tmp + 1;

		}
		if ($bc < 6 && $bc != 0)
		{
			$zeros = 6 - $bc;
			for ($i = 0 ; $i < $zeros; $i++)
			{
				$bookstring = $bookstring . ",0"; 

			}

		}
		$db_query1 = "insert into similarbooks (bookid,s1,s2,s3,s4,s5,s6) values ($db_row[0], $bookstring)";
		$db_result1 = mysqli_query($link, $db_query1);
		echo "$db_query1 <br>";
		flush();
			 $db_row = mysqli_fetch_row($db_result);
	}
}

function similarbooks($link, $string)
{
$books = array ();
$bookindex = 0;
$ranks = array ();
	$string = strtolower($string);
$escape = ", ;:.\/\"'\n\r-()!*?&\t";
$tok = strtok($string,$escape);
while ($tok != null)
{
	if ($tok == "and" || $tok == "of" || $tok == "the" || strlen($tok) < 3)
	{;
	}
	else
	{
/*	echo "$tok <br>";
	echo "$escape <br>";
echo "$search_string <br>"; */
	$db_query = "select keywordid from keywords where keyword like '$tok'";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		if (mysqli_num_rows($db_result) > 0)
		{
			$db_row = mysqli_fetch_row($db_result);
			$keywordid = $db_row[0];
			$db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid order by rank desc";
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
				$num_rows = mysqli_num_rows($db_result);
				if ($num_rows > 0)
				{
				   $trows = 0;
			   	   while($trows != $num_rows)
				   {
					   $dbrow = mysqli_fetch_row($db_result);
                       $ti = 0;
					   while ($ti != $bookindex)
					   {
						   if ($books[$ti] == $dbrow[0])
						   {
							   $ranks[$ti] = $ranks[$ti] + $dbrow[1];
							   break;
						   }
						   $ti = $ti + 1;
					   }
					   if ($ti == $bookindex)
					   {
					   		$books[$bookindex] = $dbrow[0];
					   		$ranks[$bookindex] = $dbrow[1];
							$bookindex = $bookindex + 1;
					   }
					   $trows = $trows + 1;
				   }	   

				}
			}
		}
	}
	}
    $tok = strtok($escape);
}
/*for ($ti=0; $ti < $bookindex; $ti = $ti + 1)
{
	echo "Rank:$ranks[$ti]" . " Book:$books[$ti]" . "<br>";
}
echo "<br><br><Br>";*/
$ti = 0;
$tj = 0;
for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
{
	for ($tj = $ti + 1; $tj < $bookindex; $tj = $tj + 1)
	{
		if ($ranks[$tj] > $ranks[$ti])
		{
			$temp = $ranks[$tj];
			$ranks[$tj] = $ranks[$ti];
			$ranks[$ti] = $temp;

			$temp = $books[$tj];
			$books[$tj] = $books[$ti];
			$books[$ti] = $temp;
		}
	}
}

if (count($books) < 6)
{
	$c = count($books);
	while ($c != 7)
	{
		$books[$c] = 0;
		$c = $c + 1;
	}
}

	return $books;
}

function similarbooksnew($link, $search_string, $bookid)
{
	/*$output = $output . "$search_string \n $escape "; */
	$escape = ", ;:.\/\"'\n\r-()*!?&\t";
	$books = array();
	$ranks = array();
	$wbooks = array();
	$search_string = strtolower($search_string);
	$tbr = array(array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array());
	$tok = strtok($search_string, $escape);
    $numtok = 0;
	while ($tok != null)
	{
		if ($tok == "and" || $tok == "of" || $tok == "the" || $tok== "a" || $tok == "an" || $tok == "for" || strlen($tok) < 2)
		{
    			$tok = strtok($escape);
                        continue; 
                }
		if ($tok == "s")
		{
    		$tok = strtok($escape);
			continue;
		}
		$alttok = "";
		$toklen = strlen($tok);
		if (strrpos($tok,"s") == ($toklen - 1))
		{
			for ($istr = 0 ; $istr < ($toklen - 1); $istr = $istr + 1)
			{
				$alttok = $alttok . $tok[$istr];
			}
		}
		else
		{
			$alttok = $tok . "s";
		}
		$db_query = "select keywordid from keywords where keyword like '$tok' or keyword like '$alttok'";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$num_rows = mysqli_num_rows($db_result);
			if ($num_rows > 0)
			{
				$keywordid1 = 0;
				$keywordid2 = 0;
				if ($num_rows == 2)
				{
					$db_row = mysqli_fetch_row($db_result);
					$keywordid1 = $db_row[0];
					$db_row = mysqli_fetch_row($db_result);
					$keywordid2 = $db_row[0];
					$db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1 or keywordid = $keywordid2";
				}
				else
				{
					$db_row = mysqli_fetch_row($db_result);
					$keywordid1 = $db_row[0];
					$db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1";
				}

				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					$num_rows = mysqli_num_rows($db_result);
					if ($num_rows > 0)
					{

						$trows = 0;
			   	   		while($trows != $num_rows)
				   		{
					   		$dbrow = mysqli_fetch_row($db_result);
					   		if(isset($wbooks[$dbrow[0]]) == TRUE)
					   		{
					   			$wbooks[$dbrow[0]] = $wbooks[$dbrow[0]] + $dbrow[1];
					   		}
					   		else
					   		{
					   			$wbooks[$dbrow[0]] = $dbrow[1];
					   		}
					   		$trows = $trows + 1;
				   		}	   
					}
				}
			}
	        $numtok = $numtok + 1;
		}
    	$tok = strtok($escape);
	}

/* transfer to books and ranks array */
	$bookindex = 0;
	foreach ($wbooks as $key => $value)
	{
		if ($value > 0 && $key != $bookid)
		{
			$books[$bookindex] = $key;
			$ranks[$bookindex] = $value;
			$bookindex = $bookindex + 1;
		}

	}
	$ti = 0;
	$tj = 0;
	if ($bookindex < 6)
		$max = $bookindex;
	else
		$max = 6;
	for ($ti = 0; $ti < $max; $ti = $ti + 1)
	{
		for ($tj = $ti + 1; $tj < $bookindex; $tj = $tj + 1)
		{
			if ($ranks[$tj] > $ranks[$ti])
			{
				$temp = $ranks[$tj];
				$ranks[$tj] = $ranks[$ti];
				$ranks[$ti] = $temp;

				$temp = $books[$tj];
				$books[$tj] = $books[$ti];
				$books[$ti] = $temp;
			}
		}
	}
	if (count($books) < 6)
{
	$c = count($books);
	while ($c != 7)
	{
		$books[$c] = 0;
		$c = $c + 1;
	}
}
	/*print_r($books);*/
	return($books);
}
function removenonalphanum($str)
{
	$str = iconv("UTF-8", "ASCII", $str);
        $str = str_replace("?", " ",$str);
	return $str;
}

?>
