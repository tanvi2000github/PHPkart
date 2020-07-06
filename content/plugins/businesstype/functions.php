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
if(plugin_exsists('businesstype')){	
	function get_client_business_bc(){
	  global $business_type;
	  if((isset($business_type) && $business_type == 'bc') && (isset($_SESSION['Cretailer']) && $_SESSION['Cretailer'])  && (isset($_SESSION['Cretailer_denied']) && !$_SESSION['Cretailer_denied'])){
		  return true;
	  }else{
		  return false;  
	  }
	}
	function get_client_info_status(){
	  global $business_type;
	  $status = '1';
	  if((!isset($business_type) || $business_type != 'bc')){
		 /*normal client*/
		 $status = '1'; 
	  }
	  if((isset($business_type) && $business_type == 'bc')){
		if(!$_SESSION['Cretailer_request']){
		 /*normal client*/
		 $status = '1'; 			
		}else{
		  if($_SESSION['Cretailer'] && !$_SESSION['Cretailer_denied']){
		   /* reseller approved */
		   $status = '2'; 			
		  }else if(!$_SESSION['Cretailer'] && !$_SESSION['Cretailer_denied'] && $_SESSION['Cretailer_request']){
			/* reseller IN PROCESS */
			$status = '3'; 			  
		  }else if($_SESSION['Cretailer'] && $_SESSION['Cretailer_denied']){
			/* reseller denied */
			$status = '4'; 			  
		  }
		}
	  }
	  return $status;
	}
	function get_admin_business_bc(){
	  global $business_type;
	  if((isset($business_type) && $business_type == 'bc')){
		  return true;
	  }else{
		  return false;  
	  }
	}	
}
?>