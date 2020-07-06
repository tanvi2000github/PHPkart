<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');

 $error_alert = '';
 $error_cod = '';
 $random_code = random_cod(10);

/***************** control on duplicate names ***********/	
$rs_control_result = execute('select name,id from '.$table_name.' where name = "'.str_db($_POST['name']).'" and id <> '.$_POST['id']);	  
$rs_control = mysql_fetch_array($rs_control_result);
 if($rs_control){
  $error_alert .= $lang_['products']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
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
 if($ext_error != '') $error_alert .= str_replace('{allowed_files}',implode(",", $allowed_types),$lang_['products']['UPLOAD_EXTENSION_FILES_ERROR']).'<br/>';
 if($generic_error != '') $error_alert .= $lang_['products']['UPLOAD_GENERIC_ERROR'];
 if(!empty($array_position) && count($array_position) != count(array_unique($array_position))) $error_alert .= $lang_['pl_slideshow']['POSITION_ERROR'];
/***************** / upload ***********/	

 
 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{ 	
/* image to not delete */
  $array_img_not_del = array();
  foreach($_POST as $key => $val){
	 if(mb_substr($key,0,11) == 'imgpersist_'){	
	    if(isset($_POST[$key]) && $_POST[$key] != '' && empty($_FILES['upimg_'.str_replace('imgpersist_','',$key)]["name"])){			
			$get_id = explode('_',$key);
			$ext = explode('.',$_POST[$key]);
			if(end($get_id) == 1){	
			 $img_url = $random_code.'.'.mb_strtolower(end($ext));
			}						
			$array_img[] = array(
			  "urlimg" => end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)),
			  "position" => $_POST['position_'.end($get_id)],
			  "visible" => isset($_POST['visible_'.end($get_id)]) ? 1 : 0
			);
			/* rename old images */
			rename($upload_dir.'/'.$_POST['id'].'/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			rename($upload_dir.'/'.$_POST['id'].'/150x150/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/150x150/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			$array_img_not_del[] = end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext));			
		}
	 }
  } 
/* delete old image */
	if(!empty($array_img_not_del)){
		 emptyDirectory($upload_dir.'/'.$_POST['id'],implode(',',$array_img_not_del).',150x150');
		 emptyDirectory($upload_dir.'/'.$_POST['id'].'/150x150',implode(',',$array_img_not_del));		 
	}else{
		 emptyDirectory($upload_dir.'/'.$_POST['id']);
	}
   
  $active = isset($_POST['active']) ? 1 : 0;
  if($active) execute('update '.$table_name.' set active = 0'); 	
  $val = "name = '".str_db($_POST['name'])."',";
  $val .= "active = '".$active."',";  
  $val .= "imgs = '".serialize(str_serialize($array_img))."'";
  $sql = "update ".$table_name." set ".$val." where id = '".$_POST['id']."'";
  execute($sql);
  $last_id = $_POST['id'];
  if(!empty($array_filename)){
   foreach($array_filename as $key => $val){
	 upload_resize_img($val['tempname'],$val['filename'],'150',$upload_dir.'/'.$last_id,true);		
   }
  }			 
 }
?> 