<?php

if (isset($_GET['isbn13']) == TRUE)
{
require_once('/var/apache2/2.2/htdocs/dbconnect.php');
$link = wrap_mysqli_connect();
    $isbn13 = $_GET['isbn13'];
    $isbn13 = strtolower($isbn13);
    $db_query = "select keywordid from keywords where keyword like '$isbn13'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
         $db_row = mysqli_fetch_row($db_result);
         if ($db_row != null)
         {
             $keywordid = $db_row[0];
             $db_query = "select bookid from keyword_book_map where keywordid=$keywordid";
             $db_result = mysqli_query($link, $db_query);
             if ($db_result == TRUE)
             {
                  $rows = mysqli_num_rows($db_result);
                  if ($rows > 0)
                      echo "FOUND";
                  else
                      echo "NOT FOUND";
             }
             else
                echo "NOT FOUND";
         }
         else
         {
             echo "NOT FOUND";
         }
    }
    else
    {
        echo "NOT FOUND";
    }
}
?>
