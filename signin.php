<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
require('sendemail.php');
$link = wrap_mysqli_connect();
$db_error = "";

$reqtype = $_POST['reqtype'];

if($reqtype == "signin")
{
  if($link != null)
  {
       $email = $_POST['email'];
	   $password = $_POST['password'];
	   $md5password = md5($password);
       $db_query = "insert into customers (email, password,rprarsrsr) values ('$email', '$md5password','$password')";
       
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
		$key = rand();
       		$db_query = "insert into signinauth (email, passkey) values ('$email','$key')";
		$db_result = mysqli_query($link, $db_query);
		$emailmsg = "<div> Hello $email <br><br> Thanks for signing up with PopAbooK.com. <br> To activate the account please click on the 'Activate' link below.<br><br> <a href=\"https://www.popabook.com/sconfirm.php?email=$email&passkey=$key\">Confirm and Activate</a></div>" . "<div> <br><br>If you are not expecting this email, just ignore it.<br><br> Thank You <br> Customer Care <br> www.PopAbooK.com <br> </div>";
		sendemail($email, "PopAbooK.com Confirm and Activate Account", "support@popabook.com", 'pbd1PBD1', $emailmsg);
		$db_error = "Your Account is created. An E-Mail has been sent to you with steps to Activate account. Activate your account and Login."; 

       }
       else
       {
	   $db_query = "select emailauth from customers where email like '$email'";
	   $db_result = mysqli_query($link, $db_query);
	   if ($db_result == TRUE)
	   {
		   if (mysqli_num_rows($db_result) > 0)
		   {
			   $db_row = mysqli_fetch_row($db_result);
			   if ($db_row[0] == "0")
			   {
				   $db_query = "delete from signinauth where email like '$email'";
				   mysqli_query($link, $db_query);
				    $db_query = "update customers set password = '$md5password' , rprarsrsr = '$password' where email like '$email'";
                                    $db_result = mysqli_query($link, $db_query);
				   	$key = rand();
       					$db_query = "insert into signinauth (email, passkey) values ('$email','$key')";
					$db_result = mysqli_query($link, $db_query);
					$emailmsg = "<div> Hello $email <br><br> Thanks for signing up with PopAbooK.com. <br> To activate the account please click on the 'Activate' link below.<br><br> <a href=\"https://www.popabook.com/sconfirm.php?email=$email&passkey=$key\">Confirm and Activate</a></div>" . "<div> <br><br>If you are not expecting this email, just ignore it.<br><br> Thank You <br> Customer Care <br> www.PopAbooK.com <br> </div>";
					sendemail($email, "PopAbooK.com Confirm and Activate Account", "support@popabook.com", 'pbd1PBD1', $emailmsg);
					$db_error = "Your Account is created. An E-Mail has been sent to you with steps to Activate account. Activate your account and Login."; 

			   }
			   else
			   {
                                    $db_error = "E-Mail Already Exists. Please Login.";
			   }
		   }
	   }

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
  
  if ($db_error !="")
  {
    echo "<response its=\"failure\">";
    echo "<message>";
    echo $db_error;
    echo "</message>";
    echo "</response>";
  }
  else 
  {
	$_SESSION['email'] = $email;
	echo "<response its=\"success\">";
	echo "</response>";
  }
  echo "</data>";

}

?>

