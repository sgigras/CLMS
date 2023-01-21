<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
require APPPATH.'PHPMailer/src/PHPMailer.php';
require APPPATH.'PHPMailer/src/SMTP.php';
require APPPATH.'PHPMailer/src/Exception.php';
function sendEmail($sub = '', $msg_body  = '', $reciver_mailid = '')
{
  $sender = 'noreply@aniruddhagps.com';
  $senderName = 'ATS';

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
  $recipient = $reciver_mailid;
 
  $email=explode(",", $recipient);


// Replace smtp_username with your Amazon SES SMTP user name.
  $usernameSmtp = 'AKIATPROFZH3CF7FIYZ3';

// Replace smtp_password with your Amazon SES SMTP password.
  $passwordSmtp = 'BDeAfMMk7x3GDWT3Pl/OythPjiI12TLCD2MhZfy6mIpm';

// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
  $configurationSet = 'ConfigSet';

// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
  $host = 'email-smtp.ap-south-1.amazonaws.com';
  $port = 587;

// The subject line of the email
  $subject = $sub;

// The plain-text body of the email
  $bodyText =  $sub;

// The HTML-formatted body of the email
  $bodyHtml =  $msg_body;
  foreach($email as $key=>$value){

  $mail = new PHPMailer(true);

  try {
    // Specify the SMTP settings.
    $mail->isSMTP();
    $mail->setFrom($sender, $senderName);
    $mail->Username   = $usernameSmtp;
    $mail->Password   = $passwordSmtp;
    $mail->Host       = $host;
    $mail->Port       = $port;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'tls';

//    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

    // Specify the message recipients.
    $mail->addAddress($value);
    // $mail->addCC($cc_reciver_mailid);
    // $mail->addAttachment("uploads/".$attachment_path);
    // You can also add CC, BCC, and additional To recipients here.

    // Specify the content of the message.
    $mail->isHTML(true);
    $mail->Subject    = $subject;
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = $bodyText;
    $mail->Send();

} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
}
if($mail->Send()){
  return "success";
}else{
  return "error";
}
}

function sendEmail_BSF($sub = '', $msg_body  = '', $reciver_mailid = '',$cc  = '', $attach = '')
{
  $sender = 'bsfrecruitment@bsf.nic.in';
  $senderName = 'ATS';

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
  $recipient = $reciver_mailid;
 
  $email=explode(",", $recipient);


// Replace smtp_username with your Amazon SES SMTP user name.
  $usernameSmtp = 'bsfrecruitment@bsf.nic.in';

// Replace smtp_password with your Amazon SES SMTP password.
  $passwordSmtp = 'Bsf@1342$';

// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
  $configurationSet = 'ConfigSet';

// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
  $host = 'relay.nic.in';
  $port = 25;

// The subject line of the email
  $subject = $sub;

// The plain-text body of the email
  $bodyText =  $sub;

// The HTML-formatted body of the email
  $bodyHtml =  $msg_body;
  foreach($email as $key=>$value){

  $mail = new PHPMailer(true);

  try {
    // Specify the SMTP settings.
    $mail->isSMTP();
    $mail->setFrom($sender, $senderName);
    // $mail->Username   = $usernameSmtp;
    // $mail->Password   = $passwordSmtp;
    $mail->Host       = $host;
    $mail->Port       = $port;
    // $mail->SMTPAuth   = true;
    // $mail->SMTPSecure = 'tls';

//    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

    // Specify the message recipients.
    $mail->addAddress($value);
    // $mail->addCC($cc_reciver_mailid);
    // $mail->addAttachment("uploads/".$attachment_path);
    // You can also add CC, BCC, and additional To recipients here.

    // Specify the content of the message.
    $mail->isHTML(true);
    $mail->Subject    = $subject;
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = $bodyText;
    $mail->Send();

} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
}
if($mail->Send()){
  return "success";
}else{
  return "error";
}
}

?>