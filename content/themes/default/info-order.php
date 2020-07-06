<?php
if(!isset($_SESSION['Clogged'])) exit(); 
 $sql = execute('select * from '.$table_prefix.'orders where id_client = '.$_SESSION['Cid'].' and id = '.$_POST['id']);
 $rs = mysql_fetch_array($sql);
 $arr_ordered_products = unserialize($rs['products_list']); 
 $arr_shipping_address = unserialize($rs['shipping_address']); 
 $arr_billing_address = unserialize($rs['billing_address']);
 switch($rs['payment_method']){
	 case $bank_transfer['orders_prefix']:
	    $payment_method = $bank_transfer['long_name'];
	 break;
	 case $cash_on_delivery['orders_prefix']: 
	    $payment_method = $cash_on_delivery['long_name'];
	 break;	
	 case $paypal['orders_prefix']: 
	    $payment_method = $paypal['long_name'];
	 break;	 	  
 }   
 echo '<div class="return" data-label="'.$lang_client_['info_order']['ORDER_CODE'].' '.$rs['code_order'].'" data-label-type="info">'.
 '<table class="table table-striped table-bordered">
	<thead>
	  <tr>
	    <th>'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_NAME'].'</th>
		<th>'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_UNIT_PRICE'].'</th>
		<th>'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_QUANTITY'].'</th>
		<th>'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_SUBTOTAL'].'</th>
	  </tr>
	</thead>';
 foreach($arr_ordered_products as $key => $val){
	$surcharge = 0;
	$options_display = '';
	$options_display_price = '';
	if(!empty($val['option'])){
	  foreach($val['option'] as $k => $v){					
		$options_display .= '<br/><strong class="text-info">'.$v['name'].': '
							.$v['value']
							.'</strong>';	
		$options_display_price .= '<br/><strong class="text-info">'
								.($v['price'] > 0 
							   ? 
								$v['type'].' '.$currency_l.num_formatt($v['price']).$currency_r
							   : 
								''
							  )
							  .'</strong>';						
	  }	  
	}	
	  $tax_price = (($val['price']*$val['tax_percentage'])/100);
	  $tax_offer = (($val['offer']*$val['tax_percentage'])/100);	
	echo '<tr>
			<td align="left" valign="top">'.$val['name'].$options_display.'</td>
			<td align="left" valign="top"><div class="text-right">'.($val['offer'] <= 0 ? $currency_l.num_formatt(($val['price']+$tax_price)).$currency_r : '<span style="text-decoration: line-through;">'.$currency_l.num_formatt(($val['price']+$tax_price)+$surcharge).$currency_r.'</span> '.$currency_l.num_formatt(($val['offer']+$tax_offer)).$currency_r).$options_display_price.'</div></td>
			<td align="left" valign="top"><div class="text-center">'.$val['qta'].'</div></td>
			<td align="left" valign="top"><div class="text-right">'.$currency_l.num_formatt($val['subtotal']).$currency_r.'</div></td>
	     </tr>';	 
 }
echo '</table>
 <table class="table table-striped table-bordered pull-right" style="width:auto">
   <tr>
     <td align="left" valign="top">'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_SUBTOTAL'].'</td>
     <td align="right" valign="top"><div class="text-right">'.$currency_l.num_formatt($rs['subtotal']).$currency_r.'</div></td>	 
   </tr>
   <tr>
     <td align="left" valign="top">'.$tax_name.'</td>
     <td align="right" valign="top"><div class="text-right">'.$currency_l.num_formatt($rs['tax']).$currency_r.'</div></td>	 
   </tr>';
	if(plugin_exsists('multitaxes') && $rs['pl_multitax_array'] != ''){
		$taxes_sum = array_merge_numeric_values(unserialize($rs['pl_multitax_array']));
	 foreach($taxes_sum as $key => $val){
	  echo '<tr>
		   <td align="left" valign="top">'.$key.'</td>
		   <td align="right" valign="top"><div class="text-right">'.$currency_l.num_formatt(num_formatt($val)).$currency_r.'</div></td>	 
		 </tr>';	  
	 }
	}   
echo '<tr>
     <td align="left" valign="top">'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_SHIPPING'].'</td>
     <td align="right" valign="top"><div class="text-right">'.$currency_l.num_formatt($rs['shipping_price']).$currency_r.'</div></td>	 
   </tr> 
   <tr>
     <td align="left" valign="top">'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_PAYMENT_COST'].'</td>
     <td align="right" valign="top"><div class="text-right">'.$currency_l.num_formatt($rs['payment_price']).$currency_r.'</div></td>	 
   </tr>   
   <tr>
     <td align="left" valign="top">'.$lang_client_['info_order']['TABLE_CONTENT_TITLE_TOTAL'].'</td>
     <td align="right" valign="top"><div class="text-right"><strong>'.$currency_l.num_formatt($rs['grandtotal']).$currency_r.'</strong></div></td>	 
   </tr>          
 </table>
 <div class="clearfix"></div>
 <div class="row-fluid">
   <div class="span12">'.$lang_client_['info_order']['PAYMENT_TUPE_TEXT'].': <strong class="text-info">'.$payment_method.'</strong></div>
 </div>
 <div class="row-fluid">
    <div class="span6">
	     <strong class="text-info">'.$lang_client_['info_order']['BILLING_ADDRESS_TEXT'].'</strong>
          <address>
			<strong>'.ucwords($arr_billing_address['name']).'</strong><br/>'
			.$arr_billing_address['address'].'<br/>'
			.$arr_billing_address['city'].' - '.$arr_billing_address['zipcode'].'<br/>
			<abbr title="Phone">T: </abbr> '.$arr_billing_address['phone'].'<br/>'.
			($arr_billing_address['fax'] != '' ? '<abbr title="Fax">F: </abbr> '.$arr_billing_address['fax'].'<br/>' : '').'
			<abbr title="E-mail">@: </abbr> '.$arr_billing_address['email'].'
		  </address>	
	</div>
    <div class="span6">
	     <strong class="text-info">'.$lang_client_['info_order']['SHIPPING_ADDRESS_TEXT'].'</strong>
          <address>
			<strong>'.ucwords($arr_shipping_address['name']).'</strong><br/>'
			.$arr_shipping_address['address'].'<br/>'
			.$arr_shipping_address['city'].' - '.$arr_shipping_address['zipcode'].'<br/>
			<abbr title="Phone">T: </abbr> '.$arr_shipping_address['phone'].'<br/>'.
			($arr_shipping_address['fax'] != '' ? '<abbr title="Fax">F: </abbr> '.$arr_shipping_address['fax'].'<br/>' : '').'
			<abbr title="E-mail">@: </abbr> '.$arr_shipping_address['email'].'
		  </address>	
	</div>	
 </div>
</div>';
?>