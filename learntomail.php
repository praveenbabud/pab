     <?php
              include('Mail.php');
              include('Mail/mime.php');
       
              // Constructing the email
			  $sender = "Leigh <leigh@no_spam.net>";    
			  // Your name and email address
			  $recipient = "Praveen <andpraveen@gmail.com>"; 
			  // The Recipients name and email address
			  $subject = "Test Email";
			  // Subject for the email
			  $text = 'This is a text message.';
			  // Text version of the email
			  $html = '<html><body><p>This is a html message</p></body></html>'; 
			  // HTML version of the email
              $crlf = "\n";
              $headers = array(
                              'From'          => $sender,
                              'Return-Path'   => $sender,
                              'Subject'       => $subject
                              );
       
              // Creating the Mime message
              $mime = new Mail_mime($crlf);
       
              // Setting the body of the email
              $mime->setTXTBody($text);
              $mime->setHTMLBody($html);
       
              $body = $mime->get();
              $headers = $mime->headers($headers);
       
              // Sending the email
              $mail =& Mail::factory('mail');
              $mail->send($recipient, $headers, $body);
      ?>
