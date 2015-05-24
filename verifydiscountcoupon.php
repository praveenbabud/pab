<?php
session_start();
if (isset($_POST['dc']) == TRUE)
{
     $dc = $_POST['dc'];
     $ldc = strtolower($dc);
     $dcp = 0;
     $valid = 0;

     if (verifycoupon($ldc,$dcp) == FALSE)
         $valid = 0;
     else
         $valid = 1;

     if ($valid == 0)
     {
         echo "<form id=\"discountcouponform\" onsubmit=\"return false;\"><table width=\"100%\"><tr><td>Promotion Coupon Code!?</td><td><input type=\"text\" name=\"discountcouponvalue\"></td><td><a href=\"javascript:void(0);\" onmousedown=\"this.style.backgroundColor='#00008b';\" class=\"atp\" style=\"text-decoration:none;border:solid 1px #0000ff;padding-top:2px;padding-bottom:2px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onclick=\"verifydiscountcoupon()\">&nbsp;Apply Coupon&nbsp;</a></td></tr></table></form> Sorry, $dc is not an active discount coupon";
     }
     else
     {
         echo "<form id=\"discountcouponform\" onsubmit=\"return false;\"><table width=\"100%\"><tr><td>Promotion Coupon Code!?</td><td><input type=\"text\" name=\"discountcouponvalue\"></td><td><a href=\"javascript:void(0);\"  onmousedown=\"this.style.backgroundColor='#00008b';\" class=\"atp\" style=\"text-decoration:none;border:solid 1px #0000ff;padding-top:2px;padding-bottom:2px;background-color:#2b60de;color:#ffffff;\" onmouseover=\"this.style.backgroundColor='#0000ff';this.style.color='#ffffff';this.style.cursor='pointer';\" onmouseout=\"this.style.backgroundColor='#2b60de';this.style.color='#ffffff';\" onclick=\"verifydiscountcoupon()\">&nbsp;Apply Coupon&nbsp;</a></td></tr></table></form> Congratulations your are eligible for a minimum [$dcp%] discount on each book, please check the updated shopping cart!";
     }
}
function verifycoupon($coupon,&$discount)
{
    require('dbconnect.php');
    $link = wrap_mysqli_connect();
    $coupon = trim($coupon);
    $db_query = "select type, used, mind, maxd from discountcoupons where code like '$coupon'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
        $db_row = mysqli_fetch_row($db_result);
        if ($db_row != NULL)
        {
            $type = $db_row[0];
            $nused = $db_row[1];
            $min = $db_row[2];
            $max = $db_row[3];
            if ($type == "inactive" || ($type == "onetime" && $nused >= 1))
            {
                return FALSE;
            }
            else
            {
                $range = $max - $min + 1;
                $discount = $min + $nused % $range;
                return TRUE;
            }
        }
        else
            return FALSE;
    }
    else
        return FALSE;
}
?>

