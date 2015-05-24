<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$rootloc = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:fb="http://www.facebook.com/2008/fbml" lang="en" xml:lang="en">
<head>
<LINK REL=StyleSheet HREF="http://www.popabook.com/stylev12.css" TYPE="text/css">
<link rel="shortcut icon" href="http://www.popabook.com/favicon2.ico">
<?php
require_once('dbconnect.php');
require_once('pab_util.php');
 $link = wrap_mysqli_connect();
 $session_id = session_id();
require_once('browsecatalog.php');
$browsecatalog = "";
$storeurl = "";
$linkurl = "";
$storename = "";
$title = "";
$metadata = "";
$browseoutput = "";
$searchstring = "";
if (isset($_POST['search_string']) == TRUE)
{
    $searchstring = $_POST['search_string'];
}
	if (isset($_GET['browse']) == TRUE || $searchstring != "")
	{
                if (isset($_GET['browse']) == TRUE)
		    $browsecatalog = $_GET['browse'];
                else
                    $browsecatalog = $searchstring; 
		$dbstr = rawurlencode($browsecatalog);
		mysqli_query($link,"insert into sessions_searches (session,search) values ('$session_id','$dbstr')");
		$retcode = 0;
                if ($searchstring != "")
		$browseoutput = browsecatalog($link, $browsecatalog, $title, $metadata,0,$retcode, $storeurl,1,$linkurl);
                else
		$browseoutput = browsecatalog($link, $browsecatalog, $title, $metadata,1,$retcode, $storeurl,1,$linkurl);
                if ($storeurl != "")
                {
                    $storename = $_SESSION['__PABstorename'];
                }
	}
	if ($title == "")
		echo "<title>PopAbooK.com : Online Book Store. Buy New and Used Books Online in India.</title>";
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
echo "<script type=\"text/javascript\">";
echo "var userat=\"home\";";
echo "var testbase=\"http://www.popabook.com/\";";
echo "function getstoreurl() { return '$storeurl';}";
echo "function getlinkurl() { return '$linkurl';}";
echo "function loadpopulars(){";
echo "var promo = document.getElementById(\"promotions\");";
   require_once('populars.php');
if ($storeurl == "")
$pops = showpopulars($link,0);
else
$pops = storeshowpopulars($link,$storeurl);
echo "promo.innerHTML = \"$pops\";";
echo "}"; 
 echo "function loaduserdata() {";
 echo "loadpopulars();"; 
 echo "fillbbb();";
 echo "filltbb();";
 echo "var tline = document.getElementById('tline');";
 echo "tline.innerHTML = \"<span id=\\\"logindata\\\" class=\\\"atp\\\"></span><span id=\\\"loginlogout\\\" class=\\\"atp\\\">Login</span> | <span class=\\\"ublueatp\\\" onclick=\\\"showuseraccount('myaccount')\\\" onmouseover=\\\"this.style.cursor='pointer'; this.style.textDecoration ='none';\\\" onmouseout=\\\"this.style.textDecoration ='underline';\\\">Your Account</span> | <span class=\\\"ublueatp\\\" onclick=\\\"contactus()\\\" onmouseover=\\\"this.style.cursor='pointer'; this.style.textDecoration ='none';\\\" onmouseout=\\\"this.style.textDecoration ='underline';\\\">Contact Us</span> | <span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer'; this.style.textDecoration ='none';\\\" onclick=\\\"getData('help')\\\" onmouseout=\\\"this.style.textDecoration ='underline';\\\">Help</span> <span> &nbsp;</span>\";";
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
				 echo "logindata.innerHTML='Hello $email&nbsp;';";
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
 echo "function loadwishlist() {";
 if ($link != null)
 {
	 if (isset($_SESSION['email']) == TRUE)
	 {
		$email = $_SESSION['email'];
		$db_query = "select isbn13 from wishlists where customerid in (select customer_id from customers where email like '$email')"; 	 
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			while ($db_row != null)
			{
                                $isbn13 = $db_row[0];
				$db_query1 = "select title, nlistprice, nourprice, shiptime,currency from book_inventory where isbn13 like '$db_row[0]'";
				$db_result1 = mysqli_query($link, $db_query1);
				if ($db_result1 == TRUE)
				{
					$db_row1 = mysqli_fetch_row($db_result1);
					if ($db_row1 != null)
					{
						if(file_exists("/var/apache2/2.2/htdocs/optimage/" . $isbn13 . ".jpg"))
							$image = "$isbn13.jpg";
						else
							$image = "tmpina.jpg";
						$title = rawurlencode($db_row1[0]);
                                                $listprice = converttoinr($db_row1[4],$db_row1[1]);
                                                $ourprice = converttoinr($db_row1[4],$db_row1[2]);
						echo "addtowishlistonload('$db_row[0]', '$title', '$image', $listprice,$ourprice,'$db_row1[3]');";
					}
				}
				$db_row = mysqli_fetch_row($db_result);
			}
		
		}
	 }
 }
 echo ";}";
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
		   $db_query1 = "select title,nlistprice,nourprice,shiptime,currency from book_inventory where isbn13 like '$db_row[1]'";
		   $db_result1 = mysqli_query($link, $db_query1);
		   if ($db_result1 == TRUE)
		   {
			   $db_row1 = mysqli_fetch_row($db_result1);
			   if ($db_row1 != null)
			   {
                                                $listprice = converttoinr($db_row1[4],$db_row1[1]);
                                                $ourprice = converttoinr($db_row1[4],$db_row1[2]);
				   if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[1] . ".jpg"))
					   echo "addtocartonload('" . $db_row[1] . ".jpg'," . $listprice . ",$ourprice,'" . $db_row[1] . "',\"$db_row1[0]\",$db_row[0],'$db_row1[3]');";
				   else
				   	echo "addtocartonload('tmpina.jpg'," . $listprice . ",$ourprice,'" . $db_row[1] . "',\"$db_row1[0]\",$db_row[0],'$db_row1[3]');";

			   }
		   }
		   $db_row = mysqli_fetch_row($db_result);
	   }
	}
  }
  echo "showcart();";
  
  echo "}";

?>
function verifySeal() {
		var bgHeight = "433";
		var bgWidth = "536";
		var url = "https://seal.godaddy.com:443/verifySeal?sealID=w9FOkLHyzZ066mrdpFmp7sXeOeOj9XTsRUqcveO7edaoQ74M6nPhnBN";
		window.open(url,'SealVerfication','location=yes,status=yes,resizable=yes,scrollbars=no,width=' + bgWidth + ',height=' + bgHeight);
	}
function fillbbb()
{
     var bbbdiv = document.getElementById("bbb");
     if (bbbdiv != null)
     bbbdiv.innerHTML = "<table width=\"100%\" align=\"center\" cellpadding=\"5px\" class=\"blueatp\"><tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('astrology');\">Astrology</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('autobiography')\">Autobiography</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('biography')\">Biography/Memoir</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this);\" onmouseout=\"outoflink(this);\" onclick=\"bbsearch('business-management')\">Business & Management</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('children')\">Children</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('cinema')\">Cinema</td> </tr> <tr> <td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('crime')\">Crime/Thriller/Suspense</td> 	<td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('current-affairs')\">Current Affairs </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('dictionaries')\">Dictionaries </td></tr> <tr><td>&nbsp;</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('fiction')\">Fiction</td>  <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('graphic-novel')\"> Graphic Novel</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('history')\"> History</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('home-garden')\">Home & Garden </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('languages-self-study')\" >Learn Languages </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('mind-body-spirit')\">Mind, Body, Spirit </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('non-fiction')\">Non-fiction</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('photography')\"> 	Photography </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('poetry')\">Poetry </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('politics')\">Politics</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('psychology')\"> Psychology </td>	<td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbbrowse('higher-education')\">Higher Education</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\">Reference </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbbrowse('competitive-exams')\">Competitive Exams </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('religion-philosophy')\"> 	Religion / Philosophy </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('self-help')\"> Self Help </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('short-stories')\"> Short Stories </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('society')\"> Society </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('spirituality')\">Spirituality</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('sports')\">Sports</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('cookery')\">Cookery </td></tr><tr><td colspan=\"2\">&nbsp;</td><td colspan=\"2\" align=\"center\" onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" class=\"boldatp\" onclick=\"bbbrowse('browseall')\">View Exhaustive Book Classification ...</td><td>&nbsp;</td></tr> </table>";
}
function filltbb()
{
     var tbbdiv = document.getElementById("browseoptions");
     if (tbbdiv != null)
     tbbdiv.innerHTML = "<br> <table width=\"100%\" align=\"center\" cellpadding=\"5px\" class=\"blueatp\"><tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('astrology');\">Astrology</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('autobiography')\">Autobiography</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('biography')\">Biography/Memoir</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"unbrowse()\" class=\"boldatp\" align=\"right\">Hide</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this);\" onmouseout=\"outoflink(this);\" onclick=\"bbsearch('business-management')\">Business & Management</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('children')\">Children</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('cinema')\">Cinema</td> </tr> <tr> <td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('crime')\">Crime/Thriller/Suspense</td> 	<td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('current-affairs')\">Current Affairs </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('dictionaries')\">Dictionaries </td></tr> <tr><td>&nbsp;</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('fiction')\">Fiction</td>  <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('graphic-novel')\"> Graphic Novel</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('history')\"> History</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('home-garden')\">Home & Garden </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('languages-self-study')\" >Learn Languages </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('mind-body-spirit')\">Mind, Body, Spirit </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('non-fiction')\">Non-fiction</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('photography')\"> 	Photography </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('poetry')\">Poetry </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('politics')\">Politics</td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('psychology')\"> Psychology </td>	<td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbbrowse('higher-education')\">Higher Education</td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\">Reference </td> <td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbbrowse('competitive-exams')\">Competitive Exams </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('religion-philosophy')\"> 	Religion / Philosophy </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('self-help')\"> Self Help </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('short-stories')\"> Short Stories </td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('society')\"> Society </td></tr> <tr><td>&nbsp;</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('spirituality')\">Spirituality</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('sports')\">Sports</td><td onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" onclick=\"bbsearch('cookery')\">Cookery </td></tr><tr><td colspan=\"2\">&nbsp;</td><td colspan=\"2\" align=\"center\" onmouseover=\"overlink(this)\" onmouseout=\"outoflink(this)\" class=\"boldatp\" onclick=\"bbbrowse('browseall')\">View Exhaustive Book Classification ...</td><td>&nbsp;</td></tr> </table>";
}
function getsearchdata(req,reqdata)
{
        var storeurl = getstoreurl();
                  if (storeurl != "")
                     storeurl = "&storeurl=" + storeurl;
                  var linkurl = getlinkurl();
                  if (linkurl != "")
                     linkurl = "&linkurl=" + linkurl;

         var XMLHttpRequestObject = false;
         if (window.XMLHttpRequest)
         {
            XMLHttpRequestObject = new XMLHttpRequest();
         }
         else if (window.ActiveXObject)
         {
           XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
         }
         if (XMLHttpRequestObject)
         {
            if (req == "search")
            {
                 if (reqdata == null)
                 {
                    if (document.searchform.search_string.value == "")
                      return;
                 }
                 else
                 {
                     if(reqdata == "search_string=")
                                           return;
                 }
                 XMLHttpRequestObject.open("POST", testbase + "searchdata.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                  if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                  {
                      display_response_at_center(XMLHttpRequestObject.responseText)
                      <!-- process_xml_response();   -->
                      delete XMLHttpRequestObject;
                      XMLHttpRequestObject = null;
                  }
                 }
                 if (reqdata == null)
                 {
                      XMLHttpRequestObject.send("search_string=" + encodeURIComponent(document.searchform.search_string.value) + storeurl + linkurl);
                 }
                 else
                 {
                      XMLHttpRequestObject.send(reqdata + storeurl);
                 }
                 loading();
                 userat = "";

            }
             else if (req == "getbook")
                        {
                                XMLHttpRequestObject.open("POST", testbase  + "getbook.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            display_response_at_center(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                            FB.XFBML.parse();
                       }
                   }
                                           XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata + storeurl + linkurl);
                             /*userat = "getbook"; */
                             loading();
                               userat = "";

                        }
                        else if (req == "browse")
                        {
                                XMLHttpRequestObject.open("POST",testbase + "browse.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            display_response_at_center(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
                                   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata + storeurl + linkurl);
                           userat = "";
                           loading();
                        }


         }

}
function display_response_at_center(xmldoc)
{
        var centerdata = document.getElementById("centerdata");
        centerdata.innerHTML = xmldoc;
}
function checkEnter(e,page,form)
{

     var characterCode;

         if(e && e.which)
         {
                e = e;
                characterCode = e.which;
        }
         else
         {
                characterCode = e.keyCode;
         }

         if(characterCode == 13){
                 if (page == "search")
                 {
                getsearchdata('search',null);
                 }
                 else if (page == "login")
                 {
                         processloginform(form);
                 }
else if (page == "confirm")
                 {
                         confirmorder('online');
                 }
                 else if (page == "salesaccount")
                 {
                       updatesalesaccount(form);
                 }
                 else if (page == "sellbooks")
                 {
                      sellbook(form);
                 }
                            if (e.preventDefault) e.preventDefault();
       if (e.stopPropagation) e.stopPropagation();
                return false;
}
else{
return true;
}

}

function loading()
{
    var height = screen.height;
    var width = screen.width/2  - 250 - 33;
    var thehtml = "<div style=\"text-align:left;position:absolute;top:200px;left:" + width + "px;width:33px;height:33px;\"><img src=\"http://www.popabook.com/loading.gif\"></div>";
    var ldata = document.getElementById("loginformerror");
    if (ldata != null)
    {
     ldata.innerHTML = thehtml;
    }
    else
    {
    var cdata = document.getElementById("centerdata");
     cdata.innerHTML = cdata.innerHTML + thehtml;
    }
        window.scrollTo(0,0);
}

			
</script>


</head>

<body onload="doitonload()">
<div class="container">
<div style="text-align:left;">
<div style="float:right;text-align:right;width:650px">
<span id="tline">&nbsp;
</span>
</div>
<div style="width:300px;float:left;text-align:center;padding-top:15px;"><a href="http://www.popabook.com"><img  title="HOME" width="279px" height="69px" src="http://www.popabook.com/popabook.gif" alt="PopAbooK.com" style="border-style:none;"></a></div>
<div style="width:650px;float:right;

<?php
if ($storename != "")
{
    echo "padding-top:5px;\"><a style=\"text-decoration:none;\" href=\"http://www.popabook.com/$storeurl\"><h1 style=\"color:#ff8c00;\">$storename</h1></a>";
    echo "<table><tr><td>";
echo "<div style=\"padding-top:3px;padding-bottom:3px;\"><script src=\"http://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like href=\"http://www.popabook.com/$storeurl\" layout=\"button_count\" show_faces=\"false\" width=\"100\" font=\"verdana\"></fb:like></div>";
    echo "</td><td align=\"left\">";
    echo "<script src=\"http://platform.twitter.com/widgets.js\" type=\"text/javascript\"></script> <div> <a href=\"http://twitter.com/share\" data-url=\"http://www.popabook.com/$storeurl\" data-via=\"PopAbooK\" data-text=\"Checking out $storename\" data-related=\"popabook:Social Book Store\" data-count=\"horizontal\" class=\"twitter-share-button\">Tweet</a> </div>";
    echo "</td></tr></table>";

}
else
echo "padding-top:10px;\"><span  class=\"logoatpo\" style=\"font-size:x-large;\">&nbsp;&nbsp;Up To 30% Off + FREE Shipping</span>";
?>
</div>
</div>

<?php
function showtabs($__pabstorename)
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
        if ($__pabstorename == "")
        {

        echo "<div class=\"logoatp\" style=\"width:300px;float:right;padding-left:50px;padding-top:20px;\">";
           echo "<span onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='underline';starttimer();\" onmouseout=\"this.style.textDecoration='none';stoptimer();\" onclick=\"browse()\" style=\"background-color:#2b60de;color:#ffffff;\" style=\"color:#ffffff;\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"validateinput('home')\">&nbsp;Browse BooKs&nbsp;<img width=\"8px\" height=\"8px\" src=\"http://www.popabook.com/dd.jpg\">&nbsp;</span>&nbsp;";
         echo "</div>";
        }
        else
           echo "<br><br>";
         echo "</div>";
}
showtabs($storename);

?>
<div style="text-align:left;width:100%;background-color:#2b60de; color:#ffffff; width:970px; height:2px; overflow:hidden;"></div>


<div style="text-align:left;margin:0 auto;width:970px;"> 
<div id="rightside" class="atp" style="text-align:left;float:right;vertical-align:top;">
<div id="rightsidecart"></div><div id="promotions">
</div>
</div>
<div class="dropdown" id="browseoptions" onmouseover="browse()" onmouseout="unbrowse()">
</div>

<div id="centerdata">
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
     if ($browseoutput != "")
	        echo $browseoutput;	
	else
	{
		if ($storeurl == "")
			homedata($link);
		else
			storehomedata($link, $storeurl);
	}
}
?>
</div>
</div>


<div style="text-align:left;clear:right;">
<?php
if ($storename == "")
{

 echo "<div class=\"pabheadingbackground\" style=\"text-align:left;width:715px;text-align:left;\"> <br>";
 echo "<div style=\"text-align:center;\">Browse Books</div><br> <div id=\"bbb\"> </div> </div>";
}
?>
</div>





<div style="text-align:left;clear:right;">
<div class="pabheadingbackground" style="width:715px;text-align:left;">
<table width="100%"><tr> <td align="center">
<span class="ulogoatp"> <br>Payments Powered By<br><br> </span><a href="http://www.ebs.in/" target="_blank"><img width="100px" height="100px" src="http://www.popabook.com/ebs.jpg" title="EBS" border="0"></a>
 </td> <td align="center" valign="top"><div style="border-left:1px solid #dddddd;border-bottom:1px solid #dddddd;">
<br><span class="ulogoatp">Site Secured By </span><br><br>
<span id="siteseal"><a href="javascript:verifySeal();"><img src="http://www.popabook.com/siteseal_gd_1_h_s_dv.png" width="115px" height="60px"></a><br></span></div>
</td> </tr>
</table>


</div>
</div>
<div id="footer" class="pabheadingbackground" style="text-align:center;clear:right;">
<a href="http://www.popabook.com" class="blueatp">Books</a> | <a class="blueatp" href="http://www.popabook.com/browseall">Browse Books</a> | <a href="http://blog.popabook.com" class="blueatp">Blog</a> | <a href="#" class="blueatp" onclick="getData('copyright','')">Copyright </a> | <a href="#" class="blueatp" onclick="getData('termsconditions')"> Terms and Conditions</a> | <a href="#" class="blueatp" onclick="getData('privacypolicy')">Privacy Policy</a> | <a href="#" class="blueatp">Search Books</a> | <a href="http://www.popabook.com/affiliates.php" class="blueatp">Affiliates</a> | <a href="http://www.popabook.com/directoryofbooks.php" class="blueatp">All Books</a><br><br>

</div>

</div>


<script type="text/javascript" src="http://www.popabook.com/pabv11.js"></script>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId  : '108755752503876',
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml  : true  // parse XFBML
    });
    FB.Event.subscribe('auth.sessionChange', function(response) {
      FB.XFBML.parse();
    });

  };

  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
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
