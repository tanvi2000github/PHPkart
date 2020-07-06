<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');

 $error_alert = '';
/*
 in first time the script control if userid exists and if it has a different id by request
 if it exists so an error is generated,otherwise the script go on
*/
if($_POST['category_tree'] != '0'){
 $sql = "select * from ".$table_name." where name = '".str_db($_POST['category'])."' and tree_path ='".str_db($_POST['category_tree'])."' and id <> '".$_POST['id']."'";
}else{
 $sql = "select * from ".$table_name." where name = '".str_db($_POST['category'])."' and level = 0 and id <> '".$_POST['id']."'";
}
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);

 if($rs[0]){
  $error_alert .= $lang_['categories']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }

if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
 $arr_tree = explode('|',$_POST['category_tree']);
 if($_POST['category_tree'] != '0'){
  $level_tree = count($arr_tree);
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
 $result = execute('select * from '.$table_name.' where id <> '.$_POST['id'].' and level > '.$_POST['level']);
 while ($rs = mysql_fetch_array($result)){
   $this_tree = $rs['tree_path'];
   $arr_this_tree = explode('|',$this_tree);
   if(in_array($_POST['id'],$arr_this_tree)){
	   $position_in_array = array_search($_POST['id'],$arr_this_tree)+1;
	   $arr_slice = implode('|',array_slice($arr_this_tree, $position_in_array));
	   if($arr_slice != ''){
		  $new_this_tree = $category_tree != '' ? $category_tree.'|'.$_POST['id'].'|'.$arr_slice : $_POST['id'].'|'.$arr_slice;
	   }else{
		  $new_this_tree = $category_tree != '' ? $category_tree.'|'.$_POST['id'] : $_POST['id'];
	   }
	   execute('update '.$table_prefix.'categories set tree_path = "'.str_db($new_this_tree).'", level = '.count(explode('|',$new_this_tree)).' where id = '.$rs['id']);
   }
 }
$mptt = new Zebra_Mptt();
$old_tree = $mptt->get_orizzontal_path($_POST['id'], '/');
$old_path = $mptt->get_path($_POST['id'], '/');
/***************************************/
  $val = "name = '".str_db($_POST['category'])."',";
  $val .= "tree_path = '".str_db($category_tree)."',";
  $val .= "meta_keywords = '".str_db($_POST['meta_keywords'])."',";
  $val .= "meta_description = '".str_db(strip_tags(preg_replace('/\s+/', ' ',$_POST['meta_description'])))."',";
  $val .= "level = '".str_db($level_tree)."',";
  $val .= "status = '".str_db($status)."'";
  $sql = "update ".$table_name." set ".$val." where id = '".$_POST['id']."'";
  execute($sql);
/*******************************************/
$arr_tree = explode('|',$_POST['category_tree']);
$mptt->move($_POST['id'], end($arr_tree));
/****** get new category data ****/
$mptt = new Zebra_Mptt();
$new_tree = $mptt->get_orizzontal_path($_POST['id'], '/');
/**** update status for new category and its products ***/
	  foreach($mptt->get_children($_POST['id']) as $key => $val){
	     $category_ids[] = $val['id'];
	  }
  if(!empty($category_ids)){
   execute ('update '.$table_name.' set status = '.$status.' where id in ('.implode(',',array_unique($category_ids)).')');
   execute('update '.$table_prefix.'products set visible = '.$status.' where categories in ('.implode(',',array_unique($category_ids)).')');
  }
 /******* for rewrite system **********/
 if($old_tree != $new_tree){
   rename(path_rel_products.'/'.$old_tree, path_rel_products.'/'.$new_tree);
 /**** overwrite 2d line of index file */
  $dir = $mptt->get_orizzontal_path($_POST['id'], '/');

  $count_subdirectories = count(explode('/',$dir));

   /**** / overwrite 2d line of index file */
   /**** overwrite 2d line of each product file */
	function edit_files_dir($dir,$count_subdirectories)
	{
		$count_dir = 0;
			if(file_exists($dir.'/index.php'))
			{
				  $file_to_open = file($dir.'/index.php');
				  $handle = fopen($dir.'/index.php', 'w');
				  foreach( $file_to_open as $line ) {
					 if($file_to_open[1] == $line)
					  fwrite($handle,'require_once('.str_repeat('dirname(',$count_subdirectories+3).'(__FILE__)'.str_repeat(')',$count_subdirectories+3).'.\'/include/inc_load.php\');'."\n");
					 else
					  fwrite($handle, $line);
				  }
				  fclose($handle);
				 chmod($dir.'/index.php', 0755);

			}

	    if ($directory_handle = opendir($dir)) {
	        while (($file = readdir($directory_handle)) !== false) {

		            if((!is_dir($dir.'/'.$file))&($file!=".")&($file!="..")&($file!="index.php")&($file!="inc_array_product.php")){
					  $file_to_open = file($dir.'/'.$file);
					  $handle = fopen($dir.'/'.$file, 'w');
					  foreach( $file_to_open as $line ) {
						 if($file_to_open[1] == $line)
						  fwrite($handle,'require_once('.str_repeat('dirname(',$count_subdirectories+3).'(__FILE__)'.str_repeat(')',$count_subdirectories+3).'.\'/include/inc_load.php\');'."\n");
						 else
						  fwrite($handle, $line);
					  }
					  fclose($handle);
					 chmod($dir.'/'.$file, 0755);
					}
		        	else
		        	{
		        		if((is_dir($dir.'/'.$file))&($file!=".")&($file!=".."))
		        		{
		        			if($count_dir == 0)
		        			{
		        				$count_dir++;
			        			$count_subdirectories++;
		        			}
			        		edit_files_dir($dir.'/'.$file,$count_subdirectories);
		        		}
		        	}
	        }
	        closedir($directory_handle);
	    }
	}

	edit_files_dir(path_rel_products.'/'.$dir,$count_subdirectories);
    /*if ($directory_handle = opendir(path_rel_products.'/'.$dir)) {
        while (($file = readdir($directory_handle)) !== false) {
            if((!is_dir($file))&($file!=".")&($file!="..")&($file!="index.php")&($file!="inc_array_product.php")){
			  $file_to_open = file(path_rel_products.'/'.$dir.'/'.$file);
			  $handle = fopen(path_rel_products.'/'.$dir.'/'.$file, 'w');
			  foreach( $file_to_open as $line ) {
				 if($file_to_open[1] == $line)
				  fwrite($handle,'require_once('.str_repeat('dirname(',$count_subdirectories+3).'(__FILE__)'.str_repeat(')',$count_subdirectories+3).'.\'/include/inc_load.php\');'."\n");
				 else
				  fwrite($handle, $line);
			  }
			  fclose($handle);
			 chmod(path_rel_products.'/'.$dir.'/'.$file, 0755);
			}
        }
        closedir($directory_handle);
    }*/
   /**** overwrite 2d line of each product file */
     if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
		 require_once(path_rel_products.'/'.$dir.'/inc_array_product.php');
		   $arr_product_new = $arr_container_products;
   /** rewrite array products for each roort directory of old category **/
			  foreach($old_path as $key => $val){
				 if($val['id'] != $_POST['id']){
					  $dir = $mptt->get_orizzontal_path($val['id'], '/');
					  if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
						  require(path_rel_products.'/'.$dir.'/inc_array_product.php');
						  $arr_container_products = array_diff($arr_container_products,$arr_product_new);
					  }else{
						  $arr_container_products = $array();
					  }
						  $file = fopen(path_rel_products.'/'.$dir.'/inc_array_product.php', "w+") or exit("Error!");
						  fwrite($file,'<'.'?php'."\n");
						  fwrite($file,'$arr_container_products = array('.implode(',',$arr_container_products).');'."\n");
						  fwrite($file,'?'.'>');
						  fclose($file);
						  chmod(path_rel_products.'/'.$dir.'/inc_array_product.php', 0755);
				 }
			  }
    /** / rewrite array products for each roort rectory of this category **/
	/** rewrite array products for each roort directory of this new category **/
			  foreach($mptt->get_path($_POST['id']) as $key => $val){
				 if($val['id'] != $_POST['id']){
					  $dir = $mptt->get_orizzontal_path($val['id'], '/');
					  if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
						  require(path_rel_products.'/'.$dir.'/inc_array_product.php');
						  $arr_container_products = array_unique(array_merge($arr_container_products,$arr_product_new));
						  unlink(path_rel_products.'/'.$dir.'/inc_array_product.php');
					  }else{
						  $arr_container_products = $arr_product_new;
					  }
						  $file = fopen(path_rel_products.'/'.$dir.'/inc_array_product.php', "w+") or exit("Error!");
						  fwrite($file,'<'.'?php'."\n");
						  fwrite($file,'$arr_container_products = array('.implode(',',$arr_container_products).');'."\n");
						  fwrite($file,'?'.'>');
						  fclose($file);
						  chmod(path_rel_products.'/'.$dir.'/inc_array_product.php', 0755);
				 }
			  }
   /** / rewrite array products for each roort directory of this category **/
	 }
 }
/***************************************************/
}
?>