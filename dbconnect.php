<?php
function wrap_mysqli_connect()
{
	$link = mysqli_connect('localhost', 'root', 'zulu1PopAbooK', 'zoco');
	if ($link != null)
	{
		return $link;
	}
	else
	{
		error_log(mysqli_error($link),3,'/tmp/popabookphp.errors');
		return null;
	}
}
?>
