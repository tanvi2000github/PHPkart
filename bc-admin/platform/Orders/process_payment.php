<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
$status = $_POST['status'] == 'ToProcess' ? 1 : 0;
execute('update '.$table_name.' set payed = '.$status.' where paypal_id_transaction = "" and id = '.$_POST['id']);
if(plugin_exsists('dgoods')){
  require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
  execute('update '.$table_prefix.'customers_downloads set available = '.($status ? 1 : 0).',expiration_date = '.($status ? '"'.date("Y-m-d H:i:s").'"' : '""').' where id_order = '.$_POST['id']);
  if($status){
	/* check if exist digital goods into order and get their array */
	$arr_ordered_digital_goods = array();
	$sql_p = execute('select * from '.$table_prefix.'customers_downloads where id_order = '.$_POST['id']);
	while($rs_p = mysql_fetch_array($sql_p)){
	  $arr_ordered_digital_goods[] = array("id_client" => $rs_p['id_client'], "name" => $rs_p['file_name'],"d_code" => $rs_p['download_code'],"session_guest" => $rs_p['session_guest']);
	}
	 /* get client email */
	 $sql_c = execute('select billing_address,guest from '.$table_prefix.'orders where id = '.$_POST['id']);
	 $rs_c = mysql_fetch_array($sql_c);
	 $email_c = unserialize($rs_c['billing_address']);
	 $email_c = $email_c['email'];
	 $link_list = '';
	 if($rs_c['guest']){
		 foreach($arr_ordered_digital_goods as $key => $val){
			$link_list .= $val['name'].' - <a href="'.abs_plugins_path.'/dgoods/download_purchase.php?dcode='.$val['d_code'].'&gsession='.$val['session_guest'].'" target="_blank">'.$lang_['pl_dgoods']['DOWNLOAD_LINK'].'</a><br/><br/>';
		 }
		 $hour_exp_date = explode(' ',date('Y-m-d H:i:s'));
		 $message = str_replace('{digital_goods_list}',$link_list,$lang_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_FOR_REGULAR_CLIENTS']);
		 $message .= str_replace('{expiration_date}',view_date(date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') .' + '.$dgoodg_link_deadline.' days'))).' '.$hour_exp_date[1],$lang_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_EXPIRATION_FOR_GUESTS_CLIENTS']);
	 }else{
		 foreach($arr_ordered_digital_goods as $key => $val){
			$link_list .= $val['name'].' - <a href="'.abs_plugins_path.'/dgoods/download_purchase.php?cid='.$val['id_client'].'&dcode='.$val['d_code'].'" target="_blank">'.$lang_['pl_dgoods']['DOWNLOAD_LINK'].'</a><br/><br/>';
		 }
		 $message = str_replace('{digital_goods_list}',$link_list,$lang_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_FOR_REGULAR_CLIENTS']);
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
		 $mail->Subject = str_replace('{shop_name}',$shop_title.'-',$lang_['pl_dgoods']['EMAIL_DOWNLOAD_LINK_D_GOODS_SUBJECT']);
		 $mail->Body = $message;
		  if($mail->Send()){
		  }else{
			  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
		  }
  }
}
?>