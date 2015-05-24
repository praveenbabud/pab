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

if($reqtype == "myaccount")
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
		   $db_query = "select customer_id, name, address, city, pincode, state, telephone  from customers where email like '$pemail'";
		   $db_result = mysqli_query($link, $db_query);
		   if ($db_result == TRUE)
		   {
			   $db_row = mysqli_fetch_row($db_result);
			   if ($db_row != null)
			   {
				   $address = urldecode($db_row[2]);
				   echo "<br><div class=\"logoatp\" style=\"padding-left:5px;\"> Email ID : $pemail</div><br><div class=\"pabtab\"> <div class=\"pabtabheadingsel\" onclick=\"showuseraccount('myaccount')\">Contact Info</div> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccountorders')\"> Your Orders </div> <div class=\"pabtabheading\" style=\"border-left:solid 1px #cccccc;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('sellbooks')\">Sell BooKs</div><div class=\"pabtabheading\" style=\"border-left:solid 1px #cccccc;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" onclick=\"showuseraccount('salesaccount')\">Sales Account</div></div>";
			   	  echo "<div id=\"myaccountdata\" style=\"padding-left:20px;clear:left;\"><p style=\"text-align:left;\"><span class=\"atp\">";
				  echo "<br><br><table><tr> <td>Name :</td> <td> $db_row[1]</td></tr>";
			      echo "<tr><td>Address :</td> <td>$address</td></tr>";
			      echo "<tr><td>City :</td> <td> $db_row[3]</td></tr>";
			      echo "<tr><td>Pincode : </td> <td>$db_row[4]</td></tr>";
			      echo "<tr><td>State :</td> <td>$db_row[5]</td></tr>";
			      echo "<tr><td>Telephone :</td> <td> $db_row[6]</td></tr>";
				  echo "<tr><td>Email :</td> <td> $pemail </td></tr>";
				  echo "<tr><td>Country :</td> <td> India </td></tr></table>";
				  echo "</span></p>";
			   }


		   }
	   }
  }
}

?>

