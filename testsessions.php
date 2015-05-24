<?php

session_start();

?>

<html>
<head>
<title> Welcome to voco
</title>

</head>
<center>
<body>
<?php
if (isset($_SESSION['USERNAME']))
{
  echo "Hello" . " " . $_SESSION['USERNAME'];
  echo "<br>";
  echo "Session Already Exists and User is logged In";
}
else
{
    if ($_POST['username'] != "")
    {
        $_SESSION['USERNAME']=$_POST['username'];
        echo "Hello" . " " . $_SESSION['USERNAME'];
    }
    else
    {
         echo "Hello";
    }

}

?>

<form action="testsessions.php" method="POST">
<p> Enter Your Email </p>
<input type="text" name="username"/>
<br> <p> Enter Your Password </p>
<input type="password" name="password"/>
<input type="submit" name="login"/>

</form>
</body>
</center>

</html>
