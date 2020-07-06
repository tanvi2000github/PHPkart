<?php 
 $page_title = $lang_client_['cart']['PAGE_TITLE'];  
 require_once('include/header.php');
?> 
 <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?> 
     <section class="row-fluid"><!-- BODY breadcrumb --> 
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <li class="active"><?php echo $page_title; ?></li>
        </ul>     
     </section><!-- / BODY breadcrumb -->
      <div class="box-header">
           <span class="header-text"><i class="icon icon-black icon-cart"></i> <?php echo $page_title; ?></span>     
      </div>      
     <section class="row-fluid cart-page"><!-- BODY ROW -->               
	    <section class="span12 cart-container"> 
		  <?php
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
			  $get_price = $rs_cart['offer'] > 0 ? $rs_cart['offer'] : $rs_cart['price'];	
			  $tax_value_price = ($rs_cart['price']*$rs_cart['tax'])/100;
			  if(plugin_exsists('multitaxes')) $tax_value_price = $tax_value_price+calculate_taxes_value($rs_cart['price'],$rs_cart['pl_multitax']);
			  $tax_value_offer = ($rs_cart['offer']*$rs_cart['tax'])/100;
			  if(plugin_exsists('multitaxes')) $tax_value_offer = $tax_value_offer+calculate_taxes_value($rs_cart['offer'],$rs_cart['pl_multitax']);
			  $img = ($rs_cart['url_image'] != '' ? path_abs_img_products.'/'.$rs_cart['id_product'].'/300x300/1_'.$rs_cart['url_image'] : theme_img_path.'/img_not_available.jpg');
			  $price_text = $rs_cart['offer'] > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($rs_cart['price']+$tax_value_price).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($rs_cart['offer']+$tax_value_offer).$currency_r.'</span>' : $currency_l.num_formatt($rs_cart['price']+$tax_value_price).$currency_r;
			  $arr_cart_options = unserialize($rs_cart['cart_option']);
			  $arr_product_options = unserialize($rs_cart['options']);
			  
			  foreach($arr_cart_options as $key => $val){
				$qta = $val['qta'];
				$options_display = '';
				$options_display_price = '';
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
										  .'</strong>';	
					  $options_display_price .= '<br/><strong class="text-info">'
											  .($arr_product_options[$k]['voption'][$v]['price'] > 0 
											 ? 
											  $arr_product_options[$k]['voption'][$v]['type'].' '.$currency_l.num_formatt($single_option_price).$currency_r
											 : 
											  ''
											)
											.'</strong>';	
				  }			  
				}					
				$sub_total = $sub_total+(($get_price+$surcharge)*$qta);				
				$grand_total = $grand_total+((($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta);
				$single_subtotal = (($get_price+$surcharge)+((($get_price+$surcharge)*$rs_cart['tax'])/100))*$qta;
				if(plugin_exsists('multitaxes')) $single_subtotal = $single_subtotal + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				if(plugin_exsists('multitaxes')) $grand_total = $grand_total + (calculate_taxes_value(($get_price+$surcharge),$rs_cart['pl_multitax'])*$qta);
				$tax = $tax+(((($get_price+$surcharge)*$rs_cart['tax'])/100)*$qta);
				if(plugin_exsists('multitaxes')){
				 if($rs_cart['pl_multitax'] != ''){
				   foreach(explode(',',$rs_cart['pl_multitax']) as $tax_id){
					$arr_multitaxes[] = array($tax_id => (($get_price+$surcharge)*get_tax_param($tax_id,'percentage')/100)*$qta);
				   }
				 }
				}
				$products_rows .= ' <tr>
										<td class="image" data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_IMAGE'].'">
										 <img class="lazy" data-original="'.$img.'" src="'.$img.'" alt="'.str_replace('"',"'",$rs_cart['name']).'" />
										</td>
										<td data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_NAME'].'">
										  <a href="'.path_abs_products.'/'.$rs_cart['id_product'].'-'.($rs_cart['file_name'] == '' ? filesystem($rs_cart['name']) : $rs_cart['file_name']).'.php">'.$rs_cart['name'].'</a>   
										  '.$options_display.'                                           
										</td> 
										<td data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_QUANTITY'].'">
										  <div class="input-prepend input-append">									
											<span class="add-on btn decrease-qta" data-products-to-decrease="'.$key.'"><i class="icon-minus"></i></span>								
											<input data-rel-options-product="'.$key.'" data-rel-id-product="'.$rs_cart['id_product_cart'].'" data-rel="qta_'.$key.'" type="text" name="qta-product[]" id="qta-product_'.$key.'" min="1" value="'.$qta.'" style="width:25px;" class="no_space number text-center" />										
											<span class="add-on btn increase-qta" data-products-to-increase="'.$key.'"><i class="icon-plus"></i></span>										 
										  </div>									                                           
										</td>  
										<td data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_UNIT_PRICE'].'">'
										  .($view_prices ? $price_text.$options_display_price : '').'
										</td>
										<td data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_SUBTOTAL'].'">
										  '.($view_prices ? $currency_l.num_formatt($single_subtotal).$currency_r : '').'
										</td> 											   											                              
										<td class="remove" data-title="'.$lang_client_['cart']['TABLE_CONTENT_TITLE_ACTIONS'].'">	
										  <span class="btn btn-success btn-small update-cart tooltiped" title="'.$lang_client_['cart']['TOOLTIP_UPDATE'].'" data-options-item="'.$key.'" data-id-item="'.$rs_cart['id_product_cart'].'"><i class="icon-white icon-edit"></i></span>
										  <span class="btn btn-danger btn-small remove-from-cart tooltiped" title="'.$lang_client_['cart']['TOOLTIP_REMOVE_FROM_CART_BUTTON'].'" data-options-item="'.$key.'" data-id-item="'.$rs_cart['id_product_cart'].'"><i class="icon-white icon-trash"></i></span>
										</td>
									</tr>';
			  }
		   }
		   $alert_hide = '';
		   $list_show = ' hide';
           if($products_rows != ''){
			   $alert_hide = ' hide';
			   $list_show = '';
		   } 
          ?>    
              <div class="alert-cart-container row-fluid<?php echo $alert_hide; ?>">
                <div class="span12">
                   <div class="alert alert-warning alert-block squared solid unbordered"><h4><?php echo $lang_client_['cart']['ALERT_CART_EMPTY']; ?></h4></div>
                    <br/>
                    <a class="btn btn-info btn-large squared solid unbordered pull-right" href="<?php echo abs_client_path; ?>"><?php echo $lang_client_['general']['BUTTON_RETURN_TO_SHOPPING']; ?></a>                   
                </div>
              </div>
             <div class="products-list-div<?php echo $list_show; ?>">                             
              <div class="row-fluid">
                <div class="span12" id="products-list-table">    
                                <table class="table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_IMAGE']; ?></th>
                                            <th><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_NAME']; ?></th>
                                            <th class="numeric"><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_QUANTITY']; ?></th>
                                            <th class="numeric"><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_UNIT_PRICE']; ?></th>
                                            <th class="numeric"><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_SUBTOTAL']; ?></th>
                                            <th class="numeric"><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_ACTIONS']; ?></th>
                                        </tr>
                                    </thead>                                                                
                                    <tbody class="product-tbody-container">
                                      <?php echo $products_rows; ?>
                                    </tbody>
                                </table>
                </div>
              </div>
              <br/>
              <div class="row-fluid">
                 <div class="span8"></div>
                 <div class="span4">
                                <span class="btn btn-info btn-large btn-block squared solid unbordered pull-right btn-refresh-cart"><i class="icon-white icon-refresh"></i> <?php echo $lang_client_['cart']['BUTTON_UPDATE_CART']; ?></span>
                                <br/><br/><br/>        
                                <table class="table table-hover table-striped pull-right">
                                    <tbody class="counts-container">
                                        <tr>
                                            <td><strong><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_SUBTOTAL']; ?>:</strong></td>
                                            <td class="subtotal"><?php echo (!$prices_on_login ? $currency_l.num_formatt($sub_total).$currency_r : ''); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo $tax_name; ?>:</strong></td>
                                            <td class="tax"><?php echo (!$prices_on_login ? $currency_l.num_formatt($tax).$currency_r : ''); ?></td>
                                        </tr>
                                        <?php
										 if(plugin_exsists('multitaxes') && !empty($arr_multitaxes)){
										  $taxes_sum = array_merge_numeric_values($arr_multitaxes);
										  foreach($taxes_sum as $key => $val){
										?>
                                        <tr>
                                            <td><strong><?php echo get_tax_param($key,'name'); ?>:</strong></td>
                                            <td class="tax"><?php echo (!$prices_on_login ? $currency_l.num_formatt($val).$currency_r : ''); ?></td>
                                        </tr>                                        
                                        <?php	  
										  }
										 }
										?>
                                        <tr>
                                            <td><strong><?php echo $lang_client_['cart']['TABLE_CONTENT_TITLE_TOTAL']; ?>:</strong></td>
                                            <td><?php echo (!$prices_on_login ? $currency_l.num_formatt($grand_total).$currency_r : ''); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>                          
                                <div class="row-fluid">
                                  <div class="span6 text-left">
                                    <a class="btn btn-info squared solid unbordered btn-block" href="<?php echo abs_client_path; ?>"><?php echo $lang_client_['general']['BUTTON_RETURN_TO_SHOPPING']; ?></a>
                                  </div>
                                  <div class="span6">
                                    <a class="btn btn-primary squared solid unbordered btn-block" href="<?php echo abs_client_path; ?>/check-out.php"><?php echo $lang_client_['general']['BUTTON_CHECKOUT']; ?></a>
                                  </div>
                                </div>                                                            
                 </div>
              </div> 
             </div>        
       </section>                       
     </section><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php 
     require_once('include/footer.php');
    ?>      
  </body>
</html>