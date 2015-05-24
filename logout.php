<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
$link = wrap_mysqli_connect();
$db_error = "";

$reqtype = $_POST['reqtype'];
$logout_success = "false";

if($reqtype == "logout")
{
  if($link != null)
  {
       $email = $_POST['email'];
       $db_query = "select email from usersession where session like '$session_id' and email like '$email'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) > 0)
           {
                   $logout_success = "true";
           }
           else
           {
               $logout_success = "false";
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
  $_SESSION = array();
  session_regenerate_id();
  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\" ?";
  echo ">";
  echo "<data>";
  echo "<request type=\"logout\">";
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
  else if ($logout_success == "false")
  {
    echo "<response its=\"failure\">";
    echo "<message>";
    echo "Its not a valid session.";
    echo "</message>";
    echo "</response>";
  }
  else if ($logout_success == "true")
  {
    echo "<response its=\"success\">";
    echo "<address>";
    echo $address;
    echo "</address>";
    echo "</response>";
  }
  echo "</data>";

}

?>

