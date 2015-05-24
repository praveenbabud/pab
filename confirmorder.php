<?php
header("Content-type: text/xml");
session_start();
$session_id = session_id();
require('dbconnect.php');
require('sendemail.php');
require_once('invoice.php');
$link = wrap_mysqli_connect();
$email = "";
$customer_id = "";
$db_error = "";
$email = "";
$invoicenumber = "";
$grandtotal = 0;
$paymode = $_POST['paymode'];
$address = "";
$coupon = "";
$pcn = "";
$_POST['pcn'] = "xyz";
$paymode = "cash";
if ($link != null)
{
	if (isset($_SESSION['email']))
    {
		$email = $_SESSION['email'];
	}
	if ($email != "")
	{
		$db_query = "select customer_id from customers where email like '$email'";
		$db_result = mysqli_query($link, $db_query);
		if($db_result == TRUE)
		{
			if (mysqli_num_rows($db_result) > 0)
			{
				$db_row = mysqli_fetch_row($db_result);
				$customer_id = $db_row[0];
			}
		}
		else
		{
			$db_error = "Server is Temporarily Unavailable. Please Try Again Later.";
		}	
	}
	else if (isset($_POST['email']))
	{
		$email = $_POST['email'];
	}
	else
	{
		$db_error = "E-Mail is required to process the order";
	}
        if ($db_error == "")
        {
            if (isset($_POST['pcn']) == TRUE && $paymode == "cash")
            {
                $pcn = $_POST['pcn'];
                if (verifypcn($pcn) == FALSE)
                   $db_error = "Please use a valid pabCard number"; 
            }
        }
	if ($db_error == "")
	{
		$db_query = "select now()";
		$db_result = mysqli_query($link, $db_query);
		$db_row = mysqli_fetch_row($db_result);

		$datetime = $db_row[0];
		if (isset($_POST['hno']))
			$hno = $_POST['hno'];
		else
			$hno = "";
		if (isset($_POST['street']))
			$street = $_POST['street'];
		else
			$street = "";
		if (isset($_POST['village']))
			$village = $_POST['village'];
		else
			$village = "";
    	if (isset($_POST['city']))
			$city = $_POST['city'];
		else
			$city = "";
		if (isset($_POST['state']))
			$state = $_POST['state'];
		else
			$state = "";
		if (isset($_POST['telephone']))
			$telephone = $_POST['telephone'];
		else
			$telephone = "";
		if (isset($_POST['pincode']))
			$pincode = $_POST['pincode'];
		else
			$pincode = "";
		if (isset($_POST['name']))
			$name = $_POST['name'];
		else
			$name = "";
		if (isset($_POST['address']))
			$address = $_POST['address'];
		else
			$address = "";
		if (isset($_POST['shiptime']))
			$shiptime = $_POST['shiptime'];
		else
			$shiptime = "";
		$invoicenumber = time() . "-" . mt_rand(1,999999);
		$address = rawurlencode($address);
		if (isset($_POST['shipcost']) == TRUE)
		$shipcost = $_POST['shipcost'];
		if (isset($_POST['total']) == TRUE)
                $grandtotal = $_POST['total'];
		if (isset($_POST['coupon']) == TRUE)
                $coupon = $_POST['coupon'];
                $affiliate = "";
                if (isset($_SESSION['__PABaffiliate']) == TRUE)
                    $affiliate = $_SESSION['__PABaffiliate'];
                $afftype = "";
                if (isset($_SESSION['__PABafftype']) == TRUE)
                    $afftype = $_SESSION['__PABafftype'];
                $monthyear = date('M-Y');

		$db_query = "insert into orders (invoicenumber,date_time, name, hno,street,village,city,state,pincode,email,telephone,paymentstatus,orderstatus,paymode,address,shiptime,shipcost,session,affiliate,afftype,monthyear,total,coupon,pcn) values ('$invoicenumber','$datetime','$name', '$hno', '$street','$village','$city','$state','$pincode','$email','$telephone','Pending','Not Shipped','$paymode','$address','$shiptime','$shipcost','$session_id','$affiliate', '$afftype','$monthyear',$grandtotal,'$coupon','$pcn')";
		$db_result = mysqli_query($link,$db_query);



		if ($db_result == FALSE)
		{
			$db_error = "Server is Temporarily Available";
		}
	}
	if ($db_error == "" && $customer_id != "")
	{
		$db_query = "update customers set name='$name', city='$city', state='$state', pincode='$pincode', email='$email', telephone='$telephone', address='$address' where customer_id = '$customer_id'";
		$db_result = mysqli_query($link,$db_query);
		if ($db_result == FALSE)
		{
			$db_error = "Server is Temporarily Available";
		}
	}
	$writelock = 0;
	if ($db_error == "")
	{
		$db_query = "lock tables book_inventory read, usedbooks write";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == FALSE)
		{
		     mysqli_query($link,"unlock tables");
		     exit_error("Server is Temporarily Unavailable. Please Try Again",$email);
		      exit;
		}
		$counter = 1;
		while ($counter > 0)
		{
			$isbn13name = $counter . "isbn13";
			if(isset($_POST[$isbn13name]))
			{
				$isbn13 = $_POST[$isbn13name];
				$sisbn = explode("#",$isbn13);
				if (count($sisbn) == 2)
				{
                                        $writelock = 1;
					$db_query = "select status from usedbooks where id=$sisbn[0]";
					$db_result = mysqli_query($link, $db_query);
					if ($db_result == TRUE)
					{
						$db_row = mysqli_fetch_row($db_result);
						if ($db_row != null)
						{
							if ($db_row[0] != 2)
							{
									mysqli_query($link,"unlock tables");
									exit_error("Out Of Stock;$isbn13",$email);
									exit;
							}
						}
					}
					else
					{
						mysqli_query($link,"unlock tables");
						exit_error("Server is Temporarily Unavailable. Please Try Again",$email);
						exit;
					}
				}
                                else
                                {
                                      $isbn13 = $_POST[$isbn13name];
                                      $db_query = "select shiptime from book_inventory where isbn13 like '$isbn13'";
                                      $db_result = mysqli_query($link, $db_query);
                                      if ($db_result == TRUE)
                                      {
                                          $db_row = mysqli_fetch_row($db_result);
                                          if ($db_row != null)
                                          {
                                               if ($db_row[0] == 'Out Of Stock')
                                               {
							mysqli_query($link,"unlock tables");
						    exit_error("Out Of Stock;$isbn13",$email);
						exit;
                                               }
                                          }
                                      }
                                }
			}
			else
				break;
			$counter = $counter + 1;
		}
		if ($writelock == 1)
		{	
			$counter = 1;
			while ($counter > 0)
			{
				$isbn13name = $counter . "isbn13";
				if(isset($_POST[$isbn13name]))
				{
					$isbn13 = $_POST[$isbn13name];
					$sisbn = explode("#",$isbn13);
					if (count($sisbn) == 2)
					{
						$db_query = "update usedbooks set status=3,invoicenumber='$invoicenumber' where id=$sisbn[0]";
						$db_result = mysqli_query($link, $db_query);
						if ($db_result == FALSE)
                                                {
							mysqli_query($link,"unlock tables");
							exit_error("Server is Temporarily Unavailable. Please Try Again",$email);
							exit;
                                                }
					}
				}
                                else
                                     break;
				$counter = $counter + 1;
 			}
		}
		mysqli_query($link, "unlock tables");
		$writelock = 0;
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
				$isbn13 = $_POST[$isbn13name];
			else
				break;
			if(isset($_POST[$isbn10name]))
				$isbn10 = $_POST[$isbn10name];
			else
				$isbn10 = "";
			if(isset($_POST[$quantityname]))
				$quantity = $_POST[$quantityname];
			else
				$quantity = 0;
			if(isset($_POST[$titlename]))
				$title = $_POST[$titlename];
			else
				$title = "";
			if(isset($_POST[$ourpricename]))
				$ourprice = $_POST[$ourpricename];
			else
				$ourprice = 0;
			if(isset($_POST[$listpricename]))
				$listprice = $_POST[$listpricename];
			else
				$listprice = 0;
			$db_query = "insert into order_details (invoicenumber, isbn13, isbn10, quantity, title, ourprice ,listprice) values ('$invoicenumber','$isbn13','$isbn10',$quantity,\"$title\",$ourprice,$listprice)";
			$db_result = mysqli_query($link, $db_query);
			$counter = $counter + 1;
		}

                if($paymode == "cash")
		{
			$db_query = "delete from shopping_carts where session like '$session_id'";
			$db_result = mysqli_query($link, $db_query);
		}
	}
}
else
{
	$db_error = "Server is Temporarily Unavailable. Please Try Again";
}

if ($db_error == "")
{
	if ($paymode == "cash")
	{
		header("Content-type: text/html");
		$emailmsg = generateemailinvoice($link, $invoicenumber,"");
		sendemail($email, "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
		sendemail('praveen@popabook.com', "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
		$emailmsg = generateinvoice($link, $invoicenumber, ""); 
		echo $emailmsg;
	}
        else
        {
		header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" ?>";
		echo "<data>";
		echo "<request type=\"confirmorder\">";
		echo "<email>";
		echo $email;
		echo "</email>";
		echo "</request>";
		echo "<response its=\"success\">";
		echo "<invoice>";
		echo "<orderid>";
		echo $invoicenumber;
		echo "</orderid>";
		echo "<shippingdays>";
		echo "5";
		echo "</shippingdays>";
		echo "</invoice>";
		echo "</response>";
                echo "</data>";
       }
}
else
{
    if ($paymode == 'online')
    {
        header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" ?>";
		echo "<data>";
		echo "<request type=\"confirmorder\">";
		echo "<email>";
		echo $email;
		echo "</email>";
		echo "</request>";
	echo "<response its=\"failure\">";
	echo "<message>";
	echo $db_error;
	echo "</message>";
	echo "</response>";
echo "</data>";
    }
    else
    {
		header("Content-type: text/html");
                echo "<div style=\"padding-left:25px;\"><br><br><br>$db_error</div>";
    }
}
function exit_error($db_error,$email)
{
	 header("Content-type: text/xml");
		echo "<?xml version=\"1.0\" ?>";
		echo "<data>";
		echo "<request type=\"confirmorder\">";
		echo "<email>";
		echo $email;
		echo "</email>";
		echo "</request>";
	echo "<response its=\"failure\">";
	echo "<message>";
	echo $db_error;
	echo "</message>";
	echo "</response>";
echo "</data>";
}
function verifypcn($pcn)
{
    return FALSE;
}
?>
