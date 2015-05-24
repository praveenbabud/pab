<?php
require("Mail.php");
function sendemail($receiver, $subject, $sender, $sender_password, $mailmsg)
{
	/* mail setup recipients, subject etc */
	$headers["From"] = $sender;
	$headers["To"] = $receiver;
        $headers["MIME-Version"] = '1.0';
        $headers["Content-type"] = 'text/html; charset=iso-8859-1';
	$headers["Subject"] = $subject;
	/* SMTP server name, port, user/passwd */
	$smtpinfo["host"] = "ssl://smtp.bizmail.yahoo.com";
	$smtpinfo["port"] = "465";
	$smtpinfo["auth"] = true;
	$smtpinfo["username"] = $sender;
	$smtpinfo["password"] = $sender_password;
	/* Create the mail object using the Mail::factory method */
	$mail_object = Mail::factory("smtp", $smtpinfo);
	/* Ok send mail */
	$mail = $mail_object->send($receiver, $headers, "<html><head></head><body>" . $mailmsg . "</body></html>");
	if (PEAR::isError($mail))
   	{
		error_log($mail->getMessage() . $receiver,3,'/tmp/popabookphp.errors');
		return false;
	} 
	else
	{
		return true;
	}
}
?>
