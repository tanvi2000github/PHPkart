<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
/*******************************************
************** WARNING!!! *******************
** if you want change this file be sure    **
** save it in UTF-8 because there are      **
** some special chars like polish language **
*********************************************
*********************************************/
/*
 This file will contain some general useful functions for this plugin
*/
if(plugin_exsists('slideshow')){
 function get_slideshow(){
	global $table_prefix;
?>
          <div class="wrapper slideshow container-semifluid">
              <div id="ei-slider" class="ei-slider">
                  <ul class="ei-slider-large">
				  <?php	
				    $thumbs = '';
                    $sql_slideshow = execute('select * from '.$table_prefix.'slideshow where active = 1');
                     while($rs_slideshow = mysql_fetch_assoc($sql_slideshow)){
					   if($rs_slideshow['imgs'] != ''){
						   $array_images = array_msort(unserialize($rs_slideshow['imgs']), array('position'=>SORT_ASC));
						  foreach($array_images as $key => $val){
							  if($val['visible']){
								  $thumbs .= '<li><a href="#"></a><img src="'.abs_uploads_path.'/slideshow/'.$rs_slideshow['id'].'/150x150/'.$val['urlimg'].'"  alt="" /></li>';
								  echo '<li><img src="'.abs_uploads_path.'/slideshow/'.$rs_slideshow['id'].'/'.$val['urlimg'].'" alt="" /></li>';
							  }
						  }
					   }
                     } 
                  ?>
                  </ul>
                  <ul class="ei-slider-thumbs">   
                  <li class="ei-slider-element">Current</li>                                    
                   <?php 
                    echo $thumbs;
                   ?>                                                
                  </ul>                 
              </div>
          </div>
<?php     
 }
}
?>