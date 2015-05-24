<?php

session_start();
$session_id = session_id();
require('dbconnect.php');
require_once('pab_util.php');
$link = wrap_mysqli_connect();
$db_error = "";
$address = "";

$reqtype = $_POST['reqtype'];

if($reqtype == "verifypasswordgetdata")
{
  if($link != null)
  {
       $email = $_POST['email'];
       $password = $_POST['password'];
       $db_query = "select password,hno,street,village,city,state,telephone,pincode,name,emailauth,address from customers where email like '$email'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) == 1)
           {
               $db_row = mysqli_fetch_row($db_result);
	       $md5password = md5($password);
	       if ($db_row[9] == "0")
	       {
		       $auth_success = FALSE;
		       $db_error = "Please Activate Account Before Login";
	       }
	       else if($md5password == $db_row[0])
               {
			   		$auth_success = TRUE;
			   		$hno = $db_row[1];
		   	   		$street = $db_row[2];
		   	   		$village = $db_row[3];
		           	$city = $db_row[4];
		           	$state = $db_row[5];
			   		$telephone = $db_row[6];
			   		$pincode = $db_row[7];
					$name = $db_row[8];
					$address = $db_row[10];
		           	$_SESSION['email'] = $email;
		           	$db_query = "insert into usersession (session, email,logintime) values ('$session_id', '$email',now())";
		           	$db_result = mysqli_query($link, $db_query);
               }
               else
               {
                   $auth_success = FALSE;
               }
	   }
	   else
			   $auth_success = FALSE;

       }
       else
       {
           $db_error = "Authentication Server is Temporarily Unavailable. Please Try Later";
       }
  }
  else
  {
      $db_error = "Authentication Server is Temporarily Unavailable. Please Try Later";
  }
  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\" ?";
  echo ">";
  echo "<data>";
  echo "<request type=\"verifypasswordgetdata\">";
  echo "<email>";
  echo $email;
  echo "</email>";
  echo "</request>";
  
  if ($db_error != "")
  {
    echo "<response its=\"failure\">";
    echo "<message>";
    echo $db_error;
    echo "</message>";
    echo "</response>";
  }
  else if ($auth_success == FALSE)
  {
    echo "<response its=\"failure\">";
    echo "<message>";
    	echo "Email and Password do not match. Please Try Again.";
    echo "</message>";
    echo "</response>";
  }
  else if ($auth_success == TRUE)
  {
	  echo "<response its=\"success\">";
	  echo "<name>";
	  echo $name;
	  $_SESSION['pabname'] = $name;
	  echo "</name>";
    echo "<hno>";
	  echo $hno;
    echo "</hno>";
    echo "<street>";
    echo $street;
    echo "</street>";
    echo "<village>";
    echo $village;
    echo "</village>";
    echo "<city>";
    echo $city;
	  $_SESSION['pabcity'] = $city;
    echo "</city>";
    echo "<state>";
    echo $state;
	  $_SESSION['pabstate'] = $state;
	echo "</state>";
	echo "<telephone>";
	echo $telephone;
	  $_SESSION['pabtelephone'] = $telephone;
	echo "</telephone>";
	echo "<pincode>";
	echo $pincode;
	  $_SESSION['pabpincode'] = $pincode;
	echo "</pincode>";
	echo "<address>";
	echo $address;
	  $_SESSION['pabaddress'] = $address;
	echo "</address>";
	if ($link != null)
 	{
		$db_query = "select isbn13 from wishlists where customerid in (select customer_id from customers where email like '$email')"; 	 
		$db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		{
			$db_row = mysqli_fetch_row($db_result);
			while ($db_row != null)
			{
				$db_query1 = "select title, nlistprice, nourprice, shiptime,currency from book_inventory where isbn13 like '$db_row[0]'";
				$db_result1 = mysqli_query($link, $db_query1);
				if ($db_result1 == TRUE)
				{
					$db_row1 = mysqli_fetch_row($db_result1);
					if ($db_row1 != null)
					{
                                                $isbn13 = $db_row[0];
						if(file_exists("/var/apache2/2.2/htdocs/optimage/" . $isbn13 . ".jpg"))
							$image = "$isbn13.jpg";
						else
							$image = "ina.jpg";
						$title = rawurlencode($db_row1[0]);
                                                $listprice = converttoinr($db_row1[4],$db_row1[1]); 
                                                $ourprice = converttoinr($db_row1[4],$db_row1[2]); 
						echo "<wishitem><isbn13>$db_row[0]</isbn13><title>$title</title><image>$image</image><listprice>$listprice</listprice><ourprice>$ourprice</ourprice><shiptime>$db_row1[3]</shiptime></wishitem>";
					}
				}
				$db_row = mysqli_fetch_row($db_result);
			}
		
		}
	}
	echo "</response>";
  }
  echo "</data>";

}

?>

