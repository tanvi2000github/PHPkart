<?php 
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
$rs_params_result = execute('select * from '.$table_prefix.'settings');
$rs_params = mysql_fetch_array($rs_params_result);
$val_param = array();
if(!empty($rs_params)){
	foreach($rs_params as $paramkey => $val_param){
	  if(!is_numeric($paramkey)) $param[$paramkey] = @unserialize($val_param) !== false ? unserialize($val_param) : $val_param;
	}
}
if(isset($param)){
  foreach( $param as $key => $val){
	   if(!empty($val)){
		 foreach($val as $k => $v){	   
			  ${$k} =  $v;
		 }
	   }
  }
}
?>