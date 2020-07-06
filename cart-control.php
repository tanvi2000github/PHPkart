<?php
  require_once('include/inc_load.php');
   $where = isset($_SESSION['Cid']) ? 'id_client = '.$_SESSION['Cid'] : 'id_client = 0 and session_client = "'.get_initial_user_session().'"';

   switch($_POST['action']){
	 case 'add':
	   /******** ADD A PRODUCT TO CART OR INCREASE ITS QUANTITY INTO CART **********/		
	    $rs = mysql_fetch_array(execute('select * from '.$table_prefix.'cart where id_product = '.$_POST['id_product'].' and '.$where));
		$new_qta = 0; 
		if($rs[0]){
			$arr_options = unserialize($rs['options']);
			 foreach($arr_options as $key => $val){
				if($val['options'] == array_filter($_POST['option'], 'mb_strlen')){
					$arr_options[$key]['qta'] = $val['qta'] + $_POST['qta'];	
					$count_exist_options = true;														
				}
			 }	
			 if(!isset($count_exist_options)){
				  $arr_options[random_cod(5)] = array("qta" =>$_POST['qta'],"options" => array_filter($_POST['option'], 'mb_strlen'));
			 }
		   execute("update ".$table_prefix."cart set options = '".serialize(str_serialize($arr_options))."',date = '".date("Y-m-d H:i:s")."' where id = ".$rs['id']);		 		
		}else{
			$arr_options[random_cod(5)] = array("qta" =>$_POST['qta'],"options" => (isset($_POST['option']) ? array_filter($_POST['option'], 'mb_strlen') : array()));
		   $record = 'id_product,';
		   $record .= (isset($_SESSION['Clogged']) ? 'session_client,id_client,' : 'session_client,');
		   $record .= 'options,date';
		   $val = "'".$_POST['id_product']."',";
		   $val .= (isset($_SESSION['Clogged']) ? '"'.get_initial_user_session().'",'.$_SESSION['Cid'].',' : '"'.get_initial_user_session().'",');
		   $val .= "'".serialize(str_serialize($arr_options))."',";
		   $val .= "'".date("Y-m-d H:i:s")."'";
			$sql = " insert into ".$table_prefix."cart (";
			$sql .= $record;
			$sql .= ") VALUES (";
			$sql .=  $val;
			$sql .=  ")";
			execute($sql);			
		}
	 break;
	 case 'delete':
	 /******** DELETE A PRODUCT FROM CART **********/	
		 $rs = mysql_fetch_array(execute('select options from '.$table_prefix.'cart where id = '.$_POST['id_product']));
		 $arr_options = unserialize($rs['options']);
		 unset($arr_options[$_POST['option_product']]);
		 if(!empty($arr_options))
		   execute("update ".$table_prefix."cart set options = '".serialize(str_serialize($arr_options))."' where id = ".$_POST['id_product']);
		 else
		   execute('delete from '.$table_prefix.'cart where id = '.$_POST['id_product']);
	 break;
	 case 'update':
	 /******** UPDATE CART **********/	
		 $rs = mysql_fetch_array(execute('select options from '.$table_prefix.'cart where id = '.$_POST['id_product']));
		 $arr_options = unserialize($rs['options']);
		 $arr_options[$_POST['option_product']]['qta'] = $_POST['qta'];		 
		 execute("update ".$table_prefix."cart set options = '".serialize(str_serialize($arr_options))."', date = '".date("Y-m-d H:i:s")."' where id = ".$_POST['id_product']);
	 break;	 
	 case 'update-all':
	 /******** UPDATE CART **********/	
	   $ids = implode(',', array_keys($_POST['arr_qta']));
	   $sql = execute('select options,id from '.$table_prefix.'cart where id IN ('.$ids.')');
	   while($rs = mysql_fetch_array($sql)){
		 $arr_options = unserialize($rs['options']);
		 foreach($_POST['arr_qta'][$rs['id']] as $key => $val){
			$arr_options[$key]['qta'] = $val; 
		 }
		 $arr_to_update[$rs['id']] = $arr_options;		   
	   }
		$ids = implode(',', array_keys($arr_to_update));
		$sql = "UPDATE ".$table_prefix."cart SET options = CASE id ";
		foreach ($arr_to_update as $id => $updated_options) {
			$sql .= sprintf("WHEN %d THEN %s ", $id, "'".serialize(str_serialize($updated_options))."'");
		}
		$sql .= "END WHERE id IN ($ids)";
		execute($sql);
	 break;		 
	 default:
	 break;   
   }
?>