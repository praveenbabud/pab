<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<link rel="shortcut icon" href="favicon.ico">

<head>
<title> Buy and Sell Books Online! New and Used TextBooks Reference Books Competitive Exam Books
</title>
<script type="text/javascript"
src="pab.js"></script>

<?php
echo "<script type=\"text/javascript\">";
require_once('dbconnect.php');
 $link = wrap_mysqli_connect();
 $session_id = session_id();
 echo "function loaduserdata() {";
 $found = 0;
if ($link != null)
 {
	 if (isset($_SESSION['email']))
	 {
		 $email = $_SESSION['email'];
		 $db_query = "select name, hno, street, village,city,state,pincode,telephone,address from customers where email like '$email'";
		 $db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		 {
			 $db_row = mysqli_fetch_row($db_result);
			 if ($db_row != null)
			 {
				 $found = 1;
				 echo "email = '$email';";
				 echo "name = '$db_row[0]';";
				 echo "hno = '$db_row[1]';";
				 echo "street = '$db_row[2]';";
				 echo "village = '$db_row[3]';";
				 echo "city = '$db_row[4]';";
				 echo "state = '$db_row[5]';";
				 echo "pincode = '$db_row[6]';";
				 echo "telephone = '$db_row[7]';";
				 echo "address = decodeURIComponent(\"$db_row[8]\");";
		
				 echo "var logindata = document.getElementById('logindata');";
				 echo "logindata.innerHTML='Hello $email';";
				 echo "var loginlogout = document.getElementById('loginlogout');";
				 echo "loginlogout.innerHTML = \"<span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer';this.style.textDecoration='none';\\\" onclick=\\\"allowlogout()\\\" onmouseout=\\\"this.style.textDecoration='underline';\\\">Logout</span>\";";
			 }
 		}
	 }
 }
 if ($found == 0)
 {
     echo "var logindata = document.getElementById(\"logindata\");";
				 echo "logindata.innerHTML=\"Hello\";";
				 echo "var loginlogout = document.getElementById(\"loginlogout\");";
				 echo "loginlogout.innerHTML = \"<span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer';this.style.textDecoration='none';\\\" onclick=\\\"allowlogin(0,'','')\\\" onmouseout=\\\"this.style.textDecoration='underline';\\\">Login</span>\";";
 }
 else
 {
	 echo "addloadfile(\"login.php\");";

 }

 echo "}";
 echo "function loadscart() {";
 
  if ($link != null)
  {
    $db_query = "select quantity, isbn13 from shopping_carts where session like '$session_id'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
	{
	   $index = 0;
	   $db_row = mysqli_fetch_row($db_result);
	   while ($db_row != null)
	   {
		   $index = 1;
		   $db_query1 = "select title,ourprice,listprice,shiptime from book_inventory where isbn13 like '$db_row[1]'";
		   $db_result1 = mysqli_query($link, $db_query1);
		   if ($db_result1 == TRUE)
		   {
			   $db_row1 = mysqli_fetch_row($db_result1);
			   if ($db_row1 != null)
			   {
				   if (file_exists("/var/apache2/2.2/htdocs/" . $db_row[1] . ".jpg"))
					   echo "addtocartonload('" . $db_row[1] . ".jpg'," . $db_row1[2] . ",$db_row1[1],'" . $db_row[1] . "',\"$db_row1[0]\",$db_row[0],'$db_row1[3]');";
				   else
				   	echo "addtocartonload('ina.jpg'," . $db_row1[2] . ",$db_row1[1],'" . $db_row[1] . "',\"$db_row1[0]\",$db_row[0],'$db_row1[3]');";

			   }
		   }
		   $db_row = mysqli_fetch_row($db_result);
	   }
	   if ($index == 1)
	   {
		echo "addloadfile(\"search.js\");";
	   	echo "showcart();";
	   }
	}
  }

  echo "}";

echo "function loadhomedata() {";

	echo "userat = \"home\";";
	echo " getData(\"home\");}";

echo "</script>";
	if ($found == 1)
	{
		echo "<script type=\"text/javascript\" src=\"login.js\"></script>";
	}
	if ($index == 1)
	{
		echo "<script type=\"text/javascript\" src=\"search.js\"></script>";
	}
?>
			
</head>

<body onload="doitonload()">
<table width="100%">
<tr>
<td><div style="z-index:1;"><img src="popabook.jpg" onmouseover="this.style.cursor='pointer';" onclick="loadhomedata()"></div></td>
<td align="center">
<form name="searchform" >
<input type="text" size="50" value="" name="search_string" onKeyPress="return checkEnter(event,'search')"/>
<input class="blueatp" type="button" id="searchbutton" name="searchbutton1"value="search" onclick="getData('search')" />
</form></td>
<td class="atp" align="left" width="20%">
<table> 

<tr><td><span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="loadhomedata()" onmouseout="this.style.textDecoration ='underline'">Home</span> | <span class="ublueatp" onclick="contactus()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onmouseout="this.style.textDecoration ='underline'">Contact Us</span> | <span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="getData('help')" onmouseout="this.style.textDecoration ='underline'"> Help </span></td></tr>
<tr><td><span id="login" class="atp"><span id="logindata">
Hello
</span> </span</td></tr>

<tr><td>
 <span id="loginlogout">Login</span> | <span class="ublueatp" onclick="showuseraccount()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onmouseout="this.style.textDecoration ='underline'">Your Account</span>
</td></tr>
</table>
</td>

</tr>

</table>
<hr>
<div style="width:100%">
<table width="100%" height="100%">
<tr>
<td id="leftside" valign="top">

<div id="leftsidedata" class="atp">
</div>
<br><br>
<div id="payments" class="atp" style="width:120px;border:solid 1px;text-align:center;">
<span class="boldatp"> Payments </span><hr> <br>
1. By Cash (VPP)<br><br>
<img src="india-post-logo.jpg"/><br><br>
2. Online <br><br>
<a href="http://www.ebs.in/" target="_blank"><img width="100px" height="100px" src="EBS_Flasher1.gif" title="EBS" border="0"></a>
<br><br>

</div>
<br><br>
<div id="security" class="boldatp" style="width:120px;border:solid 1px;text-align:center;">
Secured By <hr><br>
<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=w9FOkLHyzZ066mrdpFmp7sXeOeOj9XTsRUqcveO7edaoQ74M6nPhnBN"></script><br/></span>
</div>
</td>
<td id="maincenter" valign="top">

<div id="center">
<div id="centerdata">
<?php
  require_once('homedata.php');
  require_once('invoice.php');
$link = wrap_mysqli_connect();
$emailmsg = "";
if(isset($_SESSION['invoicenumber'])) 
{
	$invoicenumber = $_SESSION['invoicenumber'];
	unset($_SESSION['invoicenumber']);
	if ($_SESSION['ResponseCode'] == 0)
	{
		$emailmsg = generateinvoice($link, $invoicenumber, $_SESSION['PaymentID']);
	     echo $emailmsg; /*payment is successfull*/
	}
}
if ($emailmsg == "")
{
    homedata($link);
}
?>
</div>
</div>
</td>
<td id="rightside" valign="top">
<div class="atp">
<div id="cartitems" style="font-weight:bold">

</div>

<div id="realcart" style="height:50%;overflow:auto;"> </div>
<div id="carttotal">
</div>

<div id="confirmorder">
</div>


 
      



</div>
</td>
</tr>
</table>
</div>
<div id="footer" style="text-align:center;">
<hr><span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="loadhomedata()">Home</span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('copyright','')"> Copyright </span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('termsconditions')"> Terms and Conditions</span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('privacypolicy')"> Privacy Policy</span>

</div>
<a href="http://www.ebs.in/" onclick="false"><div onclick="getData('search','computer')">EBS</div> </a>
<div style="position:absolute;top:10px;left:10px;width:0px;height:0px;z-index:0;">
Hello Praveen
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12128118-1");
pageTracker._trackPageview();
} catch(err) {}</script>


</body>


</html>
