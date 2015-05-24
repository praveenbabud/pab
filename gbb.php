<html><head><style type="text/css">body{padding:0;margin:0;}</style></head><body>
<?php

if (isset($_GET['title']) == TRUE && isset($_GET['url']) == TRUE)
{
$title=$_GET['title'];
$url=$_GET['url'];
//$title = rawurlencode($title);
//$url = rawurlencode($url);
echo "<a title=\"$title\" style=\"border:none;\" class=\"google-buzz-button\" href=\"http://www.google.com/buzz/post\" data-button-style=\"small-count\" data-url=\"$url\"></a> <script type=\"text/javascript\" src=\"http://www.google.com/buzz/api/button.js\"></script>";
}
?>
</body></html>
