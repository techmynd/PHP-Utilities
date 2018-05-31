<?php

$var1 ="Good";
$var2 ="OK";

require_once('class.phpmailer.php');
$mail             = new PHPMailer(); // defaults to using php "mail()"
$mail->IsSendmail(); // sendmail support

$body             = file_get_contents('contents.html');
$body             = eregi_replace("[\]",'',$body);
// $body = preg_replace('/\\\\/','', $body);

$body = str_replace('$var1', $var1, $body);
$body = str_replace('$var2', $var2, $body);
$body = str_replace('$var3', $var3, $body);

// if str dont work use eregi_replace
// $body  = eregi_replace('$var3', $var3, $body);

// // or setup some {name} {email} {url} like user type controls
// //setup vars to replace
// $vars = array('{name}','{email}','{url}');
// $values = array($mmName,$mmEmail,$mmURL);
// //replace vars
// $body = str_replace($vars,$values,$body);

$mail->AddReplyTo("info@javedkhalil.com","Javed Khalil");
$mail->SetFrom('info@javedkhalil.com', 'Javed Khalil');
$mail->AddReplyTo("info@javedkhalil.com","Javed Khalil");

$address = "mypersonalsecretemailaddress@gmail.com";

$mail->AddAddress($address, "Muhamad Javed");

// $mail->AddCC($address, $name = "")
// $mail->AddBCC($address, $name = "admin@techmynd.com, admin techmynd");

$mail->Subject    = "You have got an email";

$mail->MsgHTML($body);

//$mail->AddAttachment("1.jpg"); // attachment
//$mail->AddAttachment("2.jpg"); // attachment
// $mail->addAttachment('../tmp/' . $varfile, $varfile);/*Add attachments*/

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
?>
