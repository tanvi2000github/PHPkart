<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error_alert = '';
/*
 in first time the script control if userid exists
 if it exists so an error is generated,otherwise the script go on
*/ 
$sql = "select userid from ".$table_name." where userid = '".str_db($_POST['userid'])."'";
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
 if($rs){
  $error_alert .= $lang_['admin_accounts']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
 
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
  $record = 'name,userid,password';
  $val = "'".str_db($_POST['name'])."',";
  $val .= "'".str_db($_POST['userid'])."',";
  $val .= "'".encryption(str_db($_POST['password']))."'";
  
    $sql = " insert into ".$table_name." (";
    $sql .= $record;
    $sql .= ") VALUES (";
    $sql .=  $val;
    $sql .=  ")";  
    execute($sql);
 }
?>
