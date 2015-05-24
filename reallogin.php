 <html>
 
 
 <head>
 
 
 
 <script language="javascript">
         function loginscreen()
         {
             var ptext = document.createElement("p");
             var textnode = document.createTextNode("Enter Your E-Mail Address");
             ptext.appendChild(textnode);
             var bodyelem = document.getElementById("realbody");

             var inputloginform = document.createElement("form");
             
             bodyelem.appendChild(inputloginform);
             

             
             var inputtext = document.createElement("input");
             inputtext.setAttribute("type","text");
             inputtext.setAttribute("name", "email");
             inputloginform.appendChild(ptext);
             inputloginform.appendChild(inputtext);
             
             
             var ptextpassword = document.createElement("p");
             var passwordtextnode = document.createTextNode("Enter Your Password");
             ptextpassword.appendChild(passwordtextnode);
             inputloginform.appendChild(ptextpassword);
             
             
             var inputpassword = document.createElement("input");
             inputpassword.setAttribute("type","password");
             inputpassword.setAttribute("name", "password");
             inputloginform.appendChild(inputpassword);
             
             var warning = document.createElement("p");
             var warningtextnode = document.createTextNode("Submit to Log In / Create Account");
             warning.appendChild(warningtextnode);
             inputloginform.appendChild(warning);
             
             

             var submitbutton = document.createElement("input");
             submitbutton.setAttribute("type","submit");
             submitbutton.setAttribute("value", "SUBMIT");
             inputloginform.appendChild(submitbutton);
             
             
         }
 
 
 </script>
 </head>
 <body id="realbody" onload="loginscreen()">
 
                  <?php
                  

                            if ($_GET['email'] != "")
                            {
                                echo $_GET['email'];
                                $email = $_GET['email'];

                            echo "<br>";
                            }

                            if ($_GET['address'] !="")
                            {
                            echo  $_GET['address'];
                            $address = $_GET['address'];
                            echo "<br>";
}
                            
                            if ($_GET['password'] != "")
                            {
                            echo $_GET['password'];
                            echo "<br>";
}
                            
                            if ($_GET['telephone'] != "")
                            {
                            echo $_GET['telephone'];
                            $telephone = $_GET['telephone'];
                            echo "<br>";
}

if ($_GET['email'] != "")
{
                              $md5password = md5($_GET['password']);

                              $query = "insert into customers (email, address, telephone, password) values ('$email', '$address', '$telephone', '$md5password')";
                              $checkemail = "select email from customers where email like '$email';";
    $link = mysqli_connect('localhost', 'root', '', 'zoco');
    $result = mysqli_query($link, $checkemail);
    if ($result == TRUE)
    {
       echo "Inserted New Row $email, $address and $telephone";

    }
    else
    {
           print(" Praveen $result");
           
           $errorno = mysqli_errno($link);
           print ("$errorno");

          $error1 = mysqli_error($link);
          print("$error1");
    }

}
                  

  /**

 
 <form action="login.php" method="GET">
 Email: <input type="text" name="email">
 <br>
 Password: <input type="text" name="password">
 <br>
 Address: <input type="text" name="address">
 <br>
 Telephone: <input type="text" name="telephone">
 <br>
 
 <input type="submit">
 
 
 </form>    */
 
 ?>
 </body>
    </html>





<?php



/*function sign_new_user(email, password, telelphone, address)
{
  $md5password = md5($password);
    $query = "insert into customers values ('$email', '$address', '$telephone', '$md5password')";
    $link = mysqli_connect('localhost', 'root', '', 'zoco');
    $result = mysqli_query($link, $query);
    if ($result == TRUE)
    {
       echo "Inserted New Row $email, $address and $telephone";

    }
    else
    {
          $error1 = mysqli_error($link);
          print("$error1");
    }      */
    
    /*
$username = 'praveen';
  $password = 'password';
  $md5password = md5($password);
  $link = mysqli_connect('localhost', 'root', '', 'zoco');
  echo "$md5password";
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (!$link) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Success... ' . mysqli_get_host_info($link) . "\n";


$result = mysqli_query($link, "select password from customers where email like '$username'");
if ($result == TRUE)
{
printf("Query Worked");
printf("Select returned %d rows.\n", mysqli_num_rows($result));
}
else
{
printf("Query did not work!");
}



$error1 = mysqli_error($link);
print("$error1");

$nae = mysqli_fetch_row($esult);

print("$nae[0] , $nae[1]");

print(" secon Hello, world <br> <br>\n");


$nae = mysqli_fetch_row($esult);

print("$nae[0] , $nae[1]");

}     */

?>

