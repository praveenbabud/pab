<?php
function usedbooksfromdb($link,$isbn13)
{
$output = "";
	$db_query = "select description, id, saleprice,email,name from usedbooks where status=2 and isbn13 like '$isbn13'";
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$num_rows = mysqli_num_rows($db_result);
			if ($num_rows > 0)
			{
				
				$db_row = mysqli_fetch_row($db_result);
				$db_query2 = "select title,nlistprice,currency from book_inventory where isbn13 like '$isbn13'";
				$db_result2 = mysqli_query($link, $db_query2);
                                $db_row2 = null;
                                if ($db_result2 == TRUE)
					$db_row2 = mysqli_fetch_row($db_result2);
				$output = $output . "<div style=\"padding-left:10px\"><h3>Used Books: $db_row2[0]</h3><br><br><table width=\"100%\"><tr><td>&nbsp;</td><td width=\"25%\" class=\"boldatp\">Seller/Rating<td width=\"25%\" class=\"boldatp\" >Book Condition</td><td width=\"25%\" class=\"boldatp\">Sale Price</td> <td width=\"25%\"> &nbsp;</td></tr>";

				while($db_row != null)
				{
					$db_query1 = "select name,rating,numsales from customers where email like '$db_row[3]'";
					$db_result1 = mysqli_query($link, $db_query1);
					if ($db_result1 == TRUE)
					{
						$db_row1 = mysqli_fetch_row($db_result1);
						if ($db_row1 != null)
						{
							if ($db_result2 == TRUE)
							{
								if ($db_row2 != null)
								{
									$rawtitle = rawurlencode($db_row2[0]);
									if (file_exists("/var/apache2/2.2/htdocs/optimage/". $isbn13 . ".jpg") == TRUE)
										$image = $isbn13 . ".jpg";
									else
										$image = "ina.jpg";
									if ($db_row1[2] == 0)
										$rating = "New Seller";
									else
									{
										$one = $db_row1[1] / $db_row1[2];
										$rating = "$one/10 ($db_row1[2] sales)";
									}
                                                                        $decdesc = rawurldecode($db_row[0]);
                                                                        $listprice = converttoinr($db_row2[2], $db_row2[1]);
									$output = $output . "<tr><td>&nbsp;</td><td> $db_row[4] <br> $rating</td><td>$decdesc</td><td>Rs. $db_row[2] +<br>Rs. 30 (Shipping)</td> <td align=\"right\" valign=\"top\"><a href=\"#\"> <img src=\"http://www.popabook.com/buttonatc.jpg\" alt=\"Add To Cart\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"addtocart('$image', '$listprice','$db_row[2]', '$db_row[1]#$isbn13', '$rawtitle','5-7','$db_row[4]')\"></a></td></tr><tr><td colspan=\"5\"><br><br></td></tr>";
								}
							}

						}
					}
					$db_row = mysqli_fetch_row($db_result);
				}
				$output = $output . "</table>";
			}
		}
                $output = $output . "<div class=\"boldatp\" style=\"padding-left:1px;\">Used books will be delivered in 5-7 business days</div></div>";
return $output;

}
function converttoinr($curr, $price)
{
    if ($curr == 'R')
    {
        return round($price);
    }
    else if ($curr == 'D')
    {
        $tmp = 49.10 * $price;
        return round($tmp);
    } 
    else if ($curr == 'P')
    {
        $tmp = 77.10 * $price;
        return round($tmp);
    } 
    else if ($curr == 'E')
    {
        $tmp = 63.50 * $price;
        return round($tmp);
    }
    else
         return round($price); 
}
