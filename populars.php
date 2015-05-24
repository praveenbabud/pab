<?php
require_once('homedata.php');
require_once('pab_util.php');

   function showpopulars($link,$seo)
   {
           $tidarray = array();

               $return_str = "";
	   $return_str = "<div class=\\\"populars\\\"><br>Deals of the Day<br><br><div class=\\\"pabborder\\\"></div>";
	$db_query = "select max(id) from newbooks";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
                $temparray = array();
                $temparray[0] = 0;
                $temparray[1] = 0;
                $temparray[2] = 0;
                $temparray[3] = 0;
		$maxpopularbooks = $db_row[0];
		for ($i = 0; $i < 4; $i = $i + 1)
		{
                    /*$tid = mt_rand(10, $maxpopularbooks);*/
                    $tid = $maxpopularbooks - $i;
                    if ($temparray[0] == $tid || $temparray[1] == $tid || $temparray[2] == $tid || $temparray[3] == $tid)
                    {
                       $i = $i - 1;
                       continue;
                    }
                    $temparray[$i] = $tid;
                    $db_query2 = "select bookid, teaser from newbooks where id=$tid";
                    $db_result2 = mysqli_query($link, $db_query2);
                    if ($db_result2 == TRUE)
                    {
                         $db_row = mysqli_fetch_row($db_result2);
                         if ($db_row != null)
                         {
                             $tidarray[$i] = $db_row[0];
                             $teaserarray[$i] = $db_row[1];
                         }    
                    }
                }
		for ($i = 0; $i < 4; $i = $i + 1)
		{
		    $return_str = $return_str . "<table align=\\\"center\\\">";
    		$db_query = "select isbn13, title, author1,nlistprice, nourprice,booklink,currency from book_inventory where bookid=$tidarray[$i]";
        		$db_result = mysqli_query($link, $db_query);
        		if ($db_result == TRUE)
	    		{
				$return_str = $return_str . "<tr><td><table><tr><td colspan=\\\"2\\\" class=\\\"atp\\\" align=\\\"center\\\">";
	       			$db_row = mysqli_fetch_row($db_result);
	       			if ($db_row != null)
				{
                                        $listprice = converttoinr($db_row[6],$db_row[3]);
                                        $ourprice = converttoinr($db_row[6],$db_row[4]);
		       			if (file_exists("/var/apache2/2.2/htdocs/" . $db_row[0] . ".jpg"))
						$image = $db_row[0] . ".jpg";
		       			else if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[0] . ".jpg"))
						$image = "optimage/" . $db_row[0] . ".jpg";
					$booklink = $db_row[5] . "/" . $db_row[0];
                                        $return_str = $return_str . "<span class=\\\"logoatp\\\"><br>$db_row[1]</span><br>by $db_row[2]</td></tr><tr><td>";
                                     if ($seo == 0)
                                     {
					$return_str = $return_str . "<a href=\\\"javascript:void(0);\\\"><img onclick=\\\"getsearchdata('getbook','search_string=";
					 $return_str = $return_str . $booklink;
					 $return_str = $return_str . "')\\\" width=\\\"140px\\\" height=\\\"200px\\\" src=\\\"http://www.popabook.com/$image\\\" title=\\\"$db_row[1]\\\"></a>";
                                      }
                                      else
                                      {
					$return_str = $return_str . "<a href=\\\"http://www.popabook.com/$booklink\\\"><img width=\\\"140px\\\" height=\\\"200px\\\" src=\\\"http://www.popabook.com/$image\\\" title=\\\"$db_row[1]\\\"></a>";
                                      }
            $realt = $teaserarray[$i] . "&nbsp;<a href=\\\"javascript:void(0);\\\" onclick=\\\"getsearchdata('getbook','search_string=" . $booklink . "')\\\">...</a>";

			$return_str = $return_str . "</td></tr><tr><td class=\\\"atp\\\" align=\\\"left\\\">$realt</td></tr></table></td></tr>";
					$return_str = $return_str . "<tr><td align=\\\"center\\\">";
					$save = 0;
					$save = round(($listprice - $ourprice) * 100/$listprice);
					if ($save > 0)
					{
						if ($save < 10)
			    $color = "#ffd100";
		    else if ($save < 20)
			    $color = "#0000ff";
		    else 
			    $color = "#ff8c00";
						$return_str = $return_str . "<table align=\\\"center\\\"><tr><td align=\\\"center\\\"><span class=\\\"satp\\\">"  . "    Rs. <span class=\\\"atp\\\"><s>" . $listprice . "</s></span><br>Rs. <span class=\\\"boldatp\\\">" . $ourprice  . "</span></span></td>";

						$return_str = $return_str . "<td><img src=\\\"http://www.popabook.com/savenew.jpg\\\"></td><td class=\\\"boldlogoatp\\\" style=\\\"color:$color;\\\"><img src=\\\"http://www.popabook.com/uitest/$save.gif\\\" alt=\\\"$save%\\\"></td></tr></table></td></tr>";
	    				}
	    				else
	    				{
						$return_str = $return_str . "<table align=\\\"center\\\"><tr><td align=\\\"center\\\"><span class=\\\"satp\\\">"  . "Rs. <span class=\\\"boldatp\\\">" . $listprice . "</span></span></td>";
		    				$return_str = $return_str . "<td>&nbsp;</td><td>&nbsp;</td></tr></table></td></tr>";
	    				}
				}
			}
			$return_str = $return_str . "</table>";
			$return_str = $return_str . "<div class=\\\"pabborder\\\"></div>";
		}
		
	}

        $return_str = $return_str . "</div>";
        return $return_str;

   }
function storeshowpopulars($link, $storeurl)
   {
        return showpopulars($link,1);
           /*
               $return_str = "";
	   $db_query = "select isbn13 from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl') order by id desc limit 16";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
                $numrows = mysqli_num_rows($db_result);
		for ($i = 0; $i < $numrows; $i = $i + 1)
		{
		   $db_row = mysqli_fetch_row($db_result);
                   if ($i == 12)
                   {
	   $return_str = "<div class=\\\"populars\\\"><br>Popular Books<br><br><div class=\\\"pabborder\\\"></div>";
                   }
                   if ($i >= 12)
                   {
			$return_str = $return_str . "<table align=\\\"center\\\">";
			$db_query1 = "select isbn13, title, author1,nlistprice, nourprice,booklink,currency from book_inventory where isbn13 like '$db_row[0]'";
        		$db_result1 = mysqli_query($link, $db_query1);
        		if ($db_result1 == TRUE)
	    		{
				$return_str = $return_str . "<tr><td><table><tr><td>";
	       			$db_row1 = mysqli_fetch_row($db_result1);
	       			if ($db_row1 != null)
				{
                                        $listprice = converttoinr($db_row1[6],$db_row1[3]);
                                        $ourprice = converttoinr($db_row1[6],$db_row1[4]);
		       			if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[0] . ".jpg"))
						$image = $db_row[0] . ".jpg";
					else
						$image = "ina.jpg";
					$booklink = $db_row1[5] . "/" . $db_row[0];
					$return_str = $return_str . "<a href=\\\"javascript:void(0);\\\"><img onclick=\\\"getsearchdata('getbook','search_string=";
					 $return_str = $return_str . rawurlencode($booklink);
					 $return_str = $return_str . "')\\\" width=\\\"70px\\\" height=\\\"100px\\\" src=\\\"http://www.popabook.com/optimage/$image\\\" title=\\\"$db_row1[1]\\\"></a>";

			$return_str = $return_str . "</td></tr><tr><td colspan=\\\"2\\\" class=\\\"satp\\\" align=\\\"center\\\"><span class=\\\"bluesatp\\\">$db_row1[1]</span><br>by $db_row1[2]</td></tr></table></td></tr>";
					$return_str = $return_str . "<tr><td align=\\\"center\\\">";
					$save = 0;
					$save = round(($listprice - $ourprice) * 100/$listprice);
					if ($save > 0)
					{
						if ($save < 10)
			    $color = "#ffd100";
		    else if ($save < 20)
			    $color = "#0000ff";
		    else 
			    $color = "#ff8c00";
						$return_str = $return_str . "<table align=\\\"center\\\"><tr><td align=\\\"center\\\"><span class=\\\"satp\\\">"  . "    Rs. <span class=\\\"atp\\\"><s>" . $listprice . "</s></span><br>Rs. <span class=\\\"boldatp\\\">" . $ourprice . "</span></span></td>";

						$return_str = $return_str . "<td><img src=\\\"http://www.popabook.com/savenew.jpg\\\"></td><td class=\\\"boldlogoatp\\\" style=\\\"color:$color;\\\">$save%</td></tr></table></td></tr>";
	    				}
	    				else
	    				{
						$return_str = $return_str . "<table align=\\\"center\\\"><tr><td align=\\\"center\\\"><span class=\\\"satp\\\">"  . "Rs. <span class=\\\"boldatp\\\">" . $listprice . "</span></span></td>";
		    				$return_str = $return_str . "<td>&nbsp;</td><td>&nbsp;</td></tr></table></td></tr>";
	    				}
				}
			}
			$return_str = $return_str . "</table>";
			$return_str = $return_str . "<div class=\\\"pabborder\\\"></div>";
                    }
		}
		
	}
        if ($return_str != "")
        $return_str = $return_str . "</div>";
        return $return_str; */

   }
