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
 $error = '';
 $user = str_db(str_replace('"','&quot;',$_POST['userid']));
 $email = str_db(str_replace('"','&quot;',$_POST['email']));
 $error_user = '';
 $error_mail = '';
 $sql = execute('select userid,email from '.$table_prefix.'clients where userid = "'.$user.'" or email = "'.$email.'"');
 while($rs = mysql_fetch_array($sql)){
   if(mb_strtolower($rs['userid']) == mb_strtolower($user)){
	   $error_user .= 'true';
   }
   if(mb_strtolower($rs['email']) == mb_strtolower($email)){
	   $error_mail .= 'true';
   }
 }
 if($error_user != '' || $error_mail != '') exit();
 /* SEND E-MAIL TO ADMINISTRATOR */
 $message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$lang_client_['client_registration']['ADMIN_REGISTRATION_MESSAGE']);
 if(plugin_exsists('businesstype') && isset($_POST['reseller'])){
   $message .= $lang_client_['pl_businesstype']['ADMIN_REGISTRATION_MESSAGE'];
 }
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
 $mail->From = $_POST['email'];
 $mail->FromName = $_POST['name'];
 $mail->AddAddress($admin_email);
 $mail->AddReplyTo($_POST['email']);
 $mail->Sender=$_POST['email'];
 $mail->IsHTML(true);
 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_client_['client_registration']['ADMIN_REGISTRATION_SUBJECT']);
 $mail->Body = $message;
  if($mail->Send()){
  }else{
	  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
  }
  $is_company = $_POST['is_company'] == 'private' ? 0 : 1;
  $enabled = 0;
  if(isset($registration_type) && $registration_type == 1) $enabled = 1;
  $record = 'name,enabled,is_company,lastname,tax_code,email,phone,fax,address,zipcode,city,userid,password';
  $val = "'".str_db($_POST['name'])."',";
  $val .= "'".$enabled."',";
  $val .= "'".$is_company."',";
  $val .= "'".($is_company ? '' : str_db($_POST['lastname']))."',";
  $val .= "'".($is_company ? str_db($_POST['tax_code']) : '')."',";
  $val .= "'".str_db($_POST['email'])."',";
  $val .= "'".str_db($_POST['phone'])."',";
  if(isset($_POST['fax']))
    $val .= "'".str_db($_POST['fax'])."',";
  else
    $val .= "'',";
  $val .= "'".str_db($_POST['address'])."',";
  $val .= "'".str_db($_POST['zipcode'])."',";
  $val .= "'".str_db($_POST['city'])."',";
  $val .= "'".str_db($_POST['userid'])."',";
  $val .= "'".encryption(str_db($_POST['password']))."'";

    $sql = " insert into ".$table_prefix."clients (";
    $sql .= $record;
    $sql .= ") VALUES (";
    $sql .=  $val;
    $sql .=  ")";
    execute($sql);
	$last_id = mysql_insert_id();
 /* SEND E-MAIL TO CLIENT */
 $message = $lang_client_['client_registration']['CLIENT_REGISTRATION_MESSAGE'];
 $message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$message);
 $message = str_replace('{link}',abs_client_path.'/register.php?cod='.mb_substr(encryption($last_id.$_POST['userid'].$_POST['email'].$_POST['name']),0,15),$message);
 $message = str_replace('{link_name}',abs_client_path.'/register.php?cod='.mb_substr(encryption($last_id.$_POST['userid'].$_POST['email'].$_POST['name']),0,15),$message);
  if(isset($registration_type)){
	 switch($registration_type){
		case 1:
		  $message = $lang_client_['client_registration']['CLIENT_IMMEDIATE_REGISTRATION_MESSAGE'];
		  $message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$message);
		break;
		case 2:
		  $message = $lang_client_['client_registration']['CLIENT_REGISTRATION_BY_ADMIN_MESSAGE'];
		  $message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$message);
		break;
	 }
  }
 if(plugin_exsists('businesstype') && isset($_POST['reseller'])){
   $message .= $lang_client_['pl_businesstype']['CLIENT_REGISTRATION_MESSAGE'];
 }
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
 $addresses = explode(',',$_POST['email']);
  foreach($addresses as $val){
    $mail->AddAddress($val);
  }
 $mail->AddReplyTo($admin_email);
 $mail->Sender=$admin_email;
 $mail->IsHTML(true);
 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_client_['client_registration']['CLIENT_REGISTRATION_SUBJECT']);
 $mail->Body = $message;
  if($mail->Send()){
  }else{
	  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
  }
?>