<?php 
 require_once('include/inc_load.php');
 if(!$guest_purchases && !isset($_SESSION['Clogged'])){
	  header('location:'.abs_client_path.'/register.php'); 
      exit();
 }
 if(!isset($_SESSION['Cid']) && $guest_purchases){
	if(isset($_GET['id_o']) && isset($_GET['ses'])){
	 $where = 'code_order = "'.str_db($_GET['id_o']).'" and  session_client = "'.str_db($_GET['ses']).'"';
	}else{
	 $where = 'id = '.str_db($_POST['id_order']);	
	}
 }else{
	if(isset($_GET['id_o']) && isset($_GET['ses'])){
	 $where = 'code_order = "'.str_db($_GET['id_o']).'" and  session_client = "'.str_db($_GET['ses']).'"';
	}else{	 
	 $where = 'id = '.str_db($_POST['id_order']).' and id_client = "'.str_db($_SESSION['Cid']).'"'; 
	}
 }
 $sql = execute('select * from '.$table_prefix.'orders where '.$where);
 $rs = mysql_fetch_array($sql);
 $arr_order_products = unserialize($rs['products_list']);
 $paypal_url = $paypal['sendbox'] ? 'www.sandbox.paypal.com' : 'www.paypal.com';
?> 
<form method="post" name="paypal_form" class="hidess" id="pay_pp" action="https://<?php echo $paypal_url; ?>/cgi-bin/webscr">
<input type="hidden" name="business" value="<?php echo $paypal['email']; ?>" />

<input name="custom" type="hidden" value="<?php echo $rs['code_order']; ?>" />
<input type="hidden" name="cmd" value="_cart" />
<input type="hidden" name="upload" value="1">
  
<input type="hidden" name="return" value="<?php echo abs_client_path; ?>" />
<input type="hidden" name="cancel_return" value="<?php echo abs_client_path; ?>/cart.php" />
<input type="hidden" name="notify_url" value="<?php echo abs_client_path; ?>/paypal-ipn.php" />
<input type="hidden" name="rm" value="2" />
<input type="hidden" name="currency_code" value="<?php echo $paypal['currency_code']; ?>" />
<input type="hidden" name="lc" value="<?php echo $paypal['region']; ?>" />
<input type="hidden" name="cbt" value="<?php echo $lang_client_['general']['BUTTON_CONTINUE']; ?>" />
<input type="hidden" name="handling_cart" value="<?php echo @number_format(($rs['shipping_price']+$paypal['surcharge']),2,'.',''); ?>" />
<input type="hidden" name="cs" value="0" />     
<!--<input type="hidden" name="cpp_header_image" value="logo.png" /> -->   

<?php
$item_count = 1; 
foreach($arr_order_products as $key => $val){
 echo '<input type="hidden" name="item_name_'.$item_count.'" value="'.cutOff($val['name'],124).'" />';
 echo '<input type="hidden" name="amount_'.$item_count.'" value="'.@number_format(($val['subtotal']/$val['qta']),2,'.','').'" />';
 echo '<input type="hidden" name="quantity_'.$item_count.'" value="'.$val['qta'].'" />';
 $item_count++;
}
?> 
</form>
<?php
 if(isset($_GET['ses']) && isset($_GET['id_o'])){
?>
<script type="text/javascript">
window.onload = function(){
  document.paypal_form.submit();
};
</script>
<?php	 
 }
?>