<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
 $error_alert = '';
 $error_cod = '';
 $random_code = random_cod(10);

  $name = $_POST['name'];
  $description = $_POST['description'];
  $code = $_POST['code'];
  $price = $_POST['price'];
  $offer = $_POST['offer'];
  $tax = isset($_POST['tax']) ? $_POST['tax'] : 0;
  $price_with_tax = 0;
  if(plugin_exsists('multitaxes')){
	$multitax_pl = isset($_POST['multitax']) ? implode(',',$_POST['multitax']) : '';
  }
  if(plugin_exsists('businesstype') && get_admin_business_bc()){
	$rprice = $_POST['price_type'] == '1' ? $_POST['rprice']/((100+$tax)/100) : $_POST['rprice'];
	$roffer = $_POST['price_type'] == '1' ? $_POST['roffer']/((100+$tax)/100) : $_POST['roffer'];
	$rpdiscount = $_POST['rpdiscount'];
	$rodiscount = $_POST['rodiscount'];
  }
  if($_POST['price_type'] == '1'){
	 $price = $price/((100+$tax)/100);
	 $offer = $offer/((100+$tax)/100);
	 $price_with_tax = 1;
  }
  $availability = $_POST['availability'];
  $category = $_POST['category'];
  $by_exposure = isset($_POST['by_exposure']) ? 1 : 0;
  $active = isset($_POST['visible']) ? 1 : 0;
  $showcase = isset($_POST['showcase']) ? 1 : 0;
  $unlimited_availability = isset($_POST['unlimited_availability']) ? 1 : 0;

if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
 $upload_dir = path_rel_img_products;
 $ext_error = '';
 $generic_error = '';
 $file_empty_error = '';
 $allowed_types = array ("jpg","png","jpeg");

/***************** control on duplicate codes ***********/
$rs_control_result = execute('select code,categories from '.$table_name.' where code = "'.str_db($code).'" and categories = "'.str_db($category).'" and id <> '.$_POST['id']);
$rs_control = mysql_fetch_array($rs_control_result);
 if($rs_control){
  $error_alert .= $lang_['products']['INSERT_UPDATE_DUPLICATE_ITEM_ERROR'].'<br/>';
 }
/***************** /control on duplicate codes ***********/

/***************** upload ***********/
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
 $upload_dir = path_rel_img_products;
 $ext_error = '';
 $generic_error = '';
 $allowed_types = array ("jpg","png","jpeg");
  foreach($_FILES as $key => $val){
   if(mb_substr($key,0,6) == 'upimg_'){
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
	  $array_img[end($get_id)] = array(
		"urlimg" => $file_name,
		"principale" => end($get_id) == 1 ? 1 : 0
	  );
	}
   }
  }
  if(plugin_exsists('dgoods') && isset($_POST['is_digital'])){
	   $upload_dir_digital_good = rel_uploads_path.'/digital_goods';
	   $ext_error_digital_good = '';
	   $generic_error = '';
	   $error_no_file_exists = '';
	   $allowed_types_digital_good = array ("rar","zip");
	   if(!empty($_FILES['demo_digital_good_name']["name"]) && !isset($_POST['pl_delete_demo'])){
		  $ext_digital_good_demo = explode('.',$_FILES['demo_digital_good_name']["name"]);
		  if(!in_array(mb_strtolower(end($ext_digital_good_demo)),$allowed_types_digital_good)) {
		   $ext_error_digital_good = 't';
		  }
		  if(!@is_uploaded_file($_FILES['demo_digital_good_name']["tmp_name"])) $generic_error .= 't';
	   }
	   if(!empty($_FILES['digital_good_name']["name"])){
		 $ext_digital_good = explode('.',$_FILES['digital_good_name']["name"]);
		 $file_name_digital_good = random_cod(5).'-'.random_cod(5).'-'.random_cod(8).'.'.mb_strtolower(end($ext_digital_good));
		 $original_file_name_digital_good = filesystem(str_replace('.'.end($ext_digital_good),'',$_FILES['digital_good_name']["name"])).'.'.mb_strtolower(end($ext_digital_good));
		  if(!in_array(mb_strtolower(end($ext_digital_good)),$allowed_types_digital_good)) {
		   $ext_error_digital_good = 't';
		  }
		  if(!@is_uploaded_file($_FILES[$key]["tmp_name"])) $generic_error .= 't';
	   }
	 if($ext_error_digital_good != '') $error_alert .= str_replace('{allowed_files}',implode(",", $allowed_types_digital_good),$lang_['products']['UPLOAD_EXTENSION_FILES_ERROR']).' per i prodotti digitali<br/>';
  }
 if($ext_error != '') $error_alert .= str_replace('{allowed_files}',implode(",", $allowed_types),$lang_['products']['UPLOAD_EXTENSION_FILES_ERROR']).'<br/>';
 if($generic_error != '') $error_alert .= $lang_['products']['UPLOAD_GENERIC_ERROR'];
/***************** / upload ***********/


 if($error_alert != ''){
  echo '<div class="error_alert">'.$error_alert.'</div>';
  exit();
 }else{
  /* OPTIONS */
	$arr_options = array();
	if(isset($_POST['noption'])){
	  foreach($_POST['noption'] as $key => $val){
		  if(str_replace(' ','',$val['name']) != '' && isset($_POST['noption'][$key]['voption'])){
		   $array_option_value = array();
		   $option_code = !isset($_POST['noption'][$key]['name_code']) ? random_cod(5) : $_POST['noption'][$key]['name_code'];
		   if(!isset($_POST['noption'][$key]['required_option'])){
			$option_required = 0;
		   }else{
			$option_required = 1;
		   }
			 foreach($_POST['noption'][$key]['voption'] as $k => $v){
			   $option_code_value = !isset($_POST['noption'][$key]['voption'][$k]['value_code']) ? random_cod(5) : $_POST['noption'][$key]['voption'][$k]['value_code'];
			   $array_option_value[$option_code_value] = array(
														   "value" => str_db($v['value']),
														   "price" => str_db($v['price']),
														   "type" => str_db($v['type'])
														 );
			 }
			  $arr_options[$option_code] = array(
								  "name" => str_db($val['name']),
								  "required_option" => $option_required,
								  "voption" => $array_option_value
								  );
		  }
	  }
	}
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
			$array_img[end($get_id)] = array(
			  "urlimg" => end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)),
			  "principale" => end($get_id) == 1 ? 1 : 0
			);
			/* rename old images */
			rename($upload_dir.'/'.$_POST['id'].'/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			rename($upload_dir.'/'.$_POST['id'].'/50x50/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/50x50/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			rename($upload_dir.'/'.$_POST['id'].'/70x70/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/70x70/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			rename($upload_dir.'/'.$_POST['id'].'/300x300/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/300x300/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			rename($upload_dir.'/'.$_POST['id'].'/600x600/'.$_POST[$key], $upload_dir.'/'.$_POST['id'].'/600x600/'.end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext)));
			$array_img_not_del[] = end($get_id).'_'.$random_code.'.'.mb_strtolower(end($ext));
		}
	 }
  }
/* delete old image */
	if(!empty($array_img_not_del)){
		 emptyDirectory($upload_dir.'/'.$_POST['id'],implode(',',$array_img_not_del).',50x50,70x70,300x300,600x600');
		 emptyDirectory($upload_dir.'/'.$_POST['id'].'/50x50',implode(',',$array_img_not_del));
		 emptyDirectory($upload_dir.'/'.$_POST['id'].'/70x70',implode(',',$array_img_not_del));
		 emptyDirectory($upload_dir.'/'.$_POST['id'].'/300x300',implode(',',$array_img_not_del));
		 emptyDirectory($upload_dir.'/'.$_POST['id'].'/600x600',implode(',',$array_img_not_del));
	}else{
		 emptyDirectory($upload_dir.'/'.$_POST['id']);
	}
	/** adding attributes **/
	foreach($_POST as $key => $val){
	  if(mb_substr($key,0,10) == 'nattribute'){
		 $get_arr_position = explode('_',$key);
		 $get_position = end($get_arr_position);
		 if(str_replace(' ','',$val) != '' && str_replace(' ','',$_POST['vattribute_'.$get_position]) != '')
		 $arr_attributes[] = array("attribute_name" => str_db($val),"attribute_value" => str_db($_POST['vattribute_'.$get_position]),"asfilter" => (isset($_POST['asfilter_'.$get_position]) ? 1 : 0));
	  }
	}
 /* GET STATUS BASED ON CATEGORY STATUS */
 $sql_cat = execute('select status from '.$table_prefix.'categories where id = '.$category);
 $rs_cat = mysql_fetch_array($sql_cat);
 $visible = $rs_cat['status'] ? 1 : 0;
/* get old product data */
  $res_p = execute('select * from '.$table_name.' where id  = '.$_POST['id']);
  $rs_p = mysql_fetch_array($res_p);

  $val = "name = '".str_db($name)."',";
  $val .= "file_name = '".filesystem($name)."',";
  if(!empty($array_img)){
	echo 'no empty';
  }else{
	echo 'empty';
  }
if(!empty($array_img)){
  $val .= "images = '".serialize(str_serialize($array_img))."',";
  $val .= "url_image = '".str_db($img_url)."',";
}else{
  $val .= "images = '',";
  $val .= "url_image = '',";
}
  if(plugin_exsists('businesstype') && get_admin_business_bc()){
	$val .= "rprice = '".str_db($rprice)."',";
	$val .= "roffer = '".str_db($roffer)."',";
	$val .= "rpdiscount = '".str_db($rpdiscount)."',";
	$val .= "rodiscount = '".str_db($rodiscount)."',";
  }
  $val .= "categories = '".str_db($category)."',";
  $val .= "code = '".str_db($code)."',";
  $val .= "price = '".str_db($price)."',";
  $val .= "offer = '".str_db($offer)."',";
  if(plugin_exsists('multitaxes')){
	$val .= "pl_multitax = '".str_db($multitax_pl)."',";
  }
  if(plugin_exsists('dgoods') && isset($_POST['is_digital'])){
	$val .= "pl_digital_not_available = '".(isset($_POST['digital_download_not_available']) ? 1 : 0)."',";
	if(!empty($_FILES['digital_good_name']["name"])){
		$val .= "pl_digital_code_name = '".$file_name_digital_good."',";
		$val .= "pl_digital_original_name = '".$original_file_name_digital_good."',";
	}
  }
  $val .= "tax = '".str_db($tax)."',";
  $val .= "by_exposure = '".$by_exposure."',";
  $val .= "unlimited_availability = '".$unlimited_availability."',";
  $val .= "visible = '".$visible."',";
  $val .= "description = '".str_db_content($description)."',";
  $val .= "price_with_tax = '".str_db($price_with_tax)."',";
  if(str_replace(' ','',$_POST['meta_title']) != '')
  $val .= "meta_title = '".str_db($_POST['meta_title'])."',";
  else
  $val .= "meta_title = '',";
  if(str_replace(' ','',$_POST['meta_keywords']) != '')
  $val .= "meta_keywords = '".str_db($_POST['meta_keywords'])."',";
  else
  $val .= "meta_keywords = '',";
  if(str_replace(' ','',$_POST['meta_description']) != '')
  $val .= "meta_description = '".str_db(strip_tags(preg_replace('/\s+/', ' ',$_POST['meta_description'])))."',";
  else
  $val .= "meta_description = '',";
  if(!empty($arr_attributes)){
    $val .= "attributes = '".serialize(str_serialize($arr_attributes))."',";
  }else{
	$val .= "attributes = '',";
  }
  if(!empty($arr_options)){
    $val .= "options = '".serialize(str_serialize($arr_options))."',";
  }else{
	$val .= "options = '',";
  }
  $val .= "units = '".str_db($_POST['units'])."',";
  $val .= "availability = '".str_db($availability)."',";
  $val .= "active = '".$active."',";
  $val .= "showcase = '".$showcase."'";

  $sql = "update ".$table_name." set ".$val." where id = '".$_POST['id']."'";
   execute($sql);
		$last_id = $_POST['id'];
		if(!empty($array_filename)){
		 foreach($array_filename as $key => $val){
		   upload_resize_img($val['tempname'],$val['filename'],'600,300,70,50',$upload_dir.'/'.$last_id,true);
		 }
		}
		if(plugin_exsists('dgoods') && isset($_POST['is_digital'])){
		  if(isset($_POST['pl_delete_demo'])){
			 if(file_exists(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$_POST['old_digital_file']))
		      unlink(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$_POST['old_digital_file']);
		  }else{
		   if(!empty($_FILES['demo_digital_good_name']["name"])){
			 if(file_exists(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$_POST['old_digital_file']))
			  unlink(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/'.$_POST['demo_old_digital_file']);
			  move_uploaded_file($_FILES["demo_digital_good_name"]["tmp_name"],$upload_dir_digital_good.'/'.$last_id.'/demo_'.$_POST['old_digital_file']);
		   }
		  }
		 if(!empty($_FILES['digital_good_name']["name"])){
			if(file_exists(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$_POST['old_digital_file']))
			  rename(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$_POST['old_digital_file'],rel_uploads_path.'/digital_goods/'.$_POST['id'].'/demo_'.$file_name_digital_good);
			unlink(rel_uploads_path.'/digital_goods/'.$_POST['id'].'/'.$_POST['old_digital_file']);
            move_uploaded_file($_FILES["digital_good_name"]["tmp_name"],$upload_dir_digital_good.'/'.$last_id.'/'.$file_name_digital_good);
		 }
		}
if($rs_p['file_name'] != filesystem($name)){
	$name_to_change = $rs_p['file_name'] == '' ? filesystem($rs_p['name']) : $rs_p['file_name'];
	if($category == $rs_p['categories']){
		rename(path_rel_products.'/'.$mptt->get_orizzontal_path($category, '/').'/'.$last_id.'-'.$name_to_change.'.php', path_rel_products.'/'.$mptt->get_orizzontal_path($category, '/').'/'.$last_id.'-'.filesystem($name).'.php');
	}
	rename(path_rel_products.'/'.$last_id.'-'.$name_to_change.'.php', path_rel_products.'/'.$last_id.'-'.filesystem($name).'.php');
}
if($category != $rs_p['categories']){
		$dir = $mptt->get_orizzontal_path($category, '/');
		$count_subdirectories = count(explode('/',$dir));
		      /**** overwrite 2d line of index file */
			  if(!file_exists(path_rel_products.'/'.$dir.'/'.$last_id.'-'.filesystem($name).'.php')){
					  $file = fopen(path_rel_products.'/'.$dir.'/'.$last_id.'-'.filesystem($name).'.php', "w+") or exit("Error!");
					  $control = 'require_once('.str_repeat('dirname(',$count_subdirectories+3).'(__FILE__)'.str_repeat(')',$count_subdirectories+3).'.\'/include/inc_load.php\');'."\n";
					  $control .= '$product_id = '.$last_id.';'."\n";
					  $control .= '$category_id = '.$category.';'."\n";
					  $control .= 'require_once(theme_rel_path.\'/product.php\');'."\n";
					  fwrite($file,'<'.'?php'."\n");
					  fwrite($file,$control);
					  fwrite($file,'?'.'>');
					  fclose($file);
                      chmod(path_rel_products.'/'.$dir.'/'.$last_id.'-'.filesystem($name).'.php', 0755);
			  }
			  /** rewrite array products for each root directory of old category **/
				 foreach($mptt->get_path($rs_p['categories']) as $key => $val){
						$dir = $mptt->get_orizzontal_path($val['id'], '/');
					  if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
						  require(path_rel_products.'/'.$dir.'/inc_array_product.php');
						  $arr_container_products = array_remove_item($arr_container_products,$last_id);
						  $file = fopen(path_rel_products.'/'.$dir.'/inc_array_product.php', "w+") or exit("Error!");
						  fwrite($file,'<'.'?php'."\n");
						  fwrite($file,'$arr_container_products = array('.implode(',',$arr_container_products).');'."\n");
						  fwrite($file,'?'.'>');
						  fclose($file);
						  chmod(path_rel_products.'/'.$dir.'/inc_array_product.php', 0755);
					  }
				 }
				  /** write array products for each root directory of this product **/
				 foreach($mptt->get_path($category) as $key => $val){
					 $dir = $mptt->get_orizzontal_path($val['id'], '/');
					if(file_exists(path_rel_products.'/'.$dir.'/inc_array_product.php')){
						require(path_rel_products.'/'.$dir.'/inc_array_product.php');
						 if(!in_array($last_id,$arr_container_products)){
							 $arr_container_products[] = $last_id;
						 }
					}else{
							 $arr_container_products = array($last_id);
					}
						$file = fopen(path_rel_products.'/'.$dir.'/inc_array_product.php', "w+") or exit("Error!");
						fwrite($file,'<'.'?php'."\n");
						fwrite($file,'$arr_container_products = array('.implode(',',$arr_container_products).');'."\n");
						fwrite($file,'?'.'>');
						fclose($file);
						chmod(path_rel_products.'/'.$dir.'/inc_array_product.php', 0755);
				 }
/************ delete file into old directories ****/
	  if($rs_p['categories'] != '') unlink(path_rel_products.'/'.$mptt->get_orizzontal_path($rs_p['categories'], '/').'/'.$rs_p['id'].'-'.$rs_p['file_name'].'.php');
/********** /delete files *********************/
}
    /* save attributes like filters */
    $var_attributes = '';
	execute('delete from '.$table_prefix.'products_attributes where id_product = '.$last_id);
	if(!empty($arr_attributes)){
	  foreach($arr_attributes as $key => $val){
		   if($val['asfilter'])
		   $var_attributes .= '('.$last_id.',"'.$val['attribute_name'].'","'.$val['attribute_value'].'"),';
	  }
	}
	if($var_attributes != ''){
		$var_attributes = mb_substr($var_attributes,0,-1);
		execute('insert into '.$table_prefix.'products_attributes (id_product,attribute_name,attribute_value) values '.$var_attributes);
	}
	/*****************/
 }
?>