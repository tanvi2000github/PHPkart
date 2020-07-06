<?php
 if(isset($_SESSION['Alogged'])){
  $res = execute('select * from '.$table_prefix.'products where id = '.$product_id);
 }else{
  $res = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 and id = '.$product_id);
 }
  $rs = mysql_fetch_array($res);
  if(!$rs) die(header('location:'.abs_client_path));
  $page_title = str_replace(' ','',$rs['meta_title']) != '' ? $rs['meta_title'] : $rs['name'];
  $meta_keywords = str_replace(' ','',$rs['meta_keywords']) != '' ? $rs['meta_keywords'] : '';
  $meta_description =  str_replace(' ','',$rs['meta_description']) != '' ? $rs['meta_description'] : cutOff($rs['description'],150);
  $img_product_org = ($rs['url_image'] != '' ? path_abs_img_products.'/'.$rs['id'].'/1_'.$rs['url_image'] : theme_img_path.'/img_not_available.jpg');
  $img_product = ($rs['url_image'] != '' ? path_abs_img_products.'/'.$rs['id'].'/600x600/1_'.$rs['url_image'] : theme_img_path.'/img_not_available.jpg');
  $thums_array = $rs['images'] != '' ? unserialize($rs['images']) : array();
  $AdditionalHeadTags = '<link rel="image_src" href="'.$img_product.'" />';
  if($rs['images'] != ''){
	 usort($thums_array, build_sorter('principale','desc'));
  }
  $category_id = !isset($category_id) ? $rs['categories'] : $category_id;
 if(plugin_exsists('businesstype') && get_client_business_bc()){
    $rs['offer'] = $rs['roffer'];
    $rs['price'] = $rs['rprice'];
  }
  $price = (($rs['price']*$rs['tax'])/100)+$rs['price'];
  $offer = $rs['offer'] == 0 ? 0 : (($rs['offer']*$rs['tax'])/100)+$rs['offer'];
 if(plugin_exsists('multitaxes')){
	$price = $price + calculate_taxes_value($rs['price'],$rs['pl_multitax']);
	$offer = $offer + calculate_taxes_value($rs['offer'],$rs['pl_multitax']);
 }
  $discount_percentage = $offer > 0 ? round((100 - (($rs['offer']/$rs['price'])*100)),0).'%' : '';

 require_once('include/header.php');
?>
 <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?>
     <section class="row-fluid"><!-- BODY breadcrumb -->
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <?php
		    $bread_array = explode('/',$mptt->get_orizzontal($category_id,'/'));
			$breadcrumb = '';
			foreach($bread_array as $key){
				  $breadcrumb .= '/'.filesystem(html_entity_decode($key));
				  echo '<li><a href="'.path_abs_products.$breadcrumb.'">'.$key.'</a> <span class="divider">/</span></li>';
			}
			echo '<li class="active" data-rel-menu="'.$category_id.'">'.$rs['name'].'</li>';
		  ?>
        </ul>
        <?php
		 if(isset($_SESSION['Alogged']) && (!$rs['visible'] || !$rs['active'])){
			echo '<div class="alert alert-warning"><strong>'.$lang_client_['product']['ALERT_FOR_ADMIN_VIEW'].'</strong></div>';
		 }
		?>
     </section><!-- / BODY breadcrumb -->
     <section class="row-fluid"><!-- BODY ROW -->
       <section class="span5 text-center">
        <div id="img-preview">
         <img class="lazy" data-default-size-img="<?php echo $img_product_org; ?>" data-original="<?php echo $img_product; ?>" src="<?php echo $img_product; ?>" style="cursor:zoom-in;"/>
        </div>
           <?php
		    if(count($thums_array) > 1){
		   ?>
             <div class="row-fluid">
              <section class="span12" style="margin-top:40px;">
                <div class="carousel_wrapper img-carousel">
                    <span class="carousel-prev" id="carousel-img-prev"></span>
                    <span class="carousel-next" id="carousel-img-next"></span>
                    <ul>
                        <?php
                         foreach($thums_array as $key => $val){
							$thumb = path_abs_img_products.'/'.$rs['id'].'/70x70/'.$val['urlimg'];
							$original = path_abs_img_products.'/'.$rs['id'].'/'.$val['urlimg'];
                        ?>
                            <li style="height:70px;overflow:hidden;width:auto;">
                             <a style="height:60px;overflow:hidden;width:auto;" class="thumbnail<?php echo $val['principale'] ? ' active' : ''; ?>" href="#" rel="group1">
                              <img style="cursor:pointer;" data-default-size-img="<?php echo str_replace('/70x70','',$thumb); ?>" data-img="<?php echo str_replace('70x70','600x600',$thumb); ?>" class="lazy" data-original="<?php echo $thumb; ?>" src="<?php echo $thumb; ?>" />
                             </a>
                            </li>
                        <?php
                         }
                        ?>
                    </ul>
                </div>
              </section>
             </div>
           <?php
			}
		   ?>
        </section>
	    <section class="span7" id="product-sheet">
         <div class="row-fluid">
          <div class="span12">
              <div class="box-header">
                 <span class="header-text"><?php echo $rs['name']; ?></span>
              </div>
          </div>
         </div>
         <div class="row-fluid">
          <div class="span4">
       <?php
             if(plugin_exsists('dgoods') && $rs['pl_digital']){
                if(file_exists(rel_uploads_path.'/digital_goods/'.$rs['id'].'/demo_'.$rs['pl_digital_code_name'])){
                    echo ' <a class="btn btn-info squared solid unbordered" href="'.abs_plugins_path.'/dgoods/download_demo.php?'.$rs['pl_digital_code'].'"><i class="icon-download-alt icon-white"></i> '.$lang_client_['pl_dgoods']['BUTTON_DOWNLOAD_DEMO_TEXT'].'</a><br/><br/>';
                }
             }
           ?>
           <div class="price-container">
		    <?php echo ($view_prices ?
                        ($offer > 0 ?
                              '<span data-price-value="'.$price.'" class="old-price-container">'.str_replace(' ','&nbsp;',$currency_l).'<span class="container_price_val" style="display:inline-block;text-decoration: line-through;">'.num_formatt($price).'</span>'.str_replace(' ','&nbsp;',$currency_r).'</span><span class="product-offer" data-price-value="'.$offer.'">'.$lang_client_['product']['OFFER_TEXT'].': '.str_replace(' ','&nbsp;',$currency_l).'<span class="container_price_val" style="display:inline-block;">'.num_formatt($offer).'</span>'.str_replace(' ','&nbsp;',$currency_r).'</span>' :
                              '<span data-price-value="'.$price.'" >'.str_replace(' ','&nbsp;',$currency_l).'<span class="container_price_val" style="display:inline-block;">'.num_formatt($price).'</span>'.str_replace(' ','&nbsp;',$currency_r)).'</span>'
                        : ''); ?>
            <?php echo $offer > 0 ? '<div class="discount-percentage">-&nbsp;'.$discount_percentage.'</div>' : ''; ?>
           </div>
             <span><strong><?php echo $lang_client_['product']['AVAILABILITY_TEXT']; ?>:</strong> <?php echo $rs['unlimited_availability'] ? $lang_client_['product']['UNLIMITED_AVAILABILITY_TEXT'] : num_formatt($rs['availability'],2,true).' '.$rs['units']; ?></span><br/>
             <span><strong><?php echo $lang_client_['product']['CODE_TEXT']; ?>:</strong> <?php echo $rs['code']; ?></span><br/>
             <?php
			   if($rs['options'] != ''){
				  $option_array = unserialize($rs['options']);
				  $option_array = array_msort($option_array, array('name'=>SORT_ASC));
				  foreach($option_array as $key => $val){
					 echo $val['name'].'<select class="select-of-options" data-rel="option_'.$rs['id'].'" name="option['.$key.']">';
					 if(!$val['required_option']) echo '<option value="" data-value-type="+" data-value="0"></option>';
					 $arr_option_value = $val['voption'];
					 $arr_option_value = array_msort($arr_option_value, array('value'=>SORT_ASC));
					  foreach($arr_option_value as $key => $val){
						 $option_price = $val['price']+(($val['price']*$rs['tax'])/100);
						 if(plugin_exsists('multitaxes')) $option_price = $option_price + calculate_taxes_value($val['price'],$rs['pl_multitax']);
						 echo '<option value="'.$key.'" data-value-type="'.$val['type'].'" data-value="'.$option_price.'">'.$val['value'].($val['price'] > 0 ? ' '.$val['type'].' '.$currency_l.num_formatt($option_price).$currency_r : '').'</option>';
					  }
					 echo '</select>';
				  }
			   }
			 ?>
          </div>
          <div class="span8">
           <div class="description-container">
             <?php echo $rs['description']; ?>
           </div>
           <div class="clearfix"></div>
           <br/>
           <div class="actions-container pull-right">
		   <?php
             if($rs['by_exposure']){
           ?>
                <a href="#" class="btn btn-info btn-large btn-block squared solid unbordered by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a>
           <?php
             }else{
           ?>
             <?php
			   if($rs['availability'] > 0 || $rs['unlimited_availability']){
			 ?>
               <label for="qta-product"><?php echo $lang_client_['product']['TABLE_CONTENT_TITLE_QUANTITY']; ?></label><input data-rel="qta_<?php echo $rs['id']; ?>" type="text" name="qta-product" id="qta-product" min="1" value="1" class="span2 no_space number text-center" />
             <?php
			   }
			 ?>
             <a href="#" data-rel-name="<?php echo $rs['name']; ?>" data-rel-img="<?php echo $img_product; ?>" class="add-to-cart tooltiped btn btn-info btn-large squared solid unbordered<?php echo (($rs['availability'] <= 0 && $rs['unlimited_availability'] == 0) ? ' disabled': ''); ?>" data-id="<?php echo $rs['id']; ?>"><i class="icon-white icon-shopping-cart"></i> <i class="icon-white icon-plus"></i><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a>
           <?php
             }
           ?>
           <br/><br/>
              <!-- AddThis Button BEGIN -->
              <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
              <a class="addthis_button_facebook"></a>
              <a class="addthis_button_twitter"></a>
              <a class="addthis_button_pinterest_share"></a>
              <a class="addthis_button_google_plusone_share"></a>
              <a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
              </div>
           </div>
          </div>
         </div>

         <?php
		  if($rs['attributes'] != ''){
		 ?>
         <div class="specification-container">
            <table class="table table-striped table-condensed table-bordered table-hover" >
                <tbody>
                 <?php
				  foreach(unserialize($rs['attributes']) as $key => $val){
			     ?>
                    <tr>
                        <td><strong><?php echo $val['attribute_name']; ?></strong></td>
                        <td><?php echo $val['attribute_value']; ?></td>
                    </tr>
                 <?php
				  }
				 ?>
                </tbody>
            </table>
         </div>
         <?php
		  }
		 ?>
       </section>
     </section><!-- /BODY ROW -->
     <section class="row-fluid">
		<?php
		$res_new = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 and categories = '.$rs['categories'].' and id <> '.$rs['id'].' order by rand() limit 12');
		if(mysql_num_rows($res_new) > 0){
		?>
		<section style="margin-top:20px;" class="span12" id="related-products-container">
		  <div class="box-header">
			 <span class="header-text"><?php echo $lang_client_['product']['RELATED_PRODUCTS_TEXT']; ?></span>
		  </div>
		  <div class="carousel_wrapper">
			  <span class="carousel-prev" id="carousel-offer-prev"></span>
			  <span class="carousel-next" id="carousel-offer-next"></span>
			 <!-- ------------- -->
			  <ul>
				<?php
				 while($rs_new = mysql_fetch_array($res_new)){
					 $price = (($rs_new['price']*$rs_new['tax'])/100)+$rs_new['price'];
					 $offer = $rs_new['offer'] == 0 ? 0 : (($rs_new['offer']*$rs_new['tax'])/100)+$rs_new['offer'];
					 if(plugin_exsists('multitaxes')){
						$price = $price + calculate_taxes_value($rs_new['price'],$rs_new['pl_multitax']);
						$offer = $offer + calculate_taxes_value($rs_new['offer'],$rs_new['pl_multitax']);
					 }
					 $img = $rs_new['url_image'] != '' ? path_abs_img_products.'/'.$rs_new['id'].'/600x600/1_'.$rs_new['url_image'] : theme_img_path.'/img_not_available.jpg';
					  $img_offer = $rs_new['offer'] > 0 ? '<img class="ribbon-sale" src="'.theme_img_path.'/labels/offerta.png" alt="Offer" />' : '';
					  $img_new = datediff("G", view_date($rs_new['add_data']), view_date(date("y-m-d"))) < $days_product_new  && mb_substr($rs_new['add_data'],0,10) != '0000-00-00' ? '<img class="ribbon-new" src="'.theme_img_path.'/labels/novita.png" alt="New" />' : '';
				?>
					  <li style="padding:5px;">
						<article class="product-container-grid" data-exstend-link="on">
						 <section class="product-img">
							<?php
                             echo $img_offer.$img_new;
                             if($img_offer != '') echo '<div class="sale-percentage">-'.round((100 - (($rs_new['offer']/$rs_new['price'])*100)),0).'%</div>';
                            ?>
						   <div class="img-thumb">
                             <?php
							   if(file_exists(str_replace(path_abs_img_products,path_rel_img_products,str_replace('1_','2_',$img)))){
								 echo '<div class="first-thumb hide">'.$img.'</div>';
								 echo '<div class="second-thumb hide">'.str_replace('1_','2_',$img).'</div>';
							   }
							 ?>
							 <a href="<?php echo path_abs_products.'/'.$rs_new['id'].'-'.($rs_new['file_name'] == '' ? filesystem($rs_new['name']) : $rs_new['file_name']).'.php'; ?>"><img class="lazy" data-original="<?php echo $img; ?>" src="<?php echo $img; ?>" alt="<?php echo str_replace('"',"'",$rs_new['name']); ?>" /></a>
						   </div>
						 </section>
						 <section class="product-detail-container text-center">
						   <section class="product-name" title="<?php echo $rs_new['name']; ?>"><a href="<?php echo path_abs_products.'/'.$rs_new['id'].'-'.($rs_new['file_name'] == '' ? filesystem($rs_new['name']) : $rs_new['file_name']).'.php'; ?>"><?php echo $rs_new['name']; ?></a></section>
						   <section class="price-container"><?php echo ($view_prices ? ($offer > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($price).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($offer).$currency_r.'</span>' : $currency_l.num_formatt($price).$currency_r) : ''); ?></section>
                           <section class="action-container text-center">
							 <?php
                               if($rs_new['by_exposure']){
                             ?>
                                  <a href="#" class="btn btn-info squared unbordered solid by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a>
                             <?php
                               }else{
                             ?>
                                  <a href="<?php echo path_abs_products.'/'.$rs_new['id'].'-'.($rs_new['file_name'] == '' ? filesystem($rs_new['name']) : $rs_new['file_name']).'.php'; ?>" data-rel-name="<?php echo $rs_new['name']; ?>" data-rel-img="<?php echo $img; ?>" class="add-to-cart-small<?php echo (($rs_new['availability'] <= 0 && $rs_new['unlimited_availability'] == 0) ? ' disabled' : '').($rs_new['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $rs_new['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a>
                             <?php
							   }
                             ?>
                           </section>
						   <div class="clearfix"></div>
						 </section>
						</article>
					  </li>
				<?php
				 }
				?>
			  </ul>

			 <!-- ------------- -->
		  </div>
		</section>
		<?php } ?>
     </section>
   </section> <!-- /CONTAINER -->
	<?php
     require_once('include/footer.php');
    ?>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-51fe356d7d23afaa"></script>
    <script>
    $(".description-container").niceScroll({cursorcolor:'#3A87AD',cursorborder:'1px solid #fff'});
	</script>
	<script type="text/javascript">
  $('body').on('click','#img-preview > img',function(){
    var url = $(this).data('default-size-img');
    $.lightbox(url);
    return false;
  });
	</script>
  </body>
</html>