<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
if($_POST['type'] == 'unique'){
 $box = explode(',',$_POST['id']);
}else{
 $box = $_POST['box_delete'];  
} 

$category_id_deleted = array();
  foreach($box as $cat){
	  $category_id_deleted[] = $cat;
	  foreach($mptt->get_children($cat) as $key => $val){
	     $category_id_deleted[] = $val['id']; 
	  }
	 $dir_start = $mptt->get_orizzontal_path($cat, '/');
	 /********* for rewrite system ******/
     if(file_exists(path_rel_products.'/'.$dir_start.'/inc_array_product.php')){
		require_once(path_rel_products.'/'.$dir_start.'/inc_array_product.php');
	    $arr_product_new = $arr_container_products;	  			  	  
		/* change array products for each roort directory of this category */
		foreach($mptt->get_path($cat) as $key => $val){
		   if($val['id'] != $cat){
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
	 }
    if(file_exists(path_rel_products.'/'.$dir_start) && $dir_start != '') deleteDirectory(path_rel_products.'/'.$dir_start);
    /* delete from database */	   
  } 
  foreach($box as $cat){
	  $mptt->delete($cat); 	  
  }
  /* update products table */
  execute('update '.$table_prefix.'products set visible = 0,categories = "" where categories in ('.implode(',',array_unique($category_id_deleted)).')');
/************************************/  
?>
