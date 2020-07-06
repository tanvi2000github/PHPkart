<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
$sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
if ($rs[0]){
?>
<form id="add_element_form" method="post">
  <input type="hidden" name="id" id="id" value="<?php echo $rs['id'] ?>" />
  <input type="hidden" name="old_userid" id="old_userid" value="<?php echo $rs['userid'] ?>" />
  <input type="password" style="display:none" name="old_password" id="old_password" value="<?php echo $rs['password'] ?>" />
     <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>

   <div class="container-fluid" id="conteiner_form_loader">
      <div class="checkradio-group" data-icon="icon-ok icon-white">
        <input type="radio" id="private" name="is_company" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_PRIVATE_PERSON']; ?>" data-additional-classes="btn-info" value="private" <?php echo $rs['is_company'] ? '' : 'checked'; ?> />
        <input type="radio" id="company" name="is_company" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_COMPANY']; ?>" data-additional-classes="btn-info" value="company" <?php echo $rs['is_company'] ? 'checked' : ''; ?> />
      </div>
      <div class="row-fluid">
         <div class="span6">
            <div class="row-fluid">
              <input type="text" class="required" name="name" id="name" value="<?php echo $rs['name']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_NAME']; ?>" />
              <input type="text" class="required" name="lastname" id="lastname" value="<?php echo $rs['lastname']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_LASTNAME']; ?>" />
            </div>
            <div class="row-fluid hidden">
              <input type="text" class="required ignore" name="tax_code" id="tax_code" value="<?php echo $rs['tax_code']; ?>" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_TAXCODE']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required email" name="email" id="email" value="<?php echo $rs['email']; ?>" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_EMAIL']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="phone" id="phone" value="<?php echo $rs['phone']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_PHONE']; ?>" />
              <input type="text" name="fax" id="fax" value="<?php echo $rs['fax']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_FAX']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="address" id="address" value="<?php echo $rs['address']; ?>" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_ADDRESS']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required" name="zipcode" id="zipcode" value="<?php echo $rs['zipcode']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_ZIPCODE']; ?>" />
              <input type="text" class="required" name="city" id="city" value="<?php echo $rs['city']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_CITY']; ?>" />
            </div>
         </div>
         <div class="span6">
            <div class="row-fluid">
              <input type="text" class="required" name="userid" id="userid" value="<?php echo $rs['userid']; ?>" data-array="12,12,<?php echo $lang_['clients_accounts']['FIELD_USERID']; ?>" />
            </div>
            <div class="row-fluid">
              <input type="password" class="required" name="password" id="password" value="<?php echo $rs['password']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_PASSWORD']; ?>" />
              <input type="password" class="required" name="password2" id="password2" equalTo="#password" value="<?php echo $rs['password']; ?>" data-array="12,6,<?php echo $lang_['clients_accounts']['FIELD_REPEAT_PASSWORD']; ?>" />
            </div>
            <div class="row-fluid">
              <div class="span12">
               <input type="checkbox" id="enable" data-icon="icon-ok icon-white" name="enable" class="bootstyl" data-label-name="<?php echo $lang_['clients_accounts']['FIELD_ENABLE_TO_SHOP']; ?>" data-additional-classes="btn-info btn-block" value="1" <?php echo $rs['enabled'] ? 'checked' : ''; ?> />
              </div>
            </div>
            <?php
			 if(plugin_exsists('businesstype') && get_admin_business_bc()){
			?>
            <br/>
            <div class="row-fluid">
               <div class="span12">
                 <div class="well well-small">
					<?php
                      /* retailer */
                      if($rs['retailer']){
                          if($rs['retailer_denied']){
                            echo '<div class="alert alert-block alert-warning">
                                      <strong>'.$lang_['pl_businesstype']['FORM_NOTICE_ON_REQUEST_RETAILER_DENIED'].'</strong>';
                                if($rs['retailer_denied_message'] != '') echo '<br/><br/><strong>'.$rs['retailer_denied_message'].'</strong>';
                            echo '</div>
                                  <br/>
                                  <input type="checkbox" id="denied_resell_request" data-icon="icon-ok icon-white" name="rehabilitation_resell_request" class="bootstyl" data-label-name="'.$lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_REHABILITATION_REQUEST'].'" data-additional-classes="btn-success" value="1" />';
                          }else{
                            echo '<div class="alert alert-block alert-info">
                                    <strong>'.$lang_['pl_businesstype']['FORM_NOTICE_ON_REQUEST_RETAILER_SUCCESS'].'</strong>
                                  </div>
                                  <br/>
                                  <input type="checkbox" id="denied_resell_request" data-icon="icon-ok icon-white" name="denied_resell_request" class="bootstyl" data-label-name="'.$lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_DENIED'].'" data-additional-classes="btn-inverse" value="1" />';
                          }
                          echo '<div style="margin-top:10px;" id="message_resell_request_denied_container" class="hide">';
                              if($rs['retailer_denied']){
                                 echo $lang_['pl_businesstype']['FROM_LABEL_REASON_REHABILITATION'].'<br/>
                                 <textarea style="width:96%;" name="message_resell_request_rehabilitation" id="message_resell_request_denied"></textarea>';
                              }else{
                                 echo $lang_['pl_businesstype']['FROM_LABEL_REASON_DENIED'].'<br/>
                                 <textarea style="width:96%;" name="message_resell_request_denied" id="message_resell_request_denied"></textarea>';
                              }
                                 echo '<label for="denied_message_to_client">
                                         <input type="checkbox" id="denied_message_to_client" name="denied_message_to_client" value="1" /> '.$lang_['pl_businesstype']['FROM_LABEL_SEND_MESSAGE'].'
                                       </label>
                                </div>';
                      }else if(!$rs['retailer'] && $rs['retailer_request'] && !$rs['retailer_denied']){
                      /* retailer request */
                       echo '<div class="alert alert-block alert-info">
                               <strong>'.$lang_['pl_businesstype']['FORM_NOTICE_ON_REQUEST_RETAILER'].'</strong>
                             </div>
                             <input type="checkbox" id="enable_resell_request" data-icon="icon-ok icon-white" name="enable_resell_request" class="bootstyl" data-label-name="'.$lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_ENABLE'].'" data-additional-classes="btn-primary" value="1" />
                             <br/><small><strong class="text-info">'.$lang_['pl_businesstype']['FORM_LEBEL_REQUEST_RETAILER_ENABLED_MESSAGE'].'</strong></small>
                             <br/><br/>
                             <input type="checkbox" id="denied_resell_request" data-icon="icon-ok icon-white" name="denied_resell_request" class="bootstyl" data-label-name="'.$lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_DENIED'].'" data-additional-classes="btn-inverse" value="1" />
                             <div style="margin-top:10px;" id="message_resell_request_denied_container" class="hide">
                              Motivo del Rigetto:<br/>
                              <textarea style="width:96%;" name="message_resell_request_denied" id="message_resell_request_denied"></textarea>
                              <label for="denied_message_to_client">
                                <input type="checkbox" id="denied_message_to_client" name="denied_message_to_client" value="1" /> '.$lang_['pl_businesstype']['FROM_LABEL_SEND_MESSAGE'].'
                              </label>
                             </div>';
                      }else{
                        /* normal client */
                        echo '<input type="checkbox" id="enable_resell_request" data-icon="icon-ok icon-white" name="enable_resell_request" class="bootstyl" data-label-name="'.$lang_['pl_businesstype']['FORM_BUTTON_REQUEST_RETAILER_ENABLE'].'" data-additional-classes="btn-primary" value="1" />
                              <br/><small><strong class="text-info">'.$lang_['pl_businesstype']['FORM_LEBEL_REQUEST_RETAILER_ENABLED_MESSAGE'].'</strong></small>';
                      }
                    ?>
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
<?php
}
?>