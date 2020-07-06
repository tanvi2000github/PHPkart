<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error = '';
 if($_POST['action'] == 'edit'){
	  echo 'true';
	  exit();
 }
 $user = isset($_POST['userid']) ? str_db(str_replace('"','&quot;',$_POST['userid'])) : '';
 $email = isset($_POST['email']) ? str_db(str_replace('"','&quot;',$_POST['email'])) : '';
 $where = $_POST['type'] == 'userid' ? 'userid = "'.$user.'"' : 'email = "'.$email.'"';
 $error_user = '';
 $error_mail = '';
 $sql = execute('select userid,email from '.$table_name.' where '.$where);
 $rs = mysql_fetch_array($sql);
 if($rs){
   echo 'false';
  }else{
    echo 'true';
  }
?>