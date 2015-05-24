function loadhomedata()
{
<?php
	$session_id = session_id();
    $db_query = "select quantity, isbn13 from shopping_carts where session like '$session_id'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
	{
	   $index = 0;
	   $db_row = mysqli_fetch_row($db_result);
	   while ($db_row != null)
	   {
		   $db_query1 = "select title,ourprice,listprice, from book_inventory where isbn13 like '$db_row[1]'";
		   $db_result1 = mysqli_query($link, $db_query1);
		   if ($db_result1 == TRUE)
		   {
			   $db_row1 = mysqli_fetch_row($db_result1);
			   if ($db_row1 != null)
			   {
				   echo "addtocartonload('" . $db_row[1] . ".jpg'," . $db_row1[2] . ",$db_row1[1],'" . $db_row[1] . "','$db_row1[0]',$db_row[0]);";
			   }
		   }
		   $db_row = myqli_fetch_row($db_result);
	   }
	   echo "showcart();";
	}
?>
}
