<?php
/****************************************************NOT CHANGE ALL CODE BELOW*******************************************************************/
require_once(dirname(dirname(dirname(__FILE__))).'/include/inc_load.php');
require_once(rel_client_path.'/include/inc_params.php');
@define('languageAdmin',$default_admin_language);
$lang_ = parse_ini_file(rel_admin_path.'/lang/'.languageAdmin.'/'.languageAdmin.'.ini',true);
/* include language for each plugin */
if(table_exists($table_prefix.'plugins')){
 $sql_plu = execute('select * from '.$table_prefix.'plugins where active = 1');
  while($rs_plu = mysql_fetch_array($sql_plu)){
	 /* back-end */
	 if(file_exists(rel_plugins_path.'/'.$rs_plu['shortname'].'/admin/lang/'.languageAdmin.'/'.languageAdmin.'.ini')){
		 $parser_language = parse_ini_file(rel_plugins_path.'/'.$rs_plu['shortname'].'/admin/lang/'.languageAdmin.'/'.languageAdmin.'.ini',true);
		 $lang_ = array_merge($lang_,$parser_language);
	 }	 
	 /* include function file for each plugin */  
	 if(file_exists(rel_plugins_path.'/'.$rs_plu['shortname'].'/functions.php')){
		 require_once(rel_plugins_path.'/'.$rs_plu['shortname'].'/functions.php');
	 }		 
  } 
}
/* CHECK FOR UPDATES */
	foreach (scandir(rel_updates_path.'/') as $item) {
	 if((!is_dir($item))&($item!=".")&($item!="..")){						 
	   if(file_exists(rel_updates_path.'/'.$item)){
			require_once(rel_updates_path.'/'.$item);
			unlink(rel_updates_path.'/'.$item);
	   }
	 }
	}	
?>