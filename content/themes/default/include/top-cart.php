<?php
 $where = isset($_SESSION['Cid']) ? $table_prefix.'cart.id_client = '.$_SESSION['Cid'] : $table_prefix.'cart.id_client = 0 and '.$table_prefix.'cart.session_client = "'.get_initial_user_session().'"';
 $cart_items = execute('select '.$table_prefix.'products.*,
						'.$table_prefix.'cart.id_product as id_product,
						'.$table_prefix.'cart.id as id_product_cart,
						'.$table_prefix.'cart.options as cart_option
						from '.$table_prefix.'products join '.$table_prefix.'cart on '.$table_prefix.'products.id = '.$table_prefix.'cart.id_product where '.$where.' order by '.$table_prefix.'cart.id desc');
 $num_products = 0;
 $sub_total = 0;
 $grand_total = 0;
 $tax = 0;
 $products_rows = '';
 //$count_rows = 1;  
 while($rs_cart = mysql_fetch_array($cart_items)){	 
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
	$price_text = $rs_cart['offer'] > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($rs_cart['price']+$tax_value_price).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($rs_cart['offer']+$tax_value_offer).$currency_r.'</span>' : $currency_l.num_formatt($rs_cart['price']+$tax_value_price).$currency_r;
	$arr_cart_options = unserialize($rs_cart['cart_option']);
	$arr_product_options = unserialize($rs_cart['options']);
	
	foreach($arr_cart_options as $key => $val){
	  $qta = $val['qta'];
	  $options_display = '';
	  $surcharge = 0;
	  
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
		}			  
	  }	
	  
	  $num_products = $num_products+$qta;
	  $sub_total = $sub_total+(($get_price+$surcharge)*$qta);
	  $grand_total = $grand_total+((($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta);
	  if(plugin_exsists('multitaxes')) $grand_total = $grand_total + (calculate_taxes_value($get_price+$surcharge,$rs_cart['pl_multitax'])*$qta);
	  $tax = $tax+(((($get_price+$surcharge)*$rs_cart['tax'])/100)*$qta);
	  if(plugin_exsists('multitaxes')){
	   if($rs_cart['pl_multitax'] != ''){
		 foreach(explode(',',$rs_cart['pl_multitax']) as $tax_id){
		  $arr_multitaxes[] = array($tax_id => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
		 }
	   }
	  }   
	  //if($count_rows <= 4){	  
	  $products_rows .= ' <tr>
							  <td class="image">
							   <img class="lazy" data-original="'.$img.'" src="'.$img.'" alt="'.str_replace('"',"'",$rs_cart['name']).'" />
							  </td>
							  <td class="name">
								<a href="'.path_abs_products.'/'.$rs_cart['id_product'].'-'.filesystem($rs_cart['name']).'.php">'.$rs_cart['name'].'</a><br/>
								'.($view_prices ? '<strong>'.$qta.'</strong> x '.$price_text : '').'
								'.$options_display.'
							  </td>  							                                
							  <td class="remove"><span class="btn btn-danger btn-mini remove-from-cart tooltiped" title="Remove from Cart" data-options-item="'.$key.'" data-id-item="'.$rs_cart['id_product_cart'].'"><i class="icon-white icon-trash"></i></span></td>
						  </tr>';
	//}
	//$count_rows++;						  	    	   
	}
 }
?>
  <div class="row-fluid">
    <div class="span12 text-right">
           <ul class="nav topcart">
                <li class="dropdown"> <span class="dropdown-toggle" data-toggle="dropdown"> <i class="icon32 icon-basket icon-black"></i> <span class="label label-info"><?php echo $num_products.' '.($num_products > 1 || $num_products == 0 ? $lang_client_['cart']['TEXT_PRODUCT_PLURAL'] : $lang_client_['cart']['TEXT_PRODUCT_SINGULAR']); ?></span><?php echo ($view_prices ? ' - '.$currency_l.num_formatt($grand_total).$currency_r : ''); ?> <b class="caret"></b></span>                  
                    <ul class="dropdown-menu squared">
                        <li>
                            <table>
                                <tbody class="product-tbody-container">
                                  <?php echo $products_rows; ?>
                                </tbody>
                            </table>
                            <?php
							  if($view_prices){
						    ?>
                            <table>
                                <tbody class="counts-container">
                                    <tr>
                                        <td class="text-right"><strong><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_SUBTOTAL']; ?>:</strong></td>
                                        <td class="text-right subtotal"><?php echo $currency_l.num_formatt($sub_total).$currency_r; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong><?php echo $tax_name; ?>:</strong></td>
                                        <td class="text-right tax"><?php echo $currency_l.num_formatt($tax).$currency_r; ?></td>
                                    </tr>
									<?php
                                     if(plugin_exsists('multitaxes') && !empty($arr_multitaxes)){
                                      $taxes_sum = array_merge_numeric_values($arr_multitaxes);
                                      foreach($taxes_sum as $key => $val){
                                    ?>
                                    <tr>
                                        <td><strong><?php echo get_tax_param($key,'name'); ?>:</strong></td>
                                        <td class="tax"><?php echo $currency_l.num_formatt($val).$currency_r; ?></td>
                                    </tr>                                        
                                    <?php	  
                                      }
                                     }
                                    ?>                                    
                                    <tr>
                                        <td class="text-right"><strong><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_TOTAL']; ?>:</strong></td>
                                        <td class="text-right total"><?php echo $currency_l.num_formatt($grand_total).$currency_r; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                           <?php
							  }
						   ?>
                            <div class="text-center cart-options-container"> <a class="btn btn-info squared solid unbordered" href="<?php echo abs_client_path; ?>/cart.php"><?php echo $lang_client_['general']['TEXT_CART']; ?></a> <a class="btn btn-primary squared solid unbordered" href="<?php echo abs_client_path; ?>/check-out.php"><?php echo $lang_client_['general']['BUTTON_CHECKOUT']; ?></a> </div>
                        </li>
                    </ul>
                </li>
            </ul>    
    </div>
  </div>