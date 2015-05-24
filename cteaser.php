<?php
require_once('dbconnect.php');
$link = wrap_mysqli_connect();
$isbns = array();
$isbns[0] = '9780198067726';
$isbns[1] = '9780198069973';
$isbns[2] = '9781416953685';
$isbns[3] = '9781844086825';
$isbns[4] = '9780143415473';
$isbns[5] = '9780670085194';

$pisbns = array();
$pisbns[0] = '9788184951288';
$isbnindex = count($isbns);
$itemp = 0;
for ($itemp = 0; $itemp < $isbnindex; $itemp = $itemp + 1)
{

$db_query = "select title, isbn13, blurb,bookid  from book_inventory where isbn13 like '$isbns[$itemp]'"; 
$db_result = mysqli_query($link, $db_query);
if ($db_result == TRUE)
{
   $db_row = mysqli_fetch_row($db_result);
   while ($db_row != null)
   {
       createteaser($link, $db_row[0], $db_row[1],$db_row[2],$db_row[3]);
       $db_row = mysqli_fetch_row($db_result);
   }
}
}

function createteaser($linkdb, $str,$isbn,$blurb,$bookid)
{
$realt = "";
 if (substr($blurb, 0, 12) == "Introduction")
            {
                    $realt = strstr($blurb, "<br><br>");
                    $realt = substr($realt, 8, 200);
            }
            else if (substr($blurb, 0, 8) == "Overview")
            {
                    $realt = strstr($blurb, "<br><br>");
                    $realt = substr($realt, 8, 200);
            }
            else
            {
                    $realt = substr($blurb, 0, 200);
            }
            $realtpos = strrpos($realt, " ");
            $realt = substr($realt, 0, ($realtpos + 1));

        echo "*** $str/$isbn ***<br>";
        echo "*** $realt <br><br>$blurb ***<br>";
        $db_query1 = "insert into newbooks (bookid,teaser) values ($bookid,\"$realt\")";
        echo "*** $db_query1 ***<br>";
        mysqli_query($linkdb, $db_query1); 

}
?>
