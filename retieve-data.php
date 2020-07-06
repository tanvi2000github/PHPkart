<?php
require_once('include/inc_load.php');
require_once('include/lib/phpMailer/class.phpmailer.php');
if(!isset($_POST['userid_retrieve']) && !isset($_POST['email_retrieve'])){
 echo 'data-not-exist';
 exit();
}
 if((isset($_POST['userid_retrieve']) && $_POST['userid_retrieve'] != '')  && (!isset($_POST['email_retrieve']) || $_POST['email_retrieve'] === '')){
	$where = 'where userid = "'.str_db($_POST['userid_retrieve']).'"';
 }else if((!isset($_POST['userid_retrieve']) || $_POST['userid_retrieve'] === '') && (isset($_POST['email_retrieve']) && $_POST['email_retrieve'] != '')){
	$where = 'where email = "'.str_db($_POST['email_retrieve']).'"';
 }else if((isset($_POST['userid_retrieve']) && $_POST['userid_retrieve'] != '') && (isset($_POST['email_retrieve']) && $_POST['email_retrieve'] != '')){
	$where = 'where email = "'.str_db($_POST['email_retrieve']).'" and userid = "'.str_db($_POST['userid_retrieve']).'"';
 }

 $sql = execute('select * from '.$table_prefix.'clients '.$where);
 $rs = mysql_fetch_array($sql);
 if(!$rs){
	echo 'data-not-exist';
	exit();
 }
 /* SEND E-MAIL TO CLIENT */
 $message = $lang_client_['retieve_data']['EMAIL_MESSAGE'];
 $message = str_replace('{client_name}',ucwords($rs['name'].' '.$rs['lastname']),$message);
 $message = str_replace('{userid}',ucwords($rs['userid'].' '.$rs['lastname']),$message);
 $message = str_replace('{link}',abs_client_path.'/change-password-form.php?pas='.mb_substr(encryption($rs['password']),0,15).'&em='.mb_substr(encryption($rs['email']),0,15),$message);
 $message = str_replace('{link_name}',abs_client_path.'/change-password-form.php?pas='.mb_substr(encryption($rs['password']),0,15).'&em='.mb_substr(encryption($rs['email']),0,15),$message);
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
 $mail->From = $admin_email;
 $mail->FromName = $admin_email;
 $addresses = explode(',',$rs['email']);
  foreach($addresses as $val){
    $mail->AddAddress($val);
  }
 $mail->AddReplyTo($admin_email);
 $mail->Sender=$admin_email;
 $mail->IsHTML(true);
 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_client_['retieve_data']['EMAIL_SUBJECT']);
 $mail->Body = $message;
  if($mail->Send()){
  }else{
	  //* language-part *//
	  echo "Message was not sent <br />PHP Mailer Error: " . $mail->ErrorInfo;
  }
  echo $rs['email'];
?>