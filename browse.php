<?php
header("Content-type: text/html;charset=utf-8");
if (isset($_POST['browse']) == TRUE)
{
	session_start();
	require_once('dbconnect.php');
	$link = wrap_mysqli_connect();
	$session_id = session_id();
	require_once('browsecatalog.php');
	$browsecatalog = $_POST['browse'];
        $storeurl = "";
        if (isset($_POST['storeurl']) == TRUE)
            $storeurl = $_POST['storeurl'];
        $linkurl = "";
        if (isset($_POST['linkurl']) == TRUE)
            $linkurl = $_POST['linkurl'];
        
	$dbstr = rawurlencode($browsecatalog);
	mysqli_query($link,"insert into sessions_searches (session,search) values ('$session_id','$dbstr')");
	$title = "";
	$metadata = "";
	$retcode = 200;
	if ($browsecatalog == "browseall")
	{
		$browseoutput = showbrowseoptions(0);
	}
	else
	{
		$browseoutput = browsecatalog($link, $browsecatalog, $title, $metadata,0,$retcode,$storeurl, 1, $linkurl);
		if ($retcode == 404)
		{
			$browseoutput = "<div class=\"atp\" style=\"padding-left:5px;\"> <p> We could not find books under <span class=\"blueatp\"> $browsecatalog</span> directory.<br>Server is Temporarily Unavailable. <br> Please Try Again.</p></div>";
		}
	}
	echo $browseoutput;
}
?>
