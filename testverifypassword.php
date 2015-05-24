<?php

$link = mysqli_connect('localhost', 'root', '', 'zoco');
$db_error = "";

$reqtype = $_GET['reqtype'];

if($reqtype == "verifypasswordgetdata")
{
  if($link != null)
  {
       $username = $_GET['email'];
       $password = $_GET['password'];
       $db_query = "select password,address from customers where email like '$username'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) == 1)
           {
               $db_row = mysqli_fetch_row($db_result);
               $md5password = md5($password);
               if($md5password == $db_row[0])
               {
                   $auth_success = TRUE;
                   $address = $db_row[1];
               }
               else
               {
                   $auth_success = FALSE;
               }
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
  header("Content-type: text/xml");
  echo "<?xml version=\"1.0\" ?";
  echo ">";
  echo "<data>";
  echo "<request type=\"verifypasswordgetdata\">";
  echo "<email>";
  echo $username . " " . $db_row[0] . " " . $db_row[1] . " " . $md5password . " " . $password;
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
    echo "<address>";
    echo $address;
    echo "</address>";
    echo "</response>";
  }
  echo "</data>";

}

?>

