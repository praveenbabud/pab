<?php
session_start();
require('dbconnect.php');

if (isset($_POST['reqtype']) == FALSE)
exit;
	$reqtype = $_POST['reqtype'];
	$isbn13 = $_POST['isbn13'];
	$session_id = session_id();
	if ($reqtype == 'addtocart' || $reqtype == 'updatecart')
	{
		$quantity = $_POST['quantity'];
	}
	$link = wrap_mysqli_connect();
	$db_error = "";
	if ($link == null)
	{
		$return = 'failure';
		$db_error = "Server is Temporarily Unavailable";
	}
	else
	{
		$query = "";
		if ($reqtype == 'addtocart')
		{
				$query = "insert into shopping_carts (session,isbn13,quantity) values ('$session_id', '$isbn13', '$quantity')";
		}
		else if ($reqtype == 'updatecart')
		{
			$query = "update shopping_carts set quantity = '$quantity' where session like '$session_id' and isbn13 like '$isbn13'"; 
		}
		else if ($reqtype == 'deletefromcart')
		{
			$query = "delete from shopping_carts where session like '$session_id' and isbn13 like '$isbn13'";
		}
		else
		{
			$return = 'failure';
			$db_error = "Server Could not process Inputs";	
		}
		if ($query != "")
		{
			$result = mysqli_query($link, $query);
			if ($result == TRUE)
			{
				$return = 'success';
			}
			else
			{
				$return = 'failure';
			}
		}
	}
	echo "<?xml version=\"1.0\" ?>";
	echo "<data>";
	if ($reqtype == 'addtocart')
	{
		echo "<request type=\"addtocart\">";
	}
	else if ($reqtype == 'updatecart')
	{
		echo "<request type=\"updatecart\">";
	}
	else if ($reqtype == 'deletefromcart')
	{
		echo "<request type=\"deletefromcart\">";
	}
	else
	{
		echo "<request type=\"$reqtype\">";
	}
	echo "</request>";
	echo "<response its=\"$return\">";
   	if ($return == 'failure')
	{
		echo "<message>";
		echo $db_error;
		echo "</message>";
	}
	echo "</response>";
	echo "</data>";
?>
