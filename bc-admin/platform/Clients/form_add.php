<?php
/*
 This id html of form to add a new record into database
*/
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
?>
<form id="add_element_form" method="post">
     <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>

   <div class="container-fluid" id="conteiner_form_loader">
      <div class="checkradio-group" data-icon="icon-ok icon-white">
        <input type="radio" id="private" name="is_company" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_PRIVATE_PERSON']; ?>" data-additional-classes="btn-info" value="private" checked />
        <input type="radio" id="company" name="is_company" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_COMPANY']; ?>" data-additional-classes="btn-info" value="company" />
      </div>
      <div class="row-fluid">
         <div class="span6">
            <div class="row-fluid">
              <input type="text" class="required" name="name" id="name" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_NAME']; ?>" />
              <input type="text" class="required" name="lastname" id="lastname" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_LASTNAME']; ?>" />
            </div>
            <div class="row-fluid hidden">
              <input type="text" class="required ignore" name="tax_code" id="tax_code" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_TAXCODE']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required email" name="email" id="email" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_EMAIL']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="phone" id="phone" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_PHONE']; ?>" />
              <input type="text" name="fax" id="fax" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_FAX']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="address" id="address" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_ADDRESS']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="zipcode" id="zipcode" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_ZIPCODE']; ?>" />
              <input type="text" class="required" name="city" id="city" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_CITY']; ?>" />
            </div>
         </div>
         <div class="span6">
            <div class="row-fluid">
              <input type="text" class="required" name="userid" id="userid" value="" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_USERID']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="password" class="required" name="password" id="password" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_PASSWORD']; ?>" />
              <input type="password" class="required" name="password2" id="password2" equalTo="#password" value="" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_REPEAT_PASSWORD']; ?>" />
            </div>
            <div class="row-fluid">
              <div class="span12">
               <input type="checkbox" id="enable" data-icon="icon-ok icon-white" name="enable" class="bootstyl" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_ENABLE_TO_SHOP']; ?>" data-additional-classes="btn-info btn-block" value="1" checked />
              </div>
            </div>
            <?php
			 if(plugin_exsists('businesstype') && get_admin_business_bc()){
			?>
            <br/>
            <div class="row-fluid">
               <div class="span12">
                 <div class="well well-small">
                      <input type="checkbox" id="enable_resell_request" data-icon="icon-ok icon-white" name="enable_resell_request" class="bootstyl" data-label-name="<?php $lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_ENABLE']; ?>" data-additional-classes="btn-primary" value="1" />
                      <br/><small><strong class="text-info"><?php $lang_['pl_businesstype']['FORM_LEBEL_REQUEST_RETAILER_ENABLED_MESSAGE']; ?></strong></small>
                 </div>
               </div>
            </div>
            <?php
			 }
			?>
         </div>
      </div>
      <br/><br/>
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span>
   </div>
</form>
