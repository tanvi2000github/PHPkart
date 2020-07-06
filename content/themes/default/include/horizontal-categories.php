       <div class="row-fluid" style="position:relative;">
        <div class="span12 carousel_wrapper horizontal-category">                                 
          <span class="carousel-prev" id="carousel-category-prev"></span>
          <ul>       
            <?php
             $res_cat = execute('select id,name from '.$table_prefix.'categories where level = 0 and status = 1 order by name asc');
             while($rs_cat = mysql_fetch_array($res_cat)){      					   
            ?> 
              <li>
                <a href="<?php echo path_abs_products.'/'.$mptt->get_orizzontal_path($rs_cat['id']); ?>"><?php echo $rs_cat['name']; ?></a>
              </li>
            <?php	  
             }
            ?> 
           </ul>            
          <span class="carousel-next" id="carousel-category-next"></span>                                   
        </div> 
        <span class="categories_menu_indicator"></span>       
       </div>