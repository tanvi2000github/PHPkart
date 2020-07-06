<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/include/inc_load.php');
require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
/* SEND E-MAIL TO ADMINISTRATOR */
 $message = $lang_client_['pl_businesstype']['ADMIN_RETAILER_REQUEST_EMAIL_MESSAGE'];
 $message = str_replace('{client_name}',ucwords($_SESSION['Cname'].' '.$_SESSION['Clastname']),$message);
 $message = str_replace('{client_userid}',$_SESSION['Cuserid'],$message);
 $message = str_replace('{client_email}',$_SESSION['Cemail'],$message);
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
 $mail->From = $_SESSION['Cemail'];
 $mail->FromName = $_SESSION['Cemail'];
 $mail->AddAddress($admin_email);
 $mail->AddReplyTo($_SESSION['Cemail']);
 $mail->Sender=$_SESSION['Cemail'];
 $mail->IsHTML(true);
 $mail->Subject = $lang_client_['pl_businesstype']['ADMIN_RETAILER_REQUEST_EMAIL_SUBJECT'];
 $mail->Body = $message;
  if($mail->Send()){
    execute('update '.$table_prefix.'clients set retailer_request = 1 where id = '.$_SESSION['Cid']);
     echo 'ok';
  }else{
	  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
  }
?>