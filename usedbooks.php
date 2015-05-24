<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
$link = wrap_mysqli_connect();
$db_error = "";
$reqtype = $_POST['reqtype'];

if($reqtype == "getusedbooks")
{
    $isbn13 = $_POST['isbn13'];

	$db_result = mysqli_query($link, "select from usedbooks where isbn13 like '$isbn13'");
	if ($db_result == TRUE)
	{
		$num_rows = mysqli_num_rows($db_result);
		while ($num_row != 0)
		{
			$db_row = mysqli_fetch_row($db_result);
			echo "<table
		}
	}
    if (isset($_SESSION['email']) == TRUE)
	{
		$email = $_SESSION['email'];
		$isbn13 = $_POST['isbn13'];
		$purchasedate = $_POST['purchasedate'];
		$sellprice = $_POST['sellprice'];
		$mybookid = $_POST['mybookid'];
		$description = $_POST['description'];
		$db_result = mysqli_query($link, "select customer_id from customers where email like '$email'");
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
				$customerid = $db_row[0];
				$db_result = mysqli_query($link, "select customerid from mybooks where id = $mybookid");
				if ($db_result == TRUE)
				{
					$db_row = mysqli_fetch_row($db_result);
					if ($db_row != null)
					{
						if ($customerid == $db_row[0])
						{
							$description = rawurlencode($description);
							$db_result = mysqli_query($link, "insert into usedbooks (mybookid, isbn13, purchasedate, orderid, saleprice, description) values ($mybookid,'$isbn13', '$purchasedate', '$orderid', $sellprice, '$description')");
							if ($db_result == TRUE)
							{
								$db_result = mysqli_query($link, "update mybooks set status='On Sale at INR $sellprice' where id = $mybookid");
								$db_result = mysqli_query($link, "update book_inventory set usedbookcount = usedbookcount + 1 where isbn13 like '$isbn13'");
								echo "On Sale at INR $sellprice";

					    		}
						}
					}
				}
			}
		}
		

	}
}
?>
