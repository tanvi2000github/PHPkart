<?php
$AdditionalHeadTags = '<link href="'.theme_css_path.'/slider.css" rel="stylesheet">';
/* for XSS */
foreach($_GET as $key => $val){
 ${'get_'.$key} = strip_tags($val);
}
 require_once('include/header.php');
 $sql_control_cat = execute('select id from '.$table_prefix.'categories where status = 1 and id = '.$category_id); 
 $rs_control_cat = mysql_fetch_array($sql_control_cat);
 if(!$rs_control_cat) die(header('location:'.abs_client_path)); 
   function get_part_querystring($key_to_delete){
	 $query_string = '';
	 $arr_to_delete = explode(',',$key_to_delete);
	 if(isset($_GET)){		 
		   foreach($_GET as $key => $val){
				if(!in_array($key,$arr_to_delete))
				  $query_string .= '&'.$key.'='.strip_tags($val); 
		   }					  
	 }
	 return $query_string;				   
   } 
  function cookies_filter_catalog($name_cookie,$cookie_part,$default_value){
	  global $cookies_persistence;
	  global $path_products;
	  global $client_path;
	 if(isset($_GET[$cookie_part]) && $_GET[$cookie_part] != ''){
	  $result = strip_tags($_GET[$cookie_part]);
	  setcookie($name_cookie.'['.$cookie_part.']', $result, time()+((3600*24)*$cookies_persistence),'/'.$client_path.'/'.$path_products.'/');	
	 }  
	 $result = !isset($_COOKIE[$name_cookie][$cookie_part]) ? 
	                 (isset($_GET[$cookie_part]) ? $_GET[$cookie_part] : strip_tags($default_value)) 
				   : (isset($_GET[$cookie_part]) ? $_GET[$cookie_part] : $_COOKIE[$name_cookie][$cookie_part]);
	 return $result;
  } 
  $view_mode = cookies_filter_catalog('filtercatalog','wm','grid');
  $per_page = cookies_filter_catalog('filtercatalog','pc',$products_per_page);
  $sort_by = cookies_filter_catalog('filtercatalog','sb','name');
  $order_by = cookies_filter_catalog('filtercatalog','ob','asc');
  $offer_only = isset($_GET['of']) ? $get_of : 'false';
  
  if(!empty($arr_container_products)){
  /* REWRITE $arr_container_products WHITH ONLY VISIBLE PRODUCTS */  
  $sql_visible_products = execute('select id from '.$table_prefix.'products where visible = 1 and active = 1 and id in ('.implode(',',$arr_container_products).')');
  $new_arr_container_products = array();
  while($rs_visible_products = mysql_fetch_array($sql_visible_products)){
	  $new_arr_container_products[] = $rs_visible_products['id'];
  }	 
	if(!empty($new_arr_container_products)){
	  /* GET PRODUCT ATTRIBUTES/FILTERS ARRAY */
	   $sql_attr_filter = execute('select * from '.$table_prefix.'products_attributes where id_product in ('.implode(',',$new_arr_container_products).') order by attribute_name');
		while($rs_filter = mysql_fetch_array($sql_attr_filter)){
		   $arr_filters[$rs_filter['attribute_name']][$rs_filter['attribute_value']] = $rs_filter['id_o'];	   
		}	
	}
  }
  /* FILTERS ON QUERY */
  /******** get new array product if filters are activated *******/
  if(isset($_GET['filters'])){	
    foreach(explode(',',$get_filters) as $val){
	  	$arr_ids_filters[] = $val;
	}
	$sql_f = execute('
SELECT
  s1.id_o ida,s1.attribute_name,s2.id_product idp
FROM
  '.$table_prefix.'products_attributes s1
RIGHT JOIN
  '.$table_prefix.'products_attributes s2
    ON  s2.attribute_value = s1.attribute_value
WHERE
  s1.id_o in ('.implode(',',$arr_ids_filters).')
  and
  s2.id_product in ('.implode(',',$arr_container_products).')');
	while($rs_filter_f = mysql_fetch_array($sql_f)){
	   $arr_ids_products[$rs_filter_f['attribute_name']][] = $rs_filter_f['idp'];	   
	}
	$arr_container_products = array();	
	foreach($arr_ids_products as $key => $val){
	  	$subarrays[] = $val;
	}
	$result_array = reset($subarrays);
	foreach ($subarrays as $arr) {
	  $result_array = array_intersect($result_array, $arr);
	}
	foreach($result_array as $key => $val){
	  	$arr_container_products[] = $val;
	}
  }
  /******** / get new array product if filters are activated *******/
  $sqlfilter = 'id in ('.implode(',',$arr_container_products).') and ';  
  $sqlfilter_or = '';
  $sqlfilter .= " visible = 1 and ";
  $sqlfilter .= " active = 1 and ";
  if(isset($_GET['prc']) && $_GET['prc']){
	 $arr_price_qs = explode(',',$get_prc);
	if(plugin_exsists('businesstype') && get_client_business_bc()){
	 $sqlfilter .= " ((((rprice*tax)/100)+rprice >= ".$arr_price_qs[0]." and ((rprice*tax)/100)+rprice <= ".$arr_price_qs[1]." and roffer <= 0) or (((roffer*tax)/100)+roffer >= ".$arr_price_qs[0]." and ((roffer*tax)/100)+roffer <= ".$arr_price_qs[1]." and roffer > 0)) and ";	
	}else{		 
	 $sqlfilter .= " ((((price*tax)/100)+price >= ".$arr_price_qs[0]." and ((price*tax)/100)+price <= ".$arr_price_qs[1]." and offer <= 0) or (((offer*tax)/100)+offer >= ".$arr_price_qs[0]." and ((offer*tax)/100)+offer <= ".$arr_price_qs[1]." and offer > 0)) and ";	
	}
  }
  if($offer_only == 'true'){
   if(plugin_exsists('businesstype') && get_client_business_bc()){
	 $sqlfilter .= " roffer > 0 and ";
   }else{
	 $sqlfilter .= " offer > 0 and ";	
   }
  }    
  if ($sqlfilter_or != ''){
	$sqlfilter = $sqlfilter . '('.$sqlfilter_or; 
  }
   if ($sqlfilter != ''){ 
	 if ($sqlfilter_or != ''){
	  $sqlfilter = ' Where ' . mb_substr($sqlfilter, 0, mb_strlen($sqlfilter) - 4).')';
	 }else{
	  $sqlfilter = ' Where ' . mb_substr($sqlfilter, 0, mb_strlen($sqlfilter) - 5);
	 }
   }
/* QUERY TO DB AND PRODUCTS ARRAY GENERATION */
     $min_price = 0;
	 $max_price = 1;
	 $get_currentMin_price_filter = 0;
	 $get_currentMax_price_filter = 1;
if(!empty($arr_container_products)) {
	$max_price = 1;	 	
	$get_currentMin_price_filter = 0;
	$get_currentMax_price_filter = 1;	
	asort($arr_container_products);
	$res = execute('select * from '.$table_prefix.'products '.$sqlfilter);
	while($rs = mysql_fetch_array($res)){	
			  if(plugin_exsists('businesstype') && get_client_business_bc()){
				  $rs['offer'] = $rs['roffer'];
				  $rs['price'] = $rs['rprice'];
			  }					
		$array_p[$rs['id']] = array(
									   'name' => $rs['name'],
									   'file_name' => $rs['file_name'] == '' ? filesystem($rs['name']) : $rs['file_name'],
									   'id' => $rs['id'],
									   'add_data' => $rs['add_data'],
									   'category_id' => $rs['categories'],
									   'availability' => $rs['availability'],
									   'unlimited_availability' => $rs['unlimited_availability'],
									   'code' => $rs['code'],
									   'options' => $rs['options'],
									   'description' => $rs['description'],
									   'image' => $rs['url_image'] != '' ? path_abs_img_products.'/'.$rs['id'].'/600x600/1_'.$rs['url_image'] : theme_img_path.'/img_not_available.jpg',
									   'price_order' => $rs['offer'] == 0 ? $rs['price'] : $rs['offer'],
									   'price' => (($rs['price']*$rs['tax'])/100)+$rs['price'] + (plugin_exsists('multitaxes') ? calculate_taxes_value($rs['price'],$rs['pl_multitax']) : 0),
									   'by_exposure' => $rs['by_exposure'],
									   'offer' => $rs['offer'] == 0 ? 0 : (($rs['offer']*$rs['tax'])/100)+$rs['offer'] + (plugin_exsists('multitaxes') ? calculate_taxes_value($rs['offer'],$rs['pl_multitax']) : 0)
									  );
	}
	/* MIN AND MAX PRICE CALCULATION */
	$arr_categories_prices = array($category_id);
	if(count($mptt -> get_children($category_id)) > 0){
		foreach($mptt -> get_children($category_id) as $key => $val){
		 $arr_categories_prices[] = $val['id'];
		}
	}
	$res = execute('select * from '.$table_prefix.'products where categories in ('.implode(',',$arr_categories_prices).')');
	while($rs = mysql_fetch_array($res)){
	  if(plugin_exsists('businesstype') && get_client_business_bc()){
		  $rs['offer'] = $rs['roffer'];
		  $rs['price'] = $rs['rprice'];
	  }		
	  $arr_price[] = $rs['offer'] > 0 ? ((($rs['offer']*$rs['tax'])/100)+$rs['offer']) + (plugin_exsists('multitaxes') ? calculate_taxes_value($rs['offer'],$rs['pl_multitax']) : 0) : ((($rs['price']*$rs['tax'])/100)+$rs['price']) + (plugin_exsists('multitaxes') ? calculate_taxes_value($rs['price'],$rs['pl_multitax']) : 0);
	}
    $min_price = 0;
	$max_price = max($arr_price);	 	
	$get_currentMin_price_filter = isset($_GET['prc']) && $_GET['prc'] != '' ? $arr_price_qs[0] : $min_price;
	$get_currentMax_price_filter = isset($_GET['prc']) && $_GET['prc'] != '' ? $arr_price_qs[1] : $max_price;
}
?>
  <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?> 
     <section class="row-fluid"><!-- BODY breadcrumb --> 
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <?php 
		    $get_tree = $mptt->get_path($category_id);
			foreach($get_tree as $key => $val){
			 $path_breadcrumb[] = $val['id'];
			}
		    $bread_array = explode('/',$mptt->get_orizzontal($category_id,'/'));
			$breadcrumb = '';
			foreach($bread_array as $key){
				if(end($bread_array) == $key){
				  echo '<li class="active" data-rel-menu="'.implode('|',$path_breadcrumb).'">'.$key.'</li>';
				}else{
				  $breadcrumb .= '/'.filesystem(html_entity_decode($key));
				  echo '<li><a href="'.path_abs_products.$breadcrumb.'">'.$key.'</a> <span class="divider">/</span></li>';	
				}
			}
		  ?>
        </ul>     
     </section><!-- / BODY breadcrumb -->
     <section class="row-fluid"><!-- BODY ROW --> 
       <section class="span4 left-sidebar-container"> 
        <?php require_once('include/left-sidebar.php'); ?> 
        <br/>  
           <aside class="left-sidebar">
               <nav class="accordion_menu_container">
               <div class="head default-head">
                  <div class="box-header">
                     <span class="header-text"><?php echo $lang_client_['catalog']['HEAD_FILTERS_BOX']; ?></span>
                  </div>          
               </div>
               <div class="label label-info responsiveHead"><h4><?php echo $lang_client_['catalog']['HEAD_FILTERS_BOX']; ?> <i class="col-menu"></i></h4></div>
                <div class="responsiveMenu">  
                  <br/> 
                 <?php
				 if(!empty($arr_filters)){
				  $filter_array_q = isset($_GET['filters']) ? explode(',',$get_filters) : array();
				  foreach($arr_filters as $key => $val){
					ksort($val);
					echo '<ul class="accordion-filters"><li><a href="#"><span class="active_node">'.$key.'</span></a><ul>';
					foreach($val as $key => $val){
					   echo '<li'.(in_array($val,$filter_array_q) ? ' class="active"' : '').'>';
					  echo'<label class="checkbox" for="filter_'.$val.'">
						<input type="checkbox" value="'.$val.'"  id="filter_'.$val.'" data-toggle="checkbox" '.(in_array($val,$filter_array_q) ? 'checked' : '').'/>
						'.$key.'
					  </label>';					   
					   echo '</li>'; 
					}
					echo '</ul></li></ul><br/>';
				  }		  			  
				 }
				 ?>  
                <div style="background:#fff;border:1px solid #09c;padding:5px;"> 
                  <label class="checkbox" for="offer_filter"><input type="checkbox" data-toggle="checkbox" id="offer_filter" <?php echo ($offer_only == 'false' ? '' : ' checked'); ?> /><?php echo $lang_client_['catalog']['FIELD_LABEL_ONLY_OFFER']; ?></label> 
                </div>
                <br/><br/>
                 <div class="pull-left">
                  <?php echo $currency_l.num_formatt($min_price).$currency_r; ?>
                  </div>
                  <div class="pull-right">
                  <?php echo $currency_l.num_formatt($max_price).$currency_r ?>
                  </div>                  
                  <div class="row-fluid">
                    <span class="span12">
                      <input type="text" id="slider" calss="span12" value="" data-slider-min="<?php echo $min_price; ?>" data-slider-max="<?php echo $max_price; ?>" data-slider-step="1" data-slider-value="[<?php echo $get_currentMin_price_filter.','.$get_currentMax_price_filter; ?>]" data-slider-orientation="orizontal" data-slider-selection="after" data-slider-tooltip="show">
                    </span>
                  </div>
                  <br/> 
                <div class="pull-left btn btn-warning" id="btn-reset-filter"><i class="icon-refresh icon-white"></i> <?php echo $lang_client_['catalog']['BUTTON_RESET_FILTER']; ?></div>
                <div class="pull-right btn btn-success" id="btn-filter"><i class="icon-filter icon-white"></i> <?php echo $lang_client_['catalog']['BUTTON_FILTER']; ?></div>
                <br/><br/>  
                 </div>
                </nav>                                  
           </aside>                     		  
        </section>     
	    <section class="span8">
          <?php
           if(empty($array_p)) {
            echo '<article class="span12">              
              <div class="text-center alert alert-warning alert-block">'.$lang_client_['catalog']['ALERT_NO_PRODUCT'].'.</div>
            </article>';	 
           }else{
			$counter = 0;						
			usort($array_p, build_sorter($sort_by,$order_by));
			 /* FOR PAGINATION */
			   $p_perPage = $per_page;
			   $p_total = count($array_p);
			   $page_total = ceil($p_total/$p_perPage);
			   $max_left_right = 3;		   			   
			   $page = isset($_GET['p']) && is_numeric($_GET['p']) && ($_GET['p'] != '' && $_GET['p'] <= $page_total) ? $_GET['p'] : 1;
			   $pLeft = $page > $max_left_right ? $page - $max_left_right : 1;
			   $pRight = ($page + $max_left_right) < $page_total ? $page + $max_left_right : $page_total;			   
			   $start = ($page * $p_perPage) - $p_perPage;
			  if(!isset($_GET['p']) || !is_numeric($_GET['p'])){			 
				 @header('location:?p=1'.get_part_querystring('p'));   
			   }
			 /* / FOR PAGINATION */	
			$array_p = array_slice($array_p,$start,$p_perPage);
         ?>
            <section id="filter-bar" class="span12">
               <div id="view-mode" class="pull-left">
                 <?php
				  if($view_mode == 'grid'){
					 echo '<span class="grid-view active"></span><a class="list-view" href="?p='.$page.get_part_querystring('p,wm').'&wm=list"></a>';
				  }else{
					 echo '<a class="grid-view" href="?p='.$page.get_part_querystring('p,wm').'&wm=grid"></a><span class="list-view active"></span>';  
				  }
                ?>
               </div>
               <div id="order-by" class="pull-right">
                  <a href="?p=<?php echo $page.get_part_querystring('p,ob'); ?>&ob=<?php echo ($order_by == 'desc' ? 'asc' : 'desc'); ?>"><i class="sort-by <?php echo ($order_by == 'desc' ? 'desc' : 'asc'); ?>"></i></a>
               </div>                
               <div id="sort-by" class="pull-right">
                   <strong class="text-info"><?php echo $lang_client_['catalog']['LABEL_SORT_BY']; ?>: </strong>
                   <select id="products_sortby" class="bootstyl text-left" data-additional-classes="btn-info solid unbordered" data-verse="right">
                     <?php 
                        echo '<option value="?p='.$page.get_part_querystring('p,sb').'&sb=name" '.($sort_by == 'name' ? 'selected' : '').'>'.$lang_client_['catalog']['DROPDOWN_SORT_BY_NAME'].'</option>                   
					          <option value="?p='.$page.get_part_querystring('p,sb').'&sb=price_order" '.($sort_by == 'price_order' ? 'selected' : '').'>'.$lang_client_['catalog']['DROPDOWN_SORT_BY_PRICE'].'</option>
						      <option value="?p='.$page.get_part_querystring('p,sb').'&sb=code" '.($sort_by == 'code' ? 'selected' : '').'>'.$lang_client_['catalog']['DROPDOWN_SORT_BY_CODE'].'</option>';  
					 ?>
                   </select>
               </div>              
               <?php
			   if($p_total > $products_per_page){
			   ?>
                 <div id="limiter" class="pull-right"> 
                 <strong class="text-info"><?php echo $lang_client_['catalog']['LABEL_VIEW']; ?>: </strong>
                   <select id="products_limiter_counter" class="bootstyl text-left" data-additional-classes="btn-info solid unbordered" data-verse="right">
                     <?php 
                        echo '<option value="?p=1'.get_part_querystring('p,pc').'&pc='.$products_per_page.'" '.($per_page == $products_per_page ? 'selected' : '').'>'.$products_per_page.'</option>';                    
					    echo $p_total > $products_per_page ? '<option value="?p=1'.get_part_querystring('p,pc').'&pc='.($products_per_page*2).'" '.($per_page == ($products_per_page*2) ? 'selected' : '').'>'.($products_per_page*2).'</option>' : '';
						echo $p_total > ($products_per_page*2) ? '<option value="?p=1'.get_part_querystring('p,pc').'&pc='.($products_per_page*3).'" '.($per_page == ($products_per_page*3) ? 'selected' : '').'>'.($products_per_page*3).'</option>' : '';  
					 ?>
                   </select>
                 </div>
               <?php
			   }
			   ?>
               <div class="clearfix"></div>
            </section>         
			 <?php				              			
                foreach($array_p as $key => $val){
				  $img_offer = $val['offer'] > 0 ? '<img class="ribbon-sale" src="'.theme_img_path.'/labels/offerta.png" alt="Offer" />' : ''; 
				  $img_new = datediff("G", view_date($val['add_data']), view_date(date("y-m-d"))) < $days_product_new  && mb_substr($val['add_data'],0,10) != '0000-00-00' ? '<img src="'.theme_img_path.'/labels/novita.png" class="ribbon-new" alt="New" />' : '';
				  if($view_mode == 'grid'){
				    /*************** GRID VIEW ************/ 
					if ($counter % 3 == 0 || $counter == 0){
					 echo '<div class="row-fluid">';
					}
              ?>
                  <div class="span4">
                    <article class="product-container-grid" data-exstend-link="on"> 
                     <section class="product-img">
						<?php 
                         echo $img_offer.$img_new;
                         if($img_offer != '') echo '<div class="sale-percentage">- '.round((100 - (($val['offer']/$val['price'])*100)),0).'%</div>';
                        ?>                     
                       <div class="img-thumb">
                             <?php
							   if(file_exists(str_replace(path_abs_img_products,path_rel_img_products,str_replace('1_','2_',$val['image'])))){
								 echo '<div class="first-thumb hide">'.$val['image'].'</div>';
								 echo '<div class="second-thumb hide">'.str_replace('1_','2_',$val['image']).'</div>';
							   }
							 ?>                         
                         <a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>"><img class="lazy" data-original="<?php echo $val['image']; ?>" src="<?php echo $val['image']; ?>" alt="<?php echo str_replace('"',"'",$val['name']); ?>" /></a>
                       </div>
                     </section>
                     <section class="product-detail-container text-center">
                       <section class="product-name" title="<?php echo $val['name']; ?>"><a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>"><?php echo $val['name']; ?></a></section>
                       <section class="price-container"><?php echo ($view_prices ? ($val['offer'] > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($val['price']).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($val['offer']).$currency_r.'</span>' : $currency_l.num_formatt($val['price']).$currency_r) : ''); ?></section>
                       <section class="action-container text-center">
                         <?php 
						   if($val['by_exposure']){
					     ?>
                              <a href="#" class="btn btn-info squared unbordered solid by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a> 
                         <?php 
						   }else{
					     ?>     
                              <a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>" data-rel-name="<?php echo $val['name']; ?>" data-rel-img="<?php echo $val['image']; ?>" class="add-to-cart-small<?php echo (($val['availability'] <= 0 && $val['unlimited_availability'] == 0) ? ' disabled' : '').($val['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $val['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a> 
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
					if ($counter % 3 == 0 || $counter >= count($array_p) ){
					 echo '</div><br/>';
					}	
				  }else{
					/*************** LIST VIEW ************/ 
			  ?>
                  <div class="row-fluid">
                   <div class="span12">
                    <article class="product-container-list" data-exstend-link="on"> 
                      <div class="row-fluid">
                         <section class="product-img span4">
							<?php 
                             echo $img_offer.$img_new;
                             if($img_offer != '') echo '<div class="sale-percentage">- '.round((100 - (($val['offer']/$val['price'])*100)),0).'%</div>';
                            ?>                         
                           <div class="img-thumb"> 
                             <?php
							   if(file_exists(str_replace(path_abs_img_products,path_rel_img_products,str_replace('1_','2_',$val['image'])))){
								 echo '<div class="first-thumb hide">'.$val['image'].'</div>';
								 echo '<div class="second-thumb hide">'.str_replace('1_','2_',$val['image']).'</div>';
							   }
							 ?>                                                                               
                             <a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>"><img class="lazy" data-original="<?php echo $val['image']; ?>" src="<?php echo $val['image']; ?>" alt="<?php echo str_replace('"',"'",$val['name']); ?>" /></a>
                           </div>
                         </section>
                         <section class="product-detail-container text-left span8">
                          <div class="row-fluid">
                             <div class="span7">
                               <section class="product-name" title="<?php echo $val['name']; ?>"><a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>"><?php echo $val['name']; ?></a></section>
                               <section class="description"><?php echo cutOff($val['description'],200); ?></section>
                             </div>
                             <div class="span5 text-right">
                               <section class="price-container text-right"><?php echo ($view_prices ? ($val['offer'] > 0 ? '<span class="old-price-container">'.$currency_l.num_formatt($val['price']).$currency_r.'</span> <span class="product-offer">'.$currency_l.num_formatt($val['offer']).$currency_r.'</span>' : $currency_l.num_formatt($val['price']).$currency_r) : ''); ?></section>
                               <section class="action-container text-right">  
								 <?php 
                                   if($val['by_exposure']){
                                 ?>
                                      <a href="#" class="btn btn-info squared unbordered solid by_exposure"><i class="icon-info-sign icon-white"></i> <?php echo $lang_client_['general']['BUTTON_INFO_ON_BY_EXPOSURE_PRODUCTS']; ?></a> 
                                 <?php 
                                   }else{
                                 ?>    
                                      <a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($val['category_id']).'/'.$val['id'].'-'.$val['file_name'].'.php'; ?>" data-rel-name="<?php echo $val['name']; ?>" data-rel-img="<?php echo $val['image']; ?>" class="add-to-cart-small<?php echo (($val['availability'] <= 0 && $val['unlimited_availability'] == 0) ? ' disabled' : '').($val['options'] != '' ? '': ' without-options'); ?>" data-id="<?php echo $val['id']; ?>"><?php echo $lang_client_['general']['BUTTON_ADD_TO_CART']; ?></a> 
                                 <?php 
								   }
                                 ?>                                       
                               </section>
                             </div>
                           <div class="clearfix"></div>
                          </div>
                         </section> 
                      </div>                      
                    </article>
                   </div>
                  </div>      
                  <hr/>         
              <?php 
				  }
                }			
           }
           
		   if(isset($page_total) && $page_total > 1){
		   ?>
           <div class="row-fluid">
             <div class="span12 text-right">
                <section class="pagination">
                  <ul>
					  <?php                                          
						   if($page > 1) echo '<li><a href="?p='.($page-1).get_part_querystring('p').'">«</a></li>';	                 
                           if($pLeft > 1) echo '<li class="active"><span>...</span></li>';
                           while($pLeft<$pRight){
                               if($pLeft == $page){
                                   echo '<li class="active"><span>'.$pLeft.'</span></li>';
                               }else{
                                  echo '<li><a href="?p='.$pLeft.get_part_querystring('p').'">'.$pLeft.'</a></li>';
                               }
                              $pLeft++;
                           }
                           if($pRight < $page_total) echo '<li class="active"><span>...</span></li>';
							 if($page == $page_total){
							   echo '<li class="active"><span>'.$page_total.'</span></li>';
							 }else{
							   echo '<li><a href="?p='.$page_total.get_part_querystring('p').'">'.$page_total.'</a></li>';
							   echo '<li><a href="?p='.($page+1).get_part_querystring('p').'">»</a></li>';
							 }  
                      ?>                
                  </ul>
                </section>
             </div>
           </div> 
           <?php
		   }
		   ?>
       </section>
     </section><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php 
     require_once('include/footer.php');
    ?>    
    <script type="text/javascript">
	$(function(){
     $('body').data('currency','<?php echo $currency; ?>');
	});
    </script>       
    <script type="text/javascript" src="<?php echo theme_js_path ?>/localized/bootstrap-slider.js"></script> 
    <script type="text/javascript" src="<?php echo theme_js_path ?>/localized/jquery.accordion_menu.js"></script> 
    <script type="text/javascript" src="<?php echo theme_js_path ?>/localized/catalog.js"></script>   
  </body>
</html>