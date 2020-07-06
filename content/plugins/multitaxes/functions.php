<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
/*******************************************
************** WARNING!!! *******************
** if you want change this file be sure    **
** save it in UTF-8 because there are      **
** some special chars like polish language **
*********************************************
*********************************************/
/*
 This file will contain some general useful functions for this plugin
*/
if(plugin_exsists('multitaxes')){
  $sql_taxes = execute('select * from '.$table_prefix.'taxes');
   while($rs_taxes = mysql_fetch_assoc($sql_taxes)){
	 ${md5('arr_taxes')}[$rs_taxes['id']] = $rs_taxes;
   }   
   /*
     CALCULATE A PRICE INCLUDING TAXES
     return a value
     calculate_taxes_value($price_without_taxes,$id_taxes_comma_separated)
   */   
   function calculate_taxes_value($price,$arr_product_tax){
	   global ${md5('arr_taxes')};
	   $final_value = 0;	   
	  if(isset(${md5('arr_taxes')}) && !empty(${md5('arr_taxes')})){
		 foreach(explode(',',$arr_product_tax) as $id_tax){
			 if(in_array($id_tax,array_keys(${md5('arr_taxes')}))){
				 $final_value = (($price*${md5('arr_taxes')}[$id_tax]['percentage'])/100)+$final_value;
			 }							 
		 }
	  } 
	  return $final_value;  
   }
   /*
     GET AN ARRAY WITH TAX/ES PARAMS
     return an array or a value
     get_tax_param($single_id_or_array_taxes_ids,$param_to_get)
   */    
   function get_tax_param($id,$param){
	   global ${md5('arr_taxes')};
	   $arr_param = array();
	   if(is_array($id)){
		   foreach($id as $get_id){
			 $arr_param[] = ${md5('arr_taxes')}[$get_id][$param]; 
		   }
	   }else{
		if($id != '' && $param != '')
		 $arr_param = ${md5('arr_taxes')}[$id][$param];
		else
		$arr_param = '';
	   }
	   return $arr_param;
   }
	/*
	  SUM VALUE FOR EACH EQUAL KEY INTO AN ARRAY
      return an array
	 $x = array(
	  'rArray1' => array(
			  'uzorong' => 1,
			  'ngangla' => 4,
			  'langthel' => 5
		  ),
	  'rArray2' => array(
			  'gozhi' => 5,
			  'uzorong' => 0,
			  'ngangla' => 3,
			  'langthel' => 2
		  ),
	  'rArray3' => array(
			  'gozhi' => 3,
			  'uzorong' => 0,
			  'ngangla' => 1,
			  'langthel' => 3
		  )
	  );
	  $output = array_merge_numeric_values($x);	  
	*/
	  function array_merge_numeric_values($arrays,$type=false){
		  $merged = array();
		  foreach ($arrays as $array){
			  foreach ($array as $key => $value){
				  if ( ! is_numeric($value)){
					  continue;
				  }
				  if ( ! isset($merged[$key])){
					  $merged[$key] = $value;
				  }else{
					if($type && $type == '-')
					  $merged[$key] -= $value;
					else
					  $merged[$key] += $value;
				  }
			  }
		  }
		  return $merged;
	  } 
}
?>