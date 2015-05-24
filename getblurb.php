<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$session_id = session_id();
require('dbconnect.php');
require('browsecatalog.php');
$link = wrap_mysqli_connect();
$db_error = "";

if(isset($_POST['reqtype']) == FALSE)
    exit;
$reqtype = $_POST['reqtype'];
$reviews = "";
$blurb = "";
$storeurl = "";
$linkurl = "";
if (isset($_POST['storeurl']) == TRUE)
    $storeurl = $_POST['storeurl'];
if (isset($_POST['linkurl']) == TRUE)
    $linkurl = $_POST['linkurl'];

if($reqtype == "getblurb")
{
  if($link != null)
  {
       $isbn13 = $_POST['isbn13'];
       $arr = explode("!",$isbn13);
       $isbn13 = $arr[1];
       $db_query = "select blurb,title,author1,booklink from book_inventory where isbn13 like '$isbn13'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) == 1)
           {
		   		$db_row = mysqli_fetch_row($db_result);
				$blurb = $db_row[0];
                                $booklink = $db_row[3] . "/" . $isbn13;
                                if ($linkurl != "")
                                    $booklink = $booklink . "/" . $linkurl . "/af";
                                else if ($storeurl != "")
                                    $booklink = $booklink . "/" . $storeurl. "/bookstore";
                                $xid = rawurlencode("http://www.PopAbooK.com/book/$isbn13");
                                $reviews = "<br><br><h3>Share comments/reviews, on $db_row[1], with your friends</h3><br><br><div style=\"text-align:left;padding-left:20px;\"> <fb:comments xid=\"$xid\" url=\"http://www.PopAbooK.com/$booklink\" title=\"$db_row[1] by $db_row[2]\" width=\"675\" numposts=\"5\">  </fb:comments></div>";
		   }
		   else
		   {
			   $db_error = "Server is Temporarily Unavailable. Please Try Later";
		   }
       }
       else
       {
           $db_error = "Server is Temporarily Unavailable. Please Try Later";
       }
  }
  else
  {
      $db_error = "Server is Temporarily Unavailable. Please Try Later";
  }
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
	echo "<div class=\"boxh\" style=\"line-height:1.5em;text-align:left;padding-left:15px;\"><h3>Book Overview : $db_row[1]</h3><br><br><h4>$blurb</h4>$reviews<br><br></div>";
  }

}

?>

