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
 $sql = "select * from ".$table_prefix."admin_accounts where (md5(userid) = '".$user."') and (password = '".$password."')";
 $rs_result = execute($sql);
 $result = 'not_logged';
 while ($rs = mysql_fetch_array($rs_result)) {
     $_SESSION['Alogged'] = true; 
	 $_SESSION['Aid'] = $rs['id']; 
	 $_SESSION['Aname'] = $rs['name'];     
     $result = 'logged';
 }
 echo $result;
?>