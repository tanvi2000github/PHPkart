<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
?>

   <div class="box span3 hide" id="advanced_search">
     <div class="box-header well">
       <h2><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TITLE'] ?></h2>
       <div class="box-icon">
         <span class="btn a_close" style="margin-right:5px;" rel="tooltip" title="<?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TOOLTIP_CLOSE'] ?>"><i class="icon-remove"></i></span>
         <span class="btn" id="close_search" rel="tooltip" title="<?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TOOLTIP_HIDE_SHOW'] ?>"><i class="icon-chevron-up"></i></span>                     
       </div>                   
     </div>
     <div class="box-content" id="body_search">
       <div class="container-fluid">
            <form id="form_ad_search">
              <div class="row-fluid">            
                <input type="text" name="name_r" id="name_r" value="" data-array="12,12,<?php echo $lang_['admin_accounts']['FIELD_NAME']; ?>/<?php echo $lang_['admin_accounts']['FIELD_USERID']; ?>" />
              </div>                                             
              <div calss="row-fluid">
                  <span class="btn btn-small a_search"><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_SEARCH'] ?></span>   
                  <span class="btn btn-small btn-primary a_reset"><i class="icon-white icon-refresh"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_RESET'] ?></span>
                  <!-- <span class="btn btn-small btn-danger a_close"><i class="icon-white icon-remove"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_CLOSE'] ?></span> -->              
              </div>  
            </form>
       </div> 
     </div>
   </div>