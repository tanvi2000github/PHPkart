<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('include/inc_load.php');
// devdaily.com paypal php ipn example.
// version 1.0
// this example built on the paypal php ipn example, with bug fixes,
// and no need for ssl.

// read the post from paypal and add 'cmd'
$req = 'cmd=_notify-validate';
$get_magic_quotes_exits = function_exists('get_magic_quotes_gpc') ? true : false;
$fsockopen_exits = function_exists("fsockopen") ? true : false;
$arr_result = array();
// handle escape characters, which depends on setting of magic quotes
foreach ($_POST as $key => $value){
  if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){
    $value = urlencode(stripslashes($value));
  }else{
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
  $arr_result[$key] = $value;
}

// post back to paypal to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$paypal_url = $paypal['sendbox'] ? 'www.sandbox.paypal.com' : 'www.paypal.com';
/*if($fsockopen_exits == true){
 if($paypal_ssl) $fp = fsockopen ('ssl://'.$paypal_url, 443, $errno, $errstr, 30);
 else $fp = fsockopen ($paypal_url, 80, $errno, $errstr, 30);
 if (!$fp) {
	 exit();
 }else{
	 fputs ($fp, $header . $req);
	  while (!feof($fp)) {
		  $res = fgets ($fp, 1024);
		  if (strcmp ($res, "VERIFIED") == 0) $verified = true;
		  if (strcmp ($res, "INVALID") == 0) $verified = false;
	  }
	  fclose($fp);
 }
}else{*/
	$url= 'https://'.$paypal_url.'/cgi-bin/webscr';
	$curl_result=$curl_err='';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
	curl_setopt($ch, CURLOPT_HEADER , 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$res = curl_exec($ch);
	$curl_err = curl_error($ch);
	curl_close($ch);
		  if (strcmp ($res, "VERIFIED") == 0){
		      $error = '';
			  if (mb_strtolower($_POST['receiver_email']) != $paypal['email'])  $error .= $lang_client_['ipn_paypal']['WRONG_RECEIVER_EMAIL'].'<br/>';
			  $sql = execute('select count(*) as count from '.$table_prefix.'orders where paypal_id_transaction = "'.str_db($_POST['txn_id']).'"');
			  $rs = mysql_fetch_array($sql);
			  if($rs['count'] > 0) $error .= $lang_client_['ipn_paypal']['TRANSACTION_ALREADY_PROCESSED'].'<br/>';
			  $sql = execute('select grandtotal from '.$table_prefix.'orders where code_order = "'.str_db($_POST['transaction_subject']).'"');
			  $rs = mysql_fetch_array($sql);
			  if ($_POST['mc_gross'] != @number_format($rs['grandtotal'],2,'.','')) $error .= $lang_client_['ipn_paypal']['WRONG_TRANSACTION_AMOUNT'];
			  if($error == ''){
				  $val = "paypal_array = '".serialize(str_serialize($arr_result))."',";
				  $val .= "paypal_status = '".str_db($_POST['payment_status'])."',";
				  if(mb_strtolower($_POST['payment_status']) == 'completed') $val .= "payed = 1,";
				  $val .= "paypal_id_transaction = '".str_db($_POST['txn_id'])."'";
				$sql = "update ".$table_prefix."orders set ".$val." where code_order = '".$_POST['transaction_subject']."'";
				 execute($sql);
				 if(plugin_exsists('dgoods')){
					 $sql_id_order = execute('select id from '.$table_prefix.'orders where code_order = "'.$_POST['transaction_subject'].'"');
					 $rs_id_order = mysql_fetch_array($sql_id_order);
					 require_once('include/lib/phpMailer/class.phpmailer.php');
					 execute('update '.$table_prefix.'customers_downloads set available = 1,expiration_date = "'.date("Y-m-d H:i:s").'" where id_order = '.$rs_id_order['id']);
						/* check if exist digital goods into order and get their array */
						$arr_ordered_digital_goods = array();
						$sql_p = execute('select * from '.$table_prefix.'customers_downloads where id_order = '.$rs_id_order['id']);
						while($rs_p = mysql_fetch_array($sql_p)){
						  $arr_ordered_digital_goods[] = array("id_client" => $rs_p['id_client'], "name" => $rs_p['file_name'],"d_code" => $rs_p['download_code'],"session_guest" => $rs_p['session_guest']);
						}
						 /* get client email */
						 $sql_c = execute('select billing_address,guest from '.$table_prefix.'orders where id = '.$rs_id_order['id']);
						 $rs_c = mysql_fetch_array($sql_c);
						 $email_c = unserialize($rs_c['billing_address']);
						 $email_c = $email_c['email'];
						 $link_list = '';
						 if($rs_c['guest']){
							 foreach($arr_ordered_digital_goods as $key => $val){
								$link_list .= $val['name'].' - <a href="'.abs_plugins_path.'/dgoods/download_purchase.php?dcode='.$val['d_code'].'&gsession='.$val['session_guest'].'" target="_blank">'.$lang_client_['pl_dgoods']['TABLE_CONTENT_DOWNLOAD_BUTTON'].'</a><br/><br/>';
							 }
							 $hour_exp_date = explode(' ',date('Y-m-d H:i:s'));
							 $message = str_replace('{digital_goods_list}',$link_list,$lang_client_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_FOR_REGULAR_CLIENTS']);
							 $message .= str_replace('{expiration_date}',view_date(date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') .' + '.$dgoodg_link_deadline.' days'))).' '.$hour_exp_date[1],$lang_client_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_EXPIRATION_FOR_GUESTS_CLIENTS']);
						 }else{
							 foreach($arr_ordered_digital_goods as $key => $val){
								$link_list .= $val['name'].' - <a href="'.abs_plugins_path.'/dgoods/download_purchase.php?cid='.$val['id_client'].'&dcode='.$val['d_code'].'" target="_blank">'.$lang_client_['pl_dgoods']['TABLE_CONTENT_DOWNLOAD_BUTTON'].'</a><br/><br/>';
							 }
							 $message = str_replace('{digital_goods_list}',$link_list,$lang_client_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_FOR_REGULAR_CLIENTS']);
						 }
						/* send email to client with link to download digital goods */
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
							 $mail->AddAddress($email_c);
							 $mail->AddReplyTo($admin_email);
							 $mail->Sender=$admin_email;
							 $mail->IsHTML(true);
							 $mail->Subject = str_replace('{shop_name}',$shop_title.'-',$lang_client_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_SUBJECT']);
							 $mail->Body = $message;
							  if($mail->Send()){
							  }else{
								  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
							  }
				 }
			  }
		  }
		  //if (strcmp ($res, "INVALID") == 0){

		  //}
//}
?>