<?php
require_once('include/inc_load.php');
if(!$guest_purchases && !isset($_SESSION['Clogged'])) exit();
require_once('include/lib/phpMailer/class.phpmailer.php');
execute(); // open connection to DB
require_once('include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
if (!email_exist($_POST['email'])) die('<div class="error_alert">'.$lang_client_['general']['WRONG_EMAIL_ADDRESS'].'</div>');
if($_POST['payment_method'] != $bank_transfer['orders_prefix'] && $_POST['payment_method'] != $cash_on_delivery['orders_prefix'] && $_POST['payment_method'] != $paypal['orders_prefix']){
  die('<div class="error_alert">Choose a right payment method</div>');
}
switch($_POST['payment_method']){
  case $bank_transfer['orders_prefix']:
    $_POST['payment_price'] = $bank_transfer['surcharge'];
  break;
  case $cash_on_delivery['orders_prefix']:
    $_POST['payment_price'] = $cash_on_delivery['surcharge'];
  break;
  case $paypal['orders_prefix']:
    $_POST['payment_price'] = $paypal['surcharge'];
  break;
}
 mysql_query('LOCK TABLE '.$table_prefix.'cart WRITE,'.(plugin_exsists('dgoods') ? 'LOCK TABLE '.$table_prefix.'customers_downloads WRITE,' : '').$table_prefix.'products WRITE,'.$table_prefix.'orders WRITE,'.$table_prefix.'categories WRITE,'.$table_prefix.'clients WRITE');
		  /** registration client on the fly **/
		  if($guest_purchases && !isset($_SESSION['Cid']) && $_POST['account_type'] == 'register'){
			 /** control on duplicate userid and email address **/
			 $user = str_db(str_replace('"','&quot;',$_POST['useridreg']));
			 $email = str_db(str_replace('"','&quot;',$_POST['email']));
			 $sql = execute('select userid,email from '.$table_prefix.'clients where userid = "'.$user.'" or email = "'.$email.'"');
			 while($rs = mysql_fetch_array($sql)){
			   if(mb_strtolower($rs['userid']) == mb_strtolower($user)){
				   $error_user .= 'true';
			   }
			   if(mb_strtolower($rs['email']) == mb_strtolower($email)){
				   $error_mail .= 'true';
			   }
			 }
			 if($error_user != '' || $error_mail != '')
			 die('<div class="error_alert">Duplicate UserID and/or E-mail</div>');
			 /** /control on duplicate userid and email address **/
			  $is_company = $_POST['is_company'] == 'private' ? 0 : 1;
			  $record = 'name,is_company,lastname,tax_code,email,phone,fax,address,zipcode,city,enabled,userid,password';
			  $val = "'".str_db($_POST['name'])."',";
			  $val .= "'".$is_company."',";
			  $val .= "'".($is_company ? '' : str_db($_POST['lastname']))."',";
			  $val .= "'".($is_company ? str_db($_POST['tax_code']) : '')."',";
			  $val .= "'".str_db($_POST['email'])."',";
			  $val .= "'".str_db($_POST['phone'])."',";
			  if(isset($_POST['fax']))
				$val .= "'".str_db($_POST['fax'])."',";
			  else
				$val .= "'',";
			  $val .= "'".str_db($_POST['address'])."',";
			  $val .= "'".str_db($_POST['zipcode'])."',";
			  $val .= "'".str_db($_POST['city'])."',";
			  $val .= "1,";
			  $val .= "'".str_db($_POST['useridreg'])."',";
			  $val .= "'".encryption(str_db($_POST['passwordreg']))."'";

				$sql = " insert into ".$table_prefix."clients (";
				$sql .= $record;
				$sql .= ") VALUES (";
				$sql .=  $val;
				$sql .=  ")";
				execute($sql);
				$last_id_client = mysql_insert_id();
			   $_SESSION['Clogged'] = true;
			   $_SESSION['Cid'] = $last_id_client;
			   $_SESSION['Cname'] = $_POST['name'];
			   $_SESSION['Clastname'] = $_POST['lastname'];
			   execute('update '.$table_prefix.'cart set id_client = '.$_SESSION['Cid'].' where session_client = "'.get_initial_user_session().'"');
		  }
		  /******* /registration client on the fly *************/
		   $where = isset($_SESSION['Cid']) ? $table_prefix.'cart.id_client = '.$_SESSION['Cid'] : $table_prefix.'cart.id_client = 0 and '.$table_prefix.'cart.session_client = "'.get_initial_user_session().'"';
		   $cart_items = execute('select '.$table_prefix.'products.*,
								  '.$table_prefix.'cart.id_product as id_product,
								  '.$table_prefix.'cart.id as id_product_cart,
								  '.$table_prefix.'cart.options as cart_option
								  from '.$table_prefix.'products join '.$table_prefix.'cart on '.$table_prefix.'products.id = '.$table_prefix.'cart.id_product where '.$where.' order by '.$table_prefix.'cart.id desc');
		   if(mysql_affected_rows() == 0) die('<div id="cart_empty">cart empty</div>');
		   $sub_total = 0;
		   $grand_total = 0;
		   $tax = 0;
		   $products_list = '';
		   while($rs_cart = mysql_fetch_array($cart_items)){
		      if(plugin_exsists('dgoods')){
				   if(!isset($dgoods_count)) $dgoods_count = 0;
				   if(!$rs_cart['pl_digital']) $dgoods_count++;
			  }
		    $total_qta = 0;
			  if(plugin_exsists('businesstype') && get_client_business_bc()){
				  $rs_cart['offer'] = $rs_cart['roffer'];
				  $rs_cart['price'] = $rs_cart['rprice'];
			  }
			  $get_price = $rs_cart['offer'] > 0 ? $rs_cart['offer'] : $rs_cart['price'];
			  $tax_value_price = ($rs_cart['price']*$rs_cart['tax'])/100;
			  if(plugin_exsists('multitaxes')) $tax_value_price = $tax_value_price+calculate_taxes_value($rs_cart['price'],$rs_cart['pl_multitax']);
			  $tax_value_offer = ($rs_cart['offer']*$rs_cart['tax'])/100;
			  if(plugin_exsists('multitaxes')) $tax_value_offer = $tax_value_offer+calculate_taxes_value($rs_cart['offer'],$rs_cart['pl_multitax']);
			  $img = ($rs_cart['url_image'] != '' ? path_abs_img_products.'/'.$rs_cart['id_product'].'/50x50/1_'.$rs_cart['url_image'] : theme_img_path.'/img_not_available.jpg');
			  $price_text = $rs_cart['offer'] > 0 ? $currency_l.num_formatt($rs_cart['offer']+$tax_value_offer).$currency_r : $currency_l.num_formatt($rs_cart['price']+$tax_value_price).$currency_r;
			  $arr_cart_options = unserialize($rs_cart['cart_option']);
			  $arr_product_options = unserialize($rs_cart['options']);

			  foreach($arr_cart_options as $key => $val){
				$qta = $val['qta'];
				$total_qta = $total_qta+$qta;
				$options_display = '';
				$surcharge = 0;
				$option_to_save_in_order = array();

				foreach($val['options'] as $k => $v){
				  if(isset($arr_product_options[$k]['voption'][$v])){
					  if($arr_product_options[$k]['voption'][$v]['type'] == '+')
					  $surcharge = $surcharge+$arr_product_options[$k]['voption'][$v]['price'];
					  else
					  $surcharge = $surcharge-$arr_product_options[$k]['voption'][$v]['price'];
			          $single_option_price = ($arr_product_options[$k]['voption'][$v]['price'])+(($arr_product_options[$k]['voption'][$v]['price']*$rs_cart['tax'])/100);
			          if(plugin_exsists('multitaxes')) $single_option_price = $single_option_price + calculate_taxes_value($arr_product_options[$k]['voption'][$v]['price'],$rs_cart['pl_multitax']);
					  $options_display .= '<br/><strong class="text-info">'.$arr_product_options[$k]['name'].': '
										  .$arr_product_options[$k]['voption'][$v]['value']
										  .($arr_product_options[$k]['voption'][$v]['price'] > 0
											 ?
											  ' ('.$arr_product_options[$k]['voption'][$v]['type'].' '.$currency_l.num_formatt($single_option_price).$currency_r.')'
											 :
											  ''
											)
										  .'</strong>';
					 $option_to_save_in_order[]	= array(
					                                "name" =>  $arr_product_options[$k]['name'],
					                                "value" => $arr_product_options[$k]['voption'][$v]['value'],
													"price" => $arr_product_options[$k]['voption'][$v]['price'] > 0 ? ($arr_product_options[$k]['voption'][$v]['price'])+(($arr_product_options[$k]['voption'][$v]['price']*$rs_cart['tax'])/100) : 0,
													"type" => $arr_product_options[$k]['voption'][$v]['price'] > 0 ? $arr_product_options[$k]['voption'][$v]['type'] : ''
					                              );
				  }
				}
				$single_subtotal = (($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta;
				if(plugin_exsists('multitaxes')) $single_subtotal = $single_subtotal + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$sub_total = $sub_total+(($get_price+$surcharge)*$qta);
				$grand_total = $grand_total+((($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta);
				if(plugin_exsists('multitaxes')) $grand_total = $grand_total + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$tax = $tax+(((($get_price+$surcharge)*$rs_cart['tax'])/100)*$qta);
				if(plugin_exsists('multitaxes')){
				 if($rs_cart['pl_multitax'] != ''){
				   foreach(explode(',',$rs_cart['pl_multitax']) as $tax_id){
					$arr_multitaxes[] = array($tax_id => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
					$arr_multitax_order[] = array(get_tax_param($tax_id,'name') => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
				   }
				 }
				}
				if(plugin_exsists('dgoods')){
				  $session_guest_download = '';
				  if($guest_purchases && !isset($_SESSION['Cid']) && $_POST['account_type'] == 'guest'){
					 $session_guest_download = random_cod(5).'-'.random_cod(5).'-'.random_cod(8);
				  }
				}
				$products_list .=	'<tr>
										  <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
											  <div align="left" class="article-content"><multiline label="Description">
											   <strong>'.$rs_cart['name'].'</strong><br>
											   '.$price_text.' x '.$qta.$options_display.'<br>'.$lang_client_['save_order']['SUBTOTAL_LABEL'].': '.$currency_l.num_formatt($single_subtotal).$currency_r.'
											  </multiline></div>
										  </td>
									  </tr>';
			   /** array for products to order **/
			   if(plugin_exsists('dgoods') && $rs_cart['pl_digital']){
				 $arr_digital_goods[] = array(
				  "dig_code" => $rs_cart['pl_digital_code'],
				  "file_name" => $rs_cart['name']
			     );
				 if(isset($dgoods_count) && $dgoods_count == 0) $shipping_price = 0;
			   }
			   $arr_product_ordered[] = array(
				"name" => $rs_cart['name'],
				"price" => $rs_cart['price'],
				"offer" => $rs_cart['offer'],
				"subtotal" => @number_format($single_subtotal,4,'.',''),
				"option" => $option_to_save_in_order,
				"qta" => $qta,
				"id" => $rs_cart['id_product'],
				"tax_percentage" => $rs_cart['tax'],
				"link_img" => $rs_cart['url_image'] != '' ? $rs_cart['id_product'].'/300x300/1_'.$rs_cart['url_image'] : ''
			   );
			   /** array for products to update qta into products table **/
			   if($rs_cart['unlimited_availability'] == 0) $arr_product_availability[$rs_cart['id_product']] = ($rs_cart['availability']-$total_qta);
			  }
			 /** array for products to update qta into cart **/
			 if($total_qta > $rs_cart['availability'] && $rs_cart['availability'] > 0 && $rs_cart['unlimited_availability'] == 0){
			  $arr_qta_updated[$rs_cart['id_product_cart']] = array("availability" => $rs_cart['availability']);
			 }
			 /** array for products to delete into cart **/
			 elseif($total_qta > 0 && $rs_cart['availability'] == 0 && $rs_cart['unlimited_availability'] == 0){
			  $arr_product_deleted[] = $rs_cart['id_product_cart'];
				$sub_total = $sub_total-(($get_price+$surcharge)*$qta);
				$grand_total = $grand_total-((($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta);
				if(plugin_exsists('multitaxes')) $grand_total = $grand_total - (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$tax = $tax-(((($get_price+$surcharge)*$rs_cart['tax'])/100)*$qta);
			 }
			 /** all ok, generate list products template for email **/
			 else{

			 }
		   }
		/** generate a warning alert for product that exceeded max availability **/
		if(!empty($arr_qta_updated)){
		  echo '<div id="products_to_update">[';
		  end($arr_qta_updated);
		  $lastElement = key($arr_qta_updated);
		   foreach($arr_qta_updated as $key => $val){
			   echo '{"id":'.$key.',"availability":'.intval($val['availability']).'}'.($key == $lastElement ? '' : ',');
		   }
		  echo ']</div>';
		}
		/** delete products with availability = 0 from cart and generate an error alert for each product **/
		if(!empty($arr_product_deleted)){
		 $ids = implode(',', $arr_product_deleted);
		 execute('delete from '.$table_prefix.'cart where id IN ('.$ids.')');
		  echo '<div id="products_to_delete">[';
		  end($arr_product_deleted);
		  $lastElement = end($arr_product_deleted);
		   foreach($arr_product_deleted as $val){
			   echo '{"id":'.$val.'}'.($val == $lastElement ? '' : ',');
		   }
		  echo ']</div>';
		  echo '<div id="new_report_data">[';
		    echo '{"subtotal":"'.num_formatt($sub_total).'"},
			      {"tax":"'.num_formatt($tax).'"},
				  {"grandtotal_unformatted":'.round($grand_total+$shipping_price+$_POST['payment_price'],2).'},
			      {"grandtotal":"'.num_formatt($grand_total+$shipping_price+$_POST['payment_price']).'"}';
		  echo ']</div>';
          if(plugin_exsists('multitaxes')){
						   $where = isset($_SESSION['Cid']) ? $table_prefix.'cart.id_client = '.$_SESSION['Cid'] : $table_prefix.'cart.id_client = 0 and '.$table_prefix.'cart.session_client = "'.get_initial_user_session().'"';
						   $cart_items = execute('select '.$table_prefix.'products.*,
												  '.$table_prefix.'cart.id_product as id_product,
												  '.$table_prefix.'cart.id as id_product_cart,
												  '.$table_prefix.'cart.options as cart_option
												  from '.$table_prefix.'products join '.$table_prefix.'cart on '.$table_prefix.'products.id = '.$table_prefix.'cart.id_product where '.$where.' order by '.$table_prefix.'cart.id desc');
						   while($rs_cart = mysql_fetch_array($cart_items)){
							  if(plugin_exsists('businesstype') && get_client_business_bc()){
								  $rs_cart['offer'] = $rs_cart['roffer'];
								  $rs_cart['price'] = $rs_cart['rprice'];
							  }
							  $get_price = $rs_cart['offer'] > 0 ? $rs_cart['offer'] : $rs_cart['price'];
							  $arr_cart_options = unserialize($rs_cart['cart_option']);
							  $arr_product_options = unserialize($rs_cart['options']);
							  foreach($arr_cart_options as $key => $val){
								$qta = $val['qta'];
								$surcharge = 0;
							    $arr_multitaxes = array();
								foreach($val['options'] as $k => $v){
								  if(isset($arr_product_options[$k]['voption'][$v])){
									  if($arr_product_options[$k]['voption'][$v]['type'] == '+')
									  $surcharge = $surcharge+$arr_product_options[$k]['voption'][$v]['price'];
									  else
									  $surcharge = $surcharge-$arr_product_options[$k]['voption'][$v]['price'];
								  }
								}
								if(plugin_exsists('multitaxes')){
								 if($rs_cart['pl_multitax'] != ''){
								   foreach(explode(',',$rs_cart['pl_multitax']) as $tax_id){
									$arr_multitaxes[] = array($tax_id => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
								   }
								 }
								}
							  }
						   }
						   if(!empty($arr_multitaxes)){
							  $taxes_del = array_merge_numeric_values($arr_multitaxes);
							  echo '<div id="multi_tax_div_container">[';
								$multitax_json = '';
								foreach($taxes_del as $key => $val){
									$multitax_json .=  '{"tax_data":"'.get_tax_param($key,'name').'__'.$currency_l.num_formatt($val).$currency_r.'"},';
								}
								$multitax_json = $multitax_json != '' ? mb_substr($multitax_json,0,-1) : '' ;
							  echo $multitax_json.']</div>';
						   }
           }
		}
        /** there are no errors, proceed to save data **/
		if(empty($arr_qta_updated) && empty($arr_product_deleted)){
			$random_code = random_cod(10);
		/** save order into ORDERS TABLE **/
		 $billing_address = array(
		                      "name" => isset($_POST['is_company']) && $_POST['is_company'] ? ucwords($_POST['name']) : ucwords($_POST['name'].' '.$_POST['lastname']),
							  "address" => $_POST['address'],
							  "city" => $_POST['city'],
							  "zipcode" => $_POST['zipcode'],
							  "phone" => $_POST['phone'],
							  "fax" => $_POST['fax'] != '' ? $_POST['fax'] : '',
							  "email" => $_POST['email']
		                    );
		 $shipping_address = array(
		                      "name" => isset($_POST['is_company']) && $_POST['is_company'] ? ucwords($_POST['names']) : ucwords($_POST['names'].' '.$_POST['lastnames']),
							  "address" => $_POST['addresss'],
							  "city" => $_POST['citys'],
							  "zipcode" => $_POST['zipcodes'],
							  "phone" => $_POST['phones'],
							  "fax" => $_POST['faxs'] != '' ? $_POST['faxs'] : '',
							  "email" => $_POST['emails']
		                    );
		/** send email to client **/
		 require_once(theme_rel_path.'/emails/order_client.php');
		 require_once(theme_rel_path.'/emails/order_admin.php');
		 $logo = abs_uploads_path.'/bc_logo.png';
		 $categories_list = '';
			 $res_cat = execute('select id,name from '.$table_prefix.'categories where level = 0 and status = 1 order by name asc');
			 while($rs_cat = mysql_fetch_array($res_cat)){
								 $categories_list .= '<tr>
									<td width="3"></td>
									<td valign="top"><p align="left" class="toc-item"></p></td>
									<td width="6"></td>
									<td valign="top"><p align="left" class="toc-item"><strong>
									  <a href="'.path_abs_products.'/'.$mptt->get_orizzontal_path($rs_cat['id']).'">'.$rs_cat['name'].'</a></strong></p></td>
								</tr>';
			 }
        $products_report =  '<tr>
                                        <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.$lang_client_['save_order']['SUBTOTAL_LABEL'].'</strong> '.$currency_l.num_formatt($sub_total).$currency_r.'
                                            </multiline></div>
                                        </td>
                                    </tr>';
        $products_report .=  '<tr>
                                        <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.$tax_name.'</strong> '.$currency_l.num_formatt($tax).$currency_r.'
                                            </multiline></div>
                                        </td>
                                    </tr>';
	if(plugin_exsists('multitaxes') && !empty($arr_multitaxes)){
	 $taxes_sum = array_merge_numeric_values($arr_multitaxes);
	 foreach($taxes_sum as $key => $val){
        $products_report .=  '<tr>
                                        <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.get_tax_param($key,'name').'</strong> '.$currency_l.num_formatt($val).$currency_r.'
                                            </multiline></div>
                                        </td>
                                    </tr>';
	 }
	}
        $products_report .=  '<tr>
                                        <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.$lang_client_['save_order']['SHIPPING_LABEL'].'</strong> '.$currency_l.num_formatt($shipping_price).$currency_r.'
                                            </multiline></div>
                                        </td>
                                    </tr>';
        $products_report .=  '<tr>
                                        <td class="w410" width="410" style="border-bottom:1px solid #28779c;">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.$lang_client_['save_order']['PAYEMENT_COST_LABEL'].'</strong> '.$currency_l.num_formatt($_POST['payment_price']).$currency_r.'
                                            </multiline></div>
                                        </td>
                                    </tr>';
        $products_report .=  '<tr>
                                        <td class="w410" width="410">
                                            <div align="right" class="article-content"><multiline label="Description">
                                             <strong>'.$lang_client_['save_order']['TOTAL_LABEL'].' '.$currency_l.num_formatt(($grand_total+$shipping_price)+$_POST['payment_price']).$currency_r.'</strong>
                                            </multiline></div>
                                        </td>
                                    </tr>';
		 switch($_POST['payment_method']){
			case $cash_on_delivery['orders_prefix']:
			$payment_message = $cash_on_delivery['email_message'];
			$payment_method = $cash_on_delivery['long_name'];
			break;
			case $paypal['orders_prefix']:
			$payment_message = $paypal['email_message'];
			$payment_method = $paypal['long_name'];
			break;
			case $bank_transfer['orders_prefix']:
			$payment_message = $bank_transfer['email_message'];
			$payment_method = $bank_transfer['long_name'];
			break;
		 }
		$s_address = '<strong>'.ucwords($_POST['names'].' '.$_POST['lastnames']).'</strong><br>'
		  .$_POST['addresss'].'<br>'
		  .$_POST['citys'].' - '.$_POST['zipcodes'].'<br>
		  T: '.$_POST['phones'].'<br/>'.
		  ($_POST['faxs'] != '' ? 'F: '.$_POST['faxs'].'<br/>' : '').'
		  @: '.$_POST['emails'];
		$b_address = '<strong>'.ucwords($_POST['name'].' '.$_POST['lastname']).'</strong><br>'
		  .$_POST['address'].'<br>'
		  .$_POST['city'].' - '.$_POST['zipcode'].'<br>
		  T: '.$_POST['phone'].'<br/>'.
		  ($_POST['fax'] != '' ? 'F: '.$_POST['fax'].'<br/>' : '').'
		  @: '.$_POST['email'];
		 $message = str_replace('{template_logo}',$logo,$email_template_client);
		 $message = str_replace('{shop_url}',$shop_url,$message);
		 $message = str_replace('{template_categories}',$categories_list,$message);
		 $message = str_replace('{tamplate_order_code}',$random_code.(plugin_exsists('dgoods') && !empty($arr_digital_goods) ? '<p><strong>'.$lang_client_['pl_dgoods']['EMAIL_CLIENT_NOTICE_DIGITAL_GOODS_DOWNLOAD'].'</strong></p>' : ''),$message);
		 $message = str_replace('{template_products_list}',$products_list,$message);
		 $message = str_replace('{template_products_report}',$products_report,$message);
		 $message = str_replace('{tamplate_payment_method}',$payment_method.($_POST['payment_method'] == $paypal['orders_prefix'] ? ' <a href="'.abs_client_path.'/paypal-pay.php?ses='.get_initial_user_session().'&id_o='.$random_code.'">'.$lang_client_['client_account']['BUTTON_PAY_NOW'].'</a>' : ''),$message);
		 $message = str_replace('{tamplate_payment_message}',$payment_message,$message);
		 $message = str_replace('{tamplate_billing_address}',$b_address,$message);
		 $message = str_replace('{tamplate_shipping_address}',$s_address,$message);
		 $message = str_replace('{footer_message}',str_replace('{shop_url}',$shop_url,$lang_client_['save_order']['EMAIL_FOOTER']),$message);
		 $message = str_replace('{footer_address}',$company_name.'<br/>'.$company_address.' - '.$company_zipcode.' '.$company_city,$message);
		 $message = str_replace('{CLIENT_CONGRATS_MESSAGE}',$lang_client_['save_order']['EMAIL_CLIENT_CONGRATS_MESSAGE'],$message);
		 $message = str_replace('{EMAIL_TEXT_ORDER_CODE}',$lang_client_['save_order']['EMAIL_TEXT_ORDER_CODE'],$message);
		 $message = str_replace('{EMAIL_TEXT_PRODUCTS_ORDERED}',$lang_client_['save_order']['EMAIL_TEXT_PRODUCTS_ORDERED'],$message);
		 $message = str_replace('{EMAIL_TEXT_PAYMENT_INFO}',$lang_client_['save_order']['EMAIL_TEXT_PAYMENT_INFO'],$message);
		 $message = str_replace('{EMAIL_TEXT_CHOOSEN_PAYMENT_METHOD}',$lang_client_['save_order']['EMAIL_TEXT_CHOOSEN_PAYMENT_METHOD'],$message);
		 $message = str_replace('{EMAIL_TEXT_BILLING_ADDRESS}',$lang_client_['save_order']['EMAIL_TEXT_BILLING_ADDRESS'],$message);
		 $message = str_replace('{EMAIL_TEXT_SHIPPNG_ADDRESS}',$lang_client_['save_order']['EMAIL_TEXT_SHIPPNG_ADDRESS'],$message);

		 $message_admin = str_replace('{template_logo}',$logo,$email_template_admin);
		 $message_admin = str_replace('{shop_url}',$shop_url,$message_admin);
		 $message_admin = str_replace('{tamplate_order_code}',$random_code,$message_admin);
		 $message_admin = str_replace('{tamplate_dataclient_name}',ucwords($_POST['names'].' '.$_POST['lastnames']),$message_admin);
		 $message_admin = str_replace('{tamplate_dataclient_payment_method}',$payment_method,$message_admin);
		 $message_admin = str_replace('{template_products_list}',$products_list,$message_admin);
		 $message_admin = str_replace('{template_products_report}',$products_report,$message_admin);
		 $message_admin = str_replace('{tamplate_billing_address}',$b_address,$message_admin);
		 $message_admin = str_replace('{tamplate_shipping_address}',$s_address,$message_admin);
		 $message_admin = str_replace('{EMAIL_ADMIN_CONGRATS_MESSAGE}',$lang_client_['save_order']['EMAIL_ADMIN_CONGRATS_MESSAGE'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_CLIENT_NAME}',$lang_client_['save_order']['EMAIL_TEXT_CLIENT_NAME'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_PAYMENT}',$lang_client_['save_order']['EMAIL_TEXT_PAYMENT'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_ORDER_CODE}',$lang_client_['save_order']['EMAIL_TEXT_ORDER_CODE'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_PRODUCTS_ORDERED}',$lang_client_['save_order']['EMAIL_TEXT_PRODUCTS_ORDERED'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_BILLING_ADDRESS}',$lang_client_['save_order']['EMAIL_TEXT_BILLING_ADDRESS'],$message_admin);
		 $message_admin = str_replace('{EMAIL_TEXT_SHIPPNG_ADDRESS}',$lang_client_['save_order']['EMAIL_TEXT_SHIPPNG_ADDRESS'],$message_admin);
		 $mail = new PHPMailer();
		  if($smtp_email){
		   $mail->IsSMTP();
		   $mail->Port = $smtp_port;
		   $mail->Host = $smtp_host;
		   $mail->Mailer = 'smtp';
		   $mail->SMTPAuth = true;
		   $mail->Username = $smtp_user;
		   $mail->Password = $smtp_password;
		   $mail->SMTPSecure = $smtp_secure;
		   $mail->SingleTo = true;
		  }
		 $mail->CharSet = 'UTF-8';
		 $mail->From = $admin_email;
		 $mail->FromName = $admin_email;
		 $mail->AddAddress($_POST['email']);
		 $mail->AddReplyTo($admin_email);
		 $mail->Sender=$admin_email;
		 $mail->IsHTML(true);
		 $mail->Subject = str_replace('{shop_name}',$shop_title,$lang_client_['save_order']['EMAIL_CLIENT_SUBJECT']);
		 $mail->Body = $message;
		  if($mail->Send()){
		  }else{
			  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
		  }
		/** send email to admin **/
		$mail = new PHPMailer();
		  if($smtp_email){
		   $mail->IsSMTP();
		   $mail->Port = $smtp_port;
		   $mail->Host = $smtp_host;
		   $mail->Mailer = 'smtp';
		   $mail->SMTPAuth = true;
		   $mail->Username = $smtp_user;
		   $mail->Password = $smtp_password;
		   $mail->SMTPSecure = $smtp_secure;
		   $mail->SingleTo = true;
		  }
		 $mail->CharSet = 'UTF-8';
		 $mail->From = $_POST['email'];
		 $mail->FromName = $_POST['email'];
		 $mail->AddAddress($admin_email);
		 $mail->AddReplyTo($_POST['email']);
		 $mail->Sender=$_POST['email'];
		 $mail->IsHTML(true);
		 $mail->Subject = $lang_client_['save_order']['EMAIL_ADMIN_SUBJECT'];
		 $mail->Body = $message_admin;
		  if($mail->Send()){
		  }else{
			  die('<div class="error_alert">Message was not sent <br />PHP Mailer Error: ' . $mail->ErrorInfo.'</div>');
		  }
		/** delete all product into cart for this client **/
		 execute('delete from '.$table_prefix.'cart where '.$where);
        /** insert order into database **/
		 if($guest_purchases && !isset($_SESSION['Cid']) && $_POST['account_type'] == 'register'){
			/* for register */
			$record = 'id_client,';
			$record .= 'session_client,';
			$val  = "'".str_db($last_id_client)."',";
			$val .= "'".str_db(get_initial_user_session())."',";
		 }elseif($guest_purchases && !isset($_SESSION['Cid']) && $_POST['account_type'] == 'guest'){
			/* for guest */
			$record = 'session_client,';
			$record .= 'guest,';
			$val  = "'".str_db(get_initial_user_session())."',";
			$val  .= "1,";
		 }else{
			/* for logged */
			$record = 'id_client,';
			$record .= 'session_client,';
			$val  = "'".str_db($_SESSION['Cid'])."',";
			$val .= "'".str_db(get_initial_user_session())."',";
		 }
		 if(plugin_exsists('multitaxes') && !empty($arr_multitax_order)){
			$record .= 'pl_multitax_array,';
		 }
		 $record .= 'products_list,data,subtotal,grandtotal,tax,shipping_price,payment_method,payment_price,billing_address,shipping_address,code_order';
		 if(plugin_exsists('multitaxes') && !empty($arr_multitax_order)){
			$val .= "'".serialize(str_serialize($arr_multitax_order))."',";
		 }
		 $val .= "'".serialize(str_serialize($arr_product_ordered))."',";
		 $val .= "'".date("Y-m-d H:i:s")."',";
		 $val .= "'".str_db($sub_total)."',";
		 $val .= "'".str_db(($grand_total+$shipping_price)+$_POST['payment_price'])."',";
		 $val .= "'".str_db($tax)."',";
		 $val .= "'".str_db($shipping_price)."',";
		 $val .= "'".str_db($_POST['payment_method'])."',";
		 $val .= "'".str_db($_POST['payment_price'])."',";
		 $val .= "'".serialize(str_serialize($billing_address))."',";
		 $val .= "'".serialize(str_serialize($shipping_address))."',";
		 $val .= "'".$random_code."'";
		$sql = " insert into ".$table_prefix."orders (";
		$sql .= $record;
		$sql .= ") VALUES (";
		$sql .=  $val;
		$sql .=  ")";
		execute($sql);
		$order_id = mysql_insert_id();
		/** insert into customers_download table for this client **/
		 if(plugin_exsists('dgoods') && !empty($arr_digital_goods)){
		   $insert_status = '';
		   $guest_download = 0;
		   if($guest_purchases && !isset($_SESSION['Cid']) && $_POST['account_type'] == 'guest'){
			 $guest_download = 1;
		   }
		   foreach($arr_digital_goods as $key => $val){
			  $insert_status .= '('.$order_id.',"'.$val['dig_code'].'","'.$val['file_name'].'",'.(isset($_SESSION['Cid']) ? $_SESSION['Cid'].',' : '"",').($session_guest_download != '' ? '"'.$session_guest_download.'",' : '"",').$guest_download.'),';
		   }
		   if($insert_status != ''){
			    $insert_status = mb_substr($insert_status,0,-1);
		       execute('insert into '.$table_prefix.'customers_downloads (id_order,download_code,file_name,id_client,session_guest,guest) VALUES '.$insert_status);
		   }
		 }
		/** update availability for each product into products table **/
		 if(!empty($arr_product_availability)){
		  $ids = implode(',', array_keys($arr_product_availability));
		  $sql = "UPDATE ".$table_prefix."products SET availability = CASE id ";
		  foreach ($arr_product_availability as $id => $qtas) {
			  $sql .= sprintf("WHEN %d THEN %d ", $id, $qtas);
		  }
		  $sql .= "END WHERE id IN ($ids) and unlimited_availability = 0";
		  execute($sql);
		 }
		  echo '<div id="order_id">'.$order_id.'</div>';
		}
 mysql_query('UNLOCK TABLES');
 @mysql_close(conn); // close connection to DB
?>