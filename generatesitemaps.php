<?php
set_time_limit(0);
require('dbconnect.php');
$link = wrap_mysqli_connect();
require_once('browsecatalog.php');
$db_query = "select isbn13, title,booklink from book_inventory where bookid < 25001 order by bookid desc"; 
/*$db_query = "select isbn13, title,booklink from book_inventory where bookid < 50001 and bookid > 25000 order by bookid desc";*/

/* $db_query = "select isbn13, title,booklink from book_inventory where bookid > 50000 order by bookid desc";  */
$db_result = mysqli_query($link,$db_query);
if ($db_result == TRUE)
{
	$db_row=mysqli_fetch_row($db_result);
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        
	while ($db_row != null)
	{
		echo "<url>";
		echo "<loc>";
		echo "http://www.popabook.com/";
                $link = $db_row[2] . "/" . $db_row[0];
                echo $link;
		echo "</loc>";
		echo "<lastmod>";
		echo  date("Y-m-d");
		echo "</lastmod>";
		echo "<changefreq>monthly</changefreq>";
		echo "<priority>0.5</priority>";
		echo "</url>";
		$db_row=mysqli_fetch_row($db_result);
	}
	echo "</urlset>";
}
function cleanlink($link)
{
      /*$rtlink = "";
      $len = strlen($link);
      $tmp = 0;
      while ($tmp < $len)
      {
         $ch = substr($link,$tmp,1);
         if ((ctype_alnum($ch) == TRUE) || $ch == '-' || $ch == '/')
               $rtlink = $rtlink . $ch;
         $tmp = $tmp + 1;
      }*/
    return $link;
}


?>
