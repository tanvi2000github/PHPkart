<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
$status = $_POST['status'] == 'ToProcess' ? 1 : 0;
if($status){
	$carrier = $_POST['carrier'];
	$tracking_carrier = $_POST['tracking_carrier'];
	$link_carrier = $_POST['link_carrier'];
	$process_date = $_POST['process_date'] != '' ? conv_date_db($_POST['process_date']).' '.date("H:i:s") : date("Y-m-d H:i:s");
 if(isset($_POST['send_client_email'])){
	   $sql_or = execute('select * from '.$table_name.' where id = '.$_POST['id']);
	   $rs_or = mysql_fetch_array($sql_or);
	   $arr_shipping_address = unserialize($rs_or['shipping_address']);
	   $arr_billing_address = unserialize($rs_or['billing_address']);
	   $email_address = $arr_shipping_address['email'];
	   if($arr_shipping_address['email'] != $arr_billing_address['email']) $email_address .= ','.$arr_billing_address['email'];
	   $message = str_replace('{processing_date}',view_date($process_date),str_replace('{order_code}',$rs_or['code_order'],$lang_['orders']['EMAIL_PROCESSING']));
	   if($carrier != '')
	   $message .= '<br/><br/>'.$lang_['orders']['EMAIL_CARRIER_INFO'].': <strong>'.$carrier.'</strong>';
	   if($tracking_carrier != '')
	   $message .= '<br/><br/>'.$lang_['orders']['EMAIL_TRACKING_INFO'].': <strong>'.$tracking_carrier.'</strong>';
	   if($link_carrier != '')
	   $message .= '<br/><br/>'.$lang_['orders']['EMAIL_TRACKING_LINK_INFO'].': <strong><a href="'.$link_carrier.'">'.$link_carrier.'</a></strong>';
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
		  $addresses = explode(',',$email_address);
			foreach($addresses as $val){
			  $mail->AddAddress($val);
			}
		 $mail->AddReplyTo($admin_email);
		 $mail->Sender=$admin_email;
		 $mail->IsHTML(true);
		 $mail->Subject = str_replace('{order_code}',$rs_or['code_order'],$lang_['orders']['EMAIL_PROCESSED_ORDER_SUBJECT']);
		 $mail->Body = $message;
		  if($mail->Send()){
		  }else{
			  echo "Message was not sent <br />PHP Mailer Error: " . $mail->ErrorInfo;
		  }
 }
}else{
	$carrier = '';
	$tracking_carrier = '';
	$link_carrier = '';
	$process_date = '';
}
execute ('update '.$table_name.' set processed = '.$status.',carrier = "'.$carrier.'",carrier_tracking = "'.$link_carrier.'",carrier_link = "'.$link_carrier.'",process_date = "'.$process_date.'" where id = '.$_POST['id']);
?>