<?php
require('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$db_error = "";

$reqtype = $_POST['reqtype'];

if($reqtype == "forgotpassword")
{
  if($link != null)
  {
       $email = $_POST['email'];
       $db_query = "select rprarsrsr from customers where email like '$email'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) > 0)
	   {
		   $db_row = mysqli_fetch_row($db_result);
		   $emailmsg = "<div> Your Password is " . $db_row[0] . ".</div><div> We advice you to change your password.</div><div>Thank You<br>PopAbook.com</div>";
	   	   sendemail($email, "PopAbooK.com Forgot Password", "support@popabook.com", 'pbd1PBD1', $emailmsg);
           }
           else
	   {
		   $db_error = "Could not find a customer with e-mail " . $email;
           }

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
}
if ($db_error !="")
{
	echo "<div class=\"atp\">" . $db_error . "</div>";
}
else
{
	echo "<div class=\"atp\"> Password has been sent to your e-mail address.</div>";
}

?>

