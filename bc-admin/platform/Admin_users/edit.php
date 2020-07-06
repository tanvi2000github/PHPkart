<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error_alert = ''; 
/*
 in first time the script control if userid exists and if it has a different id by request
 if it exists so an error is generated,otherwise the script go on
*/  
$sql = "select userid from ".$table_name." where userid = '".str_db($_POST['userid'])."' and id <> '".$_POST['id']."'";
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
 if($rs){
  $error_alert .= $lang_['admin_accounts']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
 
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{ 
  $val = "userid = '".str_db($_POST['userid'])."',";
  if(str_db($_POST['password']) != str_db($_POST['old_password'])){ 
    $val .= "password = '".encryption(str_db($_POST['password']))."',";
  }   
  $val .= "name = '".str_db($_POST['name'])."'";
  $sql = "update ".$table_name." set ".$val." where id = '".$_POST['id']."'";
   execute($sql);
 }
?> 