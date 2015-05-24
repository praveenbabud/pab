<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$session_id = session_id();
require('dbconnect.php');
require('browsecatalog.php');
require_once('pab_util.php');
$link = wrap_mysqli_connect();
$db_error = "";
$reviews = "";
$viewallbook = "";
$output = "";
$imagelarge = "";
if(isset($_POST['reqtype']) == FALSE)
    exit;
$reqtype = $_POST['reqtype'];
$storeurl = "";
$linkurl = "";
if (isset($_POST['storeurl']) == TRUE)
    $storeurl = $_POST['storeurl'];
if (isset($_POST['linkurl']) == TRUE)
    $linkurl = $_POST['linkurl'];
if($reqtype == "getviewallbook")
{
  if($link != null)
  {
       $isbn13 = $_POST['isbn13'];
       $arr = explode("!",$isbn13);
       $isbn13 = $arr[1];
       $title = ""; $metadata = ""; $retcode = "";
       $db_query = "lock tables usedbooks read,book_inventory read, similarbooks read";
        $db_result = mysqli_query($link, $db_query);
       $db_query = "select bookid, title, author1, boolblurb, blurb, booklink,author2, author3,author4,nlistprice,nourprice,currency,pages,isbn10,edition,publisher from book_inventory where isbn13 like '$isbn13'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
          $db_row = mysqli_fetch_row($db_result);
          if ($db_row != null)
          {
             if ($storeurl == "" && $linkurl == "")
                 $booklink = $db_row[5] . "/" .  $isbn13;
             else if ($linkurl != "")
                $booklink = $db_row[5] . "/" .  $isbn13 . "/" . $linkurl . "/af";
             else if ($storeurl != "")
                $booklink = $db_row[5] . "/" .  $isbn13 . "/" . $storeurl . "/bookstore";
             $xid = rawurlencode("http://www.PopAbooK.com/book/$isbn13");
             if (file_exists("/var/apache2/2.2/htdocs/" . $isbn13 . ".jpg"))
                      $imagelarge = $isbn13 . ".jpg";
             else if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $isbn13 . ".jpg"))
                      $imagelarge = "optimage/$isbn13" . ".jpg";
             else
                    $imagelarge = "ina.jpg";
             $simbooks = "";
             if ($storeurl == "")
                 $simbooks = showsimilarbooksonpage($link, $db_row[0], 0,$linkurl);
             if ($db_row[3] == 1)
                  $output = $output . "<div style=\"line-height:1.5em;text-align:left;padding-left:15px;\"><h3>Book Overview: $db_row[1]</h3><br><br><span class=\"atp\">$db_row[4]</span></div>";
             $output = $output . "<div style=\"text-align:left;padding-left:15px;\"><br><h3>Share comments/reviews, on $db_row[1], with your friends</h3><br><br><div style=\"text-align:left;padding-left:20px;\">";
             $output = $output . "<fb:comments xid=\"$xid\" url=\"http://www.PopAbooK.com/$booklink\" title=\"$db_row[1] by $db_row[2]\" width=\"675\" numposts=\"5\">  </fb:comments></div><br><br></div>";
            if ($simbooks != "")
            {
               $output = $output . "<div style=\"text-align:left;padding-left:15px;\"><a name=\"sb\"></a><br><h3>Similar Books : $db_row[1]</h3></div>$simbooks";
            }
            $output = $output . "<div style=\"text-align:left;padding-left:15px;\"><br><h3>Details of Book : $db_row[1]</h3><br><br> <b>Title</b> : $db_row[1]<br><b>Authors</b> : $db_row[2] $db_row[6] $db_row[7] $db_row[8]";
            if ($db_row[14]!= NULL)
                 $output = $output . "<br><b>Edition</b> : $db_row[14]";
            $listprice = converttoinr($db_row[11],$db_row[9]);
            $ourprice = converttoinr($db_row[11],$db_row[10]);
            $output = $output . "<br><b>Listprice</b> : $listprice<br><b>Our Price</b> : $ourprice";
            if ($db_row[12]!= NULL)
                $output = $output . "<br><b>Pages</b> : $db_row[12]";
            if ($db_row[13]!= NULL)
                $output = $output . "<br><b>ISBN-10</b> : $db_row[13]";
            $output = $output . "<br>ISBN-13 : $isbn13<br>Publisher : $db_row[15]</div>";
            $output = $output . "</div>";
                 $output = $output . "<br><br><div style=\"text-align:center\"><h3>Book : $db_row[1]</h3><h4> <br>by $db_row[2] <br>ISBN:$isbn13</h4></div><div class=\"pabborder\"></div>";

  	}
     }
     $db_query = "unlock tables";
        $db_result = mysqli_query($link, $db_query);
   }
   if ($output == "")
      echo "Server is Temporarily Unavailable. Please Try Later";
   else 
      echo $output;
}
?>

