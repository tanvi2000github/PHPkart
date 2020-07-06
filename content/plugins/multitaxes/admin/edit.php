<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error_alert = ''; 
/*
 in first time the script control if userid exists and if it has a different id by request
 if it exists so an error is generated,otherwise the script go on
*/  
$sql = "select name from ".$table_name." where name = '".str_db($_POST['name'])."' and id <> '".$_POST['id']."'";
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
 if($rs){
  $error_alert .= $lang_['pl_multitax']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
 
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{ 
  $val = "name = '".str_db($_POST['name'])."',"; 
  $val .= "percentage = '".str_db($_POST['percentage'])."'";
  $sql = "update ".$table_name." set ".$val." where id = '".$_POST['id']."'";
   execute($sql);
 }
?> 