<?php
 require_once('../../include/inc_load.php');
 require_once(rel_admin_path.'/control_login.php');
 require('general_tags.php');
if($_POST['type'] == 'system'){
 if($_POST['smtp_email'] == '1' &&
     ($_POST['smtp_host'] != $smtp_host ||
	  $_POST['smtp_user'] != $smtp_user) ||
	  $_POST['smtp_password'] != $smtp_password ||
	  $_POST['smtp_port'] != $smtp_port
	){
				  require_once(rel_client_path.'/include/lib/phpMailer/class.phpmailer.php');
				  $mail = new PHPMailer();
				  $mail->IsSMTP();
				  $mail->Host = $_POST['smtp_host'];
				  $mail->SMTPAuth = true;
				  $mail->Username = $_POST['smtp_user'];
				  $mail->Password = $_POST['smtp_password'];
				  $mail->Port = $_POST['smtp_port'];
				  $mail->From = $_POST['admin_email'];
				  $mail->FromName = "BootCommerce SMTP test";
				  $mail->SMTPSecure = $_POST['smtp_secure'];
				  $mail->AddAddress($_POST['admin_email'], "Test");
				  $mail->AddReplyTo($_POST['admin_email'], "BootCommerce SMTP test");
				  $mail->WordWrap = 50;
				  $mail->IsHTML(false);
				  $mail->Subject = "AuthSMTP Test from BootComemrce";
				  $mail->Body    = "This message is used to test the parameters of the SMTP server!   everything went well!";
				  if(!$mail->Send()){
					  die('<div class="error_alert">Message could not be sent to test SMTP.<br/>'.$mail->ErrorInfo.'</div>');
                  }
 }
}
 $array_excluded_fields = array('upimg_logo_header','type','upimg_logo_footer');
 foreach($_POST as $key => $val){
	if(!in_array($key,$array_excluded_fields)){
		$array_settings[$key] = str_serialize(($val));
	}
 }
 $result = execute('select * from '.$table_name);
 $rs = mysql_fetch_array($result);
 if($rs){
   execute("update ".$table_name." set ".$_POST['type']." = '".serialize($array_settings)."'");
 }else{
   execute("insert into ".$table_name." (".$_POST['type'].") values ('".serialize($array_settings)."')");
 }
/* UPLOAD LOGOS */
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
 $upload_dir = rel_uploads_path;
 $allowed_types = array ("jpg","png","jpeg");
  foreach($_FILES as $key => $val){
	if(!empty($_FILES[$key]["name"])){
	  $new_name = $key == 'upimg_logo_header' ? 'bc_logo' : 'bc_logo_footer';
	  $ext = explode('.',$_FILES[$key]["name"]);
	  $file_name = $new_name.'.'.mb_strtolower(end($ext));
	  if(in_array(mb_strtolower(end($ext)),$allowed_types) && @is_uploaded_file($_FILES[$key]["tmp_name"]) && !empty($_FILES[$key]["tmp_name"])){
		  move_uploaded_file ( $_FILES[$key]["tmp_name"] , $upload_dir.'/'.$new_name.'.png' );
	  }
	}
  }
?>