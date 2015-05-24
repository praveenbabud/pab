<?php
header("Content-type: text/html;charset=utf-8");
session_start();
$session_id = session_id();
require_once('pab_util.php');

if ($_POST['reqtype'] == "getusedbooks")
{
	if (isset($_POST['isbn13']) == TRUE)
	{
		$isbn13 = $_POST['isbn13'];
		require('dbconnect.php');
                $arrex = explode("!",$isbn13);
                $isbn13 = $arrex[1];
		$link = wrap_mysqli_connect();
		echo usedbooksfromdb($link, $isbn13);
	
	}
}
?>
