<?php
namespace app\helpers;
use yii;

class Mailer
{
	public static function mailsent(){

	require getcwd().'\phpmailer\PHPMailerAutoload.php';	
	
$mail = new \phpmailer\PHPMailerAutoload\PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'coachersy@gmail.com';                 // SMTP username
$mail->Password = 'cteladmin';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('coachersy@gmail.com', 'Admin');
$mail->addAddress('ravitejakatta78@gmail.com', 'Joe User');     // Add a recipient
$mail->addReplyTo('ravitejasql78@gmail.com', 'Information');
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here Baby';
$mail->Body    = '<b>I love You</b>';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

	return $res;
	}
}

?>
