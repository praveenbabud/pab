<?php
header("Content-type: text/html;charset=utf-8");
require('dbconnect.php');
require_once('browsecatalog.php');
$link = wrap_mysqli_connect();
$storeurl = "";
$linkurl = "";
if (isset($_POST['storeurl']) == TRUE)
    $storeurl = $_POST['storeurl'];
if (isset($_POST['linkurl']) == TRUE)
    $linkurl = $_POST['linkurl'];

if (isset($_POST['isbn13']) == TRUE && isset($_POST['reqtype']) == TRUE)
{
	if ($_POST['reqtype'] == 'getsimilarbooks')
	{
	$isbn13 = $_POST['isbn13'];
        $arr = explode("!",$isbn13);
        $isbn13 = $arr[1];
        $db_query = "lock tables usedbooks read,book_inventory read, similarbooks read";
        $db_result = mysqli_query($link, $db_query);
	$db_query = "select bookid, title from book_inventory where isbn13 like '$isbn13'";
	$db_result = mysqli_query($link, $db_query);

	if ($db_result == TRUE)
	{
		$db_row = mysqli_fetch_row($db_result);
		if($db_row != null)
		{
                        echo "<div style=\"text-align:left;padding-left:10px;\"><h3>Similar Books : $db_row[1]</h3></div>";
			echo showsimilarbooksonpage($link,$db_row[0],0,$linkurl);
		}
	}
        $db_query = "unlock tables";
        $db_result = mysqli_query($link, $db_query);
	}
}

?>
