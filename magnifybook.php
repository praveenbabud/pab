<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$session_id = session_id();
require('dbconnect.php');
require_once('pab_util.php');
$link = wrap_mysqli_connect();
$db_error = "";
$output = "";
$storeurl = "";
$linkurl = "";
$savepercent = 0;
if (isset($_POST['storeurl']) == TRUE)
    $storeurl = $_POST['storeurl'];
if (isset($_POST['linkurl']) == TRUE)
    $linkurl = $_POST['linkurl'];

if(isset($_POST['reqtype']) == FALSE)
    exit;
$reqtype = $_POST['reqtype'];

if($reqtype == "magnifybook")
{
  if($link != null)
  {
       $isbn13 = $_POST['isbn13'];
       $arr = explode("!",$isbn13);
       $isbn13 = $arr[1];
       $db_query = "select title,author1,author2,author3,author4,nlistprice,nourprice,currency,shiptime,booklink,edition from book_inventory where isbn13 like '$isbn13'";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
       {
           if(mysqli_num_rows($db_result) == 1)
           {
		$db_row = mysqli_fetch_row($db_result);
                $imagelarge = "";
                $image = "";
		$booklink = $db_row[9] . "/" . $isbn13;
                if ($linkurl != "")
                    $booklink = $booklink . "/" . $linkurl . "/af";
                if ($storeurl != "")
                    $booklink = $booklink . "/" . $storeurl . "/bookstore";
                $xid = rawurlencode("http://www.PopAbooK.com/book/$isbn13");
                $listprice = converttoinr($db_row[7], $db_row[5]);
               $ourprice = converttoinr($db_row[7], $db_row[6]);
               if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $isbn13. ".jpg"))
                     $image = $isbn13 . ".jpg";
               else
                     $image = "tmpina.jpg";
               if (file_exists("/var/apache2/2.2/htdocs/" . $isbn13 . ".jpg"))
                     $imagelarge = $isbn13 . ".jpg";
               else if (file_exists("/var/apache2/2.2/htdocs/optimage/" . $isbn13 . ".jpg"))
                     $imagelarge = "optimage/$isbn13" . ".jpg";
               else
                     $imagelarge = "tmpina.jpg";
               $output = $output . "<table width=\"100%\">";
               $output = $output . "<tr><td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=\"left\" valign=\"top\"><br><br><br><img alt=\"$db_row[0]\" title=\"$db_row[0]\" src=\"http://www.popabook.com/$imagelarge\" width=\"140px\" height=\"200px\"/></td><td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=\"75%\" align=\"left\" valign=\"top\" style=\"line-height:1.8em;\" class=\"atp\"> ";
               $output = $output . "<br><h1 style=\"line-height:1.3em;\">$db_row[0]&nbsp;</h1>$db_row[10]<br>";
               if ($db_row[1] != "")
               {
                  $output = $output . "&nbsp;by <span class=\"matp\"><b>" . $db_row[1];
               }
               if ($db_row[2] != "")
               {
                  $output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[2]";
               }
               if ($db_row[3] != "")
               {
                  $output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[3]";
               }
               if ($db_row[4] != "")
               {
                  $output = $output . "</b></span> and <span class=\"matp\"><b>$db_row[4]";
               }
               $output = $output . "</b></span>";
                $etitle = rawurlencode($db_row[0]);
                $output = $output . "<div style=\"padding-top:5px;\"><table><tr><td valign=\"top\"><iframe src=\"http://www.facebook.com/plugins/like.php?href=$xid&amp;layout=button_count&amp;show_faces=false&amp;width=125&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=21\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:125px; height:21px;\" allowTransparency=\"true\"></iframe></div></td><td valign=\"top\"><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://platform.twitter.com/widgets/tweet_button.html?url=$xid&via=PopAbooK&text=Checking%20out%20$etitle&related=popabook%3ASocial%20Book%20Store&count=horizontal\" style=\"width:130px; height:21px;\"></iframe></td><td valign=\"top\"><iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"http://www.popabook.com/gbb.php?url=$xid&title=$etitle\" style=\"border:none;overflow:hidden;width:150px;height:21px;\"></iframe></td></tr></table></div>";

                $output = $output . "<table width=\"100%\"><tr><td><div class=\"pabborder\" style=\"height:7px;\"></div><table width=\"100%\"><tr><td width=\"50%\" valign=\"top\"><div style=\"line-height:2.0em;\"><span class=\"satp\">List Price </span><span class=\"atp\">Rs. <s>$listprice</s> </span><br> Our Price <span class=\"ourprice\">Rs. $ourprice</span><br>";

                                $save = $listprice - $ourprice;
                                if ($save != 0)
                                {
                                        $savepercent =  round(($save * 100)/$listprice);
                                        $output = $output . " <span class=\"satp\">You Save </span><span class=\"logoatp\">Rs. $save</span>";
                                }
                                else
                                {
                                        $output = $output . " You Save Rs. <span class=\"atp\">0</span>";
                                }
                                $output = $output . "</div></td><td width=\"50%\" valign=\"middle\"  class=\"boldlogoatp\">";
                                if ($save != 0)
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/$savepercent.gif\" alt=\"$savepercent%\">off</div>";
                                if ($save != 0 && $ourprice > 100)
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\">+</div>";
                                if ($ourprice > 100)
                                {
                                if (($savepercent > 9 && $savepercent < 20) || $save == 0)
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/freeshipping2.gif\"></div>";
                                else
                                $output = $output . "<div class=\"logoatp\" style=\"text-align:center;\"><img src=\"http://www.popabook.com/uitest/freeshipping1.gif\"></div>";
                                }

                                $output = $output . "</td></tr></table>";
                                if ($ourprice <= 100)
                                        $output = $output . "<span class=\"satp\">Please Add Rs. 30 for shipping if Total Bill does not exceed 100</span>";

               $output = $output . "<span class=\"atp\">" ;
               if ($db_row[8] == "Out Of Stock")
               {
                    $output = $output . "<br><br><span class=\"logoatp\">$db_row[8]</span>";
               }
               else
               {
                    if ($db_row[8] == "3-5")
                                    {
                                        $output = $output . "<span class=\"logoatpo\"><br>In Stock</span>";
                                        $output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[8] </span> business days";
                                    }
                                    else
                                    {
                                        $output = $output . "<span class=\"logoatpo\"><br>Available</span>";
                                        $output = $output . "<br>Order now and receive in <span class=\"logoatp\"> $db_row[8] </span> business days";
                                    }

               }
               $output = $output . "</span>";

               $output = $output . "</td><td>&nbsp;</td>";
               $output = $output . "<td align=\"right\" valign=\"top\">";
               if ($db_row[8] != "Out Of Stock")
               {
                    $rawtitle = rawurlencode($db_row[0]);
                    $output = $output . "<div style=\"text-align:center;\"><img src=\"http://www.popabook.com/buttonatc.jpg\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"addtocart('$image', '$listprice', '$ourprice', '$isbn13', '$rawtitle','$db_row[8]','')\"> <br><br> <br><a href=\"#\" onclick=\"addtowishlist('$db_row[2]','$rawtitle',$listprice,$ourprice,'$db_row[8]','$image')\"><span class=\"boldsatp\" style=\"text-decoration:none;border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></div></td></tr></table></td></tr></table>";
               }
               else
               {
                  $rawtitle = rawurlencode($db_row[0]);
                  $output = $output . "<div><br><br><br><br><a href=\"#\" onclick=\"addtowishlist('$isbn13','$rawtitle',$listprice,$ourprice,'$db_row[8]','$image')\"><span class=\"boldsatp\" style=\"border:1px solid #2b60de;padding:3px;background-color:#fff5ee;\">Add To Wish List</span></a></div></td></tr></table></td></tr></table>";

               }
            }
        }
    }
}
  echo $output;
?>

