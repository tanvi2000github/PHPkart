     <aside class="left-sidebar">
      <nav id="categories-menu">
       <div class="head default-head">
          <div class="box-header">
             <span class="header-text"><?php echo $lang_client_['left_sidebar']['TEXT_CATEGORIES_TITLE']; ?></span>
          </div>          
       </div>
       <div class="label label-info responsiveHead"><h4><?php echo $lang_client_['left_sidebar']['TEXT_CATEGORIES_TITLE']; ?> <i class="col-menu"></i></h4></div>
                <div class="accordion_menu_container responsiveMenu">
                  <ul class="nav nav-list nav-pills nav-stacked" id="accordion-categories-menu">
				  <?php
					class categories_accordion extends Zebra_Mptt { 
					    function get_visible_products($arr=array()){
							global $table_prefix;
							if(!empty($arr)){
                            $sql = execute('select id from '.$table_prefix.'products where visible = 1 and active = 1 and id IN ('.implode(',',$arr).')');
							  return mysql_num_rows($sql);
							}else{
							  return 0;	
							}
						}
						function get_accordion($node = 0) {							
							$list = '';
							$result = $this->get_children($node, true);	
							$result = array_msort($result, array('name'=>SORT_ASC));	
							foreach ($result as $id => $properties){	
							   if($result[$id]['status']){
								 $list .= $this->get_descendants_count($node) > 0 ? '<ul class="nav nav-list nav-pills nav-stacked">' : '';
								 $count_product = 0;
								  if(file_exists(path_rel_products.'/'.$this->get_orizzontal_path($result[$id]['id'], '/').'/inc_array_product.php')){
								   include(path_rel_products.'/'.$this->get_orizzontal_path($result[$id]['id'], '/').'/inc_array_product.php');
								   $count_product = $this->get_visible_products($arr_container_products);	
								  }else{
								   $count_product = $count_product;
								  }								 
								foreach($this->get_children($result[$id]['id']) as $k => $v){									
								   if(file_exists(path_rel_products.'/'.$this->get_orizzontal_path($v['id'], '/').'/inc_array_product.php')){								   
								     if(!$v['status']){
										include(path_rel_products.'/'.$this->get_orizzontal_path($v['id'], '/').'/inc_array_product.php');									 
										 $count_product = $count_product-$this->get_visible_products($arr_container_products);
									 }
								   }								
								}
								 $list .= '<li><a data-breadcrumb="'.$result[$id]['id'].'" class="link" href="'.path_abs_products.'/'.$this->get_orizzontal_path($result[$id]['id']).'">'.$result[$id]['name'].' ('.$count_product.') '.($this->get_descendants_count($result[$id]['id']) > 0 ? '<span class="pull-right active_node"></span>' : '').'</a>';
								  $result[$id]['children'] = $this->get_tree($id);
								  $result[$id]['children'] = array_msort($result[$id]['children'], array('name'=>SORT_ASC));
								  $list .= $this->get_descendants_count($result[$id]['id']) > 0 ? '<ul class="nav nav-list nav-pills nav-stacked">' : '';								  
								  foreach($result[$id]['children'] as $key => $val){
								   if($val['status']){	
									   $count_product = 0;
										if(file_exists(path_rel_products.'/'.$this->get_orizzontal_path($val['id'], '/').'/inc_array_product.php')){
										 include(path_rel_products.'/'.$this->get_orizzontal_path($val['id'], '/').'/inc_array_product.php');
										 $count_product = $this->get_visible_products($arr_container_products);	
										}else{
										 $count_product = $count_product;
										}								 
									  foreach($this->get_children($val['id']) as $k => $v){									
										 if(file_exists(path_rel_products.'/'.$this->get_orizzontal_path($v['id'], '/').'/inc_array_product.php')){								   
										   if(!$v['status']){
											  include(path_rel_products.'/'.$this->get_orizzontal_path($v['id'], '/').'/inc_array_product.php');									 
											   $count_product = $count_product-$this->get_visible_products($arr_container_products);
										   }
										 }								
									  }
									$list .= '<li><a data-breadcrumb="'.$val['id'].'" class="link" href="'.path_abs_products.'/'.$this->get_orizzontal_path($val['id']).'">'.$val['name'].' ('.$count_product.') '.($this->get_descendants_count($val['id']) > 0 ? '<span class="pull-right active_node"></span>' : '').'</a>'.$this->get_accordion($key).'</li>';
								   }
								  }
								  $list .= $this->get_descendants_count($result[$id]['id']) > 0 ? '</ul>' : '';


								  $list .= $this->get_descendants_count($node) > 0 ? '</ul>' : '';
							   }
							 }							 
							return $list;
						}
					}  
					$mptt_accordion = new categories_accordion(); 			  
                   $res_cat = execute('select id,name from '.$table_prefix.'categories where level = 0 and status = 1 order by name asc');
                   while($rs_cat = mysql_fetch_array($res_cat)){					
					 $count_product = 0;
					  if(file_exists(path_rel_products.'/'.$mptt->get_orizzontal_path($rs_cat['id'], '/').'/inc_array_product.php')){
					   include(path_rel_products.'/'.$mptt->get_orizzontal_path($rs_cat['id'], '/').'/inc_array_product.php');
					   $count_product = $mptt_accordion->get_visible_products($arr_container_products);	
					  }else{
					   $count_product = $count_product;
					  }								 
					foreach($mptt->get_children($rs_cat['id']) as $k => $v){									
					   if(file_exists(path_rel_products.'/'.$mptt->get_orizzontal_path($v['id'], '/').'/inc_array_product.php')){								   
						 if(!$v['status']){
							include(path_rel_products.'/'.$mptt->get_orizzontal_path($v['id'], '/').'/inc_array_product.php');									 
							 $count_product = $count_product-$mptt_accordion->get_visible_products($arr_container_products);
						 }
					   }								
					}					
                  ?>           
                     <li style="position:relative;">
                       <a data-breadcrumb="<?php echo $rs_cat['id']; ?>" class="link" href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($rs_cat['id']); ?>"><?php echo $rs_cat['name'].' ('.$count_product.') '; ?><?php echo ($mptt->get_descendants_count($rs_cat['id']) > 0 ? '<span class="pull-right active_node"></span>' : ''); ?></a>                       
                       <?php 
					      if($mptt->get_descendants_count($rs_cat['id']) > 0)
							  echo $mptt_accordion -> get_accordion($rs_cat['id']); 
					   ?>
                     </li>                  	
				  <?php
                   }
                  ?>
                 </ul>                    
                </div> 
      </nav>        
     </aside>