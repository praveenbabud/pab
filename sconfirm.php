<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$db_error = "";

  if($link != null)
  {
       $email = $_GET['email'];
       $passkey = $_GET['passkey'];
       $db_query = "select passkey from signinauth where email like '$email'";       
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
	       if (mysqli_num_rows($db_result) > 0)
	       {
		       $db_row = mysqli_fetch_row($db_result);
		       if ($passkey == $db_row[0])
		       {
			       $db_query = "update customers set emailauth = 1 where email like '$email'";
			       mysqli_query($link, $db_query);
			       echo "<div> Hello $email <br><br> Your Account Has Been Activated. <br> <br> <a href=\"https://www.popabook.com\"> Click To PopAbooK</a> </div>";
	       			$emailmsg = "<div> Hello $email <br><br> Your Account Has Been Activated. <br> Thanks for signing up with www.PopAbooK.com.
		          <br> <br>Thank You <br> Customer Care <br> www.PopAbooK.com <br> </div>";
				sendemail($email, "PopAbooK.com Account Activated", "support@popabook.com", 'pbd1PBD1', $emailmsg);
		       }
		       else
		       {
			       echo "<div> Hello <br><br> Could not activate your account.<br> E-Mail and Key are not matching. <br> Contact us at support@popabook.com for help<br> <br> <a href=\"https://www.popabook.com\"> Click To PopAbooK</a> </div>";
		       }

	       }
	       else
	       {
		       echo "<div> Hello <br><br> Could not find your account.<br> Please try to sign up again. <br> Contact us at support@popabook.com for help <br> <br> <a href=\"https://www.popabook.com\"> Click To PopAbooK</a> </div>";
	       }

       }
       else
       {
           echo "Authentication Server is Temporarily Unavailable. Please Try Later";
       }
  }
  else
  {
      echo "Authentication Server is Temporarily Unavailable. Please Try Later";
  }
?>

