<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $error_alert = '';
 $error_cod = '';
 $random_code = random_cod(10);	
/***************** control on duplicate names ***********/	
$rs_control_result = execute('select name from '.$table_name.' where name = "'.str_db($_POST['name']).'"');	  
$rs_control = mysql_fetch_array($rs_control_result);
 if($rs_control){
  $error_alert .= $lang_['pl_slideshow']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
/***************** /control on duplicate names ***********/

/***************** upload ***********/
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
 $upload_dir = rel_uploads_path.'/slideshow';
 $ext_error = '';
 $generic_error = '';
 $allowed_types = array ("jpg","png","jpeg");
  foreach($_FILES as $key => $val){
	if(!empty($_FILES[$key]["name"])){
	  $new_name = $key.'_'.$random_code;
	  $ext = explode('.',$_FILES[$key]["name"]);
	  $get_id = explode('_',$key);
	  $file_name = end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext));
	  if(!in_array(mb_strtolower(end($ext)),$allowed_types)) {
	   $ext_error .= 't';
	  }
	  if(!@is_uploaded_file($_FILES[$key]["tmp_name"])) $generic_error .= 't';	  
	  if(end($get_id) == 1){	
	   $img_url = $random_code.'.'.mb_strtolower(end($ext));
	  }
	   if(!empty($_FILES[$key]["tmp_name"])){
		$array_filename[] = array(
			"tempname" => $_FILES[$key]["tmp_name"],
			"filename" => $file_name,
			"ext" => mb_strtolower(end($ext))
		);
		$array_img[] = array(
		  "urlimg" => $file_name,
		  "position" => $_POST['position_'.end($get_id)],
		  "visible" => isset($_POST['visible_'.end($get_id)]) ? 1 : 0
		);
		$array_position[] = $_POST['position_'.end($get_id)];	
	   }
	}
  } 
 if($ext_error != '') $error_alert .= str_replace('{allowed_files}',implode(",", $allowed_types),$lang_['pl_slideshow']['UPLOAD_EXTENSION_FILES_ERROR']).'<br/>';
 if($generic_error != '') $error_alert .= $lang_['pl_slideshow']['UPLOAD_GENERIC_ERROR'];
 if(count($array_position) != count(array_unique($array_position))) $error_alert .= $lang_['pl_slideshow']['POSITION_ERROR'];
/***************** / upload ***********/					
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{ 

 $active = isset($_POST['active']) ? 1 : 0;
 if($active) execute('update '.$table_name.' set active = 0');  
  $record = 'name,active,imgs';
		$val = "'".str_db($_POST['name'])."',";
		$val .= "'".$active."',";
		$val .= "'".serialize(str_serialize($array_img))."'";
		$sql = " insert into ".$table_name." (";
		$sql .= $record;
		$sql .= ") VALUES (";
		$sql .=  $val;
		$sql .=  ")";  
		execute($sql);
		$last_id = mysql_insert_id();
		if(!empty($array_filename)){
		 foreach($array_filename as $key => $val){
		   upload_resize_img($val['tempname'],$val['filename'],'150',$upload_dir.'/'.$last_id,true);
		 }
		}
 }
?>