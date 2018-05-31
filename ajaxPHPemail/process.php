<?php 
function getVar(&$value, $default = null) { return isset($value) ? $value : $default; }
// request variables
$choice1=getVar($_REQUEST["choice1"]);
$choice2=getVar($_REQUEST["choice2"]);
$choice3=getVar($_REQUEST["choice3"]);

if($choice1!='' && $choice2!='' && $choice3!='') 
	{
		// send email

$mailto=$choice2;
$mailfrom="info@techmynd.org";
$mailfrom2="javed <info@techmynd.org>";
$subject= "Ajax Email Test";
// To send HTML mail, the Content-type header must be set
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$mailfrom."\r\n";
$headers .= "Reply-To: ".$mailfrom."\r\n";
$headers .= "Return-Path: ".$mailfrom."\r\n";
$headers .= "Signed-by: techmynd.org";
$headers .='X-Mailer: PHP/' . phpversion();
// $headers .= 'Cc: mail1@hotmail.com' . "\r\n";
// $headers .= 'Bcc: mail2@hotmail.com' . "\r\n";
$mmessage = "<br><br>
Hello <strong>Buddy</strong>
<br><br>
Here are email details.
<br><br>
<strong>Name:</strong> $choice1 <br><br>
<strong>Email:</strong> $choice2 <br><br>
<strong>Comments:</strong> $choice3 <br><br>";

@mail($mailto, $subject, $mmessage, $headers);

	?>

<div class="alert alert-success">Email has been sent!</div>

	<?php

	}
?>