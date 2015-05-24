<?php
header("Content-type: text/html;charset=utf-8");
?>
<html>
<head>
</head>
<body>


<?php
set_time_limit(0);

$keylines = file("oup_parsedstocklist1.data");
$link = mysqli_connect('localhost', 'root', '', 'zoco');
$value = "";
$source = "nibs";
$datetime = date('d-m-Y');
	$isbn13 = $value;
			$isbn10 = $value;
			$listprice = $value;
			$ourprice = $value;
			$quantity = $value;
			$currency = $value;
foreach($keylines as $line)
{
		$escape = "\r\n=";
		$param = strtok ($line, $escape);
		if ($param == "###")
		{
			$lp = $listprice;
			$op = 0;
			$lp = str_replace(",", "", $lp);

			if ($source == "nibs")
			{
			   if ($lp >= 500)
				$op = $lp * 0.92;
			   else
				$op = $lp * 0.87;
			}
			else if ($source == "oup")
			{
			   if ($lp >= 500)
				$op = $lp * 0.92;
			   else
				$op = $lp * 0.87;
			}

			if ($isbn13 == "")
				$isbn13 = $isbn10;

			if ($listprice != 0 && $isbn13 != "" && $currency != "")
			{

				$db_query = "select bookid,nlistprice from book_inventory where isbn13 like '$isbn13'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result  == TRUE)
				{
					$db_row = mysqli_fetch_row($db_result);
					if ($db_row != null)
					{
						$bookid = $db_row[0];
						if ($db_row[1] != $lp)
						{
				$db_query = "update book_inventory set nlistprice=$lp , nourprice=$op , currency='$currency' where bookid=$bookid";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					echo "DEBUG : TRUE $db_query";
					echo "<br>";
				}
				else
				{
					echo "ERROR : FALSE $db_query";
					echo "<br>";
				}
						}
				$db_query = "select id from inventory where bookid=$bookid and source like '$source'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					$db_row = mysqli_fetch_row($db_result);
					if ($db_row != null)
					{
						$id = $db_row[0];
				                $db_query = "update inventory set quantity=$quantity , datetime='$datetime' where id=$id";
				                $db_result = mysqli_query($link, $db_query);
				                if ($db_result == TRUE)
				                {
					             echo "DEBUG : TRUE $db_query";
					             echo "<br>";
						} 
				                else
				                {
					             echo "ERROR : FALSE $db_query";
					             echo "<br>";
				                }
					}
					else
					{
						$db_query = "insert into inventory (bookid,quantity,source,datetime) values ($bookid, $quantity, '$source','$datetime')";
				                $db_result = mysqli_query($link, $db_query);
				                if ($db_result == TRUE)
				                {
					             echo "DEBUG : TRUE $db_query";
					             echo "<br>";
						} 
				                else
				                {
					             echo "ERROR : FALSE $db_query";
					             echo "<br>";
				                }
					}
				}
				}
				}

		       	
			$value = "";
			$isbn13 = $value;
			$isbn10 = $value;
			$quantity = $value;
			$currency = $value;
			$listprice = $value;
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
$db_query = "update inventory set quantity=0  where source='$source' and datetime not like '$datetime'";
mysqli_query($link, $db_query);

?>
