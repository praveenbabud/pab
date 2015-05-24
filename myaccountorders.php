<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
$link = wrap_mysqli_connect();
$db_error = "";
$pemail = "";
$semail = "";
$reqtype = $_POST['reqtype'];
$order_id_list = array();
$order_date_list = array();
$paymentstatus = array();
$paymentmode = array();
$orderstatus = array();
$shipcost = array();
$order_id_list_index = 0;
$xmlorders = "";

if($reqtype == "myaccount" || $reqtype == "myaccountorders")
{
  if($link != null)
  {
       if (isset($_POST['email']))
       {
       		$pemail = $_POST['email'];
	   }
	   if (isset($_SESSION['email']))
	   {
		   $semail = $_SESSION['email'];
	   }
	   if ($pemail == $semail && $pemail != "")
	   {
		   echo "<br><div class=\"logoatp\" style=\"padding-left:5px;\"> Email ID : $pemail</div><br><div class=\"pabtab\"> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('myaccount')\">Contact Info</div> <div class=\"pabtabheadingsel\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccountorders')\"> Your Orders </div> <div class=\"pabtabheading\" style=\"border-left:solid 1px #cccccc;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('sellbooks')\"> Sell BooKs</div>";
		   echo "<div class=\"pabtabheading\" style=\"border-left:solid 1px #cccccc;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('salesaccount')\">Sales Account</div>";
		   
		   echo "</div>";

		   $db_query = "select invoicenumber,date_time,orderstatus, paymentstatus,shipcost,paymode from orders where email like '$pemail' order by date_time desc";
       		$db_result = mysqli_query($link, $db_query);
       		if ($db_result == TRUE)
			{
				$norders = mysqli_num_rows($db_result);
           		if($norders > 0)
				{
					$ind = 0;
					while ($ind < $norders) 
					{
						$db_row = mysqli_fetch_row($db_result);
						$order_id_list[$order_id_list_index] = $db_row[0];
						$order_date_list[$order_id_list_index] = $db_row[1];
						$orderstatus[$order_id_list_index] = $db_row[2];
						$paymentstatus[$order_id_list_index] = $db_row[3];
						$shipcost[$order_id_list_index] = $db_row[4];
						$paymentmode[$order_id_list_index] = $db_row[5];
						$ind = $ind + 1;
						$order_id_list_index = $order_id_list_index + 1;
					}
           		}
       		}
       		else
       		{
           		$db_error = "Authentication Server is Temporarily Unavailable. Please Try Later";
			}
	   }
	   if ($db_error == "")
	   {
                   if ($norders > 0)
		   echo "<div style=\"clear:left;padding-left:100px;\"><p class=\"atp\"> <span class=\"boldatp\"><br><br>Your Orders</span></p> <p class=\"atp\" style=\"width:95%;\">";
                   else
		   echo "<div style=\"clear:left;padding-left:100px;\"><p class=\"atp\"> <span class=\"atp\"><br><br>You do not have orders in your account.</span></p> <p class=\"atp\" style=\"width:95%;\">";
                       
			$ind = 0;
	 		while ($ind < $norders)
			{
				$db_query = "select title, quantity,ourprice from order_details where invoicenumber='$order_id_list[$ind]'";
				$db_result = mysqli_query($link,$db_query);
				$total = 0;
				if ($db_result == TRUE)
				{
					$xmlorders = $xmlorders . "<order>" . "<date>" . $order_date_list[$ind] . "</date>";

					$xmlorders = $xmlorders . "<invoicenumber>" . $order_id_list[$ind] . "</invoicenumber>";
					$xmlorders = $xmlorders . "<payment>" . $paymentstatus[$ind] . "</payment>";
					$xmlorders = $xmlorders . "<orderstatus>" . $orderstatus[$ind] . "</orderstatus>";
					echo "<span class=\"boldatp\">Invoice Number : $order_id_list[$ind] </span><br>";
					echo "<span class=\"atp\"> Order Date : $order_date_list[$ind] <br>";
					echo "Order Status : $orderstatus[$ind] <br>";
					echo "Payment Mode : $paymentmode[$ind] <br>";
					echo "Payment Status : $paymentstatus[$ind]</span><br>";
						echo "<br><span class=\"boldatp\"> Items </span><br><br>";
					$db_row = mysqli_fetch_row($db_result);
					while($db_row != null)
					{
						$dt = $db_row[0];
						$dq = $db_row[1];
						$op = $db_row[2];
						$price = $dq * $op;
						$total = $total + $price;
						$xmlorders = $xmlorders . "<item>" . "<title>" . $dt . "</title>" . "<quantity>" . $dq . "</quantity>" . "<price>" . $price . "</price>" . "</item>";
						echo "$dq of $dt at Rs. $price <br>";

						$db_row = mysqli_fetch_row($db_result);
						
					}
				}
				else
				{
					$db_error = "Server is Temporarily Unavailable. Please Try Later.";
					break;
				}
					$total = $total + $shipcost[$ind];
				$xmlorders = $xmlorders . "<total>" . $total . "</total>" . "</order>";
				echo "<br><span class=\"boldatp\">Grand Total is Rs. $total </span><br>";
				echo "<hr align=\"left\" width=\"95%\">";
				$ind = $ind + 1;
			}
	                echo "</p></div>";		
	   }
  }
  else
  {
	  $db_error = "Server is Temporarily Unavailable. Please Try Later.";
  }
/*  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\" ?";
  echo ">";
  echo "<data>";
  echo "<request type=\"myaccount\">";
  echo "<email>";
  echo $pemail;
  echo "</email>";
  echo "</request>";
  
  if ($db_error !="")
  {
    echo "<response its=\"failure\">";
    echo "<message>";
    echo $db_error;
    echo "</message>";
    echo "</response>";
  }
  else
  {
	  echo "<response its=\"success\">";
	  echo $xmlorders;
    echo "</response>";
  }
  echo "</data>";*/
}

?>

