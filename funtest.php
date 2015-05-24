<?php
require_once('dbconnect.php');
require('sendemail.php');
require_once('invoice.php');
$session_id = session_id();
$link = wrap_mysqli_connect();
/*
generateemailtoseller($link, 15,'9788172235895','1277085095-574519'); */
$invoicenumber = '1296886703-167624';
$pgpayid = '1907234';
$bemail = 'roushon@math.tifr.res.in';
$emailmsg = generateemailinvoice($link, $invoicenumber,$pgpayid);
                                sendemail($bemail, "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);
                                sendemail('praveen@popabook.com', "PopAbooK.com OrderConfirmation INVOICE : $invoicenumber", "sales@popabook.com", 'pbd1PBD1', $emailmsg);

?>
