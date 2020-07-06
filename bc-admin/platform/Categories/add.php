<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
 $error_alert = '';
/*
 in first time the script control if category exists
 if it exists so an error is generated,otherwise the script go on
*/ 
if($_POST['category_tree'] != '0'){
 $sql = "select * from ".$table_name." where name = '".str_db($_POST['category'])."' and tree_path ='".str_db($_POST['category_tree'])."'";
}else{
 $sql = "select * from ".$table_name." where name = '".str_db($_POST['category'])."' and level = 0";
}
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
 if($rs){
  $error_alert .= $lang_['categories']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
 $arr_tree = explode('|',$_POST['category_tree']);
 $parent = 0;
 if($_POST['category_tree'] != '0'){
  $level_tree = count($arr_tree);
  $parent = end($arr_tree);
 }else{
  $level_tree = 0;
 }
 if(isset($_POST['status']) &&  $_POST['status'] == '1'){
  $status = 1;
 }else{
  $status = 0;
 }
 if($_POST['category_tree'] != 0){
  $category_tree = $_POST['category_tree'];
 }else{
  $category_tree = '';
 }
  $mptt = new Zebra_Mptt();
  $get_id = $mptt->add($parent,
                       str_db($_POST['category']),
					   $category_tree,$level_tree,
					   str_db(strip_tags(preg_replace('/\s+/', ' ',$_POST['meta_description']))),
                       str_db(strip_tags(preg_replace('/\s+/', ' ',$_POST['meta_keywords']))),
					   $status);  
 /******* for rewrite system **********/
  $dir = $mptt->get_orizzontal_path($get_id, '/');
  $count_subdirectories = count(explode('/',$dir)); 
  if(!file_exists(path_rel_products.'/'.$dir)) Mkdir(path_rel_products.'/'.$dir, 0755, true);
    /**** write index file fot this category */
		  if(!file_exists(path_rel_products.'/'.$dir.'/index.php')){
				  $file = fopen(path_rel_products.'/'.$dir.'/index.php', "w+") or exit("Error!");				  
				  $control = 'require_once('.str_repeat('dirname(',$count_subdirectories+3).'(__FILE__)'.str_repeat(')',$count_subdirectories+3).'.\'/include/inc_load.php\');'."\n";					  
				  $control .= '$category_id = '.$get_id.';'."\n";		  
				  $control .= 'if(file_exists(\'inc_array_product.php\')){'."\n";
					 $control .= 'require_once(\'inc_array_product.php\');'."\n";
				  $control .= '}else{'."\n";
				  $control .= '$arr_container_products = array();'."\n";
				  $control .= '}'."\n";
				  $control .= 'require_once(theme_rel_path.\'/catalog.php\');'."\n";	
				  fwrite($file,'<'.'?php'."\n");	
				  fwrite($file,$control);			   
				  fwrite($file,'?'.'>');
				  fclose($file);
                  chmod(path_rel_products.'/'.$dir.'/index.php', 0755);				  
		  } 
   /**** / write index file fot this category */
 }
/**** update status for new category based on parent's status ***/ 
 if($mptt->get_parent($get_id)){  
   $sql_cat_parent_status = execute('select status from '.$table_name.' where id = '.$mptt->get_parent($get_id));
   $rs_cat_parent_status = mysql_fetch_array($sql_cat_parent_status);
   if($rs_cat_parent_status){
     $status = $rs_cat_parent_status['status'];
   }
   execute ('update '.$table_name.' set status = '.$status.' where id ='.$get_id);
 }
?>
