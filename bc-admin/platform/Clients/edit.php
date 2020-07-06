<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
 $error_alert = '';
/*
 in first time the script control if userid exists and if it has a different id by request
 if it exists so an error is generated,otherwise the script go on
*/
$sql = execute("select count(userid) as userid from ".$table_name." where userid = '".str_db(str_replace('"','&quot;',$_POST['userid']))."' and id <> '".$_POST['id']."'");
$rs = mysql_fetch_array($sql);
 if($rs['userid'] > 0){
  $error_alert .= $lang_['clients_accounts']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
$sql = execute("select count(email) as email from ".$table_name." where email = '".str_db(str_replace('"','&quot;',$_POST['email']))."' and id <> '".$_POST['id']."'");
$rs = mysql_fetch_array($sql);
 if($rs['email'] > 0){
  $error_alert .= $lang_['clients_accounts']['INSERT_UPDATE_DUPLICATE_EMAIL_ERROR'].'<br/>';
 }

 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
  if(plugin_exsists('businesstype') && get_admin_business_bc()){
	    $message = '';
		$retailer = '';
		$retailer_denied = '';
		$retailer_denied_message = '';
	if(isset($_POST['enable_resell_request'])){
		$retailer = '1';
		$message = $lang_['pl_businesstype']['EMAIL_ENABLE_RETAILER_REQUEST_SUCCESS'];
		$message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$message);
		$message = str_replace('{shop_url}',$shop_url,$message);
		$subject = $lang_['pl_businesstype']['SUBJECT_EMAIL_ENABLE_RETAILER_REQUEST_SUCCESS'];
	}
	if(isset($_POST['denied_resell_request'])){
		$retailer_denied = '1';
		$retailer = '1';
		$retailer_denied_message = str_db_content($_POST['message_resell_request_denied']);
		if(isset($_POST['denied_message_to_client'])){
			$message = $_POST['message_resell_request_denied'];
			$subject = $lang_['pl_businesstype']['SUBJECT_EMAIL_DENIED_RETAILER_REQUEST'];
		}
	}
	if(isset($_POST['rehabilitation_resell_request'])){
		$retailer_denied = '0';
		if(isset($_POST['denied_message_to_client'])){
			$message = $_POST['message_resell_request_rehabilitation'];
			$subject = $lang_['pl_businesstype']['SUBJECT_EMAIL_REHABILITATION_RETAILER_REQUEST'];
		}
	}
	if($message != ''){
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
		 $mail->AddAddress($_POST['email']);
		 $mail->AddReplyTo($admin_email);
		 $mail->Sender=$admin_email;
		 $mail->IsHTML(true);
		 $mail->Subject = $subject;
		 $mail->Body = $message;
		  if($mail->Send()){
		  }else{
			  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
		  }
	}
  }
  $is_company = $_POST['is_company'] == 'private' ? 0 : 1;
  $enabled = isset($_POST['enable']) ? 1 : 0;
	if(isset($registration_type) && $registration_type == 2 && $enabled == 1){
	/* get client data*/
	  $sql = execute('select enabled from '.$table_name.' where id = '.$_POST['id']);
	  $rs = mysql_fetch_array($sql);
	   if(!$rs['enabled']){
		   /* SEND E-MAIL TO CLIENT */
		   $message = $lang_['clients_accounts']['CLIENT_REGISTRATION_MESSAGE'];
		   $message = str_replace('{client_name}',ucwords($_POST['name'].' '.$_POST['lastname']),$message);
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
		   $addresses = explode(',',$_POST['email']);
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
  $val = "name = '".str_db($_POST['name'])."',";
  if(plugin_exsists('businesstype') && get_admin_business_bc()){
	if($retailer != ''){
		$val .= "retailer = '".$retailer."',";
	}
	if($retailer_denied != ''){
		$val .= "retailer_denied = '".$retailer_denied."',";
	}
	if($retailer_denied_message != ''){
		$val .= "retailer_denied_message = '".$retailer_denied_message."',";
	}
  }
  $val .= "is_company = '".$is_company."',";
  $val .= "enabled = ".$enabled.",";
  $val .= "lastname = '".($is_company ? '' : str_db($_POST['lastname']))."',";
  $val .= "tax_code = '".($is_company ? str_db($_POST['tax_code']) : '')."',";
  $val .= "email = '".str_db($_POST['email'])."',";
  $val .= "phone = '".str_db($_POST['phone'])."',";
  if(isset($_POST['fax']))
    $val .= "fax = '".str_db($_POST['fax'])."',";
  else
    $val .= "'',";
  $val .= "address = '".str_db($_POST['address'])."',";
  $val .= "zipcode = '".str_db($_POST['zipcode'])."',";
  $val .= "city = '".str_db($_POST['city'])."',";
  if(str_db($_POST['password']) != str_db($_POST['old_password'])){
    $val .= "password = '".encryption(str_db($_POST['password']))."',";
  }
  $val .= "userid = '".str_db($_POST['userid'])."'";
   execute("update ".$table_name." set ".$val." where id = '".$_POST['id']."'");
 }
?>