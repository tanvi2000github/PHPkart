<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/include/inc_load.php');
		$sql_p = execute('select id,pl_digital_code_name,pl_digital_original_name,name from '.$table_prefix.'products where pl_digital_code = "'.$_SERVER["QUERY_STRING"].'"');
		$rs_p = mysql_fetch_array($sql_p);
		$file = rel_uploads_path.'/digital_goods/'.$rs_p['id'].'/demo_'.$rs_p['pl_digital_code_name'];
		if(!$file){
			die("NOT EXISTS!");
		}else{
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename= demo_".$rs_p['pl_digital_original_name']);
			header("Content-Transfer-Encoding: binary");
			readfile($file);
		}
?>