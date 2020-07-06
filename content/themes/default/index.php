<?php
$page_title = str_replace('index.php',$shop_title,last_selfURL());
$AdditionalHeadTags = '<link href="'.theme_css_path.'/slideshow.css" rel="stylesheet">';
require_once('include/header.php');
?>
  <body>
   <?php require_once('include/body-header.php');?>    
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->     
    <?php require_once('include/horizontal-categories.php');?> 
          <!-- --------- SLIDESHOW ----- -->
          <?php
		    if(plugin_exsists('slideshow')) get_slideshow();		
		  ?>
          <!-- ------------- /SLIDESHOW ----  --> 
	<?php
      $sql_showcase = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 and showcase = 1 order by rand()');
      if(mysql_num_rows($sql_showcase) > 0){
    ?>
         <div class="row-fluid"><!-- SHOWCASE --> 
            <section style="margin-top:20px;" class="span12">  
                <?php
				  $counter = 0;
				  while($rs_showcase = mysql_fetch_array($sql_showcase)){
					  if(plugin_exsists('businesstype') && get_client_business_bc()){
						  $rs_showcase['offer'] = $rs_showcase['roffer'];
						  $rs_showcase['price'] = $rs_showcase['rprice'];
					  }					  
                     $price = (($rs_showcase['price']*$rs_showcase['tax'])/100)+$rs_showcase['price'];
                     $offer = $rs_showcase['offer'] == 0 ? 0 : (($rs_showcase['offer']*$rs_showcase['tax'])/100)+$rs_showcase['offer'];
					 if(plugin_exsists('multitaxes')){
						$price = $price + calculate_taxes_value($rs_showcase['price'],$rs_showcase['pl_multitax']);						
						$offer = $offer + calculate_taxes_value($rs_showcase['offer'],$rs_showcase['pl_multitax']);										
					 }					 
                     $img = $rs_showcase['url_image'] != '' ? path_abs_img_products.'/'.$rs_showcase['id'].'/600x600/1_'.$rs_showcase['url_image'] : theme_img_path.'/img_not_available.jpg';							  			   
					  $img_offer = $rs_showcase['offer'] > 0 ? '<img class="ribbon-sale" src="'.theme_img_path.'/labels/offerta.png" alt="" />' : ''; 
					  $img_new = datediff("G", view_date($rs_showcase['add_data']), view_date(date("y-m-d"))) < $days_product_new  && mb_substr($rs_showcase['add_data'],0,10) != '0000-00-00' ? '<img class="ribbon-new" src="'.theme_img_path.'/labels/novita.png" alt="New" />' : '';	
							/*************** GRID VIEW ************/ 
							if ($counter % 4 == 0 || $counter == 0){
							 echo '<div class="row-fluid">';
							}
					  ?>
						  <div class="span3">							   
                              <article class="product-container-grid" data-exstend-link="on">      
                               <section class="product-img">
                                  <?php 
                                   echo $img_offer.$img_new;
                                     if($img_offer != '') echo '<div class="sale-percentage">- '.round((100 - (($rs_showcase['offer']/$rs_showcase['price'])*100)),0).'%</div>';
                                  ?>                            
                                 <div class="img-thumb">   
                                   <?php
                                     if(file_exists(str_replace(path_abs_img_products,path_rel_img_products,str_replace('1_','2_',$img)))){
                                       echo '<div class="first-thumb hide">'.$img.'</div>';
                                       echo '<div class="second-thumb hide">'.str_replace('1_','2_',$img).'</div>';
                                     }
                                   ?>                                                 
                                   <a href="<?php echo path_abs_products.'/'.$rs_showcase['id'].'-'.($rs_showcase['file_name'] == '' ? filesystem($rs_showcase['name']) : $rs_showcase['file_name']).'.php'; ?>"><img class="lazy" data-original="<?php echo $img; ?>" src="<?php echo $img; ?>" alt="<?php echo str_replace('"',"'",$rs_showcase['name']); ?>" /></a>
                                 </div>
                               </section>
                               <section class="product-detail-container text-center">
                                 <section class="product-name" title="<?php echo $rs_showcase['name']; ?>"><a href="<?php echo path_abs_products.'/'.$rs_showcase['id'].'-'.($rs_showcase['file_name'] == '' ? filesystem($rs_showcase['name']) : $rs_showcase['file_name']).'.php'; ?>"><?php echo $rs_showcase['name']; ?></a></section>
                                 <section class="price-container"><?php echo ($view_prices ? ($offer > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($price).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($offer).$currency_r.'</span>' : $currency_l.num_formatt($price).$currency_r) : ''); ?></section>
                                 <section class="action-container text-center">     
                                   <?php 
                                     if($rs_showcase['by_exposure']){
                                   ?>
                                        <a href="#" class="btn btn-info squared unbordered solid by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a> 
                                   <?php 
                                     }else{
                                   ?>   
                                        <a href="<?php echo path_abs_products.'/'.$rs_showcase['id'].'-'.($rs_showcase['file_name'] == '' ? filesystem($rs_showcase['name']) : $rs_showcase['file_name']).'.php'; ?>" data-rel-name="<?php echo $rs_showcase['name']; ?>" data-rel-img="<?php echo $img; ?>" class="add-to-cart-small<?php echo (($rs_showcase['availability'] <= 0 && $rs_showcase['unlimited_availability'] == 0) ? ' disabled': '').($rs_showcase['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $rs_showcase['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a>
                                   <?php 
                                     }
                                   ?>                                                                 
                                 </section>
                                 <div class="clearfix"></div>
                               </section>                       
                              </article>                            
						  </div>  
					  <?php
							$counter++;
							if ($counter % 4 == 0 || $counter >= mysql_num_rows($sql_showcase) ){
							 echo '</div><br/>';
							}	
				  }
				?>
            </section>
         </div> <!-- /SHOWCASE -->  
     <?php
			}
	?>
     <div class="row-fluid"><!-- BODY ROW --> 
        <section style="margin-top:20px;" class="span12">
          <div class="box-header">
             <span class="header-text"><?php echo $lang_client_['indext']['LATEST_PRODUCTS']; ?></span>
          </div>                                                 
          <div class="carousel_wrapper">
              <span class="carousel-prev" id="carousel-new-prev"></span>
              <span class="carousel-next" id="carousel-new-next"></span>                                                        
             <!-- ------------- -->
              <ul>
                <?php 	
                 $res_new = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 order by rand() limit 12');
                 while($rs_new = mysql_fetch_array($res_new)){
					  if(plugin_exsists('businesstype') && get_client_business_bc()){
						  $rs_new['offer'] = $rs_new['roffer'];
						  $rs_new['price'] = $rs_new['rprice'];
					  }						 
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
                             if($img_offer != '') echo '<div class="sale-percentage">- '.round((100 - (($rs_new['offer']/$rs_new['price'])*100)),0).'%</div>';
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
                                  <a href="<?php echo path_abs_products.'/'.$rs_new['id'].'-'.($rs_new['file_name'] == '' ? filesystem($rs_new['name']) : $rs_new['file_name']).'.php'; ?>" data-rel-name="<?php echo $rs_new['name']; ?>" data-rel-img="<?php echo $img; ?>" class="add-to-cart-small<?php echo (($rs_new['availability'] <= 0 && $rs_new['unlimited_availability'] == 0) ? ' disabled': '').($rs_new['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $rs_new['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a>
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
     </div><!-- /BODY ROW -->
     <div class="row-fluid">
		<?php	
		if(plugin_exsists('businesstype') && get_client_business_bc()){
		  $res_new = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 and roffer > 0 order by rand() limit 12');
		}else{				
		  $res_new = execute('select * from '.$table_prefix.'products where visible = 1 and active = 1 and offer > 0 order by rand() limit 12');
		}
		if(mysql_num_rows($res_new) > 0){
		?>                  
		<section style="margin-top:20px;" class="span12">
		  <div class="box-header">
			 <span class="header-text"><?php echo $lang_client_['indext']['ON_OFFER']; ?></span>
		  </div>                                                 
		  <div class="carousel_wrapper">
			  <span class="carousel-prev" id="carousel-offer-prev"></span>
			  <span class="carousel-next" id="carousel-offer-next"></span>                                                        
			 <!-- ------------- -->
			  <ul>
				<?php 
				 while($rs_new = mysql_fetch_array($res_new)){
					  if(plugin_exsists('businesstype') && get_client_business_bc()){
						  $rs_new['offer'] = $rs_new['roffer'];
						  $rs_new['price'] = $rs_new['rprice'];
					  }						 
					 $price = (($rs_new['price']*$rs_new['tax'])/100)+$rs_new['price'];
					 $offer = (($rs_new['offer']*$rs_new['tax'])/100)+$rs_new['offer'];
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
                             if($img_offer != '') echo '<div class="sale-percentage">- '.round((100 - (($rs_new['offer']/$rs_new['price'])*100)),0).'%</div>';
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
						   <section class="price-container"><?php echo ($view_prices ? '<span class="old-price-container">'.$currency_l.num_formatt($price).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($offer).$currency_r.'</span>' : ''); ?></section>
                           <section class="action-container text-center"> 
							 <?php 
                               if($rs_new['by_exposure']){
                             ?>
                                  <a href="#" class="btn btn-info squared unbordered solid by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a> 
                             <?php 
                               }else{
                             ?>  
                                  <a href="<?php echo path_abs_products.'/'.$rs_new['id'].'-'.($rs_new['file_name'] == '' ? filesystem($rs_new['name']) : $rs_new['file_name']).'.php'; ?>" data-rel-name="<?php echo $rs_new['name']; ?>" data-rel-img="<?php echo $img; ?>" class="add-to-cart-small<?php echo (($rs_new['availability'] <= 0 && $rs_new['unlimited_availability'] == 0) ? ' disabled': '').($rs_new['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $rs_new['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a> 
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
     </div><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php 
     require_once('include/footer.php');
    ?>      
   <script type="text/javascript" src="<?php echo theme_js_path ?>/localized/jquery.eislideshow.js"></script> 
   <script type="text/javascript" src="<?php echo theme_js_path ?>/localized/jquery.easing.1.3.js"></script> 
  </body>
</html>