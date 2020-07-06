<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/include/inc_load.php');

  if(isset($_SESSION['Alogged']) && isset($_GET["Adcode"])){
		$sql_p = execute('select id,pl_digital_code_name,pl_digital_original_name,name from '.$table_prefix.'products where pl_digital_code = "'.$_GET["Adcode"].'"');
		$rs_p = mysql_fetch_array($sql_p);
		$file = rel_uploads_path.'/digital_goods/'.$rs_p['id'].'/'.$rs_p['pl_digital_code_name'];
		if(!$file){
			die("NOT EXISTS!");
		}else{
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename= ".$rs_p['pl_digital_original_name']);
			header("Content-Transfer-Encoding: binary");
			readfile($file);
		}
   }else{
	  die($lang_client_['pl_dgoods']['NOTICE_DIGITAL_GOOD_DOWNLOAD_BAD_REQUEST']); 
   }
?>