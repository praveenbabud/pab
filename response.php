<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<link rel="shortcut icon" href="favicon.ico">

<head>
<LINK REL=StyleSheet HREF="http://www.popabook.com/stylev12.css" TYPE="text/css">
<script type="text/javascript" src="http://www.popabook.com/pabv12.js"></script>
<title> PopAbooK.com : Online Book Store. Buy New and Used Books Online in India.
</title>
		 <script type="text/javascript">
		 function redirecto()
		 {
			 /*document.location.href="http://www.popabook.com/536test/search.php";*/
			 document.red.submit();
			 /*var formdiv = document.getElementById("formdiv");
			 formdiv.parentNode.removeChild(formdiv);*/
		 }
		 </script>
</head>

<?php 
require_once('dbconnect.php');
require('sendemail.php');
require_once('invoice.php');
$session_id = session_id();
$link = wrap_mysqli_connect();
$secret_key = "eaa8c490be30febcacdf21fd74fa298c";	
$payment_success = 0;
if(isset($_GET['DR'])) {
	 require('Rc43.php');
	 $DR = preg_replace("/\s/","+",$_GET['DR']);

	 $rc4 = new Crypt_RC4($secret_key);
 	 $QueryString = base64_decode($DR);
	 $rc4->decrypt($QueryString);
	 $QueryString = split('&',$QueryString);

	 $response = array();
	 foreach($QueryString as $param){
	 	$param = split('=',$param);
		$response[$param[0]] = urldecode($param[1]);
	 }
		$orderid = $response['MerchantRefNo'];
		$invoicenumber = $orderid;
		$bname = $response['BillingName'];
		$bname = rawurlencode($bname);
		 $baddress = $response['BillingAddress'];
		$bcity = $response['BillingCity'];
		$bcity = rawurlencode($bcity);
		$bstate = $response['BillingState'];
		$bstate = rawurlencode($bstate);
		 $bcountry = $response['BillingCountry'];
		 $bpincode = $response['BillingPostalCode'];
		 $bemail = $response['BillingEmail'];
		 $bphone = $response['BillingPhone'];
		 $pgpayid = $response['PaymentID'];
		 $pgpaytime = $response['DateCreated'];
		 $pgresponsecode = $response['ResponseCode'];
		 $pgresponsemsg = $response['ResponseMessage'];
		 $description = $response['Description'];
		 $flag = $response['IsFlagged'];
		 $amount = $response['Amount'];
		 $baddress = rawurlencode($baddress);
		 $_SESSION['PaymentID'] = $pgpayid;
		 $_SESSION['invoicenumber'] = $invoicenumber;
		 $_SESSION['ResponseCode'] = $pgresponsecode;
		 if ($response['ResponseCode'] == 0)
		 {
		 	$db_query = "update orders set bname='$bname', baddress='$baddress', bcity='$bcity', bstate='$bstate', bcountry='$bcountry', bpincode='$bpincode', bemail='$bemail', bphone='$bphone', pgpaymentid='$pgpayid', pgpaymenttime='$pgpaytime', pgresponsecode='$pgresponsecode', pgresponsemsg='$pgresponsemsg', description='$description', pgflag='$flag', amount='$amount', paymentstatus='Payment Received' where invoicenumber like '$invoicenumber'"; 
		 }
		 else
		 {
		 	$db_query = "update orders set bname='$bname', baddress='$baddress', bcity='$bcity', bstate='$bstate', bcountry='$bcountry', bpincode='$bpincode', bemail='$bemail', bphone='$bphone', pgpaymentid='$pgpayid', pgpaymenttime='$pgpaytime', pgresponsecode='$pgresponsecode', pgresponsemsg='$pgresponsemsg', description='$description', pgflag='$flag', amount='$amount' where invoicenumber like '$invoicenumber'"; 
		 }

		 $db_result = mysqli_query($link, $db_query);
		 if ($db_result == TRUE)
		 {
		     ;	
		 }
		 if ($response['ResponseCode'] == 0)
		 {
				$db_query = "delete from shopping_carts where session like '$session_id'";
			        $db_result = mysqli_query($link, $db_query);
				$emailmsg = generateemailinvoice($link, $invoicenumber,$pgpayid); 
				sendemail($bemail, "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
				sendemail('praveen@popabook.com', "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
				$payment_success = 1;
				$db_query = "select coupon from orders where invoicenumber like '$invoicenumber'";
                                $db_result = mysqli_query($link, $db_query);
                                if ($db_result == TRUE)
                                {
                                   $db_row_c = mysqli_fetch_row($db_result);
                         
                                   if ($db_row_c != NULL)
                                   {
                                       if ($db_row_c[0] != "")
                                       {
                                           $db_query_inc = "update discountcoupons set used=used + 1 where code like '$db_row_c[0]'";
                                            mysqli_query($link, $db_query_inc);
                                       }
                                   }
                                }
				$db_query = "select isbn13,quantity from order_details where invoicenumber like '$invoicenumber'";
				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
                                    $db_query1 = "lock tables book_inventory write, usedbooks write";
                                    $db_result1 = mysqli_query($link, $db_query1);
					$db_row = mysqli_fetch_row($db_result);
					while ($db_row != null)
					{
					    $isbn = explode("#",$db_row[0]);
					    if (count($isbn) == 2)
					    {
						    $usedbookid = $isbn[0];
                                                    $isbn13 = $isbn[1];
						    $db_result1 = mysqli_query($link, "update usedbooks set status=4 where id=$usedbookid");
						    if ($db_result == TRUE)
						    {
                                                       generateemailtoseller($link, $usedbookid,$isbn13,$invoicenumber);
						    }

					    }
                                            else 
                                            {
                                                 $isbn13 = $db_row[0];
                                                 $db_query1 = "select shiptime,quantity from book_inventory where isbn13 like '$db_row[0]'";
                                                 $db_result1 = mysqli_query($link,  $db_query1);
                                                 if ($db_result1 == TRUE)
                                                 {
                                                     $db_row1 = mysqli_fetch_row($db_result1); 
                                                     if ($db_row1 != null)
                                                     {
                                                          if ($db_row[1] >= $db_row1[1])
                                                          {
                                                              $db_query1 = "update book_inventory set shiptime='Out Of Stock', quantity=0 where isbn13 like '$isbn13'";
                                                          }
                                                          else
                                                          {
                                                              $newstock = $db_row1[1] - $db_row[1];
                                                              if ($newstock > 4)
                                                                  $db_query1 = "update book_inventory set quantity=$newstock where isbn13 like '$isbn13'";
                                                              else
                                                                  $db_query1 = "update book_inventory set shiptime='5-7', quantity=$newstock where isbn13 like '$isbn13'";
                                                                  
                                                          }
                                                          mysqli_query($link,$db_query1);
                                                     }
                                                 }
                                            }
					    $db_row = mysqli_fetch_row($db_result);
					}
                                    $db_query1 = "unlock tables";
                                    $db_result1 = mysqli_query($link, $db_query1);
				}
		 } 
}

?>
<body onload="redirecto()">
<div class="container">
<div style="text-align:left;">
<div style="float:right;text-align:right;width:650px">
<span id="tline">&nbsp;
</span>
</div>
<div style="width:300px;float:left;text-align:center;padding-top:15px;"><a href="http://www.popabook.com"><img  title="HOME" width="279px" height="69px" src="http://www.popabook.com/popabook.gif" alt="PopAbooK.com" style="border-style:none;"></a></div>
<div style="width:650px;float:right;

<?php
echo "padding-top:10px;\"><span  class=\"logoatpo\" style=\"font-size:x-large;\">&nbsp;&nbsp;Up To 30% Off + FREE Shipping</span>";
?>
</div>
<?php
function showtabs()
{
        echo "<div style=\"clear:both;\">";
        echo "<div class=\"logoatp\" style=\"text-align:left;width:100px;float:right;padding-top:0px;\">"        ;
        echo "<a style=\"font-size:large;border:solid 1px #00008b;padding-top:3px;padding-bottom:3px;background-color:#2b60de;color:#ffffff;text-decoration:none;\" href=\"javascript:void(0)\" onclick=\"getsearchdata('search',null)\">&nbsp;GO&nbsp;</a>";
        echo "</div>";
        echo "<div class=\"logoatp\" style=\"width:340px;float:right;padding-top:0px;\">"        ;
        echo "<form  name=\"searchform\" onsubmit=\"return false;\"><input type=\"text\" size=\"50\" name=\"search_string\" onKeyPress=\"return checkEnter(event,'search','')\"></form>";
        echo "</div>";
        echo "<div class=\"logoatp\" style=\"text-align:right;width:170px;float:right;padding-top:0px;\">"        ;
        echo "Search Books:";
        echo "</div>";
echo "<div class=\"logoatp\" style=\"width:300px;float:right;padding-left:50px;padding-top:20px;\">";
           echo "<span onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='underline';starttimer();\" onmouseout=\"this.style.textDecoration='none';stoptimer();\" onclick=\"browse()\" style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('home')\">&nbsp;Browse BooKs&nbsp;<img width=\"8px\" height=\"8px\" src=\"http://www.popabook.com/dd.jpg\">&nbsp;</span>&nbsp;";
         echo "</div>";
         echo "</div>";
}
showtabs();

?>
<div style="text-align:left;width:100%;background-color:#2b60de; color:#ffffff; width:970px; height:2px; overflow:hidden;"></div>




<div style="text-align:left;margin:0 auto;width:970px;"> 
<div id="rightside" class="atp" style="text-align:left;float:right;vertical-align:top;">
<div id="rightsidecart"></div><div id="promotions">
</div>
</div>
<div class="dropdown" id="browseoptions" onmouseover="browse()" onmouseout="unbrowse()"><br>
<table width=100%" align="center" cellpadding="5px" class="blueatp"><tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('astrology');">Astrology</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('autobiography')">Autobiography</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('biography')">Biography/Memoir</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="unbrowse()" class="boldatp" align="right">Hide</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this);" onmouseout="outoflink(this);" onclick="bbsearch('business-management')">Business & Management</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('children')">Children</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('cinema')">Cinema</td> </tr>
<tr> <td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('crime')">Crime/Thriller/Suspense</td> 	<td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('current-affairs')">Current Affairs </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('dictionaries')">Dictionaries </td></tr>
<tr><td>&nbsp;</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('fiction')">Fiction</td>  <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('graphic-novel')"> Graphic Novel</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('history')"> History</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('home-garden')">Home & Garden </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('languages-self-study')" >Learn Languages </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('mind-body-spirit')">Mind, Body, Spirit </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('non-fiction')">Non-fiction</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('photography')"> 	Photography </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('poetry')">Poetry </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('politics')">Politics</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('psychology')"> Psychology </td>	<td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbbrowse('higher-education')">Higher Education</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)">Reference </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbbrowse('competitive-exams')">Competitive Exams </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('religion-philosophy')"> 	Religion / Philosophy </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('self-help')"> Self Help </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('short-stories')"> Short Stories </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('society')"> Society </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('spirituality')">Spirituality</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('sports')">Sports</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('cookery')">Cookery </td></tr><tr><td colspan="2">&nbsp;</td><td colspan="2" align="center" onmouseover="overlink(this)" onmouseout="outoflink(this)" class="boldatp" onclick="bbbrowse('browseall')">View Exhaustive Book Classification ...</td><td>&nbsp;</td></tr> </table>
</div>

<div id="centerdata">
<?php
		if ($payment_success == 1)
		 {
			echo "<div style=\"text-align:center;\"><h1> Your Payment is Successful.<br><br> Please Wait We Are Generating Invoice ... </h1></div>";
		 }
	         echo "<form name=\"red\" action=\"http://www.popabook.com\" method=\"GET\"> </form>";
?>

</div>

</div>


<div style="text-align:left;clear:right;">
<div class="pabheadingbackground" style="text-align:left;width:717px;text-align:left;">
<br><div style="text-align:center;">Browse Books</div><br>
<table width=100%" align="center" cellpadding="5px" class="blueatp"><tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('astrology');">Astrology</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('autobiography')">Autobiography</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('biography')">Biography/Memoir</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this);" onmouseout="outoflink(this);" onclick="bbsearch('business-management')">Business & Management</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('children')">Children</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('cinema')">Cinema</td> </tr>
<tr> <td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('crime')">Crime/Thriller/Suspense</td> 	<td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('current-affairs')">Current Affairs </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('dictionaries')">Dictionaries </td></tr>
<tr><td>&nbsp;</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('fiction')">Fiction</td>  <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('graphic-novel')"> Graphic Novel</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('history')"> History</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('home-garden')">Home & Garden </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('languages-self-study')" >Learn Languages </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('mind-body-spirit')">Mind, Body, Spirit </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('non-fiction')">Non-fiction</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('photography')"> 	Photography </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('poetry')">Poetry </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('politics')">Politics</td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('psychology')"> Psychology </td>	<td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbbrowse('higher-education')">Higher Education</td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)">Reference </td> <td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbbrowse('competitive-exams')">Competitive Exams </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('religion-philosophy')"> 	Religion / Philosophy </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('self-help')"> Self Help </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('short-stories')"> Short Stories </td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('society')"> Society </td></tr>
<tr><td>&nbsp;</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('spirituality')">Spirituality</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('sports')">Sports</td><td onmouseover="overlink(this)" onmouseout="outoflink(this)" onclick="bbsearch('cookery')">Cookery </td></tr><tr><td colspan="2">&nbsp;</td><td colspan="2" align="center" onmouseover="overlink(this)" onmouseout="outoflink(this)" class="boldatp" onclick="bbbrowse('browseall')">View Exhaustive Book Classification ...</td><td>&nbsp;</td></tr> </table>
</div>
</div>



<div style="text-align:left;clear:right;">
<div class="pabheadingbackground" style="width:717px;text-align:left;">
<table width="100%"><tr> <td align="center">
<span class="ulogoatp"> <br>Payments Powered By<br><br> </span><a href="http://www.ebs.in/" target="_blank"><img src="ebs.jpg" title="EBS" border="0"></a>
 </td> <td align="center" valign="top"><div style="border-left:1px solid #dddddd;border-bottom:1px solid #dddddd;">
<br><span class="ulogoatp">Site Secured By </span><br><br>
<span id="siteseal"><a href="javascript:verifySeal();"><img src="http://www.popabook.com/siteseal_gd_1_h_s_dv.png" width="115px" height="60px"></a><br></span>
</td> </tr>
</table>


</div>
</div>
<div id="footer" class="pabheadingbackground" style="text-align:center;clear:right;">
<a href="http://www.popabook.com" class="blueatp">Books</a> | <a class="blueatp" href="http://www.popabook.com/browseall">Browse Books</a> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('copyright','')"> Copyright </span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('termsconditions')"> Terms and Conditions</span> | <span class="blueatp" onmouseover="this.style.cursor='pointer';" onclick="getData('privacypolicy')"> Privacy Policy</span> <br><br>

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
