<?php
session_start();
header("Content-type: text/html;charset=utf-8");
require_once("browsecatalog.php");
$session_id = session_id();
require('dbconnect.php');
if (isset($_POST['search_string']) == FALSE)
         exit;
$search_string = $_POST['search_string'];
$link = wrap_mysqli_connect();
$title = "";
$metadata = "";
$browse_string = $search_string;
$title;
$metadata;
$retcode = 200;
$storeurl = "";
if (isset($_POST['storeurl']) == TRUE)
{
$storeurl = $_POST['storeurl'];
}
$linkurl = "";
$output = browsecatalog($link,$browse_string,$title,$metadata,0,$retcode,$storeurl,0,$linkurl);
if ($retcode == 404)
{
	$output = "<div class=\"atp\" style=\"padding-left:5px;\"> <p> We could not find the book <span class=\"blueatp\"> $browse_string</span>.<br>Server is Temporarily Unavailable. <br> Please Try Again.</p></div>";
}
echo $output;
?>
