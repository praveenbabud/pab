<?php
require_once('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$session_id = session_id();

if (isset($_POST['orderid']))
{

	$orderid = $_POST['orderid'];
	$couriername = $_POST['couriername'];
	$trackingid = $_POST['trackingid'];
	$db_query = "update orders set orderstatus='Shipped', trackingid='$trackingid', couriername = '$couriername', shipdate = now() where invoicenumber like '$orderid'";
	$db_result = mysqli_query($link, $db_query);
        if ($db_result == TRUE)
	{
		$db_query = "select name,email from orders where invoicenumber like '$orderid'";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			if (mysqli_num_rows($db_result) > 0)
			{
				$db_row = mysqli_fetch_row($db_result);
				$emailmsg = "Hello $db_row[0], <br> <br>Your Order $orderid has been Shipped using $couriername.";
				if ($trackingid != "")
					$emailmsg = $emailmsg . "<br>Tracking Id is $trackingid.";
				$emailmsg = $emailmsg . "<br><br>Support <br>PopAbooK.com";
				sendemail($db_row[1], "PopAbooK.com Shipped Books! Order:$orderid", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
				echo "Update Successful and Email sent";
				echo "<br> $emailmsg";
			}
		}
	}
	else
	{
		echo "Update failed on $orderid";
	}
}
else
{
	echo "<form action=\"ordershipped.php\" method=\"POST\"> Enter order_id <input type=\"text\" name=\"orderid\"> <br>
		Enter Tracking <input type=\"text\" name=\"trackingid\"> <br> Enter courier name <input type=\"text\" name=\"couriername\"> <br> <input type=\"submit\"></form>";
}
?>
