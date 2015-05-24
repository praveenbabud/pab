<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$rootloc = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:fb="http://www.facebook.com/2008/fbml" lang="en" xml:lang="en">
<head><title>PopAbooK.com</title>
</head>
<body>
<?php
require_once('dbconnect.php');
require_once('pab_util.php');
$link = wrap_mysqli_connect();

if (isset($_GET['pabstore']) == TRUE)
{
	$storeurl = $_GET['pabstore'];
	$db_query = "select storename from affiliates where storeurl like '$storeurl'";
	$db_result = mysqli_query($link, $db_query);
	$storename = "";
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if ($db_row != null)
		{
			$storename = $db_row[0];
		}
		else
			exit;
	}
	else
		exit;
	$db_query = "select isbn13 from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl') order by id desc limit 2";
	$db_result = mysqli_query($link, $db_query);
	echo "<div style=\"text-align:center;border:solid 1px #00008b;\">";
	echo "<div onmouseover=\"this.style.cursor='pointer';\"  style=\"border-bottom:solid 1px #00008b;font-family:verdana,georgia;font-weight:bold;font-size:small;background-color:#0000ff;color:#ffffff;padding-top:5px;padding-bottom:5px;\" <a style=\"text-decoration:none;color:white;\" href=\"http://www.PopAbooK.com/$storeurl/bookstore\" target=\"_blank\">$storename</a></div>";
	echo "<div style=\"font-family:verdana,georgia;font-weight:bold;font-size:small;color:black;\"><br>New Books in Store</div>";
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		while ($db_row != null)
		{
			$db_query1 = "select title, author1, nlistprice, nourprice, booklink,currency  from book_inventory where isbn13 like '$db_row[0]'";
			$db_result1 = mysqli_query($link, $db_query1);
			if ($db_result1 == TRUE)
			{
				$db_row1 = mysqli_fetch_row($db_result1);
				if ($db_row1 != null)
				{
                                        $listprice = converttoinr($db_row1[5],$db_row1[2]);
                                        $ourprice = converttoinr($db_row1[5],$db_row1[3]);
					if (file_exists("/var/apache2/2.2/htdocs/optimage/". $db_row[0] . ".jpg") == TRUE)
						$image = "http://www.popabook.com/optimage/" . $db_row[0] . ".jpg";
					else
						$image = "http://www.popabook.com/optimage/tmpina.jpg";
					$save = $listprice - $ourprice;
					$booklink = "http://www.PopAbooK.com/$db_row1[4]/$db_row[0]/$storeurl/bookstore";
					echo "<table align=\"center\"><tr><td align=\"center\"><a href=\"$booklink\" target=\"_blank\"> <img alt=\"$db_row1[0] by $db_row1[1] ISBN:$db_row[0]\" src=\"$image\" style=\"border-style:none;\" width=\"70px\" height=\"100px\"></a>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;color:blue;\"><a target=\"_blank\" href=\"$booklink\">$db_row1[0]</a></div>";
					echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;color:black;\">by <a target=\"_blank\" href=\"$booklink\">$db_row1[1]</a></div>";
                      if ($save != 0)
                      {
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;color:black;\">Rs. <s>$listprice</s></div>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:large;color:black;\"><span style=\"font-size:small;\">Rs.</span> $ourprice <img stye=\"border-style:none;display:inline;\" src=\"http://www.popabook.com/savenew.jpg\" width=\"12px\" height=\"16px\"> <span style=\"font-size:small;\">Rs.</span> $save</div>";
                      }
                      else
                      {
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:large;color:black;\"><span style=\"font-size:small;\">Rs.</span> $ourprice</div>";
                      }
					echo "</td></tr></table>";
				}
			}
		$db_row = mysqli_fetch_row($db_result);
		}
	}
	echo "<div style=\"text-align:left;border-bottom:solid 1px #00008b;border-top:solid 1px #00008b;\"><a  target=\"_blank\" href=\"http://www.popabook.com/$storeurl/bookstore\"><img src=\"popabook.gif\" width=\"150px\" style=\"border-style:none;\" height=\"37px\"></a><br></div>";
	echo "</div>";
	exit;
}
else if (isset($_GET['nr']) == TRUE)
{
        $affid = $_GET['nr'];
        $affid = strtolower($affid);
        $blext = "";
        $booklink = "";
        if ($affid != "" && $affid != "popabook")
             $blext = "/$affid/af";
        else
             $blext = "";
	$db_query = "select max(id) from newbooks";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		$maxnewbooks = $db_row[0];
        }
        else
	{
		exit;
        }
    if ($maxnewbooks > 18)
	    $low = $maxnewbooks - 18;
    else
	    $low = 1;
	$tempid = mt_rand($low, $maxnewbooks);
	$db_query = "select title, author1, isbn13, nlistprice , nourprice, booklink,currency from book_inventory where bookid in (select bookid from newbooks where id=$tempid)";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if ($db_row != null)
		{
                      $listprice = converttoinr($db_row[6],$db_row[3]);
                      $ourprice = converttoinr($db_row[6],$db_row[4]);
                      $booklink = $db_row[5] . "/$db_row[2]" . $blext;
                      
			$save = $listprice - $ourprice;
		      if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2] . ".jpg") == TRUE)
			      $image = "http://www.PopAbooK.com/optimage/". $db_row[2] . ".jpg";
		      else
			      $image = "http://www.PopAbooK.com/optimage/". "tmpina.jpg";
		      echo "<div style=\"text-align:center;border:solid 1px #00008b;\">";
		      echo "<div style=\"text-align:left;border-bottom:solid 1px #00008b;\"><a href=\"http://www.popabook.com$blext\" target=\"_blank\"><img style=\"border-style:none;\" src=\"popabook.gif\" width=\"150px\" height=\"37px\"></a></div>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:bold;font-size:small;color:black;\"><br>New Releases<br><br></div>";
		      echo "<a href=\"http://www.popabook.com/$booklink\" target=\"_blank\"><img style=\"border-style:none;\" src=\"$image\" width=\"70px\" height=\"100px\"></a>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;\"><a href=\"http://www.popabook.com/$booklink\" target=\"_blank\">$db_row[0]</a></div>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;color:black;\">by <a href=\"http://www.popabook.com/$booklink\" target=\"_blank\">$db_row[1]</a></div>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:small;color:black;\">Rs. <s>$listprice</s></div>";
		      echo "<div style=\"font-family:verdana,georgia;font-weight:lighter;font-size:large;color:black;\"><span style=\"font-size:small;\">Rs.</span> $ourprice <img stye=\"border-style:none;display:inline;\" src=\"http://www.popabook.com/savenew.jpg\" width=\"12px\" height=\"16px\"> <span style=\"font-size:small;\">Rs.</span> $save</div>";
		/*      echo "<div><br><a href=\"http://www.popabook.com/$booklink\" style=\"text-decoration:none;font-family:verdana,georgia;font-weight:bold;font-size:medium;width:100%;padding-top:3px;padding-bottom:3px;border:solid 1px #00008b;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#eeeeee';this.style.color='#2b60de';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\">&nbsp;Pop The BooK&nbsp;</a></div><br>";*/
	echo "<div style=\"text-align:left;border-bottom:solid 1px #00008b;border-top:solid 1px #00008b;\"><a  href=\"http://www.popabook.com$blext\" target=\"_blank\"><img src=\"http://www.popabook.com/popabook.gif\" width=\"150px\" style=\"border-style:none;\" height=\"37px\"></a><br></div>";
		      echo "</div>";
		}
	}
} 
else if (isset($_GET['sb']) == TRUE)
{
        $affid = $_GET['sb'];
        $affid = strtolower($affid);
        $urlext = "";
        if ($affid != "" && $affid != "popabook")
           $urlext = $affid . "/af";
	echo "<div style=\"text-align:center;border:solid 1px #00008b;\">";
        echo "<div style=\"font-family:verdana,georgia;text-align:center;padding-top:5px;overflow:hidden;border-bottom:solid 1px #00008b;padding-bottom:5px;background-color:#0000ff;color:#ffffff;font-weight:bold;font-size:x-small;\">Search Books @ PopAbooK.com:</div>";
echo "<div style=\"padding-top:5px;padding-bottom:5px;text-align:center;\"><form id=\"popabooksearchwidget\" method=\"POST\" target=\"_blank\" action=\"http://www.popabook.com/$urlext\"><table width=\"100%\" align=\"center\"><tr><td width=\"100%\" valign=\"top\" align=\"center\"><input type=\"text\" name=\"search_string\"></td><td>&nbsp;";
		    echo "<span><a href=\"javascript:void(0)\" onclick=\"popabooksearchwidget();\" style=\"text-decoration:none;font-family:verdana,georgia;font-weight:bold;font-size:small;width:100%;border:solid 1px #0000ff;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';\"  onmousedown=\"this.style.backgroundColor='#00008b';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\">&nbsp;GO&nbsp;</a></span></td></tr></table>";
	echo "</form></div>";
	echo "<div style=\"text-align:left;border-bottom:solid 1px #00008b;border-top:solid 1px #00008b;\"><a target=\"_blank\" href=\"http://www.popabook.com/$urlext\"><img style=\"border-style:none;\" src=\"http://www.popabook.com/popabook.gif\" width=\"150px\" height=\"37px\"></a><br></div></div>";
	echo "<script type=\"text/javascript\">function popabooksearchwidget() { theform = document.getElementById('popabooksearchwidget'); theform.submit();} </script>";
}
        
?>

</body>
</html>
