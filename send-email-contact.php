<?php
require_once('include/inc_load.php');
require_once('include/lib/phpMailer/class.phpmailer.php');
if (!empty($_POST['captcha'])) {
    if (empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
        die('<div class="error_alert">'.$lang_client_['contact_form']['WRONG_CAPTCHA'].'</div>');
    }
    unset($_SESSION['captcha']);
}else{
  die('<div class="error_alert">'.$lang_client_['contact_form']['EMPTY_CAPTCHA'].'</div>');
}
if (!email_exist($_POST['email'])) die('<div class="error_alert">'.$lang_client_['general']['WRONG_EMAIL_ADDRESS'].'</div>');
 /* SEND E-MAIL TO ADMIN FROM C ONTACT FORM */
 $message = $lang_client_['contact_form']['EMAIL_MESSAGE'];
 $message = str_replace('{name}',$_POST['name'],$message);
 $message = str_replace('{last_name}',$_POST['lastname'],$message);
 $message = str_replace('{phone}',$_POST['phone'],$message);
 $message = str_replace('{email}',$_POST['email'],$message);
 $message = str_replace('{message}',$_POST['message'],$message);
 $mail = new PHPMailer();
  if($smtp_email){
   $mail->IsSMTP();
   $mail->Port = $smtp_port;
   $mail->Host = $smtp_host;
   $mail->Mailer = 'smtp';
   $mail->SMTPAuth = true;
   $mail->Username = $smtp_user;
   $mail->Password = $smtp_password;
   $mail->SMTPSecure = $smtp_secure;
   $mail->SingleTo = true;
  }
 $mail->CharSet = 'UTF-8';
 $mail->From = $company_email;
 $mail->FromName = $_POST['email'];
 $mail->AddAddress($company_email);
 $mail->AddReplyTo($_POST['email']);
 $mail->Sender=$admin_email;
 $mail->IsHTML(true);
 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_client_['contact_form']['EMAIL_SUBJECT']);
 $mail->Body = $message;
  if($mail->Send()){
  }else{
	  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
  }
?>