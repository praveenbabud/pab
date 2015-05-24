<?php
require_once('dbconnect.php');
require_once('homedata.php');
$link = wrap_mysqli_connect();
$storeurl = "";
if (isset($_POST['storeurl']) == TRUE)
  $storeurl = $_POST['storeurl'];
if ($storeurl == "")
homedata($link);
else
storehomedata($link,$storeurl);
?>

