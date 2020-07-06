<?php
require_once('include/inc_load.php');
 $error = '';
 if(!isset($_POST['guest'])){
	$user = isset($_POST['userid']) ? str_db(str_replace('"','&quot;',$_POST['userid'])) : ''; 
	$email = isset($_POST['email']) ? str_db(str_replace('"','&quot;',$_POST['email'])) : '';
	$where = $_POST['type'] == 'userid' ? 'userid = "'.$user.'"' : 'email = "'.$email.'"';
 }elseif(isset($_POST['guest']) && $_POST['guest'] == 'false'){
	$email = isset($_POST['email']) ? str_db(str_replace('"','&quot;',$_POST['email'])) : '';
	$userreg = isset($_POST['useridreg']) ? str_db(str_replace('"','&quot;',$_POST['useridreg'])) : ''; 
	$where = $_POST['type'] == 'useridreg' ? 'userid = "'.$userreg.'"' : 'email = "'.$email.'"';
 }else{
	die('true'); 
 }
 $sql = execute('select userid,email from '.$table_prefix.'clients where '.$where);
 $rs = mysql_fetch_array($sql);
 if($rs){
   echo 'false';
  }else{
    echo 'true';
  }
?>