<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
$status = $_POST['status'] == 'ToProcess' ? 1 : 0;
	  $category_ids[] = $_POST['id'];
	  foreach($mptt->get_children($_POST['id']) as $key => $val){
	     $category_ids[] = $val['id']; 
	  }
if(!empty($category_ids)){	  
  execute ('update '.$table_name.' set status = '.$status.' where id in ('.implode(',',array_unique($category_ids)).')');
  /* update products table */
  execute('update '.$table_prefix.'products set visible = '.$status.' where categories in ('.implode(',',array_unique($category_ids)).')');
}
?>