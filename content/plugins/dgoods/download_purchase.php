<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/include/inc_load.php');
	if(!isset($_GET["dcode"]) || (!isset($_GET['cid']) && !isset($_GET["gsession"]))){
	  die($lang_client_['pl_dgoods']['NOTICE_DIGITAL_GOOD_DOWNLOAD_BAD_REQUEST']);
	}
	$sql = execute('select * from '.$table_prefix.'customers_downloads where download_code = "'.$_GET["dcode"].'"
	'.(isset($_GET['cid'])
	? 'and id_client = '.$_GET['cid'].' and guest = 0'
	: 'and session_guest = "'.$_GET["gsession"].'" and guest = 1'));
	$rs = mysql_fetch_array($sql);
	if($rs){
	  if(!$rs['available']) die($lang_client_['pl_dgoods']['NOTICE_UNPAID_ORDER_NO_DOWNLOAD_AVAILABLE']);
	  if($rs['guest']){
		if(isset($dgoodg_link_deadline) && $dgoodg_link_deadline != '0'){
		  $hour_exp_date = explode(' ',$rs['expiration_date']);
		  if(date('Y-m-d H:i:s',strtotime($rs['expiration_date'] .' + '.$dgoodg_link_deadline.' days')) < date('Y-m-d H:i:s'))
		    die(str_replace('{expiration_date}',view_date(date('Y-m-d H:i:s',strtotime($rs['expiration_date'] .' + '.$dgoodg_link_deadline.' days'))).' '.$hour_exp_date[1],$lang_client_['pl_dgoods']['NOTICE_DIGITAL_GOOD_LINK_EXPIRE']));
		}
	  }
		$sql_p = execute('select id,pl_digital_code_name,pl_digital_original_name,name from '.$table_prefix.'products where pl_digital_code = "'.$rs['download_code'].'"');
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
	  die($lang_client_['pl_dgoods']['NOTICE_DIGITAL_GOOD_DOWNLOAD_NOT_EXISTS']);
	}
?>