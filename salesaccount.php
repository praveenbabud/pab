<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$db_error = "";
$reqtype = $_POST['reqtype'];
$index = 0;
$usedbookid = array();
$isbn13 = array();
$response = "";

if ((isset($_SESSION['email']) == TRUE) && (isset($_POST['email']) == TRUE) && ($_SESSION['email'] == $_POST['email']))
{
	if($reqtype == "salesaccount")
	{
		$email = $_SESSION['email'];
		echo "<br><div class=\"logoatp\" style=\"padding-left:5px;\"> Email ID : $email</div><br><div class=\"pabtab\"> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccount')\">Contact Info</div> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccountorders')\"> Your Orders </div> <div class=\"pabtabheading\" style=\"border-left:solid 1px #cccccc;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('sellbooks')\"> Sell Your BooKs</div><div class=\"pabtabheadingsel\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('salesaccount')\"> Sales Account</div></div>";

		if ((isset($_POST['courier']) == TRUE) && isset($_POST['trackingid']) == TRUE && isset($_POST['isbn13']) == TRUE)
		{
			$arrex = explode("#",$_POST['isbn13']);
			$id = $arrex[0];
			$isbn13 = $arrex[1];
			$response = "";
			$couriername  = rawurlencode($_POST['courier']);
			$trackingid = rawurlencode($_POST['trackingid']);
			$db_query = "update usedbooks set couriername='$couriername', trackingid='$trackingid', status=5 where id=$id and isbn13 like '$isbn13'";
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
				$response = "Update Successfull";
				$db_query = "select name, invoicenumber,email from usedbooks where id=$id";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					$db_row = mysqli_fetch_row($db_result);
					$sellername = $db_row[0];
					$invoicenumber = $db_row[1];
					$emailtoseller = "<div style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Hello $db_row[0], <br> <br>Thanks for shipping the Order : $db_row[1]. <br>Your check will be shipped with in 2 business days after the order is delivered. <br> <br>Support <br>PopAbooK.com</div>";
					sendemail($email, "PopAbooK:Order Status $db_row[1]",'support@popabook.com', 'pbd1PBD1', $emailtoseller);
					$db_query = "select name,email from orders where invoicenumber like '$db_row[1]'";
					$db_result = mysqli_query($link, $db_query);
					if ($db_result == TRUE)
                                        {
                                                $db_row1 = mysqli_fetch_row($db_result);
						$emailtobuyer = "<div style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Hello $db_row1[0], <br> <br>$sellername has shipped your order $invoicenumber using $couriername.<br>Tracking Id is $trackingid.<br><br>Support<br>PopAbooK.com</div>";
						sendemail($db_row1[1], "PopAbooK: Order Shipped $db_row[1]",'support@popabook.com', 'pbd1PBD1', $emailtobuyer);
					}
				}

			}
			else
				$response = "Update Failed. Please Try Again";
		}
			$db_query = "select id, isbn13, status, description, saleprice,couriername,trackingid from usedbooks where email like '$email' and status > 0";
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
				$db_row = mysqli_fetch_row($db_result);
				if ($db_row != null)
				{
				echo "<br><br><div class=\"atp\" style=\"clear:left;text-align:center;\">$response</div><br><br>";
				echo "<table width=\"100%\" cellpadding=\"10px\" border=\"1\">";
				while ($db_row != null)
				{
					echo "<tr><td align=\"left\" width=\"50%\" valign=\"top\"> <div class=\"boldatp\" style=\"text-align:center;\">BooK Details</div>";
					$isbn13 = $db_row[1];
					$title = "";
					$usedbookid = $db_row[0];
				       $db_result1 = mysqli_query($link, "select title from book_inventory where isbn13 like '$isbn13'");
				       if ($db_result1 == TRUE)
				       {
					       $db_row1 = mysqli_fetch_row($db_result1);
					       if ($db_row1 != null)
					       {
						       $title = $db_row1[0];
					       }
				       }
				       echo "<span class=\"atp\">";
				       echo "Title : $title";
                                       $dedesc = rawurldecode($db_row[3]);
				       echo "<br> ISBN : $isbn13 <br> Description : $dedesc<br>Sale Price : $db_row[4]</span></td>";
				       echo "<td width=\"50%\" valign=\"top\">";
				       echo "<div class=\"boldatp\" style=\"text-align:center;\">Status</div>";
				       if ($db_row[2] == 1)
					       echo "<span class=\"atp\"> <br>Your request for sale is being verified. Book will be available for purchase with in 24 hours. </span>";
				       else if ($db_row[2] == 2 || $db_row[2] == 3)
					       echo "<span class=\"atp\"> <br>Your book is available for purchase. We will inform you as soon as an order is placed for the book. </span>";
				       else if ($db_row[2] == 4)
				       {
					       echo "<span class=\"atp\"> <br>Your book has been ordered.Shipping details have been e-mailed. Please update the shipping details here.</span>";
					       echo "<form id=\"$db_row[0]#$isbn13\">Courier Name: <input type=\"text\" onKeyPress=\"return checkEnter(event,'salesaccount','$db_row[0]#$isbn13')\" name=\"courier\"> <br><br> Tracking Id : <input type=\"text\" onKeyPress=\"return checkEnter(event,'salesaccount','$db_row[0]#$isbn13')\" name=\"trackingid\"><br><br><div style=\"text-align:center;\"><input type=\"button\" value=\"Update\" onclick=\"updatesalesaccount('$db_row[0]#$isbn13')\"></div></form>";
				       }
				       else if ($db_row[2] == 5)
					       echo "<span class=\"atp\"> <br>Order shipped using $db_row[5]. Tracking Id is $db_row[6]. Your cheque will be shipped after the book is delivered.</span>";
				       else if ($db_row[2] == 6)
					       echo "<span class=\"atp\"> <br>Your book is received by the customer. Your cheque will has been couriered. Thanks for using PopAbooK.com</span>";
				       echo "</td></tr>";
					$db_row = mysqli_fetch_row($db_result);

				}
				echo "</table>";
				}
				else
				{
					echo "<br><br><div style=\"clear:left;padding-left:100px;\"><br><br>You do not have books that were put on sale.</div>";
				}
			}
			
	}
}
?>
