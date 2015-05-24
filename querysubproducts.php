<?php
session_start();
$session_id = session_id();
require('dbconnect.php');
$link = wrap_mysqli_connect();
$db_error = "";
$names = array();
$ids = array ();
$index = 0;

$reqtype = $_POST['reqtype'];

if($reqtype == "querysubproducts")
{
	$productid = $_POST['productid'];
  if($link != null)
  {
       $db_query = "select name,subproductid from subproducts where productid=$productid order by name asc";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
	   {
		   $db_row = mysqli_fetch_row($db_result);
		   while ($db_row != null)
		   {
			   $names[$index] = $db_row[0];
			   $ids[$index] = $db_row[1];
			   $index = $index + 1;
			   $db_row = mysqli_fetch_row($db_result);
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
  echo "<request type=\"querysubproducts\">";
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
	echo "<response its=\"success\">";
	$temp = 0;
	while ($temp != $index)
	{
		echo "<subproduct id=\"$ids[$temp]\">$names[$temp]</subproduct>";
		$temp = $temp + 1;
	}
	echo "</response>";
  }
  echo "</data>";

}
?>

