<?php
$filenames = array();
$index = 0;
set_time_limit(0);
$dir = opendir("c:\\xampp\\htdocs\\xampp\\website\\publishers\\jaico\\images");
while (($file = readdir($dir)) !== false) {


/*	if (strstr($file, ".tif") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".TIF") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".gif") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".GIF") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".bmp") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".BMP") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".png") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}
	else if (strstr($file, ".PNG") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file $newfile <br>";
	}*/
	if (strstr($file, ".jpg") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file -thumbnail 70x100 \"optimage\\$newfile\" <br>";
	}
	else if (strstr($file, ".JPG") != null)
	{
            $arr = explode(".",$file);
            $newfile = $arr[0] . ".jpg";
            echo "convert $file -thumbnail 70x100 \"optimage\\$newfile\" <br>";
	} 


}
closedir($dir);
?>
