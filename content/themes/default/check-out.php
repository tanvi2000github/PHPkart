<?php
 $page_title = $lang_client_['checkout']['PAGE_TITLE'];
 require_once('include/header.php');
?>
 <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?>
     <section class="row-fluid"><!-- BODY breadcrumb -->
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <li><a href="<?php echo abs_client_path ?>/cart.php"><?php echo $lang_client_['checkout']['DREADCRUMB_CART']; ?></a> <span class="divider">/</span></li>
          <li class="active"><?php echo $page_title; ?></li>
        </ul>
     </section><!-- / BODY breadcrumb -->
	<?php
     if(isset($_SESSION['Clogged']) || $guest_purchases){
		 if(isset($_SESSION['Clogged'])){
		   $sql_client = execute('select * from '.$table_prefix.'clients where id='.$_SESSION['Cid']);
		   $rs_cli = mysql_fetch_array($sql_client);
		 }
		 /********* CART PRODUCTS **************/
		   $where = isset($_SESSION['Cid']) ? $table_prefix.'cart.id_client = '.$_SESSION['Cid'] : $table_prefix.'cart.id_client = 0 and '.$table_prefix.'cart.session_client = "'.get_initial_user_session().'"';
		   $cart_items = execute('select '.$table_prefix.'products.*,
								  '.$table_prefix.'cart.id_product as id_product,
								  '.$table_prefix.'cart.id as id_product_cart,
								  '.$table_prefix.'cart.options as cart_option
								  from '.$table_prefix.'products join '.$table_prefix.'cart on '.$table_prefix.'products.id = '.$table_prefix.'cart.id_product where '.$where.' order by '.$table_prefix.'cart.id desc');
		   $sub_total = 0;
		   $grand_total = 0;
		   $tax = 0;
		   $products_rows = '';
		   while($rs_cart = mysql_fetch_array($cart_items)){
			  if(plugin_exsists('businesstype') && get_client_business_bc()){
				  $rs_cart['offer'] = $rs_cart['roffer'];
				  $rs_cart['price'] = $rs_cart['rprice'];
			  }
          if(plugin_exsists('dgoods')){
           if(!isset($dgoods_count)) $dgoods_count = 0;
           if(!$rs_cart['pl_digital']) $dgoods_count++;
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
			  $row_options = '';
			  foreach($arr_cart_options as $key => $val){
				$qta = $val['qta'];
				$surcharge = 0;
				$options_display = '';
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
				$single_subtotal = (($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta;
				if(plugin_exsists('multitaxes')) $single_subtotal = $single_subtotal + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$row_options .= '<tr>
								  <td class="text-left">'.$price_text.' x '.$qta.$options_display.'</td>
								  <td class="text-right">'.$currency_l.num_formatt($single_subtotal).$currency_r.'</td>
								 </tr>';
				$sub_total = $sub_total+(($get_price+$surcharge)*$qta);
				$grand_total = $grand_total+((($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta);
				if(plugin_exsists('multitaxes')) $grand_total = $grand_total + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$tax = $tax+(((($get_price+$surcharge)*$rs_cart['tax'])/100)*$qta);
				if(plugin_exsists('multitaxes')){
				 if($rs_cart['pl_multitax'] != ''){
				   foreach(explode(',',$rs_cart['pl_multitax']) as $tax_id){
					$arr_multitaxes[] = array($tax_id => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
				   }
				 }
				}
			  }
				$products_rows .= '<tbody id="product_'.$rs_cart['id_product_cart'].'"><tr>
										<td class="text-left" colspan="2"><strong>'.$rs_cart['name'].'</strong></td>
									</tr>
									'.$row_options.'
                                    <tr class="warning hide container-warning">
										<td class="text-left" colspan="2"></td>
									</tr>
                                    <tr class="tr_error hide container-error">
										<td class="text-left" colspan="2"></td>
									</tr>
									</tbody>';
		   }
      if(plugin_exsists('dgoods')){
         if(isset($dgoods_count) && $dgoods_count == 0) $shipping_price = 0;
      }
		   $alert_hide = '';
		   $list_show = ' hide';
           if($products_rows != ''){
			   $alert_hide = ' hide';
			   $list_show = '';
		   }
          /**************************************/
    ?>
      <div class="alert-cart-container row-fluid<?php echo $alert_hide; ?>">
        <div class="span12">
           <div class="alert alert-warning alert-block squared solid unbordered"><h4><?php echo $lang_client_['checkout']['ALERT_CART_EMPTY']; ?></h4></div>
            <br/>
            <a class="btn btn-info btn-large squared solid unbordered pull-right" href="<?php echo abs_client_path; ?>"><?php echo $lang_client_['general']['BUTTON_RETURN_TO_SHOPPING']; ?></a>
        </div>
      </div>
      <div class="order-success row-fluid hide">
        <div class="span12">
           <div class="alert alert-success alert-block squared solid unbordered"><h4><?php echo str_replace('{link}',abs_client_path.'/contacts.php',str_replace('{link_name}',$lang_client_['general']['TEXT_CONTACT_US'],$lang_client_['checkout']['ALERT_ORDER_SUCCESS'])); ?>
           </h4></div>
            <br/>
            <a class="btn btn-info btn-large squared solid unbordered pull-right return-shopping" href="<?php echo abs_client_path; ?>"><?php echo $lang_client_['general']['BUTTON_RETURN_TO_SHOPPING']; ?></a>
        </div>
      </div>
     <section class="row-fluid checkout-page<?php echo $list_show; ?>"><!-- BODY ROW -->
	    <aside class="span4">
          <div class="box-header">
               <span class="header-text"><i class="icon icon-black icon-check"></i> <?php echo $lang_client_['checkout']['ORDER_PROGRESS_TEXT']; ?></span>
          </div>
          <?php
		    if(!isset($_SESSION['Clogged']) && $guest_purchases){
		  ?>
          <span data-steps-rel="fstep_1" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_CHECKOUT_METHOD']; ?></h5></span>
          <?php
			}
		  ?>
          <span data-steps-rel="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '2' : '1'; ?>" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_BILLING_ADDRESS']; ?></h5></span>
          <span data-steps-rel="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '3' : '2'; ?>" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SHIPPING_ADDRESS']; ?></h5></span>
          <span data-steps-rel="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '4' : '3'; ?>" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_PAYMENT']; ?></h5></span>
          <span data-steps-rel="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '5' : '4'; ?>" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SHIPPING']; ?></h5></span>
          <span data-steps-rel="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '6' : '5'; ?>" data-steps-rel-form="checkout-form" class="check-step"><h5><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SUMMARY']; ?></h5></span>
        </aside>

        <section class="span8">
          <div class="default-status-registration-form">
           <form method="post" action="<?php echo abs_client_path; ?>/save-order.php" accept-charset="UTF-8" id="checkout-form">
           <?php if(isset($_SESSION['Clogged']) && $rs_cli['is_company']) echo '<input name="is_company" value="1" type="hidden" />';?>
           <?php
		     if(!isset($_SESSION['Clogged']) && $guest_purchases){
		   ?>
             <div id="fstep_1">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_CHECKOUT_METHOD']; ?></span>
                  </div>
                  <div class="row-fluid">
                   <div class="span6">
                     <div style="background:#fff;border:1px solid #09c;padding:5px;display:inline-block;margin-right:20px;width:100px;">
                       <label class="radio" for="register">
                        <input type="radio" value="register" name="account_type" id="register" data-toggle="radio" checked/> <?php echo $lang_client_['checkout']['FIELD_LABEL_REGISTER']; ?>
                       </label>
                     </div>
                     <div style="background:#fff;border:1px solid #09c;padding:5px;display:inline-block;width:100px;">
                       <label class="radio" for="guest">
                        <input type="radio" value="guest" name="account_type" id="guest" data-toggle="radio" /> <?php echo $lang_client_['checkout']['FIELD_LABEL_GUEST']; ?>
                       </label>
                     </div>
                       <br/><br/>
                       <div class="text-info">
                        <?php echo $lang_client_['checkout']['ADVICE_FOR_GUEST_CUSTOMERS']; ?>
                        <?php echo $lang_client_['checkout']['ALREADY_REGISTERED_TEX_QUESTION']; ?> <span class="btn btn-info unbordered solid squared" id="login_scroll_top"><i class="icon icon-white icon-unlocked"></i> <?php echo $lang_client_['general']['TEXT_LOGIN']; ?></span>
                       </div>
                   </div>
                   <div class="span6">
                    <div class="choose_credentials">
                     <?php echo $lang_client_['checkout']['COOSE_YOUR_CREDENTIALS_TEX']; ?><br/>
                         <div class="control-group">
                           <div class="controls">
                             <div class="input-prepend">
                              <span class="add-on"><i class="icon-user"></i></span>
                              <input type="text" name="useridreg" id="useridreg" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_USERID']; ?>" value="" />
                             </div>
                           </div>
                         </div>
                         <div class="control-group">
                           <div class="controls">
                             <div class="input-prepend">
                              <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                              <input type="password" name="passwordreg" id="passwordreg" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_PASSWORD']; ?>" value="" />
                            </div>
                          </div>
                        </div>
                         <div class="control-group">
                           <div class="controls">
                             <div class="input-prepend">
                              <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                              <input type="password" name="passwordreg2" id="passwordreg2" equalTo="#passwordreg" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_REPEAT_PASSWORD']; ?>" value="" />
                            </div>
                          </div>
                        </div>
                      </div>
                   </div>
                  </div>
             </div>
            <?php
			 }
			?>
             <div class="principal_address" id="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '2' : '1'; ?>">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_BILLING_ADDRESS']; ?></span>
                  </div>
                  <?php
				   if(!isset($_SESSION['Clogged']) && $guest_purchases){
				  ?>
                    <div class="checkradio-group" data-icon="icon-ok icon-white">
                      <input type="radio" id="private" name="is_company" data-label-name="<?php echo $lang_client_['client_registration']['FIELD_LABEL_PRIVATE_TYPE']; ?>" data-additional-classes="btn-info squared unbordered solid" value="private" checked />
                      <input type="radio" id="company" name="is_company" data-label-name="<?php echo $lang_client_['client_registration']['FIELD_LABEL_COMPANY_TYPE']; ?>" data-additional-classes="btn-info squared unbordered solid" value="company" />
                    </div>
                  <?php
				   }
				  ?>
                  <div class="row-fluid">
                    <input type="text" class="required" name="name" id="name" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['name'] : ''); ?>" data-array="12,6,<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? $lang_client_['checkout']['FIELD_LABEL_COMPANY'] : $lang_client_['checkout']['FIELD_LABEL_NAME']); ?>*" />
                    <input type="<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? 'hidden' : 'text'); ?>" class="<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? '' : 'required'); ?>" name="lastname" id="lastname" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['lastname'] : ''); ?>" <?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? '' : 'data-array="12,6,'.$lang_client_['checkout']['FIELD_LABEL_LASTNAME'].'*"'); ?>  />
                  </div>
                  <?php
				   if(!isset($_SESSION['Clogged']) && $guest_purchases){
				  ?>
                  <div class="row-fluid hidden">
                    <input type="text" class="required" name="tax_code" id="tax_code" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_TAX_CODE']; ?>*" />
                  </div>
                  <?php
				   }
				  ?>
                  <div class="row-fluid">
                    <input type="text" class="required email" name="email" id="email" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['email'] : ''); ?>" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_EMAIL']; ?>*" />
                    <input type="text" class="required" name="phone" id="phone" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['phone'] : ''); ?>" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_PHONE']; ?>*" />
                    <input type="text" class="number" name="fax" id="fax" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['fax'] : ''); ?>" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_FAX']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="address" id="address" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['address'] : ''); ?>" data-array="12,12,<?php echo $lang_client_['checkout']['FIELD_LABEL_ADDRESS']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="zipcode" id="zipcode" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['zipcode'] : ''); ?>" data-array="12,6,<?php echo $lang_client_['checkout']['FIELD_LABEL_ZIPCODE']; ?>*" />
                    <input type="text" class="required" name="city" id="city" value="<?php echo (isset($_SESSION['Clogged']) ? $rs_cli['city'] : ''); ?>" data-array="12,6,<?php echo $lang_client_['checkout']['FIELD_LABEL_CITY']; ?>*" />
                  </div>
             </div>
             <div class="same_address" id="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '3' : '2'; ?>">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SHIPPING_ADDRESS']; ?></span>
                  </div>
                  <div class="row-fluid">
                    <div class="span12">
                       <input type="checkbox" id="same-address" data-icon="icon-ok icon-white" name="same-address" class="bootstyl" data-label-name="<?php echo $lang_client_['checkout']['BUTTON_COPY_BILLING_ADDRESS_TEXT']; ?>" data-additional-classes="btn-success btn-large btn-block solid unbordered squared" value="1" />
                    </div>
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="names" id="names" value="" data-array="12,6,<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? $lang_client_['checkout']['FIELD_LABEL_COMPANY'] : $lang_client_['checkout']['FIELD_LABEL_NAME']); ?>*" />
                    <input type="<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? 'hidden' : 'text'); ?>" class="<?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? '' : 'required'); ?>" name="lastnames" id="lastnames" value="" <?php echo (isset($_SESSION['Clogged']) && $rs_cli['is_company'] ? '' : 'data-array="12,6,'.$lang_client_['checkout']['FIELD_LABEL_LASTNAME'].'*"'); ?>  />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required email" name="emails" id="emails" value="" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_EMAIL']; ?>*" />
                    <input type="text" class="required" name="phones" id="phones" value="" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_PHONE']; ?>*" />
                    <input type="text" class="number" name="faxs" id="faxs" value="" data-array="12,4,<?php echo $lang_client_['checkout']['FIELD_LABEL_FAX']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="addresss" id="addresss" value="" data-array="12,12,<?php echo $lang_client_['checkout']['FIELD_LABEL_ADDRESS']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="zipcodes" id="zipcodes" value="" data-array="12,6,<?php echo $lang_client_['checkout']['FIELD_LABEL_ZIPCODE']; ?>*" />
                    <input type="text" class="required" name="citys" id="citys" value="" data-array="12,6,<?php echo $lang_client_['checkout']['FIELD_LABEL_CITY']; ?>*" />
                  </div>
             </div>
             <div id="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '4' : '3'; ?>" class="payment_method">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_PAYMENT']; ?></span>
                  </div>
                  <div class="row-fluid">
                   <?php
				     if($bank_transfer['status']){
				   ?>
                    <label for="payment_<?php echo $bank_transfer['orders_prefix']; ?>" class="only_for_register selected selected">
                       <strong><?php echo $bank_transfer['long_name']; ?></strong>
                       <br/>
                       <input type="radio" name="payment_method" id="payment_<?php echo $bank_transfer['orders_prefix']; ?>" value="<?php echo $bank_transfer['orders_prefix']; ?>" checked /><?php echo $lang_client_['checkout']['ADDITIONAL_CHARGE']; ?> <?php echo $currency_l.num_formatt($bank_transfer['surcharge']).$currency_r; ?>
                       <input type="hidden" class="value-payment" value="<?php echo $bank_transfer['surcharge']; ?>" />
                    </label>
                   <?php
					 }
					 if($cash_on_delivery['status']){
				    ?>
                    <label for="payment_<?php echo $cash_on_delivery['orders_prefix']; ?>" class="only_for_register">
                       <strong><?php echo $cash_on_delivery['long_name']; ?></strong>
                       <br/>
                       <input type="radio" name="payment_method" id="payment_<?php echo $cash_on_delivery['orders_prefix']; ?>" value="<?php echo $cash_on_delivery['orders_prefix']; ?>" /><?php echo $lang_client_['checkout']['ADDITIONAL_CHARGE']; ?> <?php echo $currency_l.num_formatt($cash_on_delivery['surcharge']).$currency_r; ?>
                       <input type="hidden" class="value-payment" value="<?php echo $cash_on_delivery['surcharge']; ?>" />
                    </label>
                    <?php
					 }
					 if($paypal['status']){
					  if((($grand_total+$shipping_price)+$paypal['surcharge']) < (($paypal['payment_limit']-$paypal['surcharge'])-$shipping_price)){
					?>
                    <label for="payment_<?php echo $paypal['orders_prefix']; ?>" class="for_guest_and_register">
                       <strong><?php echo $paypal['long_name']; ?></strong>
                       <br/>
                       <input type="radio" name="payment_method" class="payment_paypal" id="payment_<?php echo $paypal['orders_prefix']; ?>" value="<?php echo $paypal['orders_prefix']; ?>" /><?php echo $lang_client_['checkout']['ADDITIONAL_CHARGE']; ?> <?php echo $currency_l.num_formatt($paypal['surcharge']).$currency_r; ?>
                       <input type="hidden" class="value-payment" value="<?php echo $paypal['surcharge']; ?>" />
                    </label>
                   <?php
					  }
					 }
				   ?>
                   <input type="text" name="payment_price" id="payment_price" value="0" />
                  </div>
             </div>
             <div id="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '5' : '4'; ?>">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SHIPPING']; ?></span>
                  </div>
                  <div class="row-fluid">
                    <div class="span12">
                       <?php echo $lang_client_['checkout']['SHIPPING_FIXED_COST'].' '.$currency_l.num_formatt($shipping_price).$currency_r; ?>
                    </div>
                  </div>
             </div>
             <div id="fstep_<?php echo !isset($_SESSION['Clogged']) && $guest_purchases ? '6' : '5'; ?>">
                  <div class="box-header">
                       <span class="header-text"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SUMMARY']; ?></span>
                  </div>
                  <table class="table-striped table-condensed products-list-table">
                      <thead>
                          <tr>
                              <th class="text-left"><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_PRODUCT_NAME']; ?></th>
                              <th class="text-right"><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_SUBTOTAL']; ?></th>
                          </tr>
                      </thead>
                      <?php echo $products_rows; ?>
                      <tfoot>
                          <tr>
                             <td class="text-right" colspan="0"><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_SUBTOTAL']; ?></td>
                             <td class="text-right"><?php echo $currency_l; ?><span class="subtotal-container"><?php echo num_formatt($sub_total); ?></span><?php echo $currency_r; ?></td>
                          </tr>
                          <tr>
                              <td class="text-right" colspan="0"><?php echo $tax_name; ?></td>
                              <td class="text-right"><?php echo $currency_l; ?><span class="tax-container"><?php echo num_formatt($tax); ?></span><?php echo $currency_r; ?></td>
                          </tr>
						  <?php
                           if(plugin_exsists('multitaxes') && !empty($arr_multitaxes)){
                            $taxes_sum = array_merge_numeric_values($arr_multitaxes);
                            foreach($taxes_sum as $key => $val){
                          ?>
                          <tr class="multi_tax_container">
                              <td class="text-right" colspan="0"><?php echo get_tax_param($key,'name'); ?></td>
                              <td class="text-right"><?php echo $currency_l; ?><?php echo num_formatt($val); ?><?php echo $currency_r; ?></td>
                          </tr>
                          <?php
                            }
                           }
                          ?>
                          <tr>
                              <td class="text-right" colspan="0"><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_SHIPPING']; ?></td>
                              <td class="text-right"><?php echo $currency_l.num_formatt($shipping_price).$currency_r; ?></td>
                          </tr>
                          <tr>
                              <td class="text-right" colspan="0"><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_PAYMENT_COST']; ?></td>
                              <td class="text-right"><?php echo $currency_l; ?><span class="payment_price">0,00</span><?php echo $currency_r; ?></td>
                          </tr>
                          <tr>
                              <td class="text-right" colspan="0"><strong><?php echo $lang_client_['checkout']['TABLE_CONTENT_TITLE_TOTAL']; ?></strong></td>
                              <td class="text-right"><strong><?php echo $currency_l; ?></strong><strong class="grandtotal" data-grandtotal="<?php echo round($grand_total+$shipping_price,2); ?>"><?php echo num_formatt($grand_total+$shipping_price); ?></strong><strong><?php echo $currency_r; ?></strong></td>
                          </tr>
                          <tr style="border-top:1px solid #E6E6E6;background-color:#fff!important;">
                              <td class="text-left" colspan="0">
                                <br/>
                                <strong class="text-info"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_BILLING_ADDRESS']; ?></strong>
                                <span class="billing-address-container"></span>
                              </td>
                              <td class="text-left">
                                <br/>
                                <strong class="text-info"><?php echo $lang_client_['checkout']['ORDER_PROGRESS_INDICATOR_SHIPPING_ADDRESS']; ?></strong>
                                <span class="shipping-address-container"></span>
                              </td>
                          </tr>
                      </tfoot>
                  </table>
                  <br/>
                  <?php echo $lang_client_['checkout']['NOTICE_FORGET_LINK']; ?> <a href="<?php echo abs_client_path; ?>/cart.php"><?php echo $lang_client_['checkout']['RETURN_TO_CART']; ?></a>
             </div>
           </form>
           <br/><br/>
           <div id="form-order-btn" class="text-right well well-small"></div>
           <br/>
           <strong class="text-info"><small><?php echo $lang_client_['checkout']['NOTICE_FIELDS_MANDATORY']; ?></small></strong>
          </div>
           <div class="clearfix"></div>
        </section>
     </section><!-- /BODY ROW -->
	<?php
     }else{
      require_once('include/registration-form.php');
     }
    ?>

   </section> <!-- /CONTAINER -->
	<?php
     require_once('include/footer.php');
    ?>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.stepize.js"></script>
    <script type="text/javascript">
   $(function(){
        if($('#checkout-form').is(':visible')){
          $('#checkout-form').StepizeForm({
             Text_Submit:'<i class="icon-white icon-share"></i> <?php echo $lang_client_['checkout']['SEND_ORDER_BUTTON']; ?>',
             Text_Next: '<?php echo $lang_client_['general']['STEPPIZED_FORM_NEXT_BUTTON']; ?>',
             Text_Prev: '<?php echo $lang_client_['general']['STEPPIZED_FORM_PREV_BUTTON']; ?>',
             Selector_Buttons:'#form-order-btn',
             Class_Prev:'btn btn-info squared unbordered solid',
             Class_Next:'btn btn-info squared unbordered solid',
             Class_Submit:'btn btn-info squared unbordered solid'
          });
      }else{
          $('#registration-form').StepizeForm({
           Steps_Count : '#count_step',
           Text_Submit:'<i class="icon-white icon-plus"></i> <?php echo $lang_client_['general']['BUTTON_SIGN_UP']; ?>',
           Text_Next: '<?php echo $lang_client_['general']['STEPPIZED_FORM_NEXT_BUTTON']; ?>',
           Text_Prev: '<?php echo $lang_client_['general']['STEPPIZED_FORM_PREV_BUTTON']; ?>',
           Selector_Buttons:'#form-btn',
           Class_Prev:'btn btn-info squared unbordered solid',
           Class_Next:'btn btn-info squared unbordered solid',
           Class_Submit:'btn btn-info squared unbordered solid'
          });
      }
   });
	</script>
  </body>
</html>