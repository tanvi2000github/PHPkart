<?php 
@session_start();
if(!isset($_SESSION['Alogged'])){
header('location:'.abs_admin_path.'/login.php');
exit(); 
}else{
 if(IsSet($_SESSION['Aid'])){
   $sql = "select * from ".$table_prefix."admin_accounts where id = '".$_SESSION['Aid']."'";
   $rs_result = execute($sql);
   $rs = mysql_fetch_array($rs_result); 
   if($rs){ 
     define('Alogged',true);
   }else{
     define('Alogged',false);
   }
  }else{
    define('Alogged',false);
  }
}
?>