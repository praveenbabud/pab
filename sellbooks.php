<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$db_error = "";
$reqtype = $_POST['reqtype'];
$index = 0;
$usedbookid = array();
$isbn13 = array();

if (isset($_SESSION['email']) == TRUE)
{
	if($reqtype == "sellbooks")
	{
		$email = $_SESSION['email'];
		echo "<br><div class=\"logoatp\" style=\"padding-left:5px;\"> Email ID : $email</div><br><div class=\"pabtab\"> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccount')\">Contact Info</div> <div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('myaccountorders')\"> Your Orders </div> <div class=\"pabtabheadingsel\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('sellbooks')\">Sell BooKs</div>"; 
		echo "<div class=\"pabtabheading\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#ffeeff';this.style.color='#0000c8';\" style=\"border-left:solid 1px #cccccc;\" onclick=\"showuseraccount('salesaccount')\">Sales Account</div>";
		echo "</div>";
    		if (isset($_POST['isbn13']) == TRUE)
		{

			$saleprice = intval($_POST['saleprice']);
			$name = $_POST['name'];
                        $address =  rawurlencode($_POST['address']);
                        $city = $_POST['city'];
                        $pincode= $_POST['pincode'];
                        $telephone= $_POST['telephone'];
                        $state= $_POST['state'];
                        $description= rawurlencode($_POST['dbc']);
                        $saleprice = $saleprice;
                        $status = 1;
                        $isbn13 = "";
                        {
                                $isbn13 = $_POST['isbn13'];
				$db_query = "insert into usedbooks (isbn13,name,address,city,pincode,telephone,state,description,saleprice,status,email) values ('$isbn13', '$name', '$address', '$city', '$pincode', '$telephone', '$state', '$description', $saleprice, $status, '$email')";
                        }
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
                                $estimate = 0.20 * $_POST['saleprice'];
                                $estimate = intval($estimate);
                                if ($estimate < 30)
                                     $estimate = 30;
                                $estimate = $_POST['saleprice'] - $estimate;
				echo "<div class=\"atp\" style=\"clear:left;padding-left:100px;\"> <br><br>The BooK (ISBN : $isbn13) will be available for sale after verification. <br> We will verify and put it on sale within 24 hours. <br>Please check the status of the sale under Seller Account. </div>";
				
				$emailtoseller = "Hello " . $_POST['name'] . ", <br><br> We have received a request to put your book for sale.";
                                $emailtoseller = $emailtoseller . "<br>ISBN : $isbn13 <br>Description: " . $_POST['dbc'] . "<br>Sale Price : " . $_POST['saleprice'] . "<br>We will verify and put it on sale in 24 hours.<br><br>Estimated payment by PopAbooK.com to you after successful sale of book would be Rs.$estimate<br><br>Please contact us at support@popabook.com if above details are incorrect.<br><br>Support <br>PopAbooK.com";
				sendemail($email, 'PopAbooK.com:Received your request to Sell Book', 'support@popabook.com', 'pbd1PBD1', $emailtoseller);
				sendemail('praveen@popabook.com', 'PopAbooK.com:Received your request to Sell Book', 'support@popabook.com', 'pbd1PBD1', $emailtoseller);
			}
			else
			{
				echo "<div class=\"atp\"> Sorry, could not put the book on sale due to Server Error, please try again. </div>";
			}


		}
                if (1 == 1)
                {
				       echo "<div class=\"boldatp\" style=\"clear:left;padding-left:100px;\"><br><br><br><br>Sale of Books is temporarily disabled due to technical issues. We will be back soon. We are sorry for the inconvenience</div>";
                }
                else
		{
			
		/*	$db_query = "select id, isbn13 from usedbooks where email like '$email' and status=0";
			$db_result = mysqli_query($link, $db_query);
			if ($db_result == TRUE)
			{
				$num_rows = mysqli_num_rows($db_result);
				if ($num_rows > 0)
				{ */
				       echo "<div class=\"atp\" style=\"clear:left;padding-left:100px;\"> <br><form> <table ><tr><td align=\"center\" colspan=\"2\"> <span class=\"logoatp\" style=\"color:#0000ff;\">BooK Details </span><br><br></td></tr>";
				      /* {
					       $usedbookid[$index] = $db_row[0];
					       $isbn13[$index] = $db_row[1];
					       $index = $index + 1;
					       $db_row = mysqli_fetch_row($db_result);
				       }
				       $tmp = 0;
				       echo "<select><option value=\"\">Select BooK</option>";
				       while ($tmp < $index)
				       {
					       $db_result = mysqli_query($link, "select title from book_inventory where isbn13 like '$isbn13[$tmp]'");
					       if ($db_result == TRUE)
					       {
						       $db_row = mysqli_fetch_row($db_result);
						       if ($db_row != null)
						       {
							       echo "<option value=\"$usedbookid[$tmp]#$isbn13[$tmp]\">$db_row[0]</option>";
						       }
					       }
					       $tmp = $tmp + 1;
				       }
				       echo "</select>"; */
				       $name = $_SESSION['pabname'];
					$address = $_SESSION['pabaddress'];
					$city = $_SESSION['pabcity'];
					$pincode = $_SESSION['pabpincode'];
					$state = $_SESSION['pabstate'];
					$telephone = $_SESSION['pabtelephone'];
					echo "<tr><td>Enter ISBN of Book :</td><td><input name=\"isbn13\" type=\"text\"></td></tr><tr><td>Describe Book Condition : </td> <td><textarea name=\"dbc\"></textarea></td></tr><tr><td>Sale Price Rs.</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"saleprice\"></td></tr></table><br><div class=\"atp\">Please refer to following links for accurately describing the book's condition<br>and determining the sale price.<br><br>1.&nbsp;<a href=\"http://blog.popabook.com/2010/06/anatomy-of-book.html\" target=\"_blank\">Standard terms for the parts of a book</a><br>2.&nbsp;<a href=\"http://blog.popabook.com/2010/06/describe-book-condition.html\" target=\"_blank\">Describe book condition</a><br>3.&nbsp;<a href=\"http://blog.popabook.com/2010/05/value-of-used-book.html\" target=\"_blank\">Valuation of used books</a><br></div><table><tr><td align=\"center\" colspan=\"2\">  <span class=\"logoatp\" style=\"color:#0000ff;\"><br>Contact Information<br><br></span></td></tr><tr><td>Name*</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"name\" value=\"$name\"></td></tr><tr><td>Address* </td><td><textarea name=\"address\">$address</textarea></td></tr><tr><td>City/District*</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"city\" value=\"Bangalore\" readonly></td></tr><tr><td>Pincode*</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"pincode\" value=\"$pincode\"> </td></tr><tr><td>Telephone*</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"telephone\" value=\"$telephone\"> </td></tr><tr><td>State*</td><td><input type=\"text\" onKeyPress=\"return checkEnter(event,'sellbooks',this.form)\" name=\"state\" value=\"Karnataka\" readonly> </td></tr><tr><td>Country*</td><td><input type=\"text\" name=\"country\" value=\"India\" readonly> </td></tr><tr><td colspan=\"2\"> &nbsp;</tr><tr><td align=\"center\" colspan=\"2\"><input type=\"button\" name=\"Confirm Sale\" value=\"Confirm Sale\" onclick=\"sellbook(this.form)\"></td></tr></table><div class=\"satp\">By clicking on Confirm Sale button above, you agree to PopAbooK.com Terms and Conditions and agree to have read and understood the Privacy Policy.<br><br>Please note that this feature is available only in Bangalore and will expanded to other parts of the country soon.</div></form></div>";
			/*	}
				else
				{
				       echo "<div class=\"atp\" style=\"clear:left;padding-left:100px;\"> <br>Could not find books, in your account, that can be put on sale. Your books will be available for sale with in 2 days after you receive the book.</div> ";
				} 
			}
			else
			{
				$db_error = "Server is Temporarily Unavailable, please try again.";
			} */
			
		}
	}
}
?>
