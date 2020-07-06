<?php
/*
This files provide to control if a specific userid is in database
and if he/they can do login into sistem
 -----------------------------------
 ------- NO CHANGE IT PLEASE -------
 -----------------------------------
*/ 
require_once('include/inc_load.php');
 @session_start();
 $user = md5(str_db($_POST['useridLog']));
 $password = encryption(str_db($_POST['passwordLog']));
 $sql = "select * from ".$table_prefix."clients where md5(userid) = '".$user."' and password = '".$password."'";
 $rs_result = execute($sql);
 $result = 'not_logged';
 while ($rs = mysql_fetch_assoc($rs_result)) {
  if($rs['enabled']){
     $_SESSION['Clogged'] = true; 
	   foreach($rs as $key => $val){
		 $_SESSION['C'.$key] = $rs[$key];  
	   }      
     $result = 'logged';	 
	 execute('update '.$table_prefix.'cart set id_client = '.$rs['id'].',date = "'.date("Y-m-d H:i:s").'" where session_client = "'.get_initial_user_session().'"');	 
  }else{
	$result = 'need_confirmation';  
  }
 }
 echo $result;
?>