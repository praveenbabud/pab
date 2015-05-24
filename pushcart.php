<?php
header("Content-type: text/xml");
session_start();
$session_id = session_id();
$pushcart = $_POST['pushcart'];
$_SESSION['pushcart'] = $pushcart;

	$counter = 1;
		while ($counter > 0)
		{
			$isbn13name = $counter . "isbn13";
			$isbn10name = $counter . "isbn10";
			$quantityname = $counter . "quantity";
			$titlename = $counter . "title";
			$ourpricename = $counter . "ourprice";
			$listpricename = $counter . "listprice";
			if(isset($_POST[$isbn13name]))
				$_SESSION[$isbn13name] = $_POST[$isbn13name];
			else
				break;
			if(isset($_POST[$isbn10name]))
				$_SESSION[$isbn10name] = $_POST[$isbn10name];
			else
				$isbn10 = "";
			if(isset($_POST[$quantityname]))
				$_SESSION[$quantityname] = $_POST[$quantityname];
			else
				$quantity = 0;
			if(isset($_POST[$titlename]))
				$_SESSION[$titlename] = $_POST[$titlename];
			else
				$title = "";
			if(isset($_POST[$ourpricename]))
				$_SESSION[$ourpricename] = $_POST[$ourpricename];
			else
				$ourprice = 0;
			if(isset($_POST[$listpricename]))
				$_SESSION[$listpricename] = $_POST[$listpricename];
			else
				$listprice = 0;
			$counter = $counter + 1;
		}


echo "<?xml version=\"1.0\" ?>";
echo "<data>";
echo "<request type=\"pushcart\">";
echo "</request>";
	echo "<response its=\"success\">";
	echo "</response>";
echo "</data>";
?>
