<?php
set_time_limit(0);
require('dbconnect.php');
$link = wrap_mysqli_connect();
require_once('browsecatalog.php');
$db_query = "select name from subsubproducts";
$escape = ", ;:.\/\"'\n\r-()?&\t";
$db_result = mysqli_query($link,$db_query);
$othercats = array("Astrology", "Autobiography", "Biography", "Memoir", "Business", "Management", "Children", "Cinema", "Crime", "Thriller", "Suspense", "Current Affairs", "Dictionaries","Fiction", "Graphic Novel", "History", "Home", "Garden", "Languages-self-study", "Mind", "Body", "Spirit", "Non-fiction", "Photography", "Poetry", "Politics", "Psychology", "Reference", "Religion","Philosophy", "Self Help","Short Stories","Society", "Spirituality", "Sports", "Cookery");
if ($db_result == TRUE)
{
	$db_row=mysqli_fetch_row($db_result);
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        /*echo "<url>";
                        echo "<loc>";
                        echo "http://www.popabook.com/browseall";
                        echo "</loc>";
                        echo "<lastmod>";
                        echo  date("Y-m-d");
                        echo "</lastmod>";
                        echo "<changefreq>monthly</changefreq>";
                echo "<priority>0.5</priority>";
                echo "</url>";

        
	while ($db_row != null)
	{
                $books = searchbooks($link, $db_row[0], $escape);
                $numbooks = count($books);
                $pages = 0;
                if ($numbooks%10 == 0)
                   $pages = $numbooks/10;
                else
                   $pages = intval($numbooks/10) + 1;
                $tpage = 1;
                while ($tpage <= $pages)
                {
                        $name = str_replace(" ", "-", $db_row[0]);
			echo "<url>";
			echo "<loc>";
			echo "http://www.popabook.com/$name-$tpage";
			echo "</loc>";
			echo "<lastmod>";
			echo  date("Y-m-d");
			echo "</lastmod>";
			echo "<changefreq>monthly</changefreq>";
		echo "<priority>0.5</priority>";
		echo "</url>";
                             $tpage = $tpage + 1;
                }
		$db_row=mysqli_fetch_row($db_result);
	}
        $numcats = count($othercats);
        $tnumcats = 0;
        while ($tnumcats < $numcats)
        {
            $books = searchbooks($link, $othercats[$tnumcats], $escape);
                $numbooks = count($books);
                $pages = 0;
                if ($numbooks%10 == 0)
                   $pages = $numbooks/10;
                else
                   $pages = intval($numbooks/10) + 1;
                $tpage = 1;
                while ($tpage <= $pages)
                {
                        $name = str_replace(" ", "-", $othercats[$tnumcats]);
                        echo "<url>";
                        echo "<loc>";
                        echo "http://www.popabook.com/$name-$tpage";
                        echo "</loc>";
                        echo "<lastmod>";
                        echo  date("Y-m-d");
                        echo "</lastmod>";
                        echo "<changefreq>monthly</changefreq>";
                echo "<priority>0.5</priority>";
                echo "</url>";
                             $tpage = $tpage + 1;
                }
                $tnumcats = $tnumcats + 1;

        } */
        echo "<url>";
        echo "<loc>";
        echo "http://www.popabook.com/directoryofbooks.php";
        echo "</loc>";
        echo "<lastmod>";
        echo  date("Y-m-d");
        echo "</lastmod>";
        echo "<changefreq>monthly</changefreq>";
        echo "<priority>0.5</priority>";
        echo "</url>";
        $i = 1;
        for ($i = 100; $i > 0; $i = $i - 1)
        {
             echo "<url>";
        echo "<loc>";
        echo "http://www.popabook.com/directoryofbooks.php?books=2-$i";
        echo "</loc>";
        echo "<lastmod>";
        echo  date("Y-m-d");
        echo "</lastmod>";
        echo "<changefreq>monthly</changefreq>";
        echo "<priority>0.5</priority>";
        echo "</url>";

        }
        for ($i = 10000; $i > 0; $i = $i - 1)
        {
             echo "<url>";
        echo "<loc>";
        echo "http://www.popabook.com/directoryofbooks.php?books=3-$i";
        echo "</loc>";
        echo "<lastmod>";
        echo  date("Y-m-d");
        echo "</lastmod>";
        echo "<changefreq>monthly</changefreq>";
        echo "<priority>0.5</priority>";
        echo "</url>";

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
