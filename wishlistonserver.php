<?php
session_start();
require('dbconnect.php');
$link = wrap_mysqli_connect();
/*if (isset($_POST['reqtype']) == TRUE &&  isset($_POST['isbn13']) == TRUE && isset($_POST['email']) == TRUE && isset($_SESSION['email']) == TRUE)*/
/*if ((isset($_POST['reqtype']) == TRUE) &&  (isset($_POST['isbn13']) == TRUE) && (isset($_POST['email']) == TRUE)) */
	/*	&& isset($_SESSION['email']) == TRUE)*/
{
/*	if ($_POST['email'] == $_SESSION['email']) */
	{

		$reqtype = $_POST['reqtype'];
		$isbn13 = $_POST['isbn13'];
		$email = $_POST['email'];
		$db_query = "select customer_id from customers where email like '$email'";
		$db_result = mysqli_query($link,$db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
				$db_query = "";
				$customerid = $db_row[0];
				if ($reqtype == 'addtowishlist')
				{
					$db_query = "insert into wishlists (customerid, isbn13) values ($customerid,'$isbn13')";
				}
		                else if ($reqtype == 'removefromwishlist')
				{
					$db_query = "delete from wishlists where customerid=$customerid and isbn13 like '$isbn13'";
				}
				if ($db_query != "")
				{
					$db_result = mysqli_query($link, $db_query);
				}	
			}
		}
	}
}
?>
