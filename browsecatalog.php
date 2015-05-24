<?php
require_once('pab_util.php');
function browsecatalog($link, $search_string, &$title, &$metadata,$seo, &$retcode, &$storeurl,$strict,&$linkurl)
{

	$booklinkurl = 0;
        $storename = "";
        $afflink = 0;
	$escape = " -";
	$books = array ();
        $bookindex = 0;
        $search_token = "";
	$search_string=strtolower($search_string);
	$pos = strrpos($search_string,"/");
	if (($pos + 1) == strlen($search_string))
	{
		$search_string = substr($search_string, 0, $pos);
	}
	$tokens = explode("/",$search_string);
	$output = "";	
	if (count($tokens) == 1)
	{
		if ($tokens[0] == "browseall")
		{
		     return showbrowseoptions($seo);
		}
		if (strstr($tokens[0], "--") != FALSE)
		{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
		}
		$subtokens = explode("-",$tokens[0]);
		if (count($subtokens) > 1  && is_numeric($subtokens[count($subtokens) - 1]) == TRUE)
		{
			$reqpage = $subtokens[count($subtokens) -1];
			$pos = strrpos($tokens[0], "-",0);
			if ($pos === false)
			{
				;
			}
			else
			{
				$search_token = substr($tokens[0], 0, $pos);
			}
		}
		else
		{
                        if (valid_bookstore($link, $tokens[0]) == TRUE)
                        {
                                $storeurl = strtolower($tokens[0]);
                                $storename = $_SESSION['__PABstorename'];
                                $_SESSION['__PABstoreurl'] = $storeurl;
                                $metadata =  "<meta name=\"keywords\" content=\"$storename at PopAbooK.com Buy books online India\">" . "<meta name=\"Description\" content=\"$storename at PopAbooK.com Buy books online India\">";
                                $metadata = $metadata . "<link rel=\"canonical\" href=\"http://www.popabook.com/$storeurl\"/>";

                                        $title = "<title>$storename at PopAbooK.com</title>";
                                $sessionid = session_id();
                        $_SESSION['__PABaffiliate'] = $storeurl;
                        $affid = $storeurl;
                        $_SESSION['__PABafftype'] = 'pabstore';
                        $monthyear = date('M-Y');
                                $db_query = "insert into aff_traffic (session, affid, isbn13, dt, afftype, monthyear) values ('$sessionid', '$affid', '', now(),'pabstore','$monthyear')";
                                $db_result = mysqli_query($link, $db_query);

                                return $output;
                        }

			$search_token = $tokens[0];
			$reqpage = 1;
		}
	}
	else if (count($tokens) == 2)
	{
            if ($tokens[1] == "bookstore")
		{
			if (valid_bookstore($link, $tokens[0]) == TRUE)
			{
				$storeurl = strtolower($tokens[0]);
                                $storename = $_SESSION['__PABstorename'];
                                $_SESSION['__PABstoreurl'] = $storeurl;
                                $metadata =  "<meta name=\"keywords\" content=\"$storename at PopAbooK.com Buy books online India\">" . "<meta name=\"Description\" content=\"$storename at PopAbooK.com books online India\">";
				$metadata = $metadata . "<link rel=\"canonical\" href=\"http://www.popabook.com/$storeurl\"/>";
                                
					$title = "<title>$storename at PopAbooK.com</title>";
                                $sessionid = session_id();
                        $_SESSION['__PABaffiliate'] = $storeurl;
                        $affid = $storeurl;
                        $_SESSION['__PABafftype'] = 'pabstore';
                        $monthyear = date('M-Y');
                                $db_query = "insert into aff_traffic (session, affid, isbn13, dt, afftype, monthyear) values ('$sessionid', '$affid', '', now(),'pabstore','$monthyear')";
                                $db_result = mysqli_query($link, $db_query);

				return $output;
			}
			else
			{
                                $storename = "";
				$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
				$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
				$retcode = 404;
				return $output;
			}
		}
		else if ($tokens[1] == "af")
                {
                     $sessionid = session_id();
                        $_SESSION['__PABaffiliate'] = strtolower($tokens[0]);
                        $affid = $_SESSION['__PABaffiliate'];
                        $_SESSION['__PABafftype'] = 'link';
                        $monthyear = date('M-Y');
                                $db_query = "insert into aff_traffic (session, affid, isbn13, dt, afftype, monthyear) values ('$sessionid', '$affid', '', now(),'link','$monthyear')";
                                $db_result = mysqli_query($link, $db_query);
                        if (isset($_POST['search_string'])== TRUE)
                             $search_token = strtolower($_POST['search_string']);
                        else
                             return $output;
		        $reqpage = 1;
                }
                else
		{
		$booklinkurl = 1;
		$reqpage = 1;
		$search_token = $tokens[1];
                }
	}
        else if (count($tokens) == 4)
	{
		$booklinkurl = 1;
		$reqpage = 1;
		if ($tokens[3] == "af")
		{
			$afflink = 1;
			$sessionid = session_id();
				$affid = strtolower($tokens[2]);
                        $_SESSION['__PABaffiliate'] = $affid;
                        $linkurl = $affid;
                        $_SESSION['__PABafftype'] = 'link';
                        $monthyear = date('M-Y');
				$db_query = "insert into aff_traffic (session, affid,isbn13, dt, afftype,monthyear) values ('$sessionid', '$tokens[2]', '$tokens[1]', now(),'link','$monthyear')";
				$db_result = mysqli_query($link, $db_query);
				$search_token = $tokens[1];
			}
			else if ($tokens[3] == "bookstore" && valid_bookstore($link, $tokens[2]) == TRUE)
			{
				$afflink = 1;
				$sessionid = session_id();
				$affid = strtolower($tokens[2]);
                                $storename = $_SESSION['__PABstorename'];
                        $_SESSION['__PABaffiliate'] = $affid;
                        $_SESSION['__PABafftype'] = 'pabstore';
                        $monthyear = date('M-Y');
				$db_query = "insert into aff_traffic (session, affid, isbn13, dt, afftype, monthyear) values ('$sessionid', '$tokens[2]', '$tokens[1]', now(),'pabstore','$monthyear')";
				$db_result = mysqli_query($link, $db_query);
				$storeurl = strtolower($tokens[2]);
                                $_SESSION['__PABstoreurl'] = $storeurl;
				$search_token = $tokens[1];
			}
		        else
			{
                                $storename = "";
				$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
				$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
				$retcode = 404;
				return $output;
			}
	}
	else
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	if ($reqpage == 0)
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	$books = array();
        $pabcache = "__PAB_" . $storeurl . "_" . $search_token;
	if (isset($_SESSION[$pabcache]) == TRUE)
	{
		if (isset($_SESSION[$pabcache][$reqpage]) == TRUE)
		{
		$bookstring = $_SESSION[$pabcache][$reqpage];
		$tok = strtok($bookstring,"#");
		$bookindex = 0;
		while ($tok != null)
		{
			$books[$bookindex] = $tok;
			$bookindex = $bookindex + 1;
			$tok = strtok("#");
		}
		}
	}
	else 
	{
		if ($booklinkurl == 1)
		{
			$books = searchbooks($link, $search_token, $escape,$storeurl,$strict);
		}
		else
		{
                    if ($search_token == "viewallub")
                    {
                        $books = browseub($link);
                    }
                    else if ($search_token == "viewallnr")
                    {
                        //$pabcache = $pabcache + "notused";
                        $books = browsenr($link, $storeurl);
                    }
                    else
                    {
			$actual_search_string = str_replace("-", " ",$search_token);
			$db_query = "select productid from products where lower(name) like '$actual_search_string'";
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
				$num_rows = mysqli_num_rows($db_result);
				if ($num_rows > 0)
				{
					$db_row  = mysqli_fetch_row($db_result);
					if ($db_row[0] == 2)
					{
						$db_query = "select name from subproducts where productid = $db_row[0]";
					}
					else
					{
						$db_query = "select name from subsubproducts where subproductid in (select subproductid from subproducts where productid = $db_row[0])";
					}
					$db_result = mysqli_query($link, $db_query); 
				        if ($db_result == TRUE)
					{
						$num_rows = mysqli_fetch_row($db_result);
						if ($num_rows > 0)
						{
							$db_row = mysqli_fetch_row($db_result);
							$books = array();
							while ($db_row != null)
							{
								$books = array_merge($books, searchbooks($link, $db_row[0], $escape,$storeurl,1));
								$db_row = mysqli_fetch_row($db_result);
							}
						}
					}
				}
				else
				{
					$db_query = "select subproductid, productid from subproducts where lower(name) like '$actual_search_string'";
					$db_result = mysqli_query($link, $db_query);
					if ($db_result == TRUE)
					{
						$num_rows = mysqli_num_rows($db_result);
						if ($num_rows > 0)
						{
							$db_row  = mysqli_fetch_row($db_result);
							if ($db_row[1] == 2)
							{
								$books = searchbooks($link, $actual_search_string, $escape, $storeurl, 1);
							}
							else
							{
								$db_query = "select name from subsubproducts where subproductid = $db_row[0]";
						 		
							$db_result = mysqli_query($link, $db_query); 
				        		if ($db_result == TRUE)
							{
								$num_rows = mysqli_fetch_row($db_result);
								if ($num_rows > 0)
								{
									$db_row = mysqli_fetch_row($db_result);
									$books = array();
									while ($db_row != null)
									{
										$books = array_merge($books, searchbooks($link, $db_row[0], $escape, $storeurl,1));
										$db_row = mysqli_fetch_row($db_result);
									}
								}
							}
                                                        }
						}
						else
						{
							$books = searchbooks($link,$actual_search_string,$escape, $storeurl,1);
						}
					}
				}
			}
                    }
		}
			$bookindex = count($books);
			$_SESSION[$pabcache]['numitems'] = $bookindex;
			if (($bookindex%10) != 0)
				$numpages = $bookindex/10 - ($bookindex%10)/10 + 1;
			else
				$numpages = $bookindex/10;
			$page = 1;
			$tmp = 0;
			while ($numpages > 0)
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
				$_SESSION[$pabcache][$page] = $bstring;
				$numpages = $numpages - 1;
				$page = $page + 1;
			}
			if ($bookindex > 10)
				$bookindex = 10;
	}
	$numitems = $_SESSION[$pabcache]['numitems'];
	if (($numitems%10) != 0)
		$numpages = $numitems/10 - ($numitems%10)/10 + 1;
	else
		$numpages = $numitems/10;

	if ($bookindex == 0)
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	if ($reqpage > $numpages)
	{
					$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
					$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
					$retcode = 404;
					return $output;
	}
	if ($booklinkurl == 1)
	{
		if ($bookindex > 1)
		{
			$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
			$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
			$retcode = 404;
		}
		else
		{
        		$db_query = "lock tables usedbooks read,book_inventory read, similarbooks read";
        		$db_result = mysqli_query($link, $db_query);
			$db_query = "select title, author1, isbn13, nlistprice, nourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount, blurb, booklink,currency,pages,isbn10 from book_inventory where bookid = $books[0]";
			$db_result = mysqli_query($link,$db_query);
			if ($db_result == TRUE)
			{
                                        $booklink = "";
                                         $savepercent = 0;
                                        $imagelarge = "";
                                        $image = "";
				$num_rows = mysqli_num_rows($db_result);
				if ($num_rows > 0)
				{
                                        $randomnumber = mt_rand();
					$db_row = mysqli_fetch_row($db_result);
                                        $listprice = converttoinr($db_row[15], $db_row[3]);
                                        $ourprice = converttoinr($db_row[15], $db_row[4]);
					if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
                                              $image = $db_row[2] . ".jpg";
                                        else
                                              $image = "tmpina.jpg";
					if (file_exists("/var/apache2/2.2/htdocs/" . $db_row[2] . ".jpg"))
                                              $imagelarge = $db_row[2] . ".jpg";
					else if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
                                              $imagelarge = "optimage/$db_row[2].jpg";
                                        else 
                                              $imagelarge = $image;
					$output = $output . "<table width=\"100%\">";
 				$output = $output . "<tr><td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><br><br><img alt=\"$db_row[0] by $db_row[1] ISBN:$db_row[2]\" title=\"$db_row[0]\" src=\"http://www.popabook.com/$imagelarge\" width=\"140px\" height=\"200px\"/></td><td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=\"75%\" align=\"left\" valign=\"top\" style=\"line-height:1.8em;\" class=\"atp\"> ";
				$output = $output . "<br><h1 style=\"line-height:1.3em;\">$db_row[0]&nbsp;</h1>$db_row[5] <br>";
                                $xid = rawurlencode("http://www.PopAbooK.com/book/$db_row[2]");
				$db_queryubc ="select count(*) from usedbooks where isbn13 like '$db_row[2]' and status=2";
				$db_resultubc = mysqli_query($link,$db_queryubc);
				if ($db_row[1] != "")
				{
					$output = $output . "&nbsp;by <span class=\"matp\"><b>" . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[8]";
				}
		                $output = $output . "</b></span>";
                                $etitle = rawurlencode($db_row[0]);
                                $output = $output . "<div style=\"padding-top:5px;\"><table><tr><td valign=\"top\"><iframe src=\"http://www.facebook.com/plugins/like.php?href=$xid&amp;layout=button_count&amp;show_faces=false&amp;width=125&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=21\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:125px; height:21px;\" allowTransparency=\"true\"></iframe></div></td><td valign=\"top\"><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://platform.twitter.com/widgets/tweet_button.html?url=$xid&via=PopAbooK&text=Checking%20out%20$etitle&related=popabook%3ASocial%20Book%20Store&count=horizontal\" style=\"width:130px; height:21px;\"></iframe></td><td valign=\"top\"><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://www.popabook.com/gbb.php?url=$xid&title=$etitle\" style=\"border:none;overflow:hidden;width:150px;height:21px;\"></iframe></td></tr></table></div>";

                                $output = $output . "<table width=\"100%\"><tr><td><div class=\"pabborder\" style=\"height:7px;\"></div><table width=\"100%\"><tr><td width=\"50%\" valign=\"top\"><div style=\"line-height:2.0em;\"><span class=\"satp\">List Price </span><span class=\"atp\">Rs. <s>$listprice</s> </span><br> Our Price <span class=\"ourprice\">Rs. $ourprice</span><br>";

				$save = $listprice - $ourprice;
				if ($save != 0)
				{
					$savepercent =  round(($save * 100)/$listprice);
					$output = $output . " <span class=\"satp\">You Save </span><span class=\"logoatp\">Rs. $save</span>";
				}
                                else
                                {
					$output = $output . " You Save Rs. <span class=\"atp\">0</span>";
                                }
				$output = $output . "</div></td><td width=\"50%\" valign=\"middle\"  class=\"boldlogoatp\">";
                                if ($save != 0)
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/$savepercent.gif\" alt=\"$savepercent%\">off</div>";
                                if ($save != 0 && $ourprice > 100)
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\">+</div>";
                                if ($ourprice > 100)
                                {
                                if (($savepercent > 9 && $savepercent < 20) || $save == 0) 
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/freeshipping2.gif\"></div>";
                                else
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/freeshipping1.gif\"></div>";
                                }
                                $output = $output . "</td></tr></table>";
                                if ($ourprice <= 100)
					$output = $output . "<span class=\"satp\">Please Add Rs. 30 for shipping if Total Bill does not exceed 100</span>";
				$output = $output . "<span class=\"atp\">" ;
				if ($db_row[11] == "Out Of Stock")
				{
					$output = $output . "<br><br><span class=\"logoatp\">$db_row[11]</span>";
				}
				else
				{
                                    if ($db_row[11] == "3-5")
                                    {
                                        $output = $output . "<span class=\"logoatpo\"><br>In Stock</span>";
					$output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[11] </span> business days";
                                    }
                                    else
                                    {
                                        $output = $output . "<span class=\"logoatpo\"><br>Available</span>";
					$output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[11] </span> business days";
                                    }
                                    
				}
				$output = $output . "</span>";

				$output = $output . "";
				$output = $output . "</td><td>&nbsp;</td>";
				$output = $output . "<td align=\"right\" valign=\"top\">"; 
				$isbnpub ="<b>ISBN-13</b> : $db_row[2] <br>";
				if ($db_row[9] !="")
				{
					$isbnpub = $isbnpub . "<b>Publisher</b> : $db_row[9]";
				}
				$isbnpub = $isbnpub . "<br>";
				if ($db_row[11] != "Out Of Stock")
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = $db_row[14] . "/" .  $db_row[2];
                                        if ($afflink == 1)
						$booklink = $booklink . "/" . $tokens[2] . "/" . $tokens[3];

                                        $output = $output . "<div style=\"text-align:center;\"><img src=\"http://www.popabook.com/buttonatc.jpg\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"addtocart('$image', '$listprice', '$ourprice', '$db_row[2]', '$rawtitle','$db_row[11]','')\"> <br><br> <br><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"text-decoration:none;border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></div></td></tr></table></td></tr></table>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = $db_row[14] . "/" . $db_row[2];
                                        if ($afflink == 1)
						$booklink = $booklink . "/" . $tokens[2] . "/" . $tokens[3];
				
                                        $output = $output . "<div><br><br><br><br><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></div></td></tr></table></td></tr></table>";
				}
				       if ($seo == 0)
				       {
                                        if ($storeurl == "")
                                        {
					$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><br><table width=\"100%\"> <tr><td width=\"20%\">&nbsp;&nbsp;";
					if ($db_row[10] != 0)
						$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"closeblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Hide Overview</a>";
					else
						$output = $output . "&nbsp;";
					$output = $output . "</td><td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
                                        if ($db_resultubc == TRUE)
                                        {
                                            $db_rowubc = mysqli_fetch_row($db_resultubc);
                                            if ($db_rowubc != null)
                                                if ($db_rowubc[0] > 0)
                                                    $output = $output . "<a id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\" href=\"javascript:void(0)\" onclick=\"getusedbooks('$randomnumber!$db_row[2]')\">$db_rowubc[0] Used Books</a>";
						else 
			    			$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					    else 
			    			$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
                                        }
                                        else	
			    		    $output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
                                            
					$output = $output . "</td><td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('$randomnumber!$db_row[2]')\">View All</a>&nbsp;&nbsp;</td></tr></table></div>";
                                         }
                                         else
                                         {
					$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><br><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;&nbsp;";
					if ($db_row[10] != 0)
						$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"closeblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Hide Overview</a>";
					else
						$output = $output . "&nbsp;";
					$output = $output . "</td><td width=\"33%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td><td width=\"33%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('$randomnumber!$db_row[2]')\">View All</a>&nbsp;&nbsp;</td></tr></table></div>";
                                         }
				       }
				       else
				       {
                                        if ($storeurl == "")
                                        {
					$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><br><table width=\"100%\"> <tr><td width=\"20%\">&nbsp;&nbsp;";
					if ($db_row[10] != 0)
						$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a>";
					else
						$output = $output . "&nbsp;";
					$output = $output . "</td><td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
                                      if ($db_resultubc == TRUE)
                                      {
                                        $db_rowubc = mysqli_fetch_row($db_resultubc);
                                            if ($db_rowubc != null)
                                                if ($db_rowubc[0] > 0)
                                                    $output = $output . "<a id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\" href=\"javascript:void(0)\" onclick=\"getusedbooks('$randomnumber!$db_row[2]')\">$db_rowubc[0] Used Books</a>";
                                                else
                                                $output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
                                            else
                                                $output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
                                        }
                                        else
                                            $output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					$output = $output . "</td><td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"closeblurb('$randomnumber!$db_row[2]')\">Hide All</a>&nbsp;&nbsp;</td></tr></table></div>";
                                        }
                                        else
                                        {
                                            $output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><br><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;&nbsp;";
                                        if ($db_row[10] != 0)
                                                $output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a>";
                                        else
                                                $output = $output . "&nbsp;";
					$output = $output . "</td><td width=\"33%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td><td width=\"33%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"closeblurb('$randomnumber!$db_row[2]')\">Hide All</a>&nbsp;&nbsp;</td></tr></table></div>";
                                        }
				       }
					/*if ($seo == 10)
					{
						$output = $output . "<div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"><div style=\"text-align:left;padding-left:15px;\">";
                                               if ($db_row[10] == 1)
                                                $output = $output . "<br><h3>Book Overview : $db_row[0]</h3><br><br><span class=\"atp\" style=\"line-height:1.5em;\">$db_row[13]</span>";
                                                $booklinkencode = rawurlencode("http://www.PopAbooK.com/$booklink");
                                                $output = $output . "<br><br><h3>Share comments/reviews, on $db_row[0], with your friends</h3><br><br><div style=\"text-align:left;padding-left:20px;\">";
                                                   $output = $output . "<fb:comments xid=\"$xid\" url=\"http://www.PopAbooK.com/$booklink\" title=\"$db_row[0] by $db_row[1]\" width=\"675\" numposts=\"5\">  </fb:comments></div></div></div>";
					}
					else */
					{
                                            $simbooks = "";
                                            if ($storeurl == "")
						$simbooks = showsimilarbooksonpage($link, $books[0],$seo, $linkurl);
						/* $usedbooks = usedbooksfromdb($link, $db_row[2]); */
						$output = $output . "<div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\">";
						if ($db_row[10] == 1)
							$output = $output . "<div style=\"text-align:left;padding-left:15px;\"><h3><br>Book Overview: $db_row[0]</h3><br><br><span class=\"atp\" style=\"line-height:1.5em;\">$db_row[13]</span></div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div>";
                                                $output = $output . "<div style=\"text-align:left;padding-left:10px;\"><br><h3>Share comments/reviews, on $db_row[0], with your friends</h3><br><br><div style=\"text-align:left;padding-left:20px;\">";
                                                   $output = $output . "<fb:comments xid=\"$xid\" url=\"http://www.PopAbooK.com/$booklink\" title=\"$db_row[0] by $db_row[1]\" width=\"675\" numposts=\"5\">  </fb:comments></div><br><br></div>";
                                                if ($storeurl == "")
						$output = $output . "<div style=\"text-align:left;padding-left:10px;\"><a name=\"sb\"></a><br><h3>Similar Books : $db_row[0]</h3></div>$simbooks";
/* <div style=\"text-align:left;padding-left:10px;\"><a name=\"ub\"></a><br><h3>Used Books : $db_row[0]</h3><br><br>";
						if ($usedbooks == "")
							$output = $output . "<h4>Sorry. Currently there are no used books on sale for this book.</h4></div>";
						else
							$output = $output . "<h4>$usedbooks</h4></div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div>"; */
						$output = $output . "<div style=\"text-align:left;padding-left:10px;line-height:1.5em;\"><br><h3>Details of Book : $db_row[0]</h3><br><br><b>Title</b> : $db_row[0]<br><b>Authors</b> : $db_row[1] $db_row[6] $db_row[7] $db_row[8]";
                                                if ($db_row[5]!= NULL)
                                                    $output = $output . "<br><b>Edition</b> : $db_row[5]";
                                                $output = $output . "<br><b>Listprice</b> : $listprice<br><b>Our Price</b> : $ourprice";
                                                if ($db_row[16]!= NULL)
                                                    $output = $output . "<br><b>Pages</b> : $db_row[16]";
                                                if ($db_row[17]!= NULL)
                                                    $output = $output . "<br><b>ISBN-10</b> : $db_row[17]";
                                                $output = $output . "<br>$isbnpub</div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div>";
						$output = $output . "<div style=\"text-align:center\"><h3>Book : $db_row[0]</h3><h4> <br>by $db_row[1] <br>ISBN:$db_row[2]</h4></div></div>";
							
					}
                                        $metadata =  "<meta name=\"keywords\" content=\"$db_row[0] : $db_row[1] ISBN:$db_row[2]\">" . "<meta name=\"Description\" content=\"Buy $db_row[0] : $db_row[1] ListPrice: $listprice and OurPrice: $ourprice";
			        if ($savepercent > 0)
					$metadata = $metadata . " Save($savepercent%)";
				$metadata = $metadata . " + FREE Shipping Book Overview Similar Books Used Books\">";
				$metadata = $metadata . "<link rel=\"canonical\" href=\"http://www.popabook.com/$booklink\"/>";
                                
					$title = "<title>$db_row[0] : $db_row[1] : Books : $storename PopAbooK.com</title>";
					
				}
				else
				{
					$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
					$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
					$retcode = 404;
				}
			}
			else
			{
				$output = $output . " <div class=\"atp\" >Server is Temporarily not Available. Please Try Again";
				$output = $output . "<br> Go Back To <a href=\"http://www.popabook.com\">PopAbooK.com</a></div>";
				$retcode = 404;
			}
        		$db_query = "unlock tables";
        		$db_result = mysqli_query($link, $db_query);
		}
		return $output;
	}
	
	$metadata =  "<meta name=\"keywords\" content=\"$search_token Books\">" . "<meta name=\"Description\" content=\"Buy $search_token Books\">";
	$title = "<title>$search_token Books PopAbooK.com India Online Book Store</title>";

	showbooksonpage($link, $output, $reqpage, $numpages, $books, $bookindex,$search_token,$seo, $storeurl); 
	return $output;
}

function showbooksonpage($link,&$output, $reqpage, $numpages, $books, $bookindex,$search_token,$seo, $storeurl)
{
        if ($storeurl != "")
               $storeurl = "/" . $storeurl . "/bookstore";
	$output = $output . "<div style=\"width:100%\">";
	$output = $output . "<span class=\"atp\"> <br>&nbsp;&nbsp;Showing Page $reqpage of $numpages Pages for </span><span class=\"blueatp\"> $search_token<br></span>";

        $db_query = "lock tables usedbooks read,book_inventory read";
        $db_result = mysqli_query($link, $db_query);
        if ($db_result == FALSE)
        {
		$output = "<div style=\"width:100%\"> Temporarily server is unavailable, please try again. </div>";
		return;
        }

     	
	for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
	{
		$db_query = "select title, author1, isbn13, nlistprice, nourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount,booklink,currency from book_inventory where bookid = $books[$ti]";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
                                 $listprice = converttoinr($db_row[14], $db_row[3]);
                                 $ourprice = converttoinr($db_row[14], $db_row[4]);
				$randomnumber = mt_rand();
				$db_query1="select count(*) from usedbooks where isbn13 like '$db_row[2]' and status=2";
				$db_result1= mysqli_query($link,$db_query1);
				$booklink = $db_row[13] . "/" . $db_row[2] . $storeurl;
				if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
					$image = $db_row[2] . ".jpg";
				else
					$image = "tmpina.jpg";
                                if ($seo == 1)
                                {
                                        $output = $output . "<div id=\"$randomnumber!$db_row[2]main\"><br><table width=\"100%\"><tr><td>&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><a href=\"http://www.popabook.com/$booklink\"><img alt=\"$db_row[0]\" src=\"http://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></a></td><td>&nbsp;&nbsp;</td><td width=\"70%\" valign=\"top\" class=\"atp\" style=\"line-height:1.5em;\">";
                                        $output = $output . "<a href=\"http://www.popabook.com/$booklink\" style=\"text-decoration:none;\"><h2>$db_row[0]</h2></a>&nbsp;$db_row[5]";
                                }
                                else
                                {
                                        $output = $output . "<div id=\"$randomnumber!$db_row[2]main\"><br><table width=\"100%\"><tr><td>&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><a href=\"javascript:void(0);\" onclick=\"magnifybook('$randomnumber!$db_row[2]')\"><img alt=\"$db_row[0]\" src=\"http://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></a></td><td>&nbsp;&nbsp;</td><td width=\"70%\" valign=\"top\" class=\"atp\" style=\"line-height:1.5em;\">";
                                        $output = $output . "<a href=\"javascript:void(0);\" style=\"text-decoration:none;\" class=\"logoatp\" onclick=\"magnifybook('$randomnumber!$db_row[2]')\"><h2>$db_row[0]</h2></a>&nbsp;$db_row[5]";
                                }
 
				$output = $output . "<br>";
				if ($db_row[1] != "")
				{
				        $output = $output . "by <span class=\"boldatp\"><b>";
					$output = $output . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><b>$db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><b>$db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><b>$db_row[8]";
				}
		                $output = $output . "</b></span>";
			
				$output = $output . "<br><span class=\"satp\">List Price Rs. <span class=\"atp\"> <s>$listprice</s> </span><br> Our Price Rs. <span class=\"logoatp\">$ourprice";
			        if ($ourprice > 100)
					$output = $output . " + FREE Shipping</span>";
				else
					$output = $output . "</span> (Please Add Rs. 30 if Total Bill does not exceed 100)";
				$save = $listprice - $ourprice;
				if ($save != 0)
				{
					$savepercent = round(($save * 100)/$listprice);
					$output = $output . " <br>You Save Rs. <span class=\"logoatp\">$save ($savepercent%)</span>";
				}
				$output = $output . "</span>";
				$output = $output . "<span class=\"atp\">" ;
				if ($db_row[11] == "Out Of Stock")
				{
					$output = $output . "<br><span class=\"boldatp\">$db_row[11]</span>";
				}
				else
				{
					$output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[11] </span> business days";
				}
				$output = $output . "</span>";

				$output = $output . "";
				$output = $output . "</td>";
				$output = $output . "<td align=\"right\">"; 
				$isbnpub ="ISBN : $db_row[2]";
				if ($db_row[9] !="")
				{
					$isbnpub = $isbnpub . "&nbsp;&nbsp;Publisher : $db_row[9]";
				}
				$isbnpub = $isbnpub . "<br>";
				if ($db_row[11] != "Out Of Stock")
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = $db_row[13] . "/" . $db_row[2] . $storeurl;
					$output = $output . "<div><a href=\"javascript:void(0);\"> <img src=\"http://www.popabook.com/buttonatc.jpg\" onclick=\"addtocart('$image', '$listprice', '$ourprice', '$db_row[2]', '$rawtitle','$db_row[11]','')\"></a> <br><br> <a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=http%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"http://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a> </div></td></tr><tr><td>&nbsp;</td><td colspan=\"3\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table></div>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = $db_row[13] . "/" . $db_row[2] . $storeurl;
					$output = $output . "<div><a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=http%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"http://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"3\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table></div>";
				}
                                if ($storeurl == "")
                                {
				$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
				if ($db_row[10] != 0)
					$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a></td>";
				else
					$output = $output . "&nbsp;</td>";
				$output = $output . "<td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td>";
				$output = $output . "<td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td>";
				$output = $output . "<td width=\"20%\" align=\"center\">";
				if ($db_result1 == TRUE)
				{
					$db_row1 = mysqli_fetch_row($db_result1);
					if ($db_row1 != null)
					{
						if ($db_row1[0] > 0)
							$output = $output . "<a href=\"javascript:void(0)\" id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('$randomnumber!$db_row[2]')\">$db_row1[0] Used Books</a>";
						else
			    				$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					}
					else
					{
						$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					}
				}
				else
						$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
				$output = $output . "</td><td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('$randomnumber!$db_row[2]')\">View All</a></td></tr></table></div><div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"></div>";
                                }
                                else
                                {
				$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;";
				if ($db_row[10] != 0)
					$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a></td>";
				else
					$output = $output . "&nbsp;</td>";
				$output = $output . "<td width=\"33%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td><td width=\"33%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('$randomnumber!$db_row[2]')\">View All</a></td></tr></table></div><div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"></div>";
                                }
				$output = $output . "<div class=\"pabborder\"></div>";
			}
		}
	}
	$output = $output . "<div style=\"width:100%\" class=\"atp\">";

	$output = $output . "<div style=\"align:left\";>&nbsp;&nbsp;Showing Page $reqpage of $numpages Pages for <span class=\"blueatp\"> $search_token</span></div><div>"; 
	$encoded = rawurlencode($search_token);
	if ($reqpage == $numpages)
	{
		;	
	}
	else
	{
		$npage = $reqpage + 1;
		if ($seo == 1)
		{
			$output = $output . "<div class=\"pgpreviousnextshow\"><a href=\"http://www.popabook.com/$search_token-$numpages\"> LAST </a></div>";
			$output = $output . "<div class=\"pgpreviousnextshow\"><a href=\"$search_token-$npage\"> NEXT </a></div>";
		}
		else
		{
			$output = $output . "<a href=\"javascript:void(0);\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getsearchdata('search','search_string=$encoded&reqpage=$numpages')\">LAST</div></a>";
			$output = $output . "<a href=\"javascript:void(0);\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getsearchdata('search','search_string=$encoded&reqpage=$npage')\">NEXT</div></a>";
		}
	}
	if ($numpages != 1)
	$output = $output . "<div class=\"pgpreviousnextactive\"> <a href=\"javascript:void(0);\">$reqpage</a></div>";
	if ($reqpage == 1)
	{
		;
	}
	else
	{
		$ppage = $reqpage - 1;
		if ($seo == 1)
		{
			$output = $output . "<div class=\"pgpreviousnextshow\"><a href=\"$search_token-$ppage\"> PREVIOUS </a></div>";
			$output = $output . "<div class=\"pgpreviousnextshow\"><a href=\"$search_token-1\"> FIRST </a></div>";
		}
		else
		{
			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getsearchdata('search','search_string=$encoded&reqpage=$ppage')\"><a href=\"javascript:void(0);\">PREVIOUS</a></div>";
			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getsearchdata('search','search_string=$encoded&reqpage=1')\"><a href=\"javascript:void(0);\">FIRST</a></div>";
		}
	}
	$output = $output . "</div></div></div>"; 
        $db_query = "unlock tables";
	$db_result = mysqli_query($link, $db_query);
}

function searchbooks($link, $search_string, $escape, $storeurl, $strict)
{
	/*$output = $output . "$search_string \n $escape "; */
        $escape = ", ;:.\/\"'\n\r-()!*?&\t";
	$books = array();
	$ranks = array();
	$tbr = array(array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array());
	$tok = strtok($search_string, $escape);
    $numtok = 0;
        if ($storeurl != "" && $strict == 1)
        {
             $db_query = "select bookid from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl')";
             $db_result = mysqli_query($link, $db_query);
             if ($db_result == TRUE)
             {
                  $db_row = mysqli_fetch_row($db_result);
                  while ($db_row != NULL)
                  {
                      $tbr[$numtok][$db_row[0]] = 25;
                      $db_row = mysqli_fetch_row($db_result);
                  }
                 
             }
             $numtok = 1;
        }
	while ($tok != null)
	{
		if ($tok == "s")
		{
    		$tok = strtok($escape);
			continue;
		}
		$alttok = "";
		$toklen = strlen($tok);
		if (strrpos($tok,"s") == ($toklen - 1))
		{
			for ($istr = 0 ; $istr < ($toklen - 1); $istr = $istr + 1)
			{
				$alttok = $alttok . $tok[$istr];
			}
		}
		else
		{
			$alttok = $tok . "s";
		}
		$db_query = "select keywordid from keywords where keyword like '$tok' or keyword like '$alttok'";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$num_rows = mysqli_num_rows($db_result);
			if ($num_rows > 0)
			{
				$keywordid1 = 0;
				$keywordid2 = 0;
				if ($num_rows == 2)
				{
					$db_row = mysqli_fetch_row($db_result);
					$keywordid1 = $db_row[0];
					$db_row = mysqli_fetch_row($db_result);
					$keywordid2 = $db_row[0];
					$db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1 or keywordid = $keywordid2";
				}
				else
				{
					$db_row = mysqli_fetch_row($db_result);
					$keywordid1 = $db_row[0];
					$db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1";
				}

				$db_result = mysqli_query($link, $db_query);
				if ($db_result == TRUE)
				{
					$num_rows = mysqli_num_rows($db_result);
					if ($num_rows > 0)
					{

						$trows = 0;
			   	   		while($trows != $num_rows)
				   		{
					   		$dbrow = mysqli_fetch_row($db_result);
					   		if(isset($tbr[$numtok][$dbrow[0]]) == TRUE)
					   		{
					   			$tbr[$numtok][$dbrow[0]] = $tbr[$numtok][$dbrow[0]] + $dbrow[1];
					   		}
					   		else
					   		{
					   			$tbr[$numtok][$dbrow[0]] = $dbrow[1];
					   		}
					   		$trows = $trows + 1;
				   		}	   
					}
				}
			}
	        $numtok = $numtok + 1;
		}
    	$tok = strtok($escape);
	}

	/* find minimum length of array */
	$temp = 0 ;
	$minlen;
   	$minarray = 0;
	while ($temp < $numtok)
	{
		if ($temp == 0)
		{
			$minlen = count($tbr[$temp]);
			$minarray = $temp;
		}
		else
		{
			if (count($tbr[$temp]) < $minlen)
			{
				$minlen = count($tbr[$temp]);
				$minarray = $temp;
			}
		}
		$temp = $temp + 1;
	}
	foreach ($tbr[$minarray] as $key => $value)
	{
		$temp = 0;
		$flag = 1;
		while ($temp < $numtok)
		{
			if (isset($tbr[$temp][$key]) == FALSE)
				$flag = 0;
			else
				$tbr[$minarray][$key] = $tbr[$minarray][$key] + $tbr[$temp][$key];
			$temp = $temp + 1;
		}
		if ($flag == 0)
			$tbr[$minarray][$key] = 0;
	}
/* transfer to books and ranks array */
	$bookindex = 0;
	foreach ($tbr[$minarray] as $key => $value)
	{
		if ($value > 0)
		{
			$books[$bookindex] = $key;
			$ranks[$bookindex] = $value;
			$bookindex = $bookindex + 1;
		}

	}
	$ti = 0;
	$tj = 0;
	for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
	{
		for ($tj = $ti + 1; $tj < $bookindex; $tj = $tj + 1)
		{
			if ($ranks[$tj] > $ranks[$ti])
			{
				$temp = $ranks[$tj];
				$ranks[$tj] = $ranks[$ti];
				$ranks[$ti] = $temp;

				$temp = $books[$tj];
				$books[$tj] = $books[$ti];
				$books[$ti] = $temp;
			}
		}
	}
	/*print_r($books);*/
	return($books);
}
function makebooklink($title, $isbn)
{
        $title = str_replace("&amp;", " ", $title);
	$escape = ", ;:.\/\"'\n\r-()!*%?&\t";
	$tooks = array();
	$in = 0;
	$tok = strtok($title,$escape);
	while ($tok != null)
	{
		/*if(strlen($tok) == 1 || (is_numeric($tok) == TRUE && strlen($tok) < 4))
		{
			;
			$tok = strtok($escape);
		}
		else */
		{
			$tooks[$in] = $tok;
			$tok = strtok($escape);
			$in = $in + 1;
		}
	}
	$link = "";
	$it = 0;
	while ($it < $in)
	{
		if ($it == 0)
			$link = $link . $tooks[$it];
		else
			$link = $link . "-" . $tooks[$it];
		$it = $it + 1;
	}
	$link = $link . "/" . $isbn;
	return $link;
}
function othersare ($link)
{
	echo "<div class=\"box\"><div class=\"boxh\"> <span class=\"logoatp\"><br>Popular Searches<br><br></span></div>";
	echo "<br><span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','materials')\">materials</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','elections')\">elections</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','electronics')\">electronics</span>  <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','kings of manipur')\">kings of manipur</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','business writing')\">business writing</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','performance management')\">performance management</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','team building')\">team building</span> <br>
		<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','entrepreneurship')\">entrepreneurship</span> 
  </span>";
	echo "<br><br><div class=\"boxh\" ><span class=\"logoatp\"><br>Popular Categories<br><br></span> </div>";
	echo "<br><span class=\"blueatp\"> <a href=\"higher-education\">Higher Education</a><br>
		<a href=\"mechanical\">Mechanical Engineering</a> <br>
		<a href=\"Environment\">Environment</a><br>
		<a href=\"Pharmacy\">Pharmacy</a> <br>
		<a href=\"Nursing\">Nursing</a> <br>
		<a href=\"IIT\">IIT</a> <br>
		<a href=\"operating-systems\">Operating Systems</a> <br>
		<a href=\"GMAT\">GMAT</a> <br> <br>
		</span></div>";
}
function showbrowseoptions($seo)
{
	if ($seo == 0)
	{ 
		
		$keylines = file("viewall.txt");
		foreach($keylines as $line)
		{
			return $line;
		}

	}
	else
	{
		$keylines = file("viewallseo.txt");
		foreach($keylines as $line)
		{
			return $line;
		}
	}
}
function showsimilarbooksonpage($link,$bookid,$seo,$linkurl)
{
	
        $db_query = "select s1,s2,s3,s4,s5,s6 from similarbooks where bookid=$bookid";
	$db_result10 = mysqli_query($link, $db_query);
        $output = "";
        if ($linkurl != "")
            $linkurl = "/" . $linkurl . "/" ."af";
	if ($db_result10 == TRUE)
	{
		$db_row10 = mysqli_fetch_row($db_result10);
		if ($db_row10 != null)
		{	
	for ($ti = 0; $ti < 6; $ti = $ti + 1)
	{
		$db_query = "select title, author1, isbn13, nlistprice, nourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount,booklink,currency from book_inventory where bookid = $db_row10[$ti]";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
                                 $listprice = converttoinr($db_row[14], $db_row[3]);
                                 $ourprice = converttoinr($db_row[14], $db_row[4]);
                                $randomnumber = mt_rand();
                                if ($ti == 0)
				{
					$output = "";
	$output = $output . "<div style=\"width:100%;\">";
				}
				else
				{
					$output = $output . "<div class=\"similarborder\"><br></div><div class=\"pabborder\"></div>";
				}
				$db_query1="select count(*) from usedbooks where isbn13 like '$db_row[2]' and status=2";
				$db_result1= mysqli_query($link,$db_query1);
				$booklink = $db_row[13] . "/" . $db_row[2] . $linkurl;
				if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
					$image = $db_row[2] . ".jpg";
				else
					$image = "tmpina.jpg";
                                if ($seo == 1)
                                {
                                	$output = $output . "<div id=\"$randomnumber!$db_row[2]main\"><br><table width=\"100%\"><tr><td>&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><a href=\"http://www.popabook.com/$booklink\"><img alt=\"$db_row[0] by $db_row[1] ISBN:$db_row[2]\" src=\"http://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></a></td><td>&nbsp;&nbsp;</td><td width=\"70%\" valign=\"top\" class=\"atp\" style=\"line-height:1.5em;\">";
                                        $output = $output . "<a href=\"http://www.popabook.com/$booklink\" style=\"text-decoration:none;\"><h2>$db_row[0]</h2></a>&nbsp;$db_row[5]";
                                }
                                else
                                {
                                	$output = $output . "<div id=\"$randomnumber!$db_row[2]main\"><br><table width=\"100%\"><tr><td>&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><a href=\"javascript:void(0);\" onclick=\"magnifybook('$randomnumber!$db_row[2]')\"><img alt=\"$db_row[0]\" src=\"http://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></a></td><td>&nbsp;&nbsp;</td><td width=\"70%\" valign=\"top\" class=\"atp\" style=\"line-height:1.5em;\">";
                                        $output = $output . "<a href=\"javascript:void(0);\" style=\"text-decoration:none;\" class=\"logoatp\" onclick=\"magnifybook('$randomnumber!$db_row[2]')\"><h2>$db_row[0]</h2></a>&nbsp;$db_row[5]";
                                }
				$output = $output . "<br>by <span class=\"boldatp\"><b>";
				if ($db_row[1] != "")
				{
					$output = $output . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><b>$db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><b>$db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . "</b></span> and <span class=\"boldatp\"><br>$db_row[8]";
				}
		                $output = $output . "</b></span>";
			
				$output = $output . "<br><span class=\"satp\">List Price Rs. <span class=\"atp\"> <s>$listprice</s> </span><br> Our Price Rs. <span class=\"logoatp\">$ourprice";
			        if ($ourprice > 100)
					$output = $output . " + FREE Shipping</span>";
				else
					$output = $output . "</span> (Please Add Rs. 30 if Total Bill does not exceed 100)";
				$save = $listprice - $ourprice;
				if ($save != 0)
				{
					$savepercent = round(($save * 100)/$listprice);
					$output = $output . " <br>You Save Rs. <span class=\"logoatp\">$save ($savepercent%)</span>";
				}
				$output = $output . "</span>";
				$output = $output . "<span class=\"atp\">" ;
				if ($db_row[11] == "Out Of Stock")
				{
					$output = $output . "<br><span class=\"boldatp\">$db_row[11]</span>";
				}
				else
				{
					$output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[11] </span> business days";
				}
				$output = $output . "</span>";

				$output = $output . "";
				$output = $output . "</td>";
				$output = $output . "<td align=\"right\">"; 
				$isbnpub ="ISBN : $db_row[2]";
				if ($db_row[9] !="")
				{
					$isbnpub = $isbnpub . "&nbsp;&nbsp;Publisher : $db_row[9]";
				}
				$isbnpub = $isbnpub . "<br>";
				if ($db_row[11] != "Out Of Stock")
				{
					$rawtitle = rawurlencode($db_row[0]);
					$output = $output . "<div><a href=\"javascript:void(0);\"><img src=\"http://www.popabook.com/buttonatc.jpg\" onclick=\"addtocart('$image', '$listprice', '$ourprice', '$db_row[2]', '$rawtitle','$db_row[11]','')\"></a> <br><br> <a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=http%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"http://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a> </div></td></tr><tr><td>&nbsp;</td><td colspan=\"3\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table></div>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = $db_row[13] . "/" .  $db_row[2];
					$output = $output . "<div><a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=http%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"http://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"3\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table></div>";
				}
				$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
				if ($db_row[10] != 0)
					$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a></td>";
				else
					$output = $output . "&nbsp;</td>";
				$output = $output . "<td width=\"20%\" class=\"atp\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getreviews('$randomnumber!$db_row[2]')\">Friend's Reviews</a></td>";
				$output = $output . "<td width=\"20%\" class=\"atp\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td>";
				$output = $output . "<td width=\"20%\" align=\"center\">";
				if ($db_result1 == TRUE)
				{
					$db_row1 = mysqli_fetch_row($db_result1);
					if ($db_row1 != null)
					{
						if ($db_row1[0] > 0)
							$output = $output . "<a href=\"javascript:void(0)\" id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('$randomnumber!$db_row[2]')\">$db_row1[0] Used Books</a>";
						else
			    				$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					}
					else
					{
						$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					}
				}
				else
						$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
				$output = $output . "</td><td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('$randomnumber!$db_row[2]')\">View All</a></td></tr></table></div><div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"></div>";
			}
		}
	}
	if ($output != "") 
	{
		$output = $output . "<div class=\"similarborder\"><br></div><div class=\"pabborder\"></div>";
		$output = $output . "</div>"; 
	}
	else 
		$output = $output . "<div style=\"padding-left:5px;\">Sorry. Could not find similar books! </div>";

		}}
		return $output;
}
function browseub($link)
{
    $ubarray = array();
    $ubindex = 0;
    $db_query = "select distinct(isbn13) from usedbooks where status=2";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
        $db_row = mysqli_fetch_row($db_result);
        while ($db_row != null)
        {
            $db_query1 = "select bookid from book_inventory where isbn13 like '$db_row[0]'";
            $db_result1 = mysqli_query($link, $db_query1);
            if ($db_result1 == TRUE)
            {
                 $db_row1 = mysqli_fetch_row($db_result1);
                 if ($db_row1 != null)
                 {
                      $ubarray[$ubindex] = $db_row1[0];
                      $ubindex = $ubindex + 1;
                 }
            }
            $db_row = mysqli_fetch_row($db_result);
        }
                 
    }
    return $ubarray;
}
function browsenr($link, $storeurl)
{
    $ubarray = array();
    $ubindex = 0;
    if ($storeurl == "")
    $db_query = "select bookid from newbooks order by id desc";
    else
       $db_query = "select bookid from booksinstore where affid in (select affid from affiliates where storeurl like '$storeurl') order by id desc";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
        $db_row = mysqli_fetch_row($db_result);
        while ($db_row != null)
        {
            $ubarray[$ubindex] = $db_row[0];
            $ubindex = $ubindex + 1;
            $db_row = mysqli_fetch_row($db_result);
        }
    }
    return $ubarray;
}
function valid_bookstore($link , $bookstore)
{
   $storeurl = strtolower($bookstore);
   $db_query = "select storename,affid from affiliates where storeurl like '$storeurl'";
   $db_result = mysqli_query($link, $db_query);
   if ($db_result == TRUE)
   {
       if (mysqli_num_rows($db_result) == 1)
       {
               $db_row = mysqli_fetch_row($db_result);
               $_SESSION['__PABstorename'] = $db_row[0];
               $_SESSION['__PABaffid'] = $db_row[1];
               return TRUE;
       }
       else
               return FALSE;
   }
   return FALSE;
}

?>
