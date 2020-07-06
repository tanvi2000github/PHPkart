<?php
/*
 This file provide to delete selected record on table from database
 -----------------------------------
 ------- NO CHANGE IT PLEASE -------
 ----------------------------------- 
*/
require_once('include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();

$table = $_POST['tb'];
if($_POST['type'] != 'unique'){
   $box = implode(',',$_POST['box_delete']); 
}else{  
  $box = $_POST['id'];
}
/****** functions to execute other actions on not default table *********/
		   switch($table){
			   case $table_prefix.'admin_accounts':
				  execute('delete from '.$table.' where super_admin = 0 and id IN ('.$box.')');		
				  exit();
			   break;			   
			   case $table_prefix.'products':
			    $res_p = execute('select name,categories,id  from '.$table.' where id IN ('.$box.')');
				while($rs_p = mysql_fetch_array($res_p)){
				 if($rs_p['categories'] != ''){
					 $arr_cat[$rs_p['categories']][] = $rs_p['id'];
				 }
				  unlink(path_rel_products.'/'.$mptt->get_orizzontal_path($rs_p['categories'], '/').'/'.$rs_p['id'].'-'.filesystem($rs_p['name']).'.php');
				  unlink(path_rel_products.'/'.$rs_p['id'].'-'.filesystem($rs_p['name']).'.php'); 
				}
				/* change array products for each roort directory of this product categoriy */
				 foreach($arr_cat as $key_c => $val_p){
					   foreach($mptt->get_path($key_c) as $key => $val){
							  $dir = $mptt->get_orizzontal_path($val['id'], '/');
							if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
								require(path_rel_products.'/'.$dir.'/inc_array_product.php');
							  foreach($val_p as $id_p){
								$arr_container_products = array_remove_item($arr_container_products,$id_p);							  
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
				  if($_POST['type'] != 'unique'){
					 $del_dir = $_POST['box_delete']; 
					 foreach($del_dir as $key){
					  deleteDirectory(path_rel_img_products.'/'.$key);
					  if(plugin_exsists('dgoods')){
						 deleteDirectory(rel_uploads_path.'/digital_goods/'.$key);
					  }
					 }					 
				  }else{  
					$del_dir = $_POST['id'];
					deleteDirectory(path_rel_img_products.'/'.$del_dir);
					  if(plugin_exsists('dgoods')){
						 deleteDirectory(rel_uploads_path.'/digital_goods/'.$del_dir);
					  }					
				  }		
				  execute('delete from '.$table_prefix.'products_attributes where id_product IN ('.$box.')');	   			     
			   break;
			   case $table_prefix.'orders':
			       $arr_product_ordered_qta = array();
				  $sql = execute('select * from '.$table.' where id IN ('.$box.')');
				  while($rs = mysql_fetch_array($sql)){
					  $arr_ordered_products = unserialize($rs['products_list']);
					foreach($arr_ordered_products as $key => $val){
					   if(array_key_exists($val['id'],$arr_product_ordered_qta)) $arr_product_ordered_qta[$val['id']] = ($arr_product_ordered_qta[$val['id']]+$val['qta']);
					   else $arr_product_ordered_qta[$val['id']] = $val['qta'];
					}					  
				  }
				  if(plugin_exsists('dgoods')) $sql = execute('delete from '.$table_prefix.'customers_downloads where id_order IN ('.$box.')');
				  /** update availability for each product into products table **/   		
					$ids = implode(',', array_keys($arr_product_ordered_qta));
					$sql = "UPDATE ".$table_prefix."products SET availability = CASE id ";
					foreach ($arr_product_ordered_qta as $id => $qtas) {
						$sql .= sprintf("WHEN %d THEN (availability+$qtas)", $id);
					}
					$sql .= "END WHERE id IN ($ids)";
					execute($sql);
			   break;
			   case $table_prefix.'plugins':
			    $res_p = mysql_fetch_array(execute('select shortname from '.$table.' where id IN ('.$box.')'));
			     deleteDirectory(rel_plugins_path.'/'.$res_p['shortname']);
			   break;			   				   	   		   		   
			   default: ;
			   break;	 
		   }
execute('delete from '.$table.' where id IN ('.$box.')');
?>