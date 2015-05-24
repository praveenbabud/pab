<?php
function homedata($link)
{
	require_once("browsecatalog.php");
	$ntitles = array();
	$nimages = array();
	$nisbn13s = array();
	$nauthors = array();
	$nlps = array();
	$nops = array();
		$btitles = array();
	$bimages = array();
	$nteasers = array();
	$bisbn13s = array();
        $booklinks = array();
	$bauthors = array();
	$blps = array();
	$bops = array();
	$linkvalue = array();
	$linktitle = array();
	$index = 0;
	$db_query = "select max(id) from newbooks";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		$maxnewbooks = $db_row[0] - 4;
    }
else
{
	echo "Server is temporarily not Available, Please Try Later";
	return;
}
    if ($maxnewbooks > 30)
	    $low = $maxnewbooks - 30;
    else
	    $low = 1;
	$tmparray = array();
    $tidarray = array();
    $teaserarray = array();
    for ($loopi = 0; $loopi < 8; $loopi = $loopi + 1)
    {
	    $tempid = mt_rand($low, $maxnewbooks);

	    $intloop = 0;
	    for ($intloop = 0; $intloop < $loopi; $intloop = $intloop + 1)
	    {
		    if ($tempid == $tmparray[$intloop])
			    break;
	    }
	    if ($intloop != $loopi)
	    {
		    $loopi = $loopi -1;
		    continue;
	    }
	    $tmparray[$loopi] = $tempid;
            $db_query2 = "select bookid, teaser from newbooks where id=$tempid";
                    $db_result2 = mysqli_query($link, $db_query2);
                    if ($db_result2 == TRUE)
                    {
                         $db_row = mysqli_fetch_row($db_result2);
                         if ($db_row != null)
                         {
                             $tidarray[$loopi] = $db_row[0];
                             $teaserarray[$loopi] = $db_row[1];
                         }
                    }

     }
    for ($loopi = 0; $loopi < 8; $loopi = $loopi + 1)
    {
        $db_query = "select isbn13, title, author1,nlistprice, nourprice,booklink,currency from book_inventory where bookid=$tidarray[$loopi]";
       $db_result = mysqli_query($link, $db_query);
        if ($db_result == TRUE)
	    {
	       $db_row = mysqli_fetch_row($db_result);
	       while ($db_row != null)
	       {
		       $ntitles[$index] = $db_row[1];
		       if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[0] . ".jpg"))
			       $nimages[$index] = $db_row[0] . ".jpg";
		       else
			       $nimages[$index] = $db_row[0] . ".jpg";
                           
			  $nisbn13s[$index] = $db_row[0];
			  $nauthors[$index] = $db_row[2];
			  $nlps[$index] = converttoinr($db_row[6],$db_row[3]);
			  $nops[$index] = converttoinr($db_row[6],$db_row[4]);
			  $booklinks[$index] = $db_row[5];
			  $index = $index + 1;
			  $db_row = mysqli_fetch_row($db_result);
	       }
	    }
	}

	   echo "<div style=\"text-align:center;padding-left:5px;\" class=\"pabheading\"><div class=\"logoatpo\"><br>New Releases Up To 30% OFF + FREE Shipping<br><span class=\"blueatp\"><a href=\"javascript:void(0);\" onclick=\"getsearchdata('browse','browse=viewallnr')\">(View All)</a></span></div><br>";
	   echo "<table align=\"center\" class=\"satp\" width=\"100%\"><tr>";
    	for ($index = 0; $index < 2 ; $index = $index + 1)
    	{
		$save = round(($nlps[$index] - $nops[$index])*100/$nlps[$index]);

        if ($save > 0)
	    {
		    $price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "    Rs. <span class=\"atp\"><s>" . $nlps[$index] . "</s></span><br>Rs. <span class=\"boldatp\">" . $nops[$index] . "</span></span></td>";
		    if ($save < 10)
			    $color = "#ffd100";
		    else if ($save < 20)
			    $color = "#0000ff";
		    else 
			    $color = "#ff8c00";

		$price = $price . "<td><img src=\"http://www.popabook.com/savenew.jpg\"></td><td class=\"boldlogoatp\" style=\"color:$color;\"><img src=\"http://www.popabook.com/uitest/$save.gif\" title=\"$save%\" alt=\"$save%\"></td></tr></table>";
	    }
	    else
	    {
		$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "Rs. <span class=\"boldatp\">" . $nlps[$index] . "</span></span></td>";
		  $price = $price . "<td>&nbsp;</td><td>&nbsp;</td></tr></table>";
	    }
		$realt = "";
	    $booklink = $booklinks[$index] . "/" . $nisbn13s[$index];
	    $realt = $teaserarray[$index] . "&nbsp;<a href=\"javascript:void(0);\" onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\">...</a>";
		if ($index == 0)
			echo "<td  valign=\"top\" width=\"50%\" style=\"text-align:center;\"><table><tr><td width=\"50%\"valign=\"top\"><a href=\"javascript:void(0);\"><img  onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\"" . "width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" .  $nimages[$index] . "\" title=\"" . $ntitles[$index] . "\"></a><br><span class=\"bluesatp\">$ntitles[$index]</span><br>by $nauthors[$index]<br>$price</td><td valign=\"top\" width=\"50%\" align=\"left\" class=\"atp\">$realt</td></tr></table></td> ";
		else
			echo "<td  valign=\"top\" class=\"vborder\" width=\"50%\" style=\"text-align:center;\"><table><tr><td width=\"50%\"valign=\"top\"><a href=\"javascript:void(0);\"><img  onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\"" . "width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" .  $nimages[$index] . "\" title=\"" . $ntitles[$index] . "\"></a><br><span class=\"bluesatp\">$ntitles[$index]</span><br>by $nauthors[$index]<br>$price</td><td valign=\"top\" width=\"50%\" align=\"left\" class=\"atp\">$realt</td></tr></table></td> ";
	}
    echo "</tr></table></div>";
    for ($i = 0 ; $i < 3; $i = $i + 1)
    {
	$index = 2 + $i * 2;
    echo "<br><div style=\"text-align:center;padding-left:5px;\" class=\"pabheading\"><br>";
     echo "<table align=\"center\" class=\"satp\" width=\"100%\"><tr>";
    	for ($ix = 0; $ix < 2 ; $ix = $ix + 1)
    	{
	    $save = round(($nlps[$index] - $nops[$index])*100/$nlps[$index]);

        if ($save > 0)
	    {
		$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "    Rs. <span class=\"atp\"><s>" . $nlps[$index] . "</s></span><br>Rs. <span class=\"boldatp\">" . $nops[$index] . "</span></span></td>";
		    if ($save < 10)
			    $color = "#ffd100";
		    else if ($save < 20)
			    $color = "#0000ff";
		    else 
			    $color = "#ff8c00";

		$price = $price . "<td><img src=\"http://www.popabook.com/savenew.jpg\"></td><td class=\"boldlogoatp\" style=\"color:$color;\"><img src=\"http://www.popabook.com/uitest/$save.gif\" title=\"$save%\" alt=\"$save%\"></td></tr></table>";

	    }
	    else
	    {
		$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "Rs. <span class=\"boldatp\">" . $nlps[$index] . "</span></span></td>";
		  $price = $price . "<td>&nbsp;</td><td>&nbsp;</td></tr></table>";
	    }
	    $realt = "";

	    $booklink = $booklinks[$index] . "/" . $nisbn13s[$index];
	    $realt = $teaserarray[$index] . "&nbsp;<a href=\"javascript:void(0);\" onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\">...</a>";
	    if ($ix == 0)
		    echo "<td  valign=\"top\" width=\"50%\" style=\"text-align:center;\"><table><tr><td width=\"50%\"valign=\"top\"><a href=\"javascript:void(0);\"><img  onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\"" . "width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" .  $nimages[$index] . "\" title=\"" . $ntitles[$index] . "\"></a><br><span class=\"bluesatp\">$ntitles[$index]</span><br>by $nauthors[$index]<br>$price</td><td valign=\"top\" width=\"50%\" align=\"left\" class=\"atp\">$realt</td></tr></table></td> ";
	    else
		echo "<td  valign=\"top\" class=\"vborder\" width=\"50%\" style=\"text-align:center;\"><table><tr><td width=\"50%\"valign=\"top\"><a href=\"javascript:void(0);\"><img  onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\"" . "width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" .  $nimages[$index] . "\" title=\"" . $ntitles[$index] . "\"></a><br><span class=\"bluesatp\">$ntitles[$index]</span><br>by $nauthors[$index]<br>$price</td><td valign=\"top\" width=\"50%\" align=\"left\" class=\"atp\">$realt</td></tr></table></td> ";


		$index = $index + 1;
	}
    echo "</tr></table></div>";
    }
    showusedbooks($link);
}
function  showusedbooks($link)
{
    $ubisbns = array();
    $ubsaleprice = array();
    $index = 0;
    $db_query = "select count(*) from usedbooks where status=2";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
        $db_row = mysqli_fetch_row($db_result);
        if ($db_row != null)
        {
             $db_query = ""; 
             if ($db_row[0] >= 6)
             {
               $db_query = "select  isbn13, saleprice  from usedbooks where status=2 order by id desc limit 6";
             }
             else if ($db_row[0] >=3)
             {
               $db_query = "select  isbn13, saleprice  from usedbooks where status=2 order by id desc limit 3";
             }
             if ($db_query != "")
             {
                 $db_result = mysqli_query($link, $db_query);
                 if ($db_result == TRUE)
                 {
                     $db_rowub = mysqli_fetch_row($db_result);
                     while ($db_rowub != null)
                     {
                        $ubisbns[$index] = $db_rowub[0];
                        $ubsaleprice[$index] = $db_rowub[1];
                        $index = $index + 1;
                        $db_rowub = mysqli_fetch_row($db_result);
                     }
                     if ($index >= 3)
                     {
                         echo "<div class=\"pabborder\"></div>";
	   	         echo "<div style=\"text-align:center;padding-left:5px;\" class=\"pabheading\"><div class=\"logoatpo\"><br>Used Books on Sale<br><span class=\"blueatp\"><a href=\"javascript:void(0);\" onclick=\"getsearchdata('browse','browse=viewallub')\">(View All)</a></span></div><br>";
                         
                         $tindex = 0;
                         $rows = 0;
                         if ($index >= 6)
                             $rows = 2;
                         else if ($index >= 3)
                             $rows = 1;
                         $trows = 0;
                         for ($trows = 0; $trows < $rows; $trows = $trows + 1)
                         {
                         echo "<table width=\"100%\"><tr>";
                         $tindex = $trows * 3;
                         while ($tindex < (($trows * 3) + 3))
                         {
                            $db_query = "select title,author1, nlistprice,booklink,currency from book_inventory where isbn13 like '$ubisbns[$tindex]'";
                            $db_result = mysqli_query($link, $db_query);
                            if ($db_result == TRUE)
                            {
                                $db_row = mysqli_fetch_row($db_result);
                                if ($db_row != null)
                                {
                                    $price = "";
                                    $listprice = converttoinr($db_row[4],$db_row[2]);
                                    $save = round(($listprice - $ubsaleprice[$tindex])*100/$listprice);
                                    if ($save > 0) 
                                    {
                			$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "    Rs. <span class=\"atp\"><s>" . $listprice . "</s></span><br>Rs. <span class=\"boldatp\">" . $ubsaleprice[$tindex] . "</span></span></td>";
                    			if ($save < 10)
                            			$color = "#ffd100";
                    			else if ($save < 20)
                            			$color = "#0000ff";
                    			else
                            			$color = "#ff8c00";
					$price = $price . "<td><img src=\"http://www.popabook.com/savenew.jpg\"></td><td class=\"boldlogoatp\" style=\"color:$color;\"><img src=\"http://www.popabook.com/uitest/$save.gif\" title=\"$save%\" alt=\"$save%\"></td></tr></table>";

            			   }
            			   else
            			   {
                			$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "Rs. <span class=\"boldatp\">" . $ubsaleprice[$tindex] . "</span></span></td>";
                  			$price = $price . "<td>&nbsp;</td><td>&nbsp;</td></tr></table>";
            			   }

                                   $image = "";
				   $booklink = $db_row[3] . "/" . $ubisbns[$tindex];
                                   if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $ubisbns[$tindex] . ".jpg"))
                                        $image = "$ubisbns[$tindex]" . ".jpg";
                                   else
                                        $image = "tmpina.jpg";
                                   echo "<td width=\"33%\" align=\"center\"><a href=\"#\"><img  onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\" width=\"70px\" height=\"100px\" src=\"http://www.PopAbooK.com/optimage/$image\"></a><br><span class=\"bluesatp\">$db_row[0]</span><br><span class=\"satp\">by $db_row[1]</span><br>$price</td>";
                                }
                            }
                            $tindex = $tindex + 1;
                         }
                         echo "</tr></table>";
                         echo "<br><br>";
                         } 
                     }
                 }
             }
        }
    }
}
function storehomedata($link, $storeurl)
{
	require_once("pab_util.php");
	$arrayauthors = array();
        $booklinks = array();
	$arraytitles = array();
	$arrayisbn13s = array();
	$arraylistprices = array();
	$arrayourprices = array();
	$index = 0;
        $affid = $_SESSION['__PABaffid'];
	$db_query = "select isbn13 from booksinstore where affid=$affid order by id desc limit 32";
	/*$db_query = "select isbn13 from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl')  limit 32";*/
	/*$db_query = "select isbn13 from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl') order by id desc limit 32";*/
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		while ($db_row != null)
		{
			$db_query1 = "select title, author1, nlistprice, nourprice,booklink,currency from book_inventory where isbn13 like '$db_row[0]'";
			$db_result1 = mysqli_query($link, $db_query1);
			if ($db_result1 == TRUE)
			{
				$db_row1 = mysqli_fetch_row($db_result1);
				if ($db_row1 != null)
				{
					$arrayisbn13s[$index] = $db_row[0];
					$arraytitles[$index] = $db_row1[0];
					$arrayauthors[$index] = $db_row1[1];
					$arraylistprices[$index] = converttoinr($db_row1[5], $db_row1[2]);
					$arrayourprices[$index] = converttoinr($db_row1[5], $db_row1[3]);
                                        $booklinks[$index] = $db_row1[4];
                                        if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[0] . ".jpg") == TRUE)
                                           $images[$index] = $db_row[0] . ".jpg";
                                        else
					   $images[$index] = "tmpina.jpg";
					$index = $index + 1;
				}
			}
			$db_row = mysqli_fetch_row($db_result);
		}
	}
         $nbs = count($arraylistprices);
         if ($nbs > 0)
         {
	   echo "<div style=\"text-align:center;padding-left:5px;\" class=\"pabheading\"><div class=\"logoatpo\"><br>New Releases Up To 30% OFF + FREE Shipping<br><span class=\"blueatp\"><a href=\"javascript:void(0);\" onclick=\"bbbrowse('viewallnr')\">(View All)</a></span></div><br>";
         }
	 for ($i = 0 ; $i < 8; $i = $i + 1)
	 {
	   $tindex = $i * 4;
           $howmany = $nbs - $tindex;
           if ($howmany >= 4)
           {
               $howmany = 4;
           }

           $whatwidth = 25;
           if ($howmany != 0)
           $whatwidth = intval(100/$howmany);
    echo "<br><div style=\"text-align:center;padding-left:5px;\" ><br>";
     echo "<table align=\"center\" class=\"satp\" width=\"100%\"><tr>";
    	for ($ix = 0; $ix < $howmany ; $ix = $ix + 1)
	{
		$tindex = 4 * $i + $ix;
	    $save = round(($arraylistprices[$tindex] - $arrayourprices[$tindex])*100/$arraylistprices[$tindex]);

        if ($save > 0)
	    {
		$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "    Rs. <span class=\"atp\"><s>" . $arraylistprices[$tindex] . "</s></span><br>Rs. <span class=\"boldatp\">" . $arrayourprices[$tindex] . "</span></span></td>";
		    if ($save < 10)
			    $color = "#ffd100";
		    else if ($save < 20)
			    $color = "#0000ff";
		    else 
			    $color = "#ff8c00";

		$price = $price . "<td><img src=\"http://www.popabook.com/savenew.jpg\"></td><td class=\"boldlogoatp\" style=\"color:$color;\"><img src=\"http://www.popabook.com/uitest/$save.gif\" title=\"$save%\" alt=\"$save%\"></td></tr></table>";

	    }
	    else
	    {
		$price = "<table align=\"center\"><tr><td align=\"center\"><span class=\"satp\">"  . "Rs. <span class=\"boldatp\">" . $arraylistprices[$tindex] . "</span></span></td>";
		  $price = $price . "<td>&nbsp;</td><td>&nbsp;</td></tr></table>";
	    }
		$booklink = $booklinks[$tindex] . "/" .  $arrayisbn13s[$tindex];
		
		    echo "<td  align=\"center\" valign=\"top\" width=\"" . $whatwidth . "%\" style=\"text-align:center;\"><table width=\"100%\"><tr><td width=\"100%\" valign=\"top\"><a href=\"javascript:void(0);\"><img  alt=\"$arraytitles[$tindex] by $arrayauthors[$tindex] ISBN:$arrayisbn13s[$tindex]\" onclick=\"getsearchdata('getbook','search_string=" . rawurlencode($booklink) . "')\"" . "width=\"70px\" height=\"100px\" src=\"http://www.popabook.com/optimage/" .  $images[$tindex] . "\" title=\"" . $arraytitles[$tindex] . "\"></a><br><span class=\"bluesatp\"><strong>$arraytitles[$tindex]</strong></span><br>by <i>$arrayauthors[$tindex]</i><br>$price</td></tr></table></td> ";
	}
    echo "</tr></table></div>";
    }
}
?>

