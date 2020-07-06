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
                <input type="text" name="name_r" id="name_r" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_NAME']; ?>/<?php echo $lang_['clients_accounts']['FIELD_LASTNAME']; ?>/<?php echo $lang_['clients_accounts']['FIELD_USERID']; ?>" />
              </div>     
              <div class="row-fluid">            
                <input type="text" name="phone_r" id="phone_r" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_PHONE']; ?>" />
              </div> 
              <div class="row-fluid">            
                <input type="text" name="email_r" id="email_r" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_EMAIL']; ?>" />
              </div>               
              <div class="row-fluid">            
                <input type="text" name="taxcode_r" id="taxcode_r" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_TAXCODE']; ?>" />
              </div>  
              <div class="row-fluid">            
                <input type="checkbox" id="enabled_r" data-icon="icon-ok icon-white" name="enabled_r" class="bootstyl" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_SEARCH_ENABLED']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="disabled_r" data-icon="icon-ok icon-white" name="disabled_r" class="bootstyl" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_SEARCH_DISABLED']; ?>" data-additional-classes="btn-info" value="1" />
              </div>  
            <?php
			  if(plugin_exsists('businesstype') && get_admin_business_bc()){
		    ?>
              <br/>
              <div class="row-fluid">            
                <input type="checkbox" id="reseller_r" data-icon="icon-ok icon-white" name="reseller_r" class="bootstyl" data-label-name="<?php echo $lang_['pl_businesstype']['SEARCH_BUTTON_RETAILER']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
              <br/>
              <div class="row-fluid">            
                <input type="checkbox" id="retailer_padding_request_r" data-icon="icon-ok icon-white" name="retailer_padding_request_r" class="bootstyl" data-label-name="<?php echo $lang_['pl_businesstype']['SEARCH_BUTTON_RETAILER_PADDING_REQUEST']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
              <br/>
              <div class="row-fluid">            
                <input type="checkbox" id="retailer_denied_request_r" data-icon="icon-ok icon-white" name="retailer_denied_request_r" class="bootstyl" data-label-name="<?php echo $lang_['pl_businesstype']['SEARCH_BUTTON_RETAILER_DENIED']; ?>" data-additional-classes="btn-info" value="1" />
              </div>              
            <?php
			  }
			?>
              <br/><br/>                                                                                 
              <div calss="row-fluid">
                  <span class="btn btn-small a_search"><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_SEARCH'] ?></span>   
                  <span class="btn btn-small btn-primary a_reset"><i class="icon-white icon-refresh"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_RESET'] ?></span>
                  <!-- <span class="btn btn-small btn-danger a_close"><i class="icon-white icon-remove"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_CLOSE'] ?></span> -->              
              </div>  
            </form>
       </div> 
     </div>
   </div>