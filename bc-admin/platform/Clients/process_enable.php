<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
$status = $_POST['status'] == 'ToProcess' ? 1 : 0;
if(isset($registration_type) && $registration_type == 2 && $status == 1){
/* get client data*/
  $sql = execute('select * from '.$table_name.' where id = '.$_POST['id']);
  $rs = mysql_fetch_array($sql);
 if(!$rs['enabled']){
	 /* SEND E-MAIL TO CLIENT */
	 $message = $lang_['clients_accounts']['CLIENT_REGISTRATION_MESSAGE'];
	 $message = str_replace('{client_name}',ucwords($rs['name'].' '.$rs['lastname']),$message);
     $message = str_replace('{shop_url}','<a href"'.$shop_url.'" target="_blank">'.$shop_url.'</a>',$message);
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
	 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_['clients_accounts']['CLIENT_REGISTRATION_SUBJECT']);
	 $mail->Body = $message;
	  if($mail->Send()){
	  }else{
		  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
	  }
 }
}
/*update status*/
execute ('update '.$table_name.' set enabled = '.$status.' where id = '.$_POST['id']);
?>