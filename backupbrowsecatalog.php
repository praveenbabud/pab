<?php
require_once('pab_util.php');
function browsecatalog($link, $search_string, &$title, &$metadata,$seo, &$retcode)
{
	$booklink = 0;
	$escape = " -";
	$books = array ();
        $bookindex = 0;
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
		$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
		}
		$subtokens = explode("-",$tokens[0]);
		if (is_numeric($subtokens[count($subtokens) - 1]) == TRUE)
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
			$search_token = $tokens[0];
			$reqpage = 1;
		}
	}
	else if (count($tokens) == 2)
	{
		$booklink = 1;
		$reqpage = 1;
		$search_token = $tokens[1];
	}
	else
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	if ($reqpage == 0)
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	$books = array();
	if (isset($_SESSION[$search_token]) == TRUE)
	{
		if (isset($_SESSION[$search_token][$reqpage]) == TRUE)
		{
		$bookstring = $_SESSION[$search_token][$reqpage];
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
		if (count($tokens) == 2)
		{
			$books = searchbooks($link, $search_token, $escape);
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
								$books = array_merge($books, searchbooks($link, $db_row[0], $escape));
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
								$books = searchbooks($link, $actual_search_string, $escape);
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
										$books = array_merge($books, searchbooks($link, $db_row[0], $escape));
										$db_row = mysqli_fetch_row($db_result);
									}
								}
							}
                                                        }
						}
						else
						{
							$books = searchbooks($link,$actual_search_string,$escape);
						}
					}
				}
			}
		}
			$bookindex = count($books);
			$_SESSION[$search_token]['numitems'] = $bookindex;
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
				$_SESSION[$search_token][$page] = $bstring;
				$numpages = $numpages - 1;
				$page = $page + 1;
			}
			if ($bookindex > 10)
				$bookindex = 10;
	}
	$numitems = $_SESSION[$search_token]['numitems'];
	if (($numitems%10) != 0)
		$numpages = $numitems/10 - ($numitems%10)/10 + 1;
	else
		$numpages = $numitems/10;

	if ($bookindex == 0)
	{
		$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
		$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
		$retcode = 404;
		return $output;
	}
	if ($reqpage > $numpages)
	{
					$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
					$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
					$retcode = 404;
					return $output;
	}
	if (count($tokens) == 2)
	{
		if ($bookindex > 1)
		{
			$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
			$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
			$retcode = 404;
		}
		else
		{
			$db_query = "select title, author1, isbn13, listprice, ourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount, blurb from book_inventory where bookid = $books[0]";
			$db_result = mysqli_query($link,$db_query);
			if ($db_result == TRUE)
			{
				$num_rows = mysqli_num_rows($db_result);
				if ($num_rows > 0)
				{
                                        $randomnumber = mt_rand();
					$db_row = mysqli_fetch_row($db_result);
					if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
						$image = $db_row[2] . ".jpg";
					else
						$image = "ina.jpg";
					if (file_exists("/var/apache2/2.2/htdocs/" . $db_row[2]. ".jpg"))
                                              $imagelarge = $db_row[2] . ".jpg";
                                        else
                                              $imagelarge = "optimage/$db_row[2].jpg";
						$output = $output . "<br><table width=\"100%\"><tr><td>&nbsp;</td><td colspan=\"3\" class=\"atp\" align=\"left\">";
					$output = $output . "<h1>$db_row[0]&nbsp;</h1>$db_row[5]";
 				$output = $output . "</td></tr><tr><td>&nbsp;</td><td align=\"left\" valign=\"top\"><img alt=\"$db_row[0]\" src=\"https://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></td><td valign=\"top\"><span class=\"atp\"> ";
				$output = $output . " by <h4><i>";
				if ($db_row[1] != "")
				{
					$output = $output . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . " and $db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . " and $db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . " and $db_row[8]";
				}
		                $output = $output . "</i></h4></span>";
				$output = $output . "<br><span class=\"satp\">List Price Rs. <span class=\"atp\"> <s>$db_row[3]</s> </span><br> Our Price Rs. <span class=\"logoatp\">$db_row[4]";
			        if ($db_row[4] > 100)
					$output = $output . " + FREE Shipping</span>";
				else
					$output = $output . "</span> (Please Add Rs. 30 if Total Bill does not exceed 100)";
				$save = $db_row[3] - $db_row[4];
				if ($save != 0)
				{
					$savepercent = intval (($save * 100)/$db_row[3]);
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
					$booklink = makebooklink($db_row[0], $db_row[2]);

					$output = $output . "<div><img src=\"https://www.popabook.com/buttonatc.jpg\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"addtocart('$image', '$db_row[3]', '$db_row[4]', '$db_row[2]', '$rawtitle','$db_row[11]')\"> <br><br> <a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = makebooklink($db_row[0], $db_row[2]);
					$output = $output . "<div><a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				       if ($seo == 0)
				       {
					$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;";
					if ($db_row[10] != 0)
						$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"closeblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Hide Overview</a>";
					else
						$output = $output . "&nbsp;";
					$output = $output . "</td><td width=\"33%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td><td width=\"34%\" align=\"right\">";
		        		if ($db_row[12] == 0)
			    			$output = $output . "<span id=\"$randomnumber!$db_row[2]usedbooks\" class=\"blueatp\">0 Used Books</span>";
					$output = $output . "</td></tr></table></div>";
				       }
				       else
				       {
						$output = $output . "<table width=\"100%\"> <tr><td width=\"33%\" align=\"left\">&nbsp;&nbsp;</td><td width=\"33%\" align=\"center\"><a href=\"#sb\">Similar Books</a><td align=\"right\"><a href=\"#ub\">Used Books</a></td></tr></table>";
				       }
					if ($seo == 0)
					{
						$output = $output . "<div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"><br><div style=\"text-align:left;padding-left:10px;\"><h4>$db_row[13]</h4></div></div>";
					}
					else
					{
						$simbooks = showsimilarbooksonpage($link, $books[0],1);
						$usedbooks = usedbooksfromdb($link, $db_row[2]);
						$output = $output . "<div style=\"width:100%;\">";
						if ($db_row[10] == 1)
							$output = $output . "<div class=\"pabborder\"></div><div style=\"text-align:left;padding-left:10px;\"><h3><br>Book Overview: $db_row[0] </h3><br><br><h4>$db_row[13]</h4></div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div>";
						$output = $output . "<div class=\"pabborder\"></div><div style=\"text-align:left;padding-left:10px;\"><a name=\"sb\"></a><br><h3>Similar Books : $db_row[0]</h3></div>$simbooks<div style=\"text-align:left;padding-left:10px;\"><a name=\"ub\"></a><br><h3>Used Books : $db_row[0]</h3><br><br>";
						if ($usedbooks == "")
							$output = $output . "<h4>Sorry. Currently there are no used books on sale for this book.</h4></div>";
						else
							$output = $output . "<h4>$usedbooks</h4></div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div>";
						$output = $output . "<div class=\"pabborder\"></div><div style=\"text-align:left;padding-left:10px;\"><br><h3>Image of Book : $db_row[0]</h3><br><br> <img src=\"https://www.popabook.com/$imagelarge\" alt=\"$db_row[0]\" width=\"140px\" height=\"200px\"></div><div style=\"text-align:right\"><a class=\"blueatp\" href=\"#\">Top</a></div></div>";
						$output = $output . "<div style=\"text-align:center\"><h3>Book : $db_row[0]</h3><h4> <br>by $db_row[1] <br>ISBN:$db_row[2]</h4></div>";
							
					}
					$metadata =  "<meta name=\"keywords\" content=\"$db_row[0] by $db_row[1]\">" . "<meta name=\"Description\" content=\"Buy and Sell $db_row[0] ListPrice: $db_row[3] and OurPrice: $db_row[4]\">";
					$title = "<title>$db_row[0] : $db_row[1] PopAbooK.com India</title>";
					
				}
				else
				{
					$output = $output . " <div class=\"atp\" >404! Not Found We Are Sorry, looks like its an invalid URL";
					$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
					$retcode = 404;
				}
			}
			else
			{
				$output = $output . " <div class=\"atp\" >Server is Temporarily not Available. Please Try Again";
				$output = $output . "<br> Go Back To <a href=\"https://www.popabook.com\">PopAbooK.com</a></div>";
				$retcode = 404;
			}
		}
		return $output;
	}
	
	$metadata =  "<meta name=\"keywords\" content=\"$search_token\">" . "<meta name=\"Description\" content=\"Buy and Sell Books on $search_token\">";
	$title = "<title>$search_token Books PopAbooK.com India Online Book Store</title>";

	showbooksonpage($link, $output, $reqpage, $numpages, $books, $bookindex,$search_token,$seo); 
	return $output;
}

function showbooksonpage($link,&$output, $reqpage, $numpages, $books, $bookindex,$search_token,$seo)
{
	$output = $output . "<div style=\"width:100%\">";
	$output = $output . "<span class=\"atp\"> <br>&nbsp;&nbsp;Showing Page $reqpage of $numpages Pages for </span><span class=\"blueatp\"> $search_token<br></span>";

     	

	for ($ti = 0; $ti < $bookindex; $ti = $ti + 1)
	{
		$db_query = "select title, author1, isbn13, listprice, ourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount from book_inventory where bookid = $books[$ti]";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
				$randomnumber = mt_rand();
				$db_query1="select count(*) from usedbooks where isbn13 like '$db_row[2]' and status=2";
				$db_result1= mysqli_query($link,$db_query1);
				$booklink = makebooklink($db_row[0], $db_row[2]);
				if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
					$image = $db_row[2] . ".jpg";
				else
					$image = "ina.jpg";
				$output = $output . "<br><table width=\"100%\"><tr><td>&nbsp;</td><td colspan=\"3\" class=\"atp\" align=\"left\">";
				if ($seo == 1)
					$output = $output . "<a href=\"https://www.popabook.com/$booklink\"><span class=\"logoatp\">$db_row[0]</span></a>&nbsp;$db_row[5]";
				else
					$output = $output . "<span class=\"logoatp\">$db_row[0]</span>&nbsp;$db_row[5]";
 				$output = $output . "</td></tr><tr><td>&nbsp;</td><td align=\"left\" valign=\"top\"><img alt=\"$db_row[0]\" src=\"https://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></td><td valign=\"top\"><span class=\"atp\"> ";
				$output = $output . " by <h4><i>";
				if ($db_row[1] != "")
				{
					$output = $output . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . " and $db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . " and $db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . " and $db_row[8]";
				}
		                $output = $output . "</i></h4></span>";
			
				$output = $output . "<br><span class=\"satp\">List Price Rs. <span class=\"atp\"> <s>$db_row[3]</s> </span><br> Our Price Rs. <span class=\"logoatp\">$db_row[4]";
			        if ($db_row[4] > 100)
					$output = $output . " + FREE Shipping</span>";
				else
					$output = $output . "</span> (Please Add Rs. 30 if Total Bill does not exceed 100)";
				$save = $db_row[3] - $db_row[4];
				if ($save != 0)
				{
					$savepercent = intval (($save * 100)/$db_row[3]);
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
					$booklink = makebooklink($db_row[0], $db_row[2]);
					$output = $output . "<div><a href=\"#\"> <img src=\"https://www.popabook.com/buttonatc.jpg\" onclick=\"addtocart('$image', '$db_row[3]', '$db_row[4]', '$db_row[2]', '$rawtitle','$db_row[11]')\"></a> <br><br> <a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a> </div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = makebooklink($db_row[0], $db_row[2]);
					$output = $output . "<div><a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;";
				if ($db_row[10] != 0)
					$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a></td>";
				else
					$output = $output . "&nbsp;</td>";
				$output = $output . "<td width=\"33%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td>";
				$output = $output . "<td width=\"34%\" align=\"right\">";
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
				$output = $output . "</td></tr></table></div><div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"></div>";
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
			$output = $output . "<a href=\"#\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=$encoded&reqpage=$numpages')\">LAST</div></a>";
			$output = $output . "<a href=\"#\"><div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=$encoded&reqpage=$npage')\">NEXT</div></a>";
		}
	}
	if ($numpages != 1)
	$output = $output . "<div class=\"pgpreviousnextactive\"> <a href=\"#\">$reqpage</a></div>";
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
			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=$encoded&reqpage=$ppage')\"><a href=\"#\">PREVIOUS</a></div>";
			$output = $output . "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=$encoded&reqpage=1')\"><a href=\"#\">FIRST</a></div>";
		}
	}
	$output = $output . "</div></div></div>"; 
}

function searchbooks($link, $search_string, $escape)
{
	/*$output = $output . "$search_string \n $escape "; */
	$books = array();
	$ranks = array();
	$tbr = array(array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array());
	$tok = strtok($search_string, $escape);
    $numtok = 0;
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
	$escape = ", ;:.\/\"'\n\r-()?&\t";
	$tooks = array();
	$in = 0;
	$tok = strtok($title,$escape);
	while ($tok != null)
	{
		if(strlen($tok) == 1 || (is_numeric($tok) == TRUE && strlen($tok) < 4))
		{
			;
			$tok = strtok($escape);
		}
		else
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
function similarbooks($link, $bookid, $seo)
{
	$output = "";
	$db_query = "select s1,s2,s3,s4,s5,s6 from similarbooks where bookid=$bookid";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if ($db_row != null)
		{
			$output = $output . "<div style=\"padding-left:5px;\">";
			for ($i = 0; $i < 6; $i++)
			{
			$db_query1 = "select isbn13, title from book_inventory where bookid=$db_row[$i]";
			$db_result1 = mysqli_query($link, $db_query1);
			if ($db_result1 == TRUE)
			{
				$db_row1 = mysqli_fetch_row($db_result1);
				if ($db_row1 != null)
				{
					if (file_exists("/var/apache2/2.2/htdocs/" . $db_row1[0] . ".jpg"))
						$image = $db_row1[0] . ".jpg";
					else
						$image = "ina.jpg";
					$booklink = makebooklink($db_row1[1], $db_row1[0]);
					if ($seo == 1)
						$output = $output . "<table><tr><td><img width=\"70px\" height=\"100px\" alt=\"$db_row1[1]\" src=\"https://www.popabook.com/optimage/$image\"></td><td><h3><a href=\"$booklink\">$db_row1[1]</a></h3></td></tr></table>" ;
					else
						$output = $output . "<table><tr><td><img width=\"70px\" height=\"100px\" alt=\"$db_row1[1]\" src=\"https://www.popabook.com/optimage/$image\"></td><td><h3><a href=\"#\" onclick=\"getbook('search','$booklink')\">$db_row1[1]</a></h3></td></tr></table>" ;


				}

			}
			}
			$output = $output . "</div>";
		}
	}
	return $output;

    
}
function showsimilarbooksonpage($link,$bookid,$seo)
{
	
        $db_query = "select s1,s2,s3,s4,s5,s6 from similarbooks where bookid=$bookid";
	$db_result10 = mysqli_query($link, $db_query);
        $output = "";
	if ($db_result10 == TRUE)
	{
		$db_row10 = mysqli_fetch_row($db_result10);
		if ($db_row10 != null)
		{	
	for ($ti = 0; $ti < 6; $ti = $ti + 1)
	{
		$db_query = "select title, author1, isbn13, listprice, ourprice,edition,author2,author3,author4,publisher,boolblurb, shiptime, usedbookcount from book_inventory where bookid = $db_row10[$ti]";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			if ($db_row != null)
			{
                                $randomnumber = mt_rand();
				if ($ti == 0)
				{
					$output = "";
	$output = $output . "<div style=\"width:100%;\">";
        if ($seo == 0)
	$output = $output . "<div class=\"logoatp\" style=\"text-align:center;color:#000000;\">Similar Books</div>";
				}
				else
				{
					$output = $output . "<div class=\"similarborder\"><br></div><div class=\"pabborder\"></div>";
				}
				$db_query1="select count(*) from usedbooks where isbn13 like '$db_row[2]' and status=2";
				$db_result1= mysqli_query($link,$db_query1);
				$booklink = makebooklink($db_row[0], $db_row[2]);
				if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $db_row[2]. ".jpg"))
					$image = $db_row[2] . ".jpg";
				else
					$image = "ina.jpg";
				$output = $output . "<br><table width=\"100%\"><tr><td>&nbsp;</td><td colspan=\"3\" class=\"atp\" align=\"left\">";
					$output = $output . "<h2>$db_row[0]</h2>&nbsp;$db_row[5]";
 				$output = $output . "</td></tr><tr><td>&nbsp;</td><td align=\"left\" valign=\"top\"><img alt=\"$db_row[0]\" src=\"https://www.popabook.com/optimage/$image\" width=\"70px\" height=\"100px\"/></td><td valign=\"top\"><span class=\"atp\"> ";
				$output = $output . " by <h4><i>";
				if ($db_row[1] != "")
				{
					$output = $output . $db_row[1];
				}
				if ($db_row[6] != "")
				{
					$output = $output . " and $db_row[6]";
				}
				if ($db_row[7] != "")
				{
					$output = $output . " and $db_row[7]";
				}
				if ($db_row[8] != "")
				{
					$output = $output . " and $db_row[8]";
				}
		                $output = $output . "</i></h4></span>";
			
				$output = $output . "<br><span class=\"satp\">List Price Rs. <span class=\"atp\"> <s>$db_row[3]</s> </span><br> Our Price Rs. <span class=\"logoatp\">$db_row[4]";
			        if ($db_row[4] > 100)
					$output = $output . " + FREE Shipping</span>";
				else
					$output = $output . "</span> (Please Add Rs. 30 if Total Bill does not exceed 100)";
				$save = $db_row[3] - $db_row[4];
				if ($save != 0)
				{
					$savepercent = intval (($save * 100)/$db_row[3]);
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
					$booklink = makebooklink($db_row[0], $db_row[2]);
					$output = $output . "<div><a href=\"#\" <img src=\"https://www.popabook.com/buttonatc.jpg\" onclick=\"addtocart('$image', '$db_row[3]', '$db_row[4]', '$db_row[2]', '$rawtitle','$db_row[11]')\"></a> <br><br> <a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a> </div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				else
				{
					$rawtitle = rawurlencode($db_row[0]);
					$booklink = makebooklink($db_row[0], $db_row[2]);
					$output = $output . "<div><a target=\"_blank\" href=\"http://www.addtoany.com/share_save?linkname=$rawtitle&amp;linkurl=https%3A%2F%2Fwww.popabook.com/$booklink\"><img src=\"https://www.popabook.com/share_save_106_16.gif\" width=\"106\" height=\"16\" border=\"0\" alt=\"Share/Bookmark\"/></a></div></td></tr><tr><td>&nbsp;</td><td colspan=\"2\"><span class=\"satp\"> $isbnpub Pay using Credit Card or Debit Card or Internet Banking or Cash Card</span></td><td align=\"right\"><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$db_row[3],$db_row[4],'$db_row[11]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></td></tr></table>";
				}
				$output = $output . "<div id=\"$randomnumber!$db_row[2]header\"><table width=\"100%\"> <tr><td width=\"33%\">&nbsp;";
				if ($db_row[10] != 0)
					$output = $output . "<a id=\"$randomnumber!$db_row[2]overview\" href=\"javascript:void(0)\" onclick=\"getblurb('$randomnumber!$db_row[2]')\" class=\"blueatp\">Read Overview</a></td>";
				else
					$output = $output . "&nbsp;</td>";
				$output = $output . "<td width=\"33%\" class=\"atp\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('$randomnumber!$db_row[2]')\">Similar Books</a></td>";
				$output = $output . "<td width=\"34%\" align=\"right\">";
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
				$output = $output . "</td></tr></table></div><div style=\"width:100%;\" class=\"atp\" id=\"$randomnumber!$db_row[2]blurbdata\"></div>";
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
?>
