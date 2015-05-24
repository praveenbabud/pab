<?php
function generateinvoice($link, $invoicenumber, $pgpayid)
{

	$dt = date("jS F Y");
        $titles = "";
	$emailmsg = "<div style=\"border: solid 1px; margin: 10px 10px 10px 10px; padding: 10px 10px 10px 10px;\"> <div> <table width=\"100%\"> <tr><td width=\"75%\" align=\"left\"> <img src=\"http://www.popabook.com/popabook.gif\"/></td> <td align=\"left\"> <span class=\"atp\">PopAbooK.com <br> # 58/A <br> 24th Cross 17th Main <br> H.S.R. Layout Sector-3 <br> Bangalore-560102<br></span> </td></table><hr> <br><br> <center><u>INVOICE</u> </center><br><br><div>" . "<br> <span class=\"boldatp\"> Invoice Number </span>: <span class=\"atp\"> $invoicenumber </span><br><span class=\"boldatp\"> Order Date </span>: <span class=\"atp\"> $dt </span><br> ";
	if ($pgpayid != "")
	{
		$emailmsg  = $emailmsg . "<span class=\"boldatp\">PaymentId </span> : " . "<span class=\"atp\">" . $pgpayid . "</span><br><span class=\"boldatp\">Payment Status </span> : " . "<span class=\"atp\"> Payment Received</span><br>";
	}
    else
	{
        $emailmsg = $emailmsg . "<span class=\"boldatp\">Payment Status </span> : " . "<span class=\"atp\"> Payment Pending</span><br>";
	}
        $dbtotal = 0;

         $db_query = "select name, address, city, pincode, telephone, email, state, shiptime, shipcost, total from orders where invoicenumber like '$invoicenumber'";
	 $db_result = mysqli_query($link, $db_query); 
			  if ($db_result == TRUE) 
			  { 
				  $db_row = mysqli_fetch_row($db_result);
				  $name = $db_row[0];
				  $address = urldecode($db_row[1]);
				  $city = $db_row[2];
				  $pincode = $db_row[3];
				  $telephone = $db_row[4];
				  $email = $db_row[5];
				  $state = $db_row[6];
				  $shiptime = $db_row[7];
				  $shipcost = $db_row[8];
				  $dbtotal = $db_row[9];
			  }
	$emailmsg = $emailmsg . "<span class=\"boldatp\">Delivery Status</span> : " . "<span class=\"atp\">Your Order will be delivered in " . $shiptime . " business days</span><br><br><br></div>"; 
	$db_query = "select isbn13,title,ourprice, quantity from order_details where invoicenumber like '$invoicenumber'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
	     $norders = mysqli_num_rows($db_result);
		 if($norders > 0) 
		 {
			  $emailmsg = $emailmsg . "<div ><table cellspacing=\"10\" width=\"100%\"><tr><td width=\"60%\" class=\"boldatp\">Title<br></td><td class=\"boldatp\">Our Price<br></td><td class=\"boldatp\">Quantity<br></td><td class=\"boldatp\">Total<br></td></tr>";
			  $grandtotal = 0;
			  $db_row = mysqli_fetch_row($db_result);
			  while ($db_row != NULL)
			  {
				  $sellinfo = "";
				  $isbn = explode("#",$db_row[0]);
				  if (count($isbn) == 2)
				  {
					$isbn13 = $isbn[1];
					$sellinfo = "Used book sold by ";
					  $db_query1= "select name from customers where email in (select email from usedbooks where id=$isbn[0])";
					  $db_result1 = mysqli_query($link, $db_query1);
					  if ($db_result1 == TRUE)
					  {
						  $db_row1 = mysqli_fetch_row($db_result1);
						  if ($db_row1 != null)
						  {
							  $sellinfo = $sellinfo . $db_row1[0] . ". Procured, verified, packed and shipped by PopAbooK.com";
						  }
					  }
				  }
				  else
					  $isbn13 = $db_row[0];
						$title = $db_row[1];
                                  if ($titles != "")
                                      $titles = $titles . " and " . $title;
                                  else
                                      $titles = $title;
                                     
						$ourprice = $db_row[2];
						$quantity = $db_row[3];
						$total = $ourprice * $quantity;
						$grandtotal = $grandtotal + $total;
						$emailmsg = $emailmsg . "<tr> <td width=\"60%\" class=\"atp\"> $title <br> ISBN:$isbn13<br>$sellinfo<br> </td>" . "<td valign=\"top\" class=\"atp\">  $ourprice</td>" .  " <td valign=\"top\" class=\"atp\"> $quantity </td>" . "<td valign=\"top\" class=\"atp\"> $total </td></tr>" ;
						$db_row = mysqli_fetch_row($db_result);
			  }
			  $emailmsg = $emailmsg . "</table></div>";
			 
                      if ($shipcost > 0)
			      $emailmsg = $emailmsg . "<br><div class=\"atp\"> Shipping Cost is Rs. $shipcost </div>";
		      else
			      $emailmsg = $emailmsg . "<br><div class=\"atp\"> FREE Shipping </div>";

              $grandtotal = $grandtotal + $shipcost; 

	          $emailmsg = $emailmsg . "<br><div class=\"boldatp\"> Grand Total is Rs. $dbtotal</div>";

        	  $emailmsg = $emailmsg . "<br><div class=\"iatp\"> A copy of invoice has been sent to your e-mail address<br>If you do not receive invoice in couple of minutes, please check your spam folder</div>";

			  $emailmsg = $emailmsg . "<br><div class=\"boldatp\"> Shipping Address<br><br>" . "<span class=\"atp\">Name:$name <br>" . "Address: $address <br>"  . "City:$city <br>" . "State:$state <br>" . "Pincode:$pincode <br>" . "Telephone:$telephone<br>". "India <br>". "</span></div>";

			  $emailmsg = $emailmsg . "<br><div class=\"atp\"> Thank You For Choosing PopAbooK.com</div><br>";
		      $emailmsg = $emailmsg . "<br><div class=\"atp\"> For Shipping/Order related issues please email us @ <a href=\"mailto:support@popabook.com\">support@popabook.com</a></div></div>";
		}
	}
        $xid = rawurlencode("http://www.popabook.com");
        $titlesenc = rawurlencode($titles);
        $swf = "<div style=\"text-align:left;padding-left:20px;\"><br><br><h3>Share your purchase with friends</h3><br><br><table><td colspan=\"2\" class=\"boldatp\">1.On Twitter</td></tr><tr><td class=\"atp\">Just bought $titles!</td><td><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://platform.twitter.com/widgets/tweet_button.html?url=$xid&via=PopAbooK&text=Just%20bought%20$titlesenc&related=popabook%3ASocial%20Book%20Store&count=horizontal\" style=\"width:130px; height:21px;\"></iframe></td></tr><tr><td colspan=\"2\" class=\"boldatp\">2.On Facebook</td></tr><tr><td colspan=\"2\"><fb:comments xid=\"$xid\" url=\"http://www.PopAbooK.com\" title=\"just bought $titles\" width=\"675\" numposts=\"3\">  </fb:comments> </td></tr><tr><td colspan=\"2\" class=\"boldatp\">3.On Google Buzz</td></tr><tr><td class=\"atp\">Just bought $titles!</td><td><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://www.popabook.com/gbb.php?url=$xid&title=Just%20bought%20$titlesenc\" style=\"border:none;overflow:hidden;width:150px;height:21px;\"></iframe></td></tr></table></div>";
        $emailmsg = $swf . $emailmsg;
	return $emailmsg;
}
function generateemailinvoice($link, $invoicenumber, $pgpayid)
{

	$dt = date("jS F Y");
        $dbtotal = 0;
	$emailmsg = "<div style=\"border: solid 1px; margin: 10px 10px 10px 10px; padding: 10px 10px 10px 10px;\"> <div> <table width=\"100%\"> <tr><td width=\"50%\" align=\"left\"> <a href=\"http://www.popabook.com\"><img src=\"http://www.popabook.com/popabook.gif\"></a></td><td width=\"25%\"> &nbsp;</td> <td align=\"left\"> <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">PopAbooK.com <br> # 58/A <br> 24th Cross 17th Main <br> H.S.R. Layout Sector-3 <br> Bangalore-560102<br></span> </td></table><hr> <br><br> <center><u>INVOICE</u> </center><br><br><div>" . "<br> <span style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Invoice Number </span>: <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $invoicenumber </span><br><span style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Order Date </span>: <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $dt </span><br> ";
	if ($pgpayid != "")
	{
		$emailmsg  = $emailmsg . "<span style=\"font-family:verdana;font-weight:bolder;font-size:small;\">PaymentId </span> : " . "<span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">" . $pgpayid . "</span><br><span style=\"font-family:verdana;font-weight:bolder;font-size:small;\">Payment Status </span> : " . "<span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> Payment Received</span><br>";
	}
    else
	{
        $emailmsg = $emailmsg . "<span style=\"font-family:verdana;font-weight:bolder;font-size:small;\">Payment Status </span> : " . "<span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> Payment Pending</span><br>";
	}
         $db_query = "select name, address, city, pincode, telephone, email, state, shiptime, shipcost,total from orders where invoicenumber like '$invoicenumber'";
			  $db_result = mysqli_query($link, $db_query); 
			  if ($db_result == TRUE) 
			  { 
				  $db_row = mysqli_fetch_row($db_result);
				  $name = $db_row[0];
				  $address = urldecode($db_row[1]);
				  $city = $db_row[2];
				  $pincode = $db_row[3];
				  $telephone = $db_row[4];
				  $email = $db_row[5];
				  $state = $db_row[6];
				  $shiptime = $db_row[7];
				  $shipcost = $db_row[8];
				  $dbtotal = $db_row[9];
			  }
         	
	$emailmsg = $emailmsg . "<span style=\"font-family:verdana;font-weight:bolder;font-size:small;\">Delivery Status</span> : " . "<span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Your Order will be delivered in ". $shiptime . " business days</span><br><br><br></div>"; 
	$db_query = "select isbn13,title,ourprice, quantity from order_details where invoicenumber like '$invoicenumber'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
	     $norders = mysqli_num_rows($db_result);
		 if($norders > 0) 
		 {
			$emailmsg = $emailmsg . "<div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"><table cellspacing=\"10\" width=\"100%\"><tr><td width=\"60%\" style=\"font-family:verdana;font-weight:bold;font-size:small;\">Title<br></td><td style=\"font-family:verdana;font-weight:bold;font-size:small;\">Our Price<br></td><td style=\"font-family:verdana;font-weight:bold;font-size:small;\">Quantity<br></td><td style=\"font-family:verdana;font-weight:bold;font-size:small;\">Total<br></td></tr>";
			  $grandtotal = 0;
			  $db_row = mysqli_fetch_row($db_result);
			  while ($db_row != NULL)
			  {
				  $sellinfo = "";
				  $isbn = explode("#",$db_row[0]);
				  if (count($isbn) == 2)
				  {
					$isbn13 = $isbn[1];
					$sellinfo = "Used book sold and shipped by ";
					  $db_query1= "select name from customers where email in (select email from usedbooks where id=$isbn[0])";
					  $db_result1 = mysqli_query($link, $db_query1);
					  if ($db_result1 == TRUE)
					  {
						  $db_row1 = mysqli_fetch_row($db_result1);
						  if ($db_row1 != null)
						  {
							  $sellinfo = $sellinfo . $db_row1[0] . ". Procured, verified, packed and shipped by PopAbooK.com";
						  }
					  }
				  }
				  else
					  $isbn13 = $db_row[0];
						$title = $db_row[1];
						$ourprice = $db_row[2];
						$quantity = $db_row[3];
						$total = $ourprice * $quantity;
						$grandtotal = $grandtotal + $total;
						$emailmsg = $emailmsg . "<tr> <td width=\"60%\" style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $title <br> ISBN:$isbn13<br>$sellinfo<br> </td>" . "<td valign=\"top\" style=\"font-family:verdana;font-weight:lighter;font-size:small;\">  $ourprice</td>" .  " <td valign=\"top\" style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $quantity </td>" . "<td valign=\"top\" style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $total </td></tr>" ;
						$db_row = mysqli_fetch_row($db_result);
			  }
			  $emailmsg = $emailmsg . "</table></div>";
			 
                      if ($shipcost > 0)
			      $emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> Shipping Cost is Rs. $shipcost </div>";
		      else
		      		$emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> FREE Shipping </div>";


              $grandtotal = $grandtotal + $shipcost; 

	          $emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Grand Total is Rs. $dbtotal </div>";

			  $emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Shipping Address<br><br>" . "<span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Name:$name <br>" . "Address: $address <br>"  . "City:$city <br>" . "State:$state <br>" . "Pincode:$pincode <br>" . "Telephone:$telephone<br>". "India <br>". "</span></div>";

			  $emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> Thank You For Choosing PopAbooK.com</div><br>";
		      $emailmsg = $emailmsg . "<br><div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> For Shipping/Order related issues please email us @ <a href=\"mailto:support@popabook.com\">support@popabook.com</a></div></div>";
		}
	}
	return $emailmsg;
}
function generateemailtoseller($link, $usedbookid,$isbn13,$invoicenumber)
{
	$dt = date("jS F Y");
			$bname = "";
			$baddress = "";
			$bcity = "";
			$bpincode = ""; 
			$bstate = "";
			$btelephone = "";
			$title = "";
			$price = 0;
	$db_query = "select name,email from usedbooks where id=$usedbookid";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
                $db_row = mysqli_fetch_row($db_result);
                if ($db_row != null)
                {
		$semail = $db_row[1];
		$sname = $db_row[0];
                }
	}
	else
		return;
	$db_query = "select name, address,city,pincode, state, telephone from orders where invoicenumber like '$invoicenumber'";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if($db_row != null)
		{
			$bname = $db_row[0];
			$baddress = $db_row[1];
			$bcity = $db_row[2];
			$bpincode = $db_row[3];
			$bstate = $db_row[4];
			$btelephone = $db_row[5];
		}
	}
	else
		return;
	$db_query = "select title,ourprice from order_details where invoicenumber like '$invoicenumber' and isbn13 like '$usedbookid#$isbn13'";
	$db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if($db_row != null)
		{
			$title = $db_row[0];
			$price = $db_row[1];
		}
	}
	else
		return;
	$emailmsg = "<div style=\"border: solid 1px; margin: 10px 10px 10px 10px; padding: 10px 10px 10px 10px;\"> <div> <table width=\"100%\"> <tr><td width=\"50%\" align=\"left\"> <a href=\"http://www.popabook.com\"><img alt=\"PopAbooK.com\" title=\"PopAbooK.com\"src=\"http://www.popabook.com/popabook.gif\"></a></td><td width=\"25%\"> &nbsp;</td> <td align=\"left\"> <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">PopAbooK.com <br> # 58/A <br> 24th Cross 17th Main <br> H.S.R. Layout Sector-3 <br> Bangalore-560102<br></span> </td></table><hr> <br><br><center><u>INVOICE</u> </center><br><br><div>" . "<br> <span style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Invoice Number </span>: <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $invoicenumber </span><br><span style=\"font-family:verdana;font-weight:bolder;font-size:small;\"> Order Date </span>: <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> $dt </span><br> ";
	$emailmsg = $emailmsg . " <br><span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> We received an order for your book</span>";
	$emailmsg = $emailmsg . "<br> <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Title:$title <br>ISBN:$isbn13<br>Price:$price</span>";
	$emailmsg = $emailmsg . " <br><br><span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Please keep the book ready, we will come collect and ship the book.</span>";


	$emailmsg = $emailmsg . "<br><span style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> PopAbooK.com charges following commission rates <br> Rs. 30 or 20% whatever is higher.</span>";
	$comm = intval(0.2 * ($price));
	if ($comm < 30)
		$comm = 30;

	$emailmsg = $emailmsg . " <br><span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Commission for Rs. $price is Rs. $comm </span>";
	$total = $price - $comm;
	$emailmsg = $emailmsg . " <br><span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">You will receive a check for Rs. $total </span>";


	$emailmsg = $emailmsg . "<br> <span style=\"font-family:verdana;font-weight:lighter;font-size:small;\">Thank you for choosing our service.</span>";
		      $emailmsg = $emailmsg . "<br><br><div style=\"font-family:verdana;font-weight:lighter;font-size:small;\"> For Shipping/Order related issues please email us @ <a href=\"mailto:support@popabook.com\">support@popabook.com</a></div></div>";
	sendemail($semail, "PopAbooK.com Ship Order INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
				sendemail('praveen@popabook.com', "PopAbooK.com Ship Order INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
}
?>
