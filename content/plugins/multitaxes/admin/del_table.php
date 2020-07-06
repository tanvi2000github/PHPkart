<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
  /* update taxes into product table */  
  $sql_p = execute('select id,pl_multitax from '.$table_prefix.'products');
  while($rs_p = mysql_fetch_array($sql_p)){
	  $new_multitax = array_remove_item(explode(',',$rs_p['pl_multitax']),$_POST['id']);
	  execute('update '.$table_prefix.'products set pl_multitax = "'.implode(',',$new_multitax).'" where id ='.$rs_p['id']);
  }
  execute('delete from '.$table_prefix.'taxes where id ='.$_POST['id']);  
?>
