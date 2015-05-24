<?php
session_start();
$rootloc = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<link rel="shortcut icon" href="favicon.ico">

<head>
<LINK REL=StyleSheet HREF="style.css" TYPE="text/css">
<?php
require_once('dbconnect.php');
 $link = wrap_mysqli_connect();
 $session_id = session_id();
require_once('browsecatalog.php');
$browsecatalog = "";
$title = "";
$metadata = "";
	if (isset($_GET['browse']) == TRUE)
	{
		$browsecatalog = $_GET['browse'];
		$dbstr = rawurlencode($browsecatalog);
		mysqli_query($link,"insert into sessions_searches (session,search) values ('$session_id','$dbstr')");
		$browseoutput = browsecatalog($link, $browsecatalog, $title, $metadata);
	}
	if ($title == "")
		echo "<title>PopAbooK.com Buy and Sell Books Online India! New and Used TextBooks Reference Books Competitive Exam Books</title>";
	else
		echo "$title";
	if ($metadata == "")
	{
		echo "<meta name=\"keywords\" content=\"Textbooks, Reference books, competitive exam books, technical and professional books\">";
		echo "<meta name=\"Description\" content=\"Buy and Sell textbooks reference books comptetitive exam books professional books\">";
	}
	else
		echo $metadata;
echo "<script type=\"text/javascript\" src=\"pab.js\"></script>";
echo "<script type=\"text/javascript\" src=\"login.js\"></script>";
echo "<script type=\"text/javascript\" src=\"search.js\"></script>";
echo "<script type=\"text/javascript\">";
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
     echo "var logindata = document.getElementById(\"logindata\");\n";
				 echo "logindata.innerHTML=\"\";\n";
				 echo "var loginlogout = document.getElementById(\"loginlogout\");\n";
				 echo "loginlogout.innerHTML = \"<span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer';this.style.textDecoration='none';\\\" onclick=\\\"allowlogin(0,'','')\\\" onmouseout=\\\"this.style.textDecoration='underline';\\\">Login</span>\";";
 }
 else
 {
	 echo "addloadfile(\"login.js\");";

 }

 echo "}";
 echo "function loadscart() {";
 $scart = 0; 
  if ($link != null)
  {
    $db_query = "select quantity, isbn13 from shopping_carts where session like '$session_id'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
	{
	   $db_row = mysqli_fetch_row($db_result);
	   while ($db_row != null)
	   {
		   $scart = 1;
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
	   if ($scart == 1)
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
?>
			


</head>

<body onload="doitonload()">
<div class="container">
<table width="100%" align="center">
<tr>
<td align="left" valign="top"><div > <img src="popabook.jpg" onmouseover="this.style.cursor='pointer';" onclick="loadhomedata()"> </div></td>
<td align="right" valign="top">
<div style="text-align:right;">
<span id="logindata" class="atp"></span> <span id="loginlogout" class="atp">Login</span> | <span class="ublueatp" onclick="showuseraccount()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onmouseout="this.style.textDecoration ='underline'">Your Account</span> | <span class="ublueatp" onclick="contactus()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onmouseout="this.style.textDecoration ='underline'">Contact Us</span> | <span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="getData('help')" onmouseout="this.style.textDecoration ='underline'">Help</span> <span> &nbsp;</span>
</div> </td>
</tr>
</table>

<div class="divider">
<table width="100%"><tr><td align="center" valign="top">
<a href="browse" style="color:#ffffff" onmouseover="browse(event)"><span class="whitebox"><span class="logoatp">Browse All</span></span></a></td><td align="right" valign="center">
<form name="searchform" ><span class="logoatp">Search Books:</span>
<input type="text" size="50" value="" name="search_string" onKeyPress="return checkEnter(event,'search')"/>

</form>
</td><td align="left" valign="top"><div class="whitebox" style="text-align:center" onclick="getData('search')" onmouseover="this.style.cursor='pointer';"><span class="logoatp">SEARCH</span></div></td></tr></table>
<div class="dropdown"> </div>
</div>
<div style="text-align:left;"> <div class="dropdown" id="browse" onmouseover="browse(event)" onmouseout="funbrowse()" style="color:blue;background-color:#ffffff;width:300px;text-align:left;top:0px;left:0px;height:100px;z-index:-1;border-bottom:1px solid #dddddd;border-right:1px solid #dddddd;border-left:1px solid #dddddd;"><span class="ublueatp" onclick="getData('browse','higher-education')">Higher Education </span> PreSchool Professionals Competitive Exams <br> Higher Education PreSchool Professionals Competitive Exams<br> Higher Education PreSchool Professionals Competitive Exams</div><div class="dropdown" style="top:-100px;"> 

<table> <tr> <td class="left" valign="top">

<div id="maincenter" style="width:100%;text-align:left;">
<div id="center">
<div id="centerdata" style="text-align:left;">
<?php
  require_once('homedata.php');
	require_once('invoice.php');
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
	if ($browsecatalog != "")
	        echo $browseoutput;	
	else
    		homedata($link);
}
?>
</div>
</div>
</div>

</td>
<td class="right" valign="top">
<div id="rightside" class="atp" style="width:100%;text-align:left;">
</div>
</td></table>
</div> 

 

</div>
<div class="pabheading">
<table width="100%"><tr> <td align="center">
<span class="logoatp"> <br>Payments Powered By<br><br> </span>
<table align="center"><tr> <td><span class="atp"> 1. Credit Cards, Debit Cards, Cash Cards and Internet Banking By </span></td> <td> &nbsp;</td><td> <a href="http://www.ebs.in/" target="_blank"><img src="EBS_Flasher1.gif" title="EBS" border="0"></a> </td> </tr><tr> <td> <span class="atp"> 2. Cash on Delivery By </span> </td><td> &nbsp;</td> <td> <img src="india-post-logo.jpg"/> </td></tr>
 </table>
</td>
<td align="center" valign="top"><div style="border-left:1px solid #dddddd;border-bottom:1px solid #dddddd;">
<br><span class="logoatp">Site Secured By </span><br><br>
<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=w9FOkLHyzZ066mrdpFmp7sXeOeOj9XTsRUqcveO7edaoQ74M6nPhnBN"></script><br/></span> </div>
</td> </tr>
</table>


</div>
<div id="footer" class="pabheading" style="text-align:center;">
<span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="loadhomedata()">Home</span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('copyright','')"> Copyright </span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('termsconditions')"> Terms and Conditions</span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('privacypolicy')"> Privacy Policy</span>

</div>
</div>

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
