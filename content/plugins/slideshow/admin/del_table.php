<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
if($_POST['type'] != 'unique'){
   $box = implode(',',$_POST['box_delete']); 
}else{  
  $box = $_POST['id'];
}
  execute('delete from '.$table_prefix.'slideshow where id in ('.$box.')');
  if($_POST['type'] != 'unique'){
	 $del_dir = $_POST['box_delete']; 
	 foreach($del_dir as $key){
	  deleteDirectory(rel_uploads_path.'/slideshow/'.$key);
	 }					 
  }else{  
	$del_dir = $_POST['id'];
	deleteDirectory(rel_uploads_path.'/slideshow/'.$del_dir);
  }	
?>
