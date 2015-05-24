<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$rootloc = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:fb="http://www.facebook.com/2008/fbml" lang="en" xml:lang="en">
<head>
<LINK REL=StyleSheet HREF="stylev4.css" TYPE="text/css">
<link rel="shortcut icon" href="favicon2.ico">
<?php
require_once('dbconnect.php');
require_once('sendemail.php');
$store = "";
$link = wrap_mysqli_connect();
$session_id = session_id();
require_once('browsecatalog.php');
$browsecatalog = "";
$title = "";
$metadata = "";
	if ($title == "")
		echo "<title>PopAbooK.com : Welcome Affiliates.</title>";
	else
		echo "$title";
	if ($metadata == "")
	{
                echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />";
		echo "<meta name=\"keywords\" content=\"Textbooks, Reference books, competitive exam books, technical and professional books\">";
		echo "<meta name=\"Description\" content=\"Buy New and Used Books Online in India. \">";
	}
	else
        {
                echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />";
		echo $metadata;
	}
?>
<script type="text/javascript">

function verifySeal() {
		var bgHeight = "433";
		var bgWidth = "536";
		var url = "https://seal.godaddy.com:443/verifySeal?sealID=w9FOkLHyzZ066mrdpFmp7sXeOeOj9XTsRUqcveO7edaoQ74M6nPhnBN";
		window.open(url,'SealVerfication','location=yes,status=yes,resizable=yes,scrollbars=no,width=' + bgWidth + ',height=' + bgHeight);
}
function validateinput(formname)
{
	theform = document.getElementById(formname);
	if (formname == 'affjoin')
	{
		if (theform.elements[0].value != "")
		{
			if(validateEmail(theform.elements[0].value) == false)
			{
			   alert("Invalid input for Email");
			   theform.elements[0].focus();
			   return false;
			}

		}
		else
		{
			   alert("Enter E-Mail Address");
			   theform.elements[0].focus();
			   return false;
		}
		if (theform.elements[1].value == "")
		{
			   alert("Enter Password");
			   theform.elements[1].focus();
			   return false;
		}
		if (theform.elements[2].value == "")
		{
			   alert("Re-Enter Password");
			   theform.elements[2].focus();
			   return false;
		}
		if (theform.elements[1].value != theform.elements[2].value)
		{
			   alert("Passwords are not matching, please enter again");
			   theform.elements[2].focus();
			   return false;
		}
		if (theform.elements[3].value == "")
		{
			   alert("Please Enter Affiliate Name");
			   theform.elements[3].focus();
			   return false;
		}
		if (theform.elements[4].value == "")
		{
			   alert("Please Enter Affiliate Address");
			   theform.elements[4].focus();
			   return false;
		}
		if (theform.elements[5].value == "")
		{
			   alert("Please Enter Affiliate City");
			   theform.elements[5].focus();
			   return false;
		}
		if (theform.elements[6].value == "")
		{

			   alert("Please Enter Affiliate Pincode");
			   theform.elements[6].focus();
			   return false;
		}
		else
		{
			if (validateNumeric(theform.elements[6].value) == false)
			{
			   alert("Affiliate Pincode has to be Numeric");
			   theform.elements[6].focus();
			   return false;
			}
		}
		if (theform.elements[7].value == "")
		{
			   alert("Please Enter Affiliate State");
			   theform.elements[7].focus();
			   return false;
		}
		if (theform.elements[9].value == "")
		{
			   alert("Please Enter Telephone Number");
			   theform.elements[9].focus();
			   return false;
		}
		else
		{
			if (validateNumeric(theform.elements[9].value) == false)
			{
			   alert("Affiliate Telephone has to be Numeric");
			   theform.elements[9].focus();
			   return false;
			}
                }
		
	}
        else if (formname == "pabstorestartdata")
        {
		if (theform.elements[0].value == "")
		{
			   alert("Please Enter pabStore name in URL");
			   theform.elements[0].focus();
			   return false;
		}
                else if (validatestoreurl(theform.elements[0].value) == false)
                {
			   alert("Invalid characters in pabStore name in URL");
			   theform.elements[0].focus();
			   return false;
                           
                }
		if (theform.elements[1].value == "")
		{
			   alert("Please Enter pabStore name in Home Page");
			   theform.elements[1].focus();
			   return false;
		}
                else if (validatestorename(theform.elements[1].value) == false)
                {
			   alert("Invalid characters in pabStore name in Home Page");
			   theform.elements[1].focus();
			   return false;
                }
                           
        }
	theform.submit();
}
function validateEmail(email) {
    //Validating the email field
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	//"
    if (! email.match(re)) {
        return (false);
    }
    return(true);
}
function validateNumeric(numValue){
		if (!numValue.toString().match(/^[-]?\d*\.?\d*$/)) 
				return false;
		return true;		
	}
function validatestoreurl(numValue){
		if (!numValue.toString().match(/^[a-zA-Z\-0-9]+$/))
				return false;
		return true;		
	}
function validatestorename(numValue){
		if (!numValue.toString().match(/^[a-zA-Z\-0-9\s]+$/))
				return false;
		return true;		
	}

</script>


</head>

<body>
<div class="container">
<div style="text-align:left;">
<div style="float:right;text-align:right;width:650px">
<form id="login" method="POST" action="affiliates.php"><input type="hidden" name="action" value="login"></form>
<a class="ublueatp" href="http://www.popabook.com/affiliates.php?cu=cu">Contact Us</a>
<span> &nbsp;&nbsp;&nbsp;&nbsp;</span> 
</div>
<div style="width:300px;float:left;text-align:center;padding-top:15px;"><a href="http://www.popabook.com"><img  title="HOME" width="279px" height="69px" src="http://www.popabook.com/popabook.gif" alt="PopAbooK.com" style="border-style:none;"></a></div>
<div style="width:650px;float:right;">
<span class="logoatpo" style="font-size:xx-large;font-weight:bold;">&nbsp;&nbsp;Affiliates</span>
</div>


<div style="text-align:left;margin:0 auto;width:970px;"> 
<?php 

if (isset($_GET['tc']) == TRUE)
{
  showtabs("home");
  echo "<br><br>";
  termsandcond();
}
else if (isset($_GET['cu']) == TRUE)
{
  showtabs("home");
  echo "<br><br>";
  contactus();
}
else if (isset($_POST['action']) == TRUE || isset($_GET['action']) == TRUE)
{
	if ($_POST['action'] == "join")
	{
		$error = "";
		handle_action_join($error);	

	}
	else if ($_POST['action'] == "joinrequest")
	{
	     handle_action_joinrequest($link);
	}
	else if ($_POST['action'] == "login")
	{
		handle_action_login();
	}
	else if ($_POST['action'] == "loginrequest")
	{
		handle_action_loginrequest($link);
	}
	else if ($_POST['action'] == 'afflinks')
	{
		handle_action_afflinks($link);
	}
	else if ($_POST['action'] == 'widgets')
	{
		handle_action_widgets($link);
	}
	else if ($_POST['action'] == 'pabstore')
	{
		handle_action_pabstore($link);
	}
        else if (isset($_GET['action']) == TRUE && $_GET['action'] == 'affauthentication')
        {
                handle_action_affauthentication($link);
        }
	else if ($_POST['action'] == 'pabstorestartdata')
	{
		handle_action_pabstore($link);
	}
	else if ($_POST['action'] == 'home')
	{
		if ($_POST['home'] == 'join')
		{
			$error = "";
			handle_action_join($error);	
		}
		else if ($_POST['home'] == 'addaffiliate')
		{
			handle_action_addaffiliate($link);
		}
		else if ($_POST['home'] == 'loginrequest')
		{
			handle_action_loginrequest($link);
		}
		else if ($_POST['home'] == 'logout')
		{
                        unset($_SESSION['__PAB_affemail']);
                        unset($_SESSION['__PAB_afflinkurl']);
                        unset($_SESSION['__PAB_affstoreurl']);
                        unset($_SESSION['__PAB_affstorename']);
			homepage($link,"");
		}
	}
}
else
{
	$error = "";
      homepage($link,$error);	
}

?>


</div>



<div style="text-align:left;">
<div class="pabheadingbackground" style="text-align:left;">
<table width="100%"><tr> <td align="center">
<span class="ulogoatp"> <br>Payments Powered By<br><br> </span><a href="http://www.ebs.in/" target="_blank"><img width="100px" height="100px" src="ebs.jpg" title="EBS" border="0"></a>
 </td> <td align="center" valign="top"><div style="border-left:1px solid #dddddd;border-bottom:1px solid #dddddd;">
<br><span class="ulogoatp">Site Secured By </span><br><br>
<span id="siteseal"><a href="javascript:verifySeal();"><img src="siteseal_gd_1_h_s_dv.png" width="115px" height="60px"></a><br></span></div>
</td> </tr>
</table>


</div>
</div>
<div id="footer" class="pabheadingbackground" style="text-align:center;clear:right;color:blue;">
<a href="http://www.popabook.com" class="blueatp">Books</a> | <a class="blueatp" href="http://www.popabook.com/browseall">Browse Books</a> | <a href="http://blog.popabook.com" class="blueatp">Blog</a> |  <a class="blueatp" href="http://www.popabook.com/affiliates.php?tc=tc">Terms and Conditions</a> | <a href="http://www.popabook.com/affiliates.php?help=help" class="blueatp">Help</a><br><br>

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
<?php
function handle_action_join($error)
{
	showtabs("home");
	echo "<br><br><div class=\"atp\" style=\"padding-left:10px;\">";
	if ($error != "")
		echo "<span class=\"boldatpo\">$error <br><br></span>";
	echo "<span style=\"font-size:x-large;\">Welcome to the Viral Affiliate Program</span><br><br>";
		echo "<form id=\"affjoin\" method=\"POST\" action=\"affiliates.php\">";
		echo "<table>";
		echo "<tr><td>Enter E-Mail Address</td><td><input name=\"affemail\" type=\"text\"></td></tr>";
		echo "<tr><td>Enter Password</td><td><input name=\"affpassword\" type=\"password\"></td></tr>";
		echo "<tr><td>Re-Enter Password</td><td><input name=\"afftpassword\" type=\"password\"></td></tr>";
		echo "</table>";
		echo "<br><br><h3>Address (Your earnings check will be sent to this address)</h3><br><br>";
		echo "<table>";
		echo "<tr><td>Name :</td><td><input name=\"affname\" type=\"text\"></td></tr>";
		echo "<tr><td>Address :</td><td><textarea name=\"affaddress\" type=\"text\"></textarea></td></tr>";
		echo "<tr><td>City :</td><td><input name=\"affcity\" type=\"text\"></td></tr>";
		echo "<tr><td>Pincode :</td><td><input name=\"affpincode\" type=\"text\"></td></tr>";
		echo "<tr><td>State :</td><td><input name=\"affstate\" type=\"text\"></td></tr>";
		echo "<tr><td>Country :</td><td><input name=\"affcountry\" value=\"India\" type=\"text\" readonly></td></tr>";
		echo "<tr><td>Telephone :</td><td><input name=\"afftelephone\" type=\"text\"></td></tr>";
		echo "</table>";
		echo "<input type=\"hidden\" name=\"action\" value=\"home\">";
		echo "<input type=\"hidden\" name=\"home\" value=\"addaffiliate\">";
	       echo "<br>";	
		echo "<br><div style=\"padding-left:20px;\"><a style=\"text-decoration:none;font-size:large;border:solid 1px #0000ff;padding-top:3px;padding-bottom:3px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\"  onmousedown=\"this.style.backgroundColor='#00008b';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" href=\"javascript:void(0);\" onclick=\"validateinput('affjoin');\">&nbsp;Join As Affiliate&nbsp;</a></div>";
	        echo "</form></div><br><br><br>";
}
function handle_action_addaffiliate($link)
{
	$arrayurllink = array();
	$index = 0;
	$name = "";
	$address = "";
	$address = "";
	$city = "";
	$pincode = "";
	$state = "";
	$password = "";
        $telephone = "";
	if (isset($_POST['affemail']) == TRUE && isset($_POST['affname']) == TRUE && isset($_POST['affaddress']) == TRUE &&
	    isset($_POST['affcity']) == TRUE && isset($_POST['affpincode']) == TRUE && isset($_POST['affstate']) == TRUE && isset($_POST['affpassword']) == TRUE && isset($_POST['afftelephone']) == TRUE)
	{
		$email = $_POST['affemail'];
	     $db_query = "select email,auth from affiliates where email like '$email'";
	     $db_result = mysqli_query($link, $db_query);
	     if ($db_result == TRUE)
	     {
	     $rows = mysqli_num_rows($db_result);
             $realrows = $rows;
             $db_row = mysqli_fetch_row($db_result);
	     if ($rows == 0 || ($rows == 1 && $db_row[1] == 0))
	     {
		     $name = $_POST['affname']; 
		     $address = $_POST['affaddress'];
		     $address = rawurlencode($address);
		     $city = $_POST['affcity'];
		     $pincode = $_POST['affpincode'];
		     $state = $_POST['affstate'];
		     $telephone = $_POST['afftelephone'];
		     $password = $_POST['affpassword'];
		     $lname = strtolower($name);
		     $exarray = explode(" ", $lname);

		     if (count($exarray) == 1)
		     {
		         $arrayurllink[$index] = trim($exarray[0]); $index = $index + 1;
		         $arrayurllink[$index] = substr($email, 0, 1) . trim($exarray[0]); $index = $index + 1;
		         $arrayurllink[$index] = trim($exarray[0]) . substr($email, 0, 1); $index = $index + 1;
	             }
	             else if (count($exarray) > 1)
		     {
	                 $arrayurllink[$index] = substr(trim($exarray[1]), 0, 1) . trim($exarray[0]); $index = $index + 1;
	                 $arrayurllink[$index] = trim($exarray[0]) . substr(trim($exarray[1]), 0, 1); $index = $index + 1;
	                 $arrayurllink[$index] = substr(trim($exarray[0]), 0, 1) . trim($exarray[1]); $index = $index + 1;
	                 $arrayurllink[$index] = trim($exarray[1]) . substr(trim($exarray[0]), 0, 1); $index = $index + 1;
	             }
		     $urllink = "";
		     $i = 0;
                     
                     for ($i = 0; $i < $index; $i = $i + 1)
		     {
			     $db_query = "select email from affiliates where linkurl like '$arrayurllink[$i]'";
			     $db_result = mysqli_query($link, $db_query);
			     if ($db_result == TRUE)
			     {
				     $rows = mysqli_num_rows($db_result);
				     if($rows == 0)
				     {
					     $urllink = $arrayurllink[$i];
					     break;
				     }
			     }
	             }
	             if ($i == $index)
		     {
			     /*need to work hard*/
			     $base = $exarray[0];
			     $suffix = 1;
			     while (1)
			     {
				$temp = $base . $suffix;
			     	$db_query = "select email from affiliates where linkurl like '$temp'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
				     $rows = mysqli_num_rows($db_result);
				     if($rows == 0)
				     {
					$urllink = $temp;
					break;
				     }
	                        }
	                        $suffix = $suffix + 1;
	                     }
	             }

                     $md5password = md5($password);

                     if ($realrows == 0)
		         $db_query = "insert into affiliates (name, address, city, pincode, state, password, email,linkurl,telephone) values ('$name', '$address', '$city', '$pincode', '$state', '$md5password', '$email', '$urllink','$telephone')";
                     else
		         $db_query = "update affiliates set name='$name', address='$address', city='$city', pincode='$pincode', state='$state', password='$md5password', linkurl='$urllink', telephone='$telephone' where email like '$email'";
		     $db_result = mysqli_query($link, $db_query);
		     if ($db_result == TRUE)
		     {
			     $key = mt_rand();
			     $db_query = "delete from affauth where email like '$email'";
			     mysqli_query($link, $db_query);
			     $db_query = "insert into affauth (affkey, email) values ('$key', '$email')";
			     mysqli_query($link, $db_query);
			     showtabs("home");
			     $emailmsg = "Hello $name, <br><br> Thanks for becoming an affiliate of PopAbooK.com. We wish you good luck. To activate your account please click the link below. If you are not expecting this e-mail, just ignore it. <br><br><a href=\"http://www.PopAbooK.com/affiliates.php?action=affauthentication&email=$email&key=$key\">Confirm Affiliate Account</a><br><br><br>Please contact support@popabook.com for all affiliate issues.<br><br>Regards,<br>Support<br>www.PopAbooK.com";
                              sendemail($email, "PopAbooK.com Confirm and Activate Account", "support@popabook.com", 'pbd1PBD1', $emailmsg);

			     echo "<div style=\"padding-left:50px;\" class=\"atp\"> <br><br><br>Congratulations $name! an e-mail has been sent to your E-Mail Id to authenticate the e-mail address. Please follow the instructions in the e-mail to activate your account<br><br><br><br><br><br><Br></div>";
		     }
	     }
	     else
	     {
		     $error = "$email already has an affiliate account, please use a different e-mail to Join";
		     handle_action_join($error);
	     }
            }

	}
}
function handle_action_loginrequest($link)
{
	if (isset($_POST['email']) == TRUE && isset($_POST['password']) == TRUE)
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		$db_query = "select password,linkurl,storeurl,name,storename,auth,address,city,pincode,state from affiliates where email like '$email'";
		$md5password = md5($password);
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
                                if ($db_row[5] == 1)
                                { 
				if ($md5password == $db_row[0])
				{
					$_SESSION['__PAB_affemail'] = $email;
					$_SESSION['__PAB_afflinkurl'] = $db_row[1];
					$_SESSION['__PAB_affstoreurl'] = $db_row[2];
					$_SESSION['__PAB_affname'] = $db_row[3];
					$_SESSION['__PAB_affstorename'] = $db_row[4];
                                        $_SESSION['__PAB_affaddress'] = $db_row[6];
                                        $_SESSION['__PAB_affcity'] = $db_row[7];
                                        $_SESSION['__PAB_affpincode'] = $db_row[8];
                                        $_SESSION['__PAB_affstate'] = $db_row[9];
					homepage($link,"");
				}
				else
				{
					$error = "e-mail and password did not match";
					homepage($link, $error);
				}
                                }
                                else
                                {
					$error = "Activate your account to Login";
					homepage($link, $error);
                                }
			}
			else
			{
					$error = "e-mail and password did not match";
					homepage($link, $error);
			}
		}
		else
		{
					$error = "Server is temporarily unavailable";
					homepage($link, $error);
		}
	}
}
function handle_action_afflinks($link)
{
	showtabs("afflinks");
        echo "<div class=\"logoatp\" style=\"font-size:medium;padding-top:50px;padding-left:50px;padding-bottom:25px;\"><span style=\"font-weight:bold;\">Affiliate Links</span> are text links to PopAbooK.com or to specific books on PopAbooK.com. These text links can be embedded in blog/website.</div>";
	$linkurl = "";
	if (isset($_SESSION['__PAB_affemail']) == TRUE)
	{
		$linkurl = $_SESSION['__PAB_afflinkurl'];
	}
        else
        {
            echo "<div style=\"padding-top:25px;padding-bottom:25px;text-align:center;\" class=\"atp\"> Please login for affiliate links</div>";
        }
	echo "<script type=\"text/javascript\"> function getlinkurl(){ return '$linkurl';}</script>";
	if ($linkurl == "")
		$directlink = "http://www.PopAbooK.com/Johnny-Gone-Down/9788172237868";
	else
		$directlink = "http://www.PopAbooK.com/Johnny-Gone-Down/9788172237868/$linkurl/af";
	echo "<div style=\"text-align:center;\" class=\"logoatp\">Search for books and generate text links and image links</div><br><br>";
	echo "<table width=\"100%\">";
	echo "<tr>";
	echo "<td width=\"50%\" valign=\"top\" align=\"center\">"; 
	dosearch($link,"afflinks");
	
        /* all search results go here*/	
	
	echo"</td>";
	echo "<td width=\"50%\" valign=\"top\"><div id=\"afflinksreal\" style=\"padding-left:5px;border-left:solid 1px #cccccc;\">";
	echo "<div class=\"logoatp\" style=\"text-align:center;\">Johnny Gone Down</div>";
	echo "<div class=\"boldatp\"><br><br>URL<br><br></div>";
	echo "<div class=\"atp\"><a href=\"$directlink\" target=\"_blank\">$directlink</a><br><br></div>";
	echo "<div class=\"boldatp\"><br><br>Text Link <span class=\"atp\"> like <a href=\"$directlink\" target=\"_blank\">Johnny Gone Down</a></span><br><br></div>";
	echo "<div style=\"width:100%;\"><table width=\"100%\"><tr><td><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"$directlink\" target=\"_blank\">Johnny Gone Down</a></textarea></td></tr></table></div>"; 
	echo "<div class=\"boldatp\"><br><br>Text Link <span class=\"atp\"> like <a href=\"$directlink\" target=\"_blank\">Johnny Gone Down by Karan Bajaj</a></span><br><br></div>";
	echo "<div style=\"width:100%;\"><table width=\"100%\"><tr><td><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"$directlink\" target=\"_blank\">Johnny Gone Down by Karan Bajaj</a></textarea></td></tr></table></div>";
	echo "<div class=\"boldatp\"><br><br><table><tr><td>Image Link <span class=\"atp\"> like </span></td><td><a href=\"$directlink\" target=\"_blank\"><img width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/9788172237868.jpg\"></a></td></tr></table><br><br></div>";
	echo "<div><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"$directlink\" target=\"_blank\"><img width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/9788172237868.jpg\"></a></textarea></div>";
	echo "</div></td>";
	echo "</tr>";
	echo "</table><br><br><br><br>";
}
function showtabs($htab)
{
        /*echo "<div style=\"float:right;width:970px;\">"; */
	echo "<div class=\"logoatp\" style=\"width:670px;float:right;padding-top:15px;\">";
	if ($htab == "home")
	   echo "<span style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('home')\">&nbsp;Home&nbsp;</span>&nbsp;";
	else
		echo "<span class=\"logoatp\" onmouseout=\"this.style.backgroundColor='#eeeeee';\" onmouseover=\"this.style.backgroundColor='#cccccc';this.style.cursor='pointer';\" style=\"background-color:#eeeeee;color:#2b60de;\" onclick=\"validateinput('home')\">&nbsp;Home&nbsp;</span>&nbsp;";
	if ($htab == "afflinks")
	   echo "<span style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('afflinks')\">&nbsp;Affiliate Links&nbsp;</span>&nbsp;";
	else
		echo "<span class=\"logoatp\" onmouseout=\"this.style.backgroundColor='#eeeeee';\" onmouseover=\"this.style.backgroundColor='#cccccc';this.style.cursor='pointer';\" style=\"background-color:#eeeeee;color:#2b60de;\" onclick=\"validateinput('afflinks')\">&nbsp;Affiliate Links&nbsp;</span>&nbsp;";
	if ($htab == "widgets")
	   echo "<span style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('widgets')\">&nbsp;Widgets&nbsp;</span>&nbsp;";
	else
		echo "<span class=\"logoatp\" onmouseout=\"this.style.backgroundColor='#eeeeee';\" onmouseover=\"this.style.backgroundColor='#cccccc';this.style.cursor='pointer';\" style=\"background-color:#eeeeee;color:#2b60de;\" onclick=\"validateinput('widgets')\">&nbsp;Widgets&nbsp;</span>&nbsp;";
	if ($htab == "pabstore")
	   echo "<span style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('pabstore')\">&nbsp;pabStore&nbsp;</span>&nbsp;";
	else
		echo "<span class=\"logoatp\" onmouseout=\"this.style.backgroundColor='#eeeeee';\" onmouseover=\"this.style.backgroundColor='#cccccc';this.style.cursor='pointer';\" style=\"background-color:#eeeeee;color:#2b60de;height:100%;\" onclick=\"validateinput('pabstore')\">&nbsp;pabStore&nbsp;</span>";
	echo "</div>";
	
	echo "<div style=\"text-align:left;clear:both;background-color:#2b60de;overflow:hidden;width:970px;height:2px;\"></div>";
	echo "<form id=\"widgets\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"widgets\"></form>";
	echo "<form id=\"pabstore\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"pabstore\"></form>";
	echo "<form id=\"home\" method=\"POST\" action=\"affiliates.php\"></form>";
	echo "<form id=\"afflinks\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"afflinks\"></form>";
        /*echo "</div>";*/
}
function dosearch($link, $product)
{
    require_once("browsecatalog.php");
    $session_id = session_id();
	    echo "<form id=\"pabstoresearch\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"$product\"> <table><tr><td valign=\"middle\"><input type=\"text\" size=\"40\" name=\"search_string\"></td><td valign=\"middle\"><a class=\"logoatp\" style=\"border:solid 1px #0000ff;text-decoration:none;background-color:#2b60de;color:#ffffff;\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\"  onmousedown=\"this.style.backgroundColor='#00008b';\" onmouseover=\"this.style.cursor='pointer';this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" href=\"javascript:validateinput('pabstoresearch')\">&nbsp;SEARCH&nbsp;</a></td></tr></table></form>";
    if (isset($_POST['search_string']) == FALSE)
    {
	    return;
    }
$search_string = $_POST['search_string'];
$books = array ();
$bookindex = 0;
$ranks = array ();
$escape = ", ;:.\/\"'\n\r-()?&\t";
$reqpage = 1;
$search_string=strtolower($search_string);
$numtok = 0;
$dbstr = rawurlencode($search_string);
mysqli_query($link,"insert into sessions_searches (session,search) values ('$session_id','$dbstr')");
if (isset($_SESSION[$search_string]) == TRUE)
{
	if (isset($_POST['reqpage']) == TRUE)
	{
		$reqpage = $_POST['reqpage'];
	}
	else
	{
		$reqpage = 1;
	}

	$bookstring = $_SESSION[$search_string][$reqpage];
	$tok = strtok($bookstring,"#");
	while ($tok != null)
	{
		$books[$bookindex] = $tok;
		$bookindex = $bookindex + 1;
		$tok = strtok("#");
	}
}
else 
{
	$books = searchbooks($link, $search_string, $escape,"",1);
	$bookindex = count($books);
        if ($bookindex != 0)
        {
	$_SESSION[$search_string]['numitems'] = $bookindex;
	if (($bookindex%10) != 0)
		$numpages = $bookindex/10 - ($bookindex%10)/10 + 1;
	else
		$numpages = $bookindex/10;
	$page = 1;
	$tmp = 0;
	$tnumpages = $numpages;
	while ($tnumpages > 0)
	{
		$bstring = "";
		for ($i = 0 ; $i < 10; $i = $i + 1)
		{
			if ($tmp < $bookindex)
			{
				$bstring = $bstring . $books[$tmp] . "#";
			}
			$tmp = $tmp + 1;
		}
		$_SESSION[$search_string][$page] = $bstring;
		$tnumpages = $tnumpages - 1;
		$page = $page + 1;
	}
	if ($bookindex > 10)
		$bookindex = 10;
        }
}
if ($bookindex != 0)
{
$numitems = $_SESSION[$search_string]['numitems'];
if (($numitems%10) != 0)
	$numpages = $numitems/10 - ($numitems%10)/10 + 1;
else
	$numpages = $numitems/10;
$output = "";
}
if ($bookindex == 0)
{
	echo "<div class=\"atp\" style=\"padding-left:5px;\"> <p> We could not find any matches for the search string <span class=\"blueatp\">" . $search_string . ".</span><br>Please try using different search string. <br>We are constantly trying to add to our inventory,<br> incase you could not find a match now please try later. <br>Thank You for your support.</p></div>";
        return;	
}

showbookstoaff($link,$output, $reqpage, $numpages, $books, $bookindex,$search_string,$product);
echo $output;
}


function showbookstoaff($link,&$output, $reqpage, $numpages, $books, $bookindex,$search_string,$product)
{
	
	$output = $output . "<div style=\"width:100%\">";
	$output = $output . "<span class=\"atp\"> <br>&nbsp;&nbsp;Showing Page $reqpage of $numpages Pages for </span><span class=\"blueatp\"> $search_string<br></span>";

        $db_query = "lock tables book_inventory read";
        $db_result = mysqli_query($link, $db_query);
        if ($db_result == FALSE)
        {
		$output = "<div style=\"width:100%\"> Temporarily server is unavailable, please try again. </div>";
		return;
	}
	$affid = "";
	if (isset($_SESSION['__PAB_afflinkurl']) == TRUE)
		$affid = $_SESSION['__PAB_afflinkurl'];
	else
		$affid = "";

     	
	for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
	{
		$db_query = "select title, author1, isbn13, booklink from book_inventory where bookid = $books[$ti]";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
				$booklink = $db_row[3] . "/" . $db_row[2]; 
				if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
					$image = $db_row[2] . ".jpg";
				else
					$image = "ina.jpg";
				$enauthor = rawurlencode($db_row[1]);
				$entitle = rawurlencode($db_row[0]);
				$output = $output . "<br><table width=\"100%\"><tr><td valign=\"top\">&nbsp;</td><td><img src=\"http://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"></td><td class=\"boldatp\" align=\"left\"> $db_row[0] <span class=\"atp\">by $db_row[1]</span></td></tr><tr><td>&nbsp;</td>";
				if ($product == 'pabstore')
				{
					$formid = "addtostore" . $db_row[2];
					$output = $output . "<td colspan=\"2\" align=\"center\"><a class=\"atp\" style=\"border:solid 1px #0000ff;padding-top:2px;padding-bottom:2px;text-decoration:none;background-color:#2b60de;color:#ffffff;\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onmouseover=\"this.style.cursor='pointer';this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" onmousedown=\"this.style.backgroundColor='#00008b';\" href=\"javascript:void(0)\" onclick=\"validateinput('$formid')\">&nbsp;Add To Store&nbsp;</a>";
					$output = $output .  "<form id=\"$formid\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"isbn13\" value=\"$db_row[2]\"><input type=\"hidden\" name=\"action\" value=\"pabstore\"><input type=\"hidden\" name=\"pabstore\" value=\"addbook\"><input type=\"hidden\" name=\"search_string\" value=\"$search_string\"><input type=\"hidden\" name=\"reqpage\" value=\"$reqpage\"> </form></td>";

				}
				else
				{
					$output = $output . "<td colspan=\"2\" align=\"center\"><a class=\"atp\" style=\"text-decoration:none;padding-top:2px;padding-bottom:2px;border:solid 1px #0000ff;background-color:#2b60de;color:#ffffff;\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onmousedown=\"this.style.backgroundColor='#00008b';\" href=\"javascript:void(0);\" onmouseover=\"this.style.cursor='pointer';this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" onclick=\"generateafflinks('$entitle', '$enauthor', '$db_row[2]', '$booklink', '$image','$affid')\">&nbsp;Generate Links&nbsp;</a></td>";
				}
				$output = $output .  "</tr></table>";
			}
		}
	}
	$enss = $search_string;
	$enss = rawurlencode($enss);
	$output = $output . "<div style=\"width:100%\" class=\"atp\">";

	$output = $output . "<div style=\"align:left\";>&nbsp;&nbsp;Showing Page $reqpage of $numpages Pages for <span class=\"blueatp\"> $search_string</span><br></div><div>"; 
	$encoded = rawurlencode($search_token);
	if ($reqpage == $numpages)
	{
		;	
	}
	else
	{
		$npage = $reqpage + 1;
		$formid = "afflinkssearch" . $numpages;
		$output = $output . "<form id=\"$formid\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"reqpage\" value=\"$numpages\"><input type=\"hidden\" name=\"search_string\" value=\"$search_string\"><input type=\"hidden\" name=\"action\" value=\"$product\"></form>";
		$formid = "afflinkssearch" . $npage;
		$output = $output . "<form id=\"$formid\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"reqpage\" value=\"$npage\"><input type=\"hidden\" name=\"search_string\" value=\"$search_string\"><input type=\"hidden\" name=\"action\" value=\"$product\"></form>";
		$formid = "afflinkssearch" . $numpages;

			$output = $output . "<a href=\"javascript:validateinput('$formid')\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" >LAST</div></a>";
		$formid = "afflinkssearch" . $npage;
			$output = $output . "<a href=\"javascript:validateinput('$formid')\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\">NEXT</div></a>";
	}
	if ($numpages != 1)
	$output = $output . "<div class=\"pgpreviousnextactive\"> <a href=\"#\">$reqpage</a></div>";
	if ($reqpage == 1)
	{
		;
	}
	else
	{
		$ppage = $reqpage - 1;
		$formid = "afflinkssearch" . $ppage;
		$output = $output . "<form id=\"$formid\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"reqpage\" value=\"$ppage\"><input type=\"hidden\" name=\"search_string\" value=\"$search_string\"><input type=\"hidden\" name=\"action\" value=\"$product\"></form>";
		$formid = "afflinkssearch" . "1";
		$output = $output . "<form id=\"$formid\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"reqpage\" value=\"1\"><input type=\"hidden\" name=\"search_string\" value=\"$search_string\"><input type=\"hidden\" name=\"action\" value=\"$product\"></form>";
		$formid = "afflinkssearch" . $ppage;

			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\"><a href=\"javascript:validateinput('$formid')\">PREVIOUS</a></div>";
		$formid = "afflinkssearch" . "1";
			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\"><a href=\"javascript:validateinput('$formid')\">FIRST</a></div>";
	}
	$output = $output . "</div></div></div>"; 
        $db_query = "unlock tables";
	$db_result = mysqli_query($link, $db_query); 
}

function handle_action_widgets($link)
{
	showtabs('widgets');
        $affid = "";
        echo "<div class=\"logoatp\" style=\"font-size:medium;padding-top:50px;padding-left:50px;padding-bottom:25px;\"><span style=\"font-weight:bold;\">Widgets</span> are stand-alone web applications that can be embedded into third party websites by any user on a page where they have rights of authorship (e.g. a webpage, blog, or profile on a social media site). Widgets are fun and engaging allowing users to deliver dynamic content to the users. PopAbooK.com widgets can be embedded in to blog/website by the affiliates.</div>";
        if (isset($_SESSION['__PAB_affemail']) == FALSE)
        {
            echo "<div style=\"padding-top:25px;padding-bottom:25px;text-align:center;\" class=\"atp\"> Please login for affiliate widgets</div>";
            $affid = "popabook";
        }
        else
            $affid = $_SESSION['__PAB_afflinkurl'];
        
        echo "<table width=\"100%\"><tr>";
        echo "<td width=\"50%\">";
	echo "<div style=\"text-align:center;\"><span class=\"boldatp\">New Releases Widget</span><br><iframe src=\"widgets.php?nr=$affid\" width=\"225px\" height=\"400px\" frameborder='0' scrolling='no'></iframe></div>";
	echo "<div style=\"text-align:center;\"><span class=\"boldatp\">Code for the New Releases Widget</span><br><br><textarea rows=\"3\" cols=\"40\" readonly><iframe src=\"http://www.popabook.com/widgets.php?nr=$affid\" width=\"225px\" height=\"400px\" frameborder='0' scrolling='no'></iframe></textarea></div>";
        echo "</td>";
        echo "<td width=\"50%\" valign=\"top\">";
	echo "<div style=\"text-align:center;\"><span class=\"boldatp\">Search Books Widget</span><br><iframe src=\"widgets.php?sb=$affid\" width=\"225px\" height=\"175px\" frameborder='0' scrolling='no'></iframe></div>";
	echo "<div style=\"text-align:center;\"><span class=\"boldatp\">Code for the Search Books Widget</span><br><br><textarea readonly rows=\"3\" cols=\"40\"><iframe src=\"http://www.popabook.com/widgets.php?sb=$affid\" width=\"225px\" height=\"175px\" frameborder='0' scrolling='no'></iframe></textarea></div>";
        echo "</td>";
        echo "</tr></table>";
        echo "<div class=\"boldatp\" style=\"font-size:medium;padding-left:50px;\"><br><br>Widget for pabStore is available under the pabStore tab<br><br><br><br></div>"; 
}
function handle_action_pabstore($link)
{
	showtabs('pabstore');
        $email = "";
        $storeurl = "";
        $storename = "";
        if (isset($_SESSION['__PAB_affemail']) == TRUE)
        {
                   $storeurl = $_SESSION['__PAB_affstoreurl'];
                   $storename = $_SESSION['__PAB_affstorename'];
                   $email = $_SESSION['__PAB_affemail'];
        }
	if (isset($_POST['pabstore']) == FALSE)
	{
                if ($email == "" || $storeurl == "")
		{
	   echo "<div class=\"logoatp\" style=\"padding-left:50px;\"><br>What is pabStore?</div>";
	   echo "<div class=\"logoatp\" style=\"padding-left:50px;font-size:medium;\"><br>pabStore is an affiliate tool, affilites can start a niche bookstore completely FREE! Promote and Sell books choosen from PopAbooK.com and earn commission on sales from the pabStore just as from affiliate links and widgets<br><br><br></div>";
        if ($email != "")
        {
	echo "<div style=\"padding-left:50px;\">";
	echo "<br><a  href=\"javascript:validateinput('pabstorestart');\" style=\"text-decoration:none;font-family:verdana,georgia;font-weight:bold;font-size:large;border:solid 1px #0000ff;padding-top:5px;padding-bottom:5px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onmousedown=\"this.style.backgroundColor='#00008b';\">&nbsp;Start Your Own BookStore Now!&nbsp;</a><br><br><br><br>";
	echo "<form id=\"pabstorestart\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"pabstore\"><input type=\"hidden\" name=\"pabstore\" value=\"start\"></form>";
	echo "</div>";
        }
        else
        {
	    echo "<div style=\"padding-left:50px;\">";
            echo "<span class=\"atp\" style=\"font-size:medium;\"><a href=\"affiliates.php\">Become an Affiliate and create pabStore</a></span> ";
            echo "</div><br><br><br><br>";
        }
		}
		else
		{
			managepabstore($link);
		}
	}
	else
	{
		if ($_POST['pabstore'] == 'start')
		{
                         pabstorestart("");
	   		/*echo "<div class=\"atp\" style=\"padding-left:50px;padding-right:50px;\"><br><br><br>";
			echo "A pabStore has unique URL that you can share/promote and your customers can visit the store using this URL. A typical URL of a pabStore looks like this \"www.PopAbooK.com/<span class=\"blueatp\">EXAMPLE</span>/bookstore\" where ";
			echo "\"EXAMPLE\" is the name of the bookstore, its almost like the domain name! ";
			echo "Please choose a name that your customer's can easily remember and identify.<br><br>";
			echo "A pabStore also has a store name that is shown on home page of the store! \"EXAMPLE BOOKS\" this name is a little more descriptive than the name in the URL and it need not be unique";
			echo "<br><br><form id=\"pabstorestartdata\" method=\"POST\" action=\"affiliates.php\">";
			echo "<table>";
			echo "<tr><td><span class=\"atp\">Store Name in URL</span></td><td><input type=\"text\" name=\"storeurl\"></td></tr>";
			echo "<tr><td><span class=\"atp\">Store Name on Home Page</span></td><td><input type=\"text\" name=\"storename\"></td></tr>";
			echo "</table>";
	                echo "<br><br><br><a href=\"javascript:validateinput('pabstorestartdata');\" style=\"text-decoration:none;font-family:verdana,georgia;font-weight:bold;font-size:large;border:solid 1px #00008b;padding-top:5px;padding-bottom:5px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#eeeeee';this.style.color='#2b60de';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\">&nbsp;Start pabStore&nbsp;</a><br><br><br>";
			echo "<input type=\"hidden\" value=\"pabstore\" name=\"action\">";
			echo "<input type=\"hidden\" value=\"startdata\" name=\"pabstore\">";
			echo "</form>";
			echo "</div>"; */
		}
		else if ($_POST['pabstore'] == 'startdata')
		{
			if (isset($_POST['storeurl']) == TRUE && isset($_POST['storename']) == TRUE)
			{
				$storeurl = $_POST['storeurl'];
				$storename = $_POST['storename'];
				$storeurl = strtolower($storeurl);
				$email = $_SESSION['__PAB_affemail'];
				$db_query = "select email from affiliates where storeurl like '$storeurl'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					if (mysqli_num_rows($db_result) == 0)
					{
						$db_query = "update affiliates set storeurl='$storeurl' , storename='$storename' where email like '$email'";
						$db_result = mysqli_query($link, $db_query);
						if ($db_result == TRUE)
						{
							$_SESSION['__PAB_affstoreurl'] = $storeurl;
							$_SESSION['__PAB_affstorename'] = $storename;
							echo "<div style=\"padding-left:50px;\" class=\"atp\"><br><br>Your pabStore is ready, Add, Promote and Sell books</div>";
							managepabstore($link);
							
						}
					}
                                        else
                                        {
                                             pabstorestart("pab Store URL already exists, please choose another name");
                                               
                                        }
				}
			}

		}
		else if ($_POST['pabstore'] == 'addbook')
		{
			if (isset($_POST['isbn13']) == TRUE && isset($_SESSION['__PAB_affemail']) == TRUE)
			{
				$email = $_SESSION['__PAB_affemail'];
				$isbn13 = $_POST['isbn13'];
				$db_query = "select affid from affiliates where email like '$email'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					$db_row = mysqli_fetch_row($db_result);
					if ($db_row != null)
					{
						$affid = $db_row[0];
                                                $db_query ="select bookid from book_inventory where isbn13 like '$isbn13'";
                                                $db_result = mysqli_query($link, $db_query);
                                                if ($db_result == TRUE)
                                                {
                                                $db_row = mysqli_fetch_row($db_result);
                                                if ($db_row != NULL)
                                                {
                                                     
						$db_query = "insert into booksinstore (isbn13,affid,dt,bookid) values ('$isbn13', $affid,now(),$db_row[0])";
						mysqli_query($link, $db_query);
                                                }
                                                }
					}
				}
				managepabstore($link);
			}
		}
	}
}
function managepabstore($link)
{
	$linkurl = "";
	if (isset($_SESSION['__PAB_affemail']) == TRUE)
	{
		$linkurl = $_SESSION['__PAB_afflinkurl'];
	}
	$storeurl = "";
	$storename = "";
	if (isset($_SESSION['__PAB_affstoreurl']) == TRUE)
	{
		$storeurl = $_SESSION['__PAB_affstoreurl'];
		$storename = $_SESSION['__PAB_affstorename'];
	}
	echo "<script type=\"text/javascript\"> function getlinkurl(){ return '$linkurl';}</script>";
	echo "<script type=\"text/javascript\"> function getstoreurl(){ return '$storeurl';}</script>";
	echo "<br><br><div style=\"text-align:center;\" class=\"atp\">URL of $storename bookstore : <a target=\"_blank\" href=\"http://www.PopAbooK.com/$storeurl/bookstore\">http://www.PopAbooK.com/$storeurl/bookstore</a></div><br><br>";

	echo "<div style=\"text-align:center;\" class=\"atp\">Search for books and add books to pabStore. If the book is not found please contact us at support@popabook.com and we will add it</div><br><br>";
	echo "<table width=\"100%\">";
	echo "<tr>";
	echo "<td width=\"50%\" valign=\"top\" align=\"center\">"; 
	dosearch($link,"pabstore");
	
        /* all search results go here*/	
	
	echo"</td>";
	echo "<td width=\"50%\" valign=\"top\" align=\"center\"><div id=\"afflinksreal\" style=\"padding-left:5px;border-left:solid 1px #cccccc;\">";
	$completestoreurl = "http://www.PopAbooK.com/" . $storeurl . "/bookstore";
	$email = $_SESSION['__PAB_affemail'];
	$db_query = "select count(*) from booksinstore where affid in (select affid from affiliates where email like '$email')";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if ($db_row != null)
		{
			if ($db_row[0] != 0)
			{
	echo "<div class=\"logoatp\" style=\"text-align:center;\">$storename</div>";
	echo "<div class=\"boldatp\"><br><br>URL<br><br></div>";
	echo "<div class=\"atp\"><a href=\"$completestoreurl\">$completestoreurl</a><br><br></div>";
	echo "<div class=\"boldatp\">pabStore Widget<br><br></div>";
	echo "<div><iframe src=\"http://www.popabook.com/widgets.php?pabstore=$storeurl\" width=\"225px\" height=\"500px\" frameborder='0' scrolling='no'></iframe></div>";
        echo "<div class=\"boldatp\">Code for pabStore Widget</div>";
	echo "<textarea readonly rows=\"3\" cols=\"60\"><iframe src=\"http://www.popabook.com/widgets.php?pabstore=$storeurl\" width=\"225px\" height=\"500px\" frameborder='0' scrolling='no'></iframe></textarea>";

			}
			else
			{
				echo "<span class=\"atp\"> There are no books in your store, please add atleast 20-30 books</span>";
			}
		}
	}
	echo "</div></td>";
	echo "</tr>";
	echo "</table>";
        echo "<br><br><br>";

}
function homepage($link, $error)
{
	showtabs("home");
	echo "<div class=\"atp\" style=\"padding-left:10px;padding-right:10px;\">";

	if (isset($_SESSION['__PAB_affemail']) == FALSE)
	{

	echo "<br><br><span class=\"logoatpo\" style=\"font-size:xx-large;\">Make Money Advertising PopAbooK.com Products</span> <br><br>";
	echo "<table><tr><td width=\"65%\"><span class=\"atp\" style=\"font-size:medium;\">Earn up to <span style=\"font-weight:bold;\"><u>8% of the Selling Price</u></span> by Advertising PopAbooK.com Products</span>";
	echo "<br><br>";
	echo "<span class=\"atp\" style=\"font-size:medium;\">Advertise on your Website, Blog and Social Media Platforms[Facebook, Twitter ...]. People following these links can earn you up to 8% of the selling price! The inherently viral links can exponentially increase the following and your earnings! <span style=\"font-weight:bold;\">Your earnings potential depends on your followers and their followers and their followers ...</span></span>";
        echo "<div class=\"atp\" style=\"font-size:medium;\"><br><br><span style=\"font-weight:bold;\">Affiliate Tools</span><br><ul><li><span style=\"font-weight:bold;\">Affiliates Links</span> are text links that can be embedded in your blog/website<br><br></li><li><span style=\"font-weight:bold;\">Widgets</span> are standalone web applications delivering dynamic content. They can be embedded in to your blog/website<br><br></li><li><span style=\"font-weight:bold;\">pabStore</span> is a virtual online bookstore. Affiliates can start a Niche bookstore completely FREE! and promote and sell books<br><br></li></ul></div>";
/*
	echo "<br><br><br><div style=\"padding-left:50px;\"><a style=\"text-decoration:none;font-size:large;border:solid 1px #0000ff;padding-top:5px;padding-bottom:5px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmousedown=\"this.style.backgroundColor='#00008b';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" href=\"javascript:validateinput('affjoinform')\">&nbsp;&nbsp;Join As Affiliate Now!&nbsp;&nbsp;</a></div><form id=\"affjoinform\" name=\"affjoinform\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"join\"></form><br><br><br>"; */
	echo "</td><td width=\"35%\" valign=\"top\"><div style=\"padding-left:10px;\">";
	if ($error != "")
		echo "<div class=\"boldatpo\"><br><br>$error <br><br></div>";
	echo "<div style=\"border: 1px solid #eeeeee;background-color:#eeeeee;padding-left:15px;padding-right:5px;width:200px;\"> <br><div class=\"boldatp\">Already an Affiliate? Login to access your account</div><br><form id=\"loginrequest\" method=\"POST\" action=\"affiliates.php\"><span class=\"atp\">Enter Your E-Mail Address</span><input type=\"text\" name=\"email\"><br><br><span class=\"atp\">Enter Your Password</span><input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"action\" value=\"home\"> <input type=\"hidden\" name=\"home\" value=\"loginrequest\"></form>";
	echo "<div><br><a style=\"text-decoration:none;font-size:large;border:solid 1px #0000ff;padding-top:3px;padding-bottom:3px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onmousedown=\"this.style.backgroundColor='#00008b';\" href=\"javascript:validateinput('loginrequest')\">&nbsp;Login&nbsp;</a></div>";
	echo "<div class=\"boldatp\"><br>Not an Affiliate?<br><br></div><div><a  onmousedown=\"this.style.backgroundColor='#00008b';\" style=\"text-decoration:none;font-size:large;border:solid 1px #0000ff;padding-top:5px;padding-bottom:5px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" href=\"javascript:validateinput('affjoinform')\">&nbsp;Join Now! Its FREE!&nbsp;</a></div><br><br></div></div></td></tr></table>";
	echo "<form id=\"affjoinform\" name=\"affjoinform\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"home\"><input type=\"hidden\" name=\"home\" value=\"join\"></form>";
	}
	else
	{
		$name = $_SESSION['__PAB_affname'];
		$address = $_SESSION['__PAB_affaddress'];
                $address = rawurldecode($address);
		$city = $_SESSION['__PAB_affcity'];
		$pincode = $_SESSION['__PAB_affpincode'];
		$state = $_SESSION['__PAB_affstate'];
                echo "<div class=\"atp\" style=\"padding-left:50px;font-size:medium;\">";	
                echo "<table width=\"100%\"><tr><td width=\"70%\">";
		;echo "<span><br><br><br>Welcome <span style=\"font-weight:bold;\">$name</span></span>&nbsp;<a class=\"satp\" href=\"javascript:validateinput('logout')\">(logout)</a>"; 
                echo "<form id=\"logout\" method=\"POST\" action=\"affiliates.php\"><input type=\"hidden\" name=\"action\" value=\"home\"><input type=\"hidden\" name=\"home\" value=\"logout\"></form>";
                echo "</td><td>";
/*                echo "<div class=\"logoatp\" >Your Address </div>"; */
                echo "<div>Name : $name </div>";
                echo "<div>Address : $address</div>";
                echo "<div>City : $city</div>";
                echo "<div>Pincode : $pincode</div>";
                echo "<div>State : $state, India</div>";
                echo "</td></tr></table>";
                echo "<br><br><br>";
                affiliatereport($link);
                echo "</div>";
                echo "<br><br><br>";
	}
	echo "</div>";
}
function handle_action_affauthentication($link)
{
	if (isset($_GET['email']) == TRUE && isset($_GET['key']) == TRUE)
	{
		$email = $_GET['email'];
		$key = $_GET['key'];
		$db_query = "select * from affauth where email like '$email' and affkey like '$key'";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$rows = mysqli_num_rows($db_result);
			if ($rows == 1)
			{
				$db_query = "update affiliates set auth=1 where email like '$email'";
				mysqli_query($link, $db_query);
				showtabs("home");
				echo "<div class=\"atp\" style=\"padding-left:50px;\"><br.<br><br> $email account has been verified, please <a href=\"http://www.popabook.com/affiliates.php\">login</a> to access your account<br><br><br></div>";
			}
                        else
                        {
				showtabs("home");
				echo "<div class=\"atp\" style=\"padding-left:50px;\"><br.<br><br> $email account could not be verified, please contact support@popabook.com to resolve the issue<br><br><br></div>";
                        }
		}
	}
}
function pabstorestart($error)
{
	   		echo "<div class=\"atp\" style=\"padding-left:50px;padding-right:50px;\"><br><br><span class=\"boldatpo\">$error</span><br>";
			echo "A pabStore has unique URL that you can share/promote and your customers can visit the store using this URL. A typical URL of a pabStore looks like this \"www.PopAbooK.com/<span class=\"blueatp\">EXAMPLE</span>/bookstore\" where ";
			echo "\"EXAMPLE\" is the name of the bookstore, its almost like the domain name! ";
			echo "Please choose a name that your customer's can easily remember and identify.<br><br>";
			echo "A pabStore also has a store name that is shown on home page of the store! \"EXAMPLE BOOKS\" this name is a little more descriptive than the name in the URL and it need not be unique";
			echo "<br><br><form id=\"pabstorestartdata\" method=\"POST\" action=\"affiliates.php\">";
			echo "<table>";
			echo "<tr><td><span class=\"atp\">Store Name in URL</span></td><td><input type=\"text\" name=\"storeurl\"></td></tr>";
			echo "<tr><td><span class=\"atp\">Store Name on Home Page</span></td><td><input type=\"text\" name=\"storename\"></td></tr>";
			echo "</table>";
	                echo "<br><br><br><a href=\"javascript:validateinput('pabstorestartdata');\" style=\"text-decoration:none;font-family:verdana,georgia;font-weight:bold;font-size:large;border:solid 1px #0000ff;padding-top:5px;padding-bottom:5px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onmousedown=\"this.style.backgroundColor='#00008b';\">&nbsp;Start pabStore&nbsp;</a><br><br><br>";
			echo "<input type=\"hidden\" value=\"pabstore\" name=\"action\">";
			echo "<input type=\"hidden\" value=\"startdata\" name=\"pabstore\">";
			echo "</form>";
			echo "</div>";
}
function affiliatereport($link)
{
                $fd = date('F Y');
                $fdd = date('d-y-m') . " at " . date('h-i-s a e');
                echo "<span style=\"font-weight:bold;\">Summary of transactions for $fd as on $fdd</span><br><br>";
                $monthyear = date('M-Y');
                $affid = $_SESSION['__PAB_afflinkurl']; 
                $ttr = 0;
                $db_query = "select count(*) from aff_traffic where affid like '$affid' and afftype like 'link' and monthyear like '$monthyear'";
                $db_result = mysqli_query($link, $db_query);
                if ($db_result == TRUE)
                {
                   $db_row = mysqli_fetch_row($db_result);
                   if ($db_row != null)
                     $ttr = intval($db_row[0]);
                }
                $affid = $_SESSION['__PAB_affstoreurl']; 
                $db_query = "select count(*) from aff_traffic where affid like '$affid' and afftype like 'pabstore' and monthyear like '$monthyear'";
                $db_result = mysqli_query($link, $db_query);
                if ($db_result == TRUE)
                {
                   $db_row = mysqli_fetch_row($db_result);
                   if ($db_row != null)
                     $ttr = $ttr + intval($db_row[0]);
                }
                  /* now orders */
                $affid = $_SESSION['__PAB_afflinkurl'];
                $tor = 0;
                $orders = "";
                $comm = 0;
                $db_query = "select invoicenumber, amount, orderstatus,paymentstatus from orders where affiliate like '$affid' and afftype like 'link' and monthyear like '$monthyear'";
                $db_result = mysqli_query($link, $db_query);
                if ($db_result == TRUE)
                {
                   $db_row = mysqli_fetch_row($db_result);
                   while ($db_row != null)
                   {
                     if ($db_row[3] == "Payment Received")
                     {
                        $tor = $tor + 1;
                        $comm = $comm + intval($db_row[1]);
                        $orders = $orders . "<table width=\"100%\"><tr><td width=\"33%\">$db_row[0]</td><td width=\"33%\">Received : Rs. $db_row[1]</td><td width=\"33%\">$db_row[2]</td></tr></table>";
                     }
                        $db_row = mysqli_fetch_row($db_result);
                   }
                }
                $affid = $_SESSION['__PAB_affstoreurl'];
                $db_query = "select invoicenumber, amount, orderstatus,paymentstatus from orders where affiliate like '$affid' and afftype like 'pabstore' and monthyear like '$monthyear'";
                $db_result = mysqli_query($link, $db_query);
                if ($db_result == TRUE)
                {
                   $db_row = mysqli_fetch_row($db_result);
                   while ($db_row != null)
                   {
                     if ($db_row[3] == "Payment Received")
                     {
                        $tor = $tor + 1;
                        $comm = $comm + intval($db_row[1]);
                        $orders = $orders . "<table width=\"100%\"><tr><td width=\"33%\">$db_row[0]</td><td width=\"33%\">Received : Rs. $db_row[1]</td><td width=\"33%\">$db_row[2]</td></tr></table>";
                     }
                        $db_row = mysqli_fetch_row($db_result);
                   }

                }
                $cr = 0;
                if ($comm != 0)
                {
                    if ($tor <= 20)
                            $cr = intval ($comm * 0.06);
                    else if ($tor <= 28)
                            $cr = intval ($comm * 0.0625);
                    else if ($tor <= 40)
                            $cr = intval ($comm * 0.065);
                    else if ($tor <= 56)
                            $cr = intval ($comm * 0.0675);
                    else if ($tor <= 78)
                            $cr = intval ($comm * 0.07);
                    else if ($tor <= 110)
                            $cr = intval ($comm * 0.0725);
                    else if ($tor <= 154)
                            $cr = intval ($comm * 0.075);
                    else if ($tor <= 216)
                            $cr = intval ($comm * 0.0775);
                    else if ($tor > 216)
                            $cr = intval ($comm * 0.08);
                }
                else
                   $cr = 0;
                   $crr = 0;
                if ($ttr != 0)
                $crr = floatval($tor/$ttr * 100);

                
                echo "<div>Total traffic received : $ttr</div>";
                echo "<div>Total orders received : $tor</div>";
                echo "<div>Total commission earned : $cr</div>";
                echo "<div>Conversion rate : $crr</div>";
                if ($tor > 0)
                {
                      
                    echo "<div style=\"font-size:medium;font-weight:bold;\"><br><br>Details of Orders<br><br>";
                        echo "<table width=\"100%\"><tr><td width=\"33%\">Invoice Number</td><td width=\"33%\">Transaction Detail</td><td width=\"33%\">Order Status</td></tr></table>";
                    echo "</div>";
                      echo $orders;
                }

}
?>
<script type="text/javascript">
function generateafflinks(entitle, enauthor, isbn, booklink, image, affid)
{
	var gal = document.getElementById('afflinksreal');
	var title = unescape(entitle);
	var author = unescape(enauthor);
	var directlink = "";
	if (affid == "")
		directlink = "http://www.PopAbooK.com/" + booklink;
	else
		directlink = "http://www.PopAbooK.com/" + booklink + "/" + affid + "/af";
	gal.innerHTML = "<div class=\"logoatp\" style=\"text-align:center;\">" + title + "</div>" + "<div class=\"boldatp\"><br><br>URL<br><br></div>" + "<div class=\"atp\">" + "<a href=\"" + directlink + "\" target=\"_blank\">" + directlink + "</a><br><br></div>" + "<div class=\"boldatp\"><br><br>Text Link <span class=\"atp\"> like <a href=\"" + directlink + "\"" + "target=\"_blank\"" + ">" + title + "</a></span><br><br></div>" + "<div style=\"width:100%;\"><table width=\"100%\"><tr><td><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"" + directlink + "\" target=\"_blank\">" + title + "</a></textarea></td></tr></table></div>" + "<div class=\"boldatp\"><br><br>Text Link <span class=\"atp\"> like <a href=\"" + directlink + "\" target=\"_blank\">" + title + " by " + author + "</a></span><br><br></div>" + "<div style=\"width:100%;\"><table width=\"100%\"><tr><td><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"" + directlink + "\" target=\"_blank\">" + title + " by " + author + "</a></textarea></td></tr></table></div>" + "<div class=\"boldatp\"><br><br><table><tr><td>Image Link <span class=\"atp\"> like </span></td><td><a href=\"" + directlink + "\" target=\"_blank\"><img width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" + image + "\"></a></td></tr></table><br><br></div>" + "<div><textarea class=\"atp\" readonly rows=\"3\" cols=\"60\"><a href=\"" + directlink + "\" target=\"_blank\"><img width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" + image + "\"></a></textarea></div>";
}
</script>
<?php

function termsandcond()
{
    echo "<div style=\"padding-left:50px;\" class=\"atp\">";
    echo "<p>This Affiliates Program Operating Agreement contains the terms and conditions that govern your participation in the PopAbooK.com Affiliates Program. We,. .us,. or .our. means PopAbooK.com (excluding those that sell retail products), as the case may be. .You. or .your. means the applicant/affiliate.</p>";

echo "<p>CREATING  AN AFFILIATE ACCOUNT INDICATES THAT YOU AGREE TO THE TERMS AND CONDITIONS OF THIS OPERATING AGREEMENT, OR BY CONTINUING TO PARTICIPATE IN THE PROGRAM FOLLOWING OUR POSTING OF A CHANGE NOTICE, REVISED OPERATING AGREEMENT, OR REVISED OPERATIONAL DOCUMENTATION ON THE PopAbooK.com SITE, YOU (A) AGREE TO BE BOUND BY THIS OPERATING AGREEMENT; (B) ACKNOWLEDGE AND AGREE THAT YOU HAVE INDEPENDENTLY EVALUATED THE DESIRABILITY OF PARTICIPATING IN THE PROGRAM AND ARE NOT RELYING ON ANY REPRESENTATION, GUARANTEE, OR STATEMENT OTHER THAN AS EXPRESSLY SET FORTH IN THIS OPERATING AGREEMENT; AND (C) HEREBY REPRESENT AND WARRANT THAT YOU ARE LAWFULLY ABLE TO ENTER INTO CONTRACTS (E.G., YOU ARE NOT A MINOR) AND THAT YOU ARE AND WILL REMAIN IN COMPLIANCE WITH THIS OPERATING AGREEMENT, INCLUDING THE AFFILIATES PROGRAM PARTICIPATION REQUIREMENTS. IN ADDITION, IF THIS OPERATING AGREEMENT IS BEING AGREED TO BY A COMPANY OR OTHER LEGAL ENTITY, THEN THE PERSON AGREEING TO THIS OPERATING AGREEMENT ON BEHALF OF THAT COMPANY OR ENTITY HEREBY REPRESENTS AND WARRANTS THAT HE OR SHE IS AUTHORIZED AND LAWFULLY ABLE TO BIND THAT COMPANY OR ENTITY TO THIS OPERATING AGREEMENT.</p>";
echo "<p class=\"bluetitle\"> 1. Description of the Program</p>";
 echo "<p>The purpose of the Program is to permit you to advertise Products on your blog/website/social media platform and to earn advertising fees for Qualifying Purchases (defined below) made by your end users. A .Product. is any item sold on the PopAbooK.com, including products sold by thirdparty merchants. In order to facilitate your advertisement of Products, we may make available to you data, images, text, link formats, widgets, links, and other linking tools, and other information in connection with the Program.</p>";

echo "<p class=\"bluetitle\">2. Enrollment</p>";
echo "<p> To begin the enrollment process, you must submit a complete and accurate Program application. We may reject your application if we determine that you are advertising on a website/blog that is unsuitable. Unsuitable sites include those that: <ul><li>(a) promote or contain sexually explicit materials;</li><li> (b) promote violence or contain violent materials;</li><li> (c) promote or contain libelous or defamatory materials;</li><li> (d) promote discrimination, or employ discriminatory practices, based on race, sex, religion, nationality, disability, sexual orientation, or age;</li><li> (e) promote or undertake illegal activities;</li><li> (h) otherwise violate intellectual property rights.</li></ul></p>";
echo "<p> If we reject your application, you are welcome to reapply at any time. However, if we accept your application and we later determine that your site is unsuitable, we may terminate this Operating Agreement.</p>";
echo "<p> You will ensure that the information in your Program application and otherwise associated with your account, including your email address and other contact information and identification of your site, is at all times complete, accurate, and up-to-date. We may send notifications (if any), approvals (if any), and other communications relating to the Program and this Operating Agreement to the email address then-currently associated with your Program account. You will be deemed to have received all notifications, approvals, and other communications sent to that email address, even if the email address associated with your account is no longer current.</p>";

echo "<p class=\"bluetitle\"> 3. Links on Your Site</p>";
echo "<p>After you have been notified that you have been accepted into the Program, you may display Special Links on your site. .Special Links. are links to the PopAbooK.com products that you place on your site in accordance with this Operating Agreement, that properly utilize the special .tagged. link formats we provide, and that comply with the Affiliates Program Linking Requirements. Special Links permit accurate tracking, reporting, and accrual of advertising fees.</p>";
echo "<p> You may earn advertising fees only with respect to activity on the PopAbooK.com website occurring through Special Links. We will have no obligation to pay you advertising fees if you fail to properly format the links on your site to the PopAbooK.com as Special Links, including to the extent that such failure may result in any reduction of advertising fee amounts that would otherwise be paid to you under this Operating Agreement.</p>";
echo "<p class=\"bluetitle\"> 4. Program Requirements</p>";
echo "<p>You will provide us with any information that we request to verify your compliance with this Operating Agreement. If we determine that you have not complied with any requirement or restriction described on the Affiliates Program Participation Requirements or you have otherwise violated this Operating Agreement, we may (in addition to any other rights or remedies available to us) withhold any advertising fees payable to you under this Operating Agreement, terminate this Operating Agreement, or both.</p>";
echo "<p> In addition, you hereby consent to us:<br><ul><li> * sending you emails relating to the Program from time to time</li><li> * monitoring, recording, using, and disclosing information about your site and visitors to your site that we obtain in connection with your display of Special Links (e.g., that a particular PopAbooK.com customer clicked through a Special Link from your site before buying a Product on the PopAbooK.com).</li><li> * monitoring, crawling, and otherwise investigating your site to verify compliance with this Operating Agreement.</li></ul></p>";

echo "<p class=\"bluetitle\">5. Responsibility for Your Site</p>";
echo "<p> You will be solely responsible for your site, including its development, operation, and maintenance and all materials that appear on or within it. For example, you will be solely responsible for:

    * the technical operation of your site and all related equipment;
    * displaying Special Links and Content on your site in compliance with this Operating Agreement and any agreement between you and any other person or entity (including any restrictions or requirements placed on you by any person or entity that hosts your site);
    * creating and posting, and ensuring the accuracy, completeness, and appropriateness of, materials posted on your site (including all Product descriptions and other Product-related materials and any information you include within or associate with Special Links);
    * using the Content, your site, and the materials on or within your site in a manner that does not infringe, violate, or misappropriate any of our rights or those of any other person or entity (including copyrights, trademarks, privacy, publicity or other intellectual property or proprietary rights);
    * disclosing on your site accurately and adequately, either through a privacy policy or otherwise, how you collect, use, store, and disclose data collected from visitors, including, where applicable, that third parties (including us and other advertisers) may serve content and advertisements, collect information directly from visitors, and place or recognize cookies on visitors. browsers; and
    * any use that you make of the Content and the PopAbooK.com widgets/links, whether or not permitted under this Operating Agreement.

We will have no liability for these matters or for any of your end users. claims relating to these matters, and you agree to defend, indemnify, and hold us, our affiliates and licensors, and our and their respective employees, officers, directors, and representatives, harmless from and against all claims, damages, losses, liabilities, costs, and expenses (including attorneys. fees) relating to (a) your site or any materials that appear on your site, including the combination of your site or those materials with other applications, content, or processes; (b) the use, development, design, manufacture, production, advertising, promotion, or marketing of your site or any materials that appear on or within your site, and all other matters described in this Section 5; (c) your use of any Content, whether or not such use is authorized by or violates this Operating Agreement or violates applicable law; (d) your violation of any term or condition of this Operating Agreement; or (e) your or your employees' negligence or willful misconduct.</p>";
echo "<p class=\"bluetitle\"> 6. Order Processing</p>";
echo "<p>We will process Product orders placed by customers who follow Special Links from your site to the PopAbooK.com. We reserve the right to reject orders that do not comply with any requirements on the PopAbooK.com, as they may be updated from time to time. We will track Qualifying Purchases (defined below) for reporting and advertising fee accrual purposes and will make available to you reports summarizing those Qualifying Purchases.</p>";
echo "<p class=\"bluetitle\"> 7. Advertising Fees</p>";
echo "<p> We will pay you advertising fees on Qualifying Purchases as defined in the Fee Schedule below. Subject to the exclusions set forth below, a .Qualifying Purchase. occurs when (a) a customer clicks through a Special Link on your blog/website/social media platform to the PopAbooK.com; (b) during a single Session that customer adds a Product to his or her shopping cart and places the order for that Product no later than 30 days following the customers initial click-through and (c) the Product is shipped to and paid for by, the customer.</p><table width=\"100%\">";
echo "<tr><td width=\"50%\">Orders Per Month</td><td width=\"50%\">Commission</td></tr>";
echo "<tr><td width=\"50%\">0-20</td><td width=\"50%\">6.00%</td></tr>";
echo "<tr><td width=\"50%\">21-28</td><td width=\"50%\">6.25%</td></tr>";
echo "<tr><td width=\"50%\">29-40</td><td width=\"50%\">6.50%</td></tr>";
echo "<tr><td width=\"50%\">41-56</td><td width=\"50%\">6.75%</td></tr>";
echo "<tr><td width=\"50%\">57-78</td><td width=\"50%\">7.00%</td></tr>";
echo "<tr><td width=\"50%\">79-110</td><td width=\"50%\">7.25%</td></tr>";
echo "<tr><td width=\"50%\">111-154</td><td width=\"50%\">7.50%</td></tr>";
echo "<tr><td width=\"50%\">155-216</td><td width=\"50%\">7.75%</td></tr>";
echo "<tr><td width=\"50%\"> > 216</td><td width=\"50%\">8.00%</td></tr>";
echo "</table>";
echo "<p> Qualifying Purchases exclude, and we will not pay advertising fees on any of, the following: * any Product that, after expiration of the applicable Session, is added to a customer's Shopping Cart, is purchased by a customer <br> * any Product purchase that is not correctly tracked or reported because the links from your site to the PopAbooK.com are not properly formatted; <br> * any Product purchased through a Special Link by you or on your behalf, including Products you purchase through Special Links for yourself, friends, relatives, or associates (e.g., personal orders, orders for your own use, and orders placed by you for or on behalf of any other person or entity); <br> * any Product purchased for resale or commercial use of any kind;<br> * any Product purchased after termination of this Operating Agreement; * any Product order that is canceled or returned;</p>";
echo "<p class=\"bluetitle\"> 8. Advertising Fee Payment</p>";
echo "We will pay you advertising fees on a monthly basis for Qualifying Purchases shipped in a given month.  We will pay you approximately with in 20 days following the end of each calendar month using bank cheque  payment method, may with hold advertising fees until the total amount due to you is atleast Rs. 1000</p>";
echo "<p class=\"bluetitle\"> 9. Policies and Pricing</p>";
echo "<p> Customers who buy products through this Program are our customers with respect to all activities they undertake in connection with the PopAbooK.com. Accordingly, as between you and us, all pricing, terms of sale, rules, policies, and operating procedures concerning customer orders, customer service, and product sales set forth on PopAbooK.com will apply to those customers, and we may change them at any time.</p>";
echo "<p class=\"bluetitle\"> 10. Identifying Yourself as an Associate</p>";
echo "<p>You will not issue any press release or make any other public communication with respect to this Operating Agreement, your use of the Content, or your participation in the Program. You will not misrepresent or embellish the relationship between us and you (including by expressing or implying that we support, sponsor, endorse, or contribute to any charity or other cause), or express or imply any relationship or affiliation between us and you or any other person or entity except as expressly permitted by this Operating Agreement. You must, however, clearly state the following on your site: .[Insert your name] is a participant in the PopAbooK.com Affiliate Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to PopAbooK.com</p>";
echo "<p class=\"bluetitle\"> 11. Limited License</p>";
echo "<p>Subject to the terms of this Operating Agreement and solely for the limited purposes of advertising Products on, and directing end users to, PopAbooK.com in connection with the Program, we hereby grant you a limited, revocable, non-transferable, non-sublicensable, non-exclusive, royalty-free license to (a) copy and display the Content solely on your blog/website/social media platform; and (b) use only those of our trademarks and logos that we may make available to you as part of Content solely on your site and in accordance with the Affiliate Program.</p>";
echo "<p> The license set forth in the Section above will immediately and automatically terminate if at any time you do not timely comply with any obligation under this Operating Agreement, or otherwise upon termination of this Operating Agreement. In addition, we may terminate the license set forth in the above Section in whole or in part upon written notice to you. You will promptly remove from your site and delete or otherwise destroy all of the Content and with respect to which the license set forth in the above section is terminated or as we may otherwise request from time to time.</p>";

echo "<p class=\"bluetitle\"> 12. Reservation of Rights; Submissions</p>";
echo "<p> Other than the limited licenses expressly set forth in Section 11, we reserve all right, title and interest (including all intellectual property and proprietary rights) in and to, and you do not, by virtue of this Operating Agreement or otherwise, acquire any ownership interest or rights in or to, the Program, Special Links, link formats, Content, any domain name owned or operated by us or our affiliates, Operational Documentation, our and our affiliates. trademarks and logos, and any other intellectual property and technology that we provide or use in connection with the Program. If you provide us or any of our affiliates with suggestions, reviews, modifications, data, images, text, or other information or content about a product or in connection with this Operating Agreement, any Content, or your participation in the Program, or if you modify any Content in any way, (collectively, .Your Submission.), you hereby irrevocably assign to us all right, title, and interest in and to Your Submission and grant us (even if you have designated Your Submission as confidential) a perpetual, paid-up royalty-free, nonexclusive, worldwide, irrevocable, freely transferable right and license to (a) use, reproduce, perform, display, and distribute Your Submission in any manner; (b) adapt, modify, re-format, and create derivative works of Your Submission for any purpose; (c) use and publish your name in the form of a credit in conjunction with Your Submission (however, we will not have any obligation to do so); and (d) sublicense the foregoing rights to any other person or entity. Additionally, you hereby warrant that: (y) Your Submission is your original work, or you obtained Your Submission in a lawful manner; and (z) our and our sublicensees. exercise of rights under the license above will not violate any person.s or entity.s rights, including any copyright rights. You agree to provide us such assistance as we may require to document, perfect, or maintain our rights in and to Your Submission.</p>";
echo "<p class=\"bluetitle\"> 13. Compliance with Laws</p>";
echo "<p>In connection with your participation in the Program you will comply with all applicable laws, ordinances, rules, regulations, orders, licenses, permits, judgments, decisions, and other requirements of any governmental authority that has jurisdiction over you, including laws of India.</p>";
echo "<p class=\"bluetitle\"> 14. Term and Termination</p>";
echo "<p>The term of this Operating Agreement will begin upon our acceptance of your Program application and will end when terminated by either you or us. Either you or we may terminate this Operating Agreement at any time, with or without cause, by giving the other party written notice of termination. Upon any termination of this Operating Agreement, any and all licenses you have with respect to Content will automatically terminate and you will immediately stop using the Content and promptly remove from your site and delete or otherwise destroy all links to the PopAbooK.com, all PopAbooK.com logos, all other Content, and any other materials provided or made available by or on behalf of us to you under this Operating Agreement or otherwise in connection with the Program. We may withhold accrued unpaid advertising fees for a reasonable period of time following termination to ensure that the correct amount is paid (e.g., to account for any cancelations or returns). Upon any termination of this Operating Agreement, all rights and obligations of the parties will be extinguished, except unpaid payment obligations of us under this Operating Agreement, will survive the termination of this Operating Agreement. No termination of this Operating Agreement will relieve either party for any liability for any breach of, or liability accruing under, this Operating Agreement prior to termination.</p>";
echo "<p class=\"bluetitle\"> 15. Modification</p>";
echo "<p>We may modify any of the terms and conditions contained in this Operating Agreement (and any Operational Documentation) at any time and in our sole discretion by posting a change notice, revised agreement, or revised Operational Documentation on the PopAbooK.com. Modifications may include, for example, changes to the Affiliates Program Advertising Fee Schedule, Associates Program Participation Requirements, payment procedures, and other Program requirements. IF ANY MODIFICATION IS UNACCEPTABLE TO YOU, YOUR ONLY RECOURSE IS TO TERMINATE THIS OPERATING AGREEMENT. YOUR CONTINUED PARTICIPATION IN THE PROGRAM FOLLOWING OUR POSTING OF A CHANGE NOTICE, REVISED OPERATING AGREEMENT, OR REVISED OPERATIONAL DOCUMENTATION ON PopAbooK.com WILL CONSTITUTE YOUR BINDING ACCEPTANCE OF THE CHANGE.</p>";
echo "<p class=\"bluetitle\"> 16. Relationship of Parties</p>";
echo "<p> You and we are independent contractors, and nothing in this Operating Agreement will create any partnership, joint venture, agency, franchise, sales representative, or employment relationship between you and us or our respective affiliates. You will have no authority to make or accept any offers or representations on our or our affiliates. behalf. You will not make any statement, whether on your site or otherwise, that contradicts or may contradict anything in this section. If you authorize, assist, encourage, or facilitate another person or entity to take any action related to the subject matter of this Operating Agreement, you will be deemed to have taken the action yourself.</p>";
echo "<p class=\"bluetitle\"> 17. Limitation of Liability</p>";
echo "<p>WE WILL NOT BE LIABLE FOR INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR EXEMPLARY DAMAGES (INCLUDING ANY LOSS OF REVENUE, PROFITS, GOODWILL, USE, OR DATA) ARISING IN CONNECTION WITH THIS OPERATING AGREEMENT, THE PROGRAM, OPERATIONAL DOCUMENTATION, PopAbooK.com , OR THE SERVICE OFFERINGS (DEFINED BELOW), EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF THOSE DAMAGES. FURTHER, OUR AGGREGATE LIABILITY ARISING IN CONNECTION WITH THIS OPERATING AGREEMENT, THE PROGRAM, PopAbooK.com, AND THE SERVICE OFFERINGS WILL NOT EXCEED THE TOTAL ADVERTISING FEES PAID OR PAYABLE TO YOU UNDER THIS OPERATING AGREEMENT IN THE TWELVE MONTHS IMMEDIATELY PRECEDING THE DATE ON WHICH THE EVENT GIVING RISE TO THE MOST RECENT CLAIM OF LIABILITY OCCURRED.</p>";
echo "<p class=\"bluetitle\"> 18. Disclaimers</p>";
echo "<p>THE PROGRAM, PopAbooK.com, ANY PRODUCTS AND SERVICES OFFERED ON PopAbooK.com, ANY SPECIAL LINKS, LINK FORMATS, OPERATIONAL DOCUMENTATION, CONTENT, PopAbooK.com  AND OUR AFFILIATES. TRADEMARKS AND LOGOS, AND ALL TECHNOLOGY, SOFTWARE, FUNCTIONS, MATERIALS, DATA, IMAGES, TEXT, AND OTHER INFORMATION AND CONTENT PROVIDED OR USED BY OR ON BEHALF OF US OR OUR AFFILIATES OR LICENSORS IN CONNECTION WITH THE PROGRAM (COLLECTIVELY THE \"SERVICE OFFERINGS\") ARE PROVIDED \"AS IS.\" NEITHER WE NOR ANY OF OUR AFFILIATES OR LICENSORS MAKE ANY REPRESENTATION OR WARRANTY OF ANY KIND, WHETHER EXPRESS, IMPLIED, STATUTORY, OR OTHERWISE WITH RESPECT TO THE SERVICE OFFERINGS. EXCEPT TO THE EXTENT PROHIBITED BY APPLICABLE LAW, WE AND OUR AFFILIATES AND LICENSORS DISCLAIM ALL WARRANTIES WITH RESPECT TO THE SERVICE OFFERINGS, INCLUDING ANY IMPLIED WARRANTIES OF MERCHANTABILITY, SATISFACTORY QUALITY, FITNESS FOR A PARTICULAR PURPOSE, NON-INFRINGEMENT, AND QUIET ENJOYMENT, AND ANY WARRANTIES ARISING OUT OF ANY COURSE OF DEALING, PERFORMANCE, OR TRADE USAGE. WE MAY DISCONTINUE ANY SERVICE OFFERING, OR MAY CHANGE THE NATURE, FEATURES, FUNCTIONS, SCOPE, OR OPERATION OF ANY SERVICE OFFERING, AT ANY TIME AND FROM TIME TO TIME. NEITHER WE NOR ANY OF OUR AFFILIATES OR LICENSORS WARRANT THAT THE SERVICE OFFERINGS WILL CONTINUE TO BE PROVIDED, WILL FUNCTION AS DESCRIBED, CONSISTENTLY OR IN ANY PARTICULAR MANNER, OR WILL BE UNINTERRUPTED, ACCURATE, ERROR FREE, OR FREE OF HARMFUL COMPONENTS. NEITHER WE NOR ANY OF OUR AFFILIATES OR LICENSORS WILL BE RESPONSIBLE FOR (A) ANY ERRORS, INACCURACIES, OR SERVICE INTERRUPTIONS, INCLUDING POWER OUTAGES OR SYSTEM FAILURES; OR (B) ANY UNAUTHORIZED ACCESS TO OR ALTERATION OF, OR DELETION, DESTRUCTION, DAMAGE, OR LOSS OF, YOUR SITE OR ANY DATA, IMAGES, TEXT, OR OTHER INFORMATION OR CONTENT. NO ADVICE OR INFORMATION OBTAINED BY YOU FROM US OR FROM ANY OTHER PERSON OR ENTITY OR THROUGH THE PROGRAM, CONTENT, OPERATIONAL DOCUMENTATION, PopAbooK.com , OR THE PopAbooK.com/affiliates SITE WILL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THIS OPERATING AGREEMENT. FURTHER, NEITHER WE NOR ANY OF OUR AFFILIATES OR LICENSORS WILL BE RESPONSIBLE FOR ANY COMPENSATION, REIMBURSEMENT, OR DAMAGES ARISING IN CONNECTION WITH (X) ANY LOSS OF PROSPECTIVE PROFITS OR REVENUE, ANTICIPATED SALES, GOODWILL, OR OTHER BENEFITS, (Y) ANY INVESTMENTS, EXPENDITURES, OR COMMITMENTS BY YOU IN CONNECTION WITH THIS OPERATING AGREEMENT OR YOUR PARTICIPATION IN THE PROGRAM, OR (Z) ANY TERMINATION OF THIS OPERATING AGREEMENT OR YOUR PARTICIPATION IN THE PROGRAM.</p>";
echo "<p class=\"bluetitle\"> 19. Disputes</p>";
echo "<p>Any dispute relating in any way to the Program or this Operating Agreement shall be subject Indian courts</p>";
echo "<p class=\"bluetitle\"> 20. Miscellaneous </p>";
echo "<p>You acknowledge and agree that we and our affiliates may at any time (directly or indirectly) solicit customer referrals on terms that may differ from those contained in this Operating Agreement or operate sites that are similar to or compete with your site. You may not assign this Operating Agreement, by operation of law or otherwise, without our express prior written approval. Subject to that restriction, this Operating Agreement will be binding on, inure to the benefit of, and be enforceable against the parties and their respective successors and assigns. Our failure to enforce your strict performance of any provision of this Operating Agreement will not constitute a waiver of our right to subsequently enforce this provision or any other provision of this Operating Agreement. In the event of any conflict between this Operating Agreement and the Operational Documentation, this Operating Agreement will control. If you are enrolled to use the Product Advertising API and in the event of any conflict between this Operating Agreement and the .com Product Advertising API License Agreement (. License Agreement .), this Operating Agreement will control except that the License Agreement will control with respect to your use of the Product Advertising API, Data Feed, and Product Advertising Content (each as defined in the License Agreement). Whenever used in this Operating Agreement, the terms .include(s),. .including,. .e.g.,. and .for example. mean, respectively, .include(s), without limitation,. .including, without limitation,. .e.g., without limitation,. and .for example, without limitation.. Any determinations or updates that may be made by us, any actions that may be taken by us, and any approvals that may be given by us under this Operating Agreement, may be made, taken, or given in our sole discretion.</p>"; 
}
function contactus()
{
echo "<div style=\"padding-left:50px;\"><br><br><p class=\"boldatp\"> Support/Order Related Issues </p><p class=\"atp\">Monday to Saturday (10:00 AM to 6:00 PM)<br>Phone: +919538851062<br>E-Mail:<a href=\"mailto:support@popabook.com\">support@PopAbooK.com</a><br><p><br><p class=\"boldatp\"> Feedback and Suggestions</p><p class=\"atp\"> E-Mail:<a href=\"mailto:feedback@popabook.com\">feedback@PopAbooK.com</a><p><br> <p class=\"boldatp\"> Our Address </p> <p class=\"atp\"># 58/A <br>24th Cross, 18th Main<br>H.S.R. Layout, Sector-3 <br> Bangalore-560102<br> India <br></p> <p class=\"boldatp\">Business</p><p class=\"atp\">E-Mail:<a href=\"mailto:praveen@popabook.com\">praveen@PopAbooK.com</a> </p> <p> <a href=\"http://www.twitter.com/PopAbooK\"><img src=\"http://www.popabook.com/follow_bird_us-a.png\" alt=\"Follow PopAbooK on Twitter\"/></a></p></div>";
}

?>
