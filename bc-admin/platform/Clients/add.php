<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error_alert = '';
/*
 in first time the script control if userid exists
 if it exists so an error is generated,otherwise the script go on
*/ 
$sql = execute("select count(userid) as userid from ".$table_name." where userid = '".str_db(str_replace('"','&quot;',$_POST['userid']))."'");
$rs = mysql_fetch_array($sql);
 if($rs['userid'] > 0){
  $error_alert .= $lang_['clients_accounts']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
$sql = execute("select count(email) as email from ".$table_name." where email = '".str_db(str_replace('"','&quot;',$_POST['email']))."'");
$rs = mysql_fetch_array($sql); 
 if($rs['email'] > 0){
  $error_alert .= $lang_['clients_accounts']['INSERT_UPDATE_DUPLICATE_EMAIL_ERROR'].'<br/>';
 } 
 
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
  $is_company = $_POST['is_company'] == 'private' ? 0 : 1;
  $enabled = isset($_POST['enable']) ? 1 : 0;
  $record = 'name,';
   if(plugin_exsists('businesstype') && get_admin_business_bc()){  
      $record .= 'retailer,retailer_request,';
   }
  $record .= 'is_company,enabled,lastname,tax_code,email,phone,fax,address,zipcode,city,userid,password';
  $val = "'".str_db($_POST['name'])."',";
   if(plugin_exsists('businesstype') && get_admin_business_bc()){  
      $val .= "'".(isset($_POST['enable_resell_request']) ? 1 : 0)."',";
	  $val .= "'".(isset($_POST['enable_resell_request']) ? 1 : 0)."',";
   }  
  $val .= "'".$is_company."',";
  $val .= "'".$enabled."',";
  $val .= "'".($is_company ? '' : str_db($_POST['lastname']))."',";  
  $val .= "'".($is_company ? str_db($_POST['tax_code']) : '')."',";
  $val .= "'".str_db($_POST['email'])."',";
  $val .= "'".str_db($_POST['phone'])."',";
  if(isset($_POST['fax']))
    $val .= "'".str_db($_POST['fax'])."',";
  else
    $val .= "'',";
  $val .= "'".str_db($_POST['address'])."',";
  $val .= "'".str_db($_POST['zipcode'])."',";
  $val .= "'".str_db($_POST['city'])."',";
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
