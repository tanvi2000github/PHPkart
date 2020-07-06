<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/inc_params.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
if ($rs[0]){
$array_images = $rs['images'] != '' ? unserialize($rs['images']) : '';
$array_attributes = $rs['attributes'] != '' ? unserialize($rs['attributes']) : '';
$array_options = $rs['options'] != '' ? unserialize($rs['options']) : $array_options = array();
if(!empty($array_images)) usort($array_images, build_sorter('principale','desc'));
?>
<form id="add_element_form" method="post">
   <input type="hidden" name="id" id="id" value="<?php echo $rs['id'] ?>" />
   <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>
   <div class="container-fluid" id="conteiner_form_loader">
      <div class="row-fluid">
        <ul class="nav nav-tabs" id="tab_head" style="margin-bottom:0px;">
          <li class="active"><a href="#tab_general" data-toggle="tab"><?php echo $lang_['products']['TABS_GENERAL']; ?></a></li>
          <li><a href="#tab_additional_options" data-toggle="tab"><?php echo $lang_['products']['TABS_ADDITIONAL_OPTIONS']; ?></a></li>
          <li><a href="#tab_images" data-toggle="tab"><?php echo $lang_['products']['TABS_IMAGES']; ?></a></li>
          <li><a href="#tab_seo" data-toggle="tab"><?php echo $lang_['products']['TABS_SEO']; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_general">
             <div class="row-fluid">
              <div class="span6">
                  <div class="row-fluid">
                      <div class="span12">
                      <?php
                         if(plugin_exsists('dgoods')){
                       ?>
                         <input type="hidden" name="is_digital" value="<?php echo ($rs['pl_digital'] ? 1 : 0); ?>" />
                      <?php
                         }
                       ?>
                       <input type="checkbox" id="visible" data-icon="icon-ok icon-white" name="visible" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_VISIBLE']; ?>" data-additional-classes="btn-primary" value="1" <?php echo ($rs['active'] ? 'checked' : ''); ?> />
                       <input type="checkbox" id="showcase" data-icon="icon-ok icon-white" name="showcase" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_SHOWCASE']; ?>" data-additional-classes="btn-primary" value="1" <?php echo ($rs['showcase'] ? 'checked' : ''); ?> />
                       <input type="checkbox" id="by_exposure" data-icon="icon-ok icon-white" name="by_exposure" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_BY_EXPOSURE']; ?>" data-additional-classes="btn-primary" value="1" <?php echo ($rs['by_exposure'] ? 'checked' : ''); ?> />
                      </div>
                  </div>
                  <br/>
         <?php
                   if(plugin_exsists('dgoods')){
                 ?>
                 <input type="hidden" name="old_digital_file" value="<?php echo $rs['pl_digital_code_name']; ?>" />
                  <div class="row-fluid<?php echo !$rs['pl_digital'] ? ' hide' : ''; ?>" id="digital_file_upload_container">
                    <div class="well well-small span12">
                       <label class="text-error" for="digital_download_not_available">
                         <input type="checkbox" name="digital_download_not_available" id="digital_download_not_available" value="1" <?php echo $rs['pl_digital_not_available'] ? 'checked' : ''; ?> /> <?php echo $lang_['pl_dgoods']['FORM_NOT_AVAILABLE_FOR_DOWNLOAD_LABEL']; ?>
                         <i class="icon-info-sign" rel-tooltip="tooltip" title="<?php echo $lang_['pl_dgoods']['TOOLTIP_NOT_AVAILABELE_FOR_DOWNLOAD']; ?>"></i>
                       </label>
                       <br/>
             <?php
                           if($rs['pl_digital']) echo '<div class="text-info">
                '.$lang_['pl_dgoods']['LABEL_UPLOADED_FILE'].' <strong class="alert alert-info">'.$rs['pl_digital_original_name'].'</strong>
               </div><br/>';
                       ?>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="input-append" style="padding:0px;margin:0px;">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
                            <span class="btn btn-file"><span class="fileupload-new"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE']; ?></span>
                            <span class="fileupload-exists"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE']; ?></span>
                            <input type="file" name="digital_good_name" /></span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_DELETE_FILE']; ?></a>
                          </div>
                          <br/>
                          <?php echo ($rs['pl_digital'] ? '<small class="text-info"><strong>'.$lang_['pl_dgoods']['NOTICE_ON_REUPLOAD_FILE'].'</strong></small>' : ''); ?>
                        </div>
                        <br/>
             <?php
                           if($rs['pl_digital'] && file_exists(rel_uploads_path.'/digital_goods/'.$rs['id'].'/demo_'.$rs['pl_digital_code_name'])) echo '<div class="text-info">
                '.$lang_['pl_dgoods']['LABEL_UPLOADED_FILE'].' <strong class="alert alert-info">demo_'.$rs['pl_digital_original_name'].'</strong></div><br/>
               <label for="pl_delete_demo"><input type="checkbox" name="pl_delete_demo" id="pl_delete_demo" value="1" /> '.$lang_['pl_dgoods']['FORM_DELETE_EXISTING_DEMO_FILE_FIELD'].'</label><br/>';
                       ?>
                        <div class="fileupload fileupload-new" data-provides="fileupload" id="digital_demo_file_upload_container">
                          <div class="input-append" style="padding:0px;margin:0px;">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
                            <span class="btn btn-file"><span class="fileupload-new"><?php echo file_exists(rel_uploads_path.'/digital_goods/'.$rs['id'].'/demo_'.$rs['pl_digital_code_name']) ? $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE_DEMO'] : $lang_['pl_dgoods']['FORM_BUTTON_SELECT_FILE_DEMO']; ?></span>
                            <span class="fileupload-exists"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE_DEMO']; ?></span>
                            <input type="file" name="demo_digital_good_name" /></span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_DELETE_FILE_DEMO']; ?></a>
                          </div>
                          <br/>
                          <?php echo ($rs['pl_digital'] ? '<small class="text-info"><strong>'.$lang_['pl_dgoods']['NOTICE_ON_REUPLOAD_FILE'].'</strong></small>' : ''); ?>
                        </div>
                    </div>
                  </div>
         <?php
           }
                 ?>
                  <div class="row-fluid">
                    <input type="text" class="required" name="name" id="name" value="<?php echo $rs['name']; ?>" data-array="12,6,<?php echo $lang_['products']['FIELD_NAME']; ?>" />
                    <input type="text" class="required" name="code" id="code" value="<?php echo $rs['code']; ?>" data-array="12,6,<?php echo $lang_['products']['FIELD_CODE']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="number<?php echo ($rs['unlimited_availability'] ? '' : ' required'); ?>" name="availability" id="availability" value="<?php echo input_num_formatt($rs['availability'],2,true); ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_AVAILABILITY']; ?>" />
                    <div class="span4">
                    <label>&nbsp;</label>
                     <input type="checkbox" id="unlimited_availability" data-icon="icon-ok icon-white" name="unlimited_availability" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_UNLUMITED_AVAILABILITY']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($rs['unlimited_availability'] ? 'checked' : ''); ?> />
                    </div>
                    <input type="text" class="required" name="units" id="units" value="<?php echo $rs['units']; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_UNITS']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <select type="text" class="required" name="category" id="category" data-array="12,8,<?php echo $lang_['products']['FIELD_CATEGORY']; ?>">
                      <option value=""><?php echo $lang_['products']['FIRST_OPTION_ON_CATEGORIES_DROPDOWN']; ?></option>
                      <?php
                        $mptt = new Zebra_Mptt();
                        echo $mptt->get_selectables(0,false,false,$rs['categories']);
                      ?>
                    </select>
                    <div class="span4"><label>&nbsp;</label><span class="btn btn-info" id="add_category"><?php echo $lang_['products']['BUTTON_ADD_CATEGORY']; ?></span></div>
                  </div>
                  <div class="row-fluid">
                    <div class="span6">
                     <label><strong><?php echo $lang_['products']['FIELD_TYPE_PRICE']; ?>: </strong></label>
                      <div class="checkradio-group" data-icon="icon-ok icon-white">
                        <input type="radio" id="with_vat" name="price_type" data-label-name="<?php echo $lang_['products']['FIELD_PRICE_WITH_VAT']; ?>" data-additional-classes="btn-info" value="1" <?php echo $rs['price_with_tax'] ? 'checked' : ''; ?> />
                        <input type="radio" id="without_vat" name="price_type" data-label-name="<?php echo $lang_['products']['FIELD_PRICE_WITHOUT_VAT']; ?>" data-additional-classes="btn-info" value="0" <?php echo !$rs['price_with_tax'] ? 'checked' : ''; ?> />
                      </div>
                   <?php
				     if(plugin_exsists('multitaxes')){
						 echo '<br/><small><strong>'.$lang_['pl_multitax_products_form']['NOTICE_PRICE_TYPE'].'</strong></small><br/><br/>';
					 }
				   ?>
                    </div>
                    <input type="text" class="required number" name="price" id="price" value="<?php echo $rs['price_with_tax'] ? input_num_formatt(((($rs['price']*$rs['tax'])/100)+$rs['price'])) : input_num_formatt($rs['price']); ?>" data-array="12,3,<?php echo $lang_['products']['FIELD_PRICE']; ?>" />
                    <input type="text" class="number" name="offer" id="offer" value="<?php echo $rs['offer'] > 0 ? ($rs['price_with_tax'] ? input_num_formatt(((($rs['offer']*$rs['tax'])/100)+$rs['offer'])) : input_num_formatt($rs['offer'])) : ''; ?>" data-array="12,3,<?php echo $lang_['products']['FIELD_OFFER']; ?>" />
                  </div>

                  <?php
				   if(plugin_exsists('businesstype') && get_admin_business_bc()){
				  ?>
                  <div class="row-fluid well well-small">
                   <div class="row-fluid">
                    <div class="span6">
                    <label>&nbsp;</label>
                      <strong><?php echo $lang_['pl_businesstype']['FORM_RETAILER_PANEL_LABEL']; ?></strong>
                    </div>
                    <input type="text" class="required number" name="rprice" id="rprice" value="<?php echo $rs['rprice'] > 0 ? ($rs['price_with_tax'] ? input_num_formatt(((($rs['rprice']*$rs['tax'])/100)+$rs['rprice'])) : input_num_formatt($rs['rprice'])) : ''; ?>" data-array="12,3,<?php echo $lang_['products']['FIELD_PRICE']; ?>" />
                    <input type="text" class="number" name="roffer" id="roffer" value="<?php echo $rs['roffer'] > 0 ? ($rs['price_with_tax'] ? input_num_formatt(((($rs['roffer']*$rs['tax'])/100)+$rs['roffer'])) : input_num_formatt($rs['roffer'])) : ''; ?>" data-array="12,3,<?php echo $lang_['products']['FIELD_OFFER']; ?>" />
                   </div>
                   <div class="row-fluid">
                    <div class="span6"></div>
                     <input type="text" class="required number" name="rpdiscount" id="rpdiscount" value="<?php echo $rs['rprice'] > 0 ? input_num_formatt($rs['rpdiscount'],2,true) : ''; ?>" data-array="12,3,discount (%)" />
                     <input type="text" class="number" name="rodiscount" id="rodiscount" value="<?php echo $rs['roffer'] > 0 ? input_num_formatt($rs['rodiscount'],2,true) : ''; ?>" data-array="12,3,discount (%)" />
                    </div>
                  </div>
                  <?php
				   }
				  ?>

                  <div class="row-fluid">
                    <input type="text" class="number" name="tax" id="tax" value="<?php echo input_num_formatt($rs['tax'],2,true); ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_TAX']; ?>" />
                  </div>
                   <?php
				     if(plugin_exsists('multitaxes')){
				      $sql_pl_multitax = execute('select * from '.$table_prefix.'taxes');
					   if(mysql_num_rows($sql_pl_multitax) > 0){
				   ?>
                        <div class="row-fluid">
                          <div class="span12">
                           <label><strong><?php echo $lang_['pl_multitax_products_form']['LABEL_MULTITAXES']; ?></strong></label>
                         <?php
						   $arr_product_taxes = $rs['pl_multitax'] != '' ? explode(',',$rs['pl_multitax']) : array();
                            while($rs_pl_multitax = mysql_fetch_array($sql_pl_multitax)){
								$tax_checked = !empty($arr_product_taxes) && in_array($rs_pl_multitax['id'],$arr_product_taxes) ? ' checked' : '';
                         ?>
                            <input type="checkbox" data-icon="icon-ok icon-white" name="multitax[]" id="tax_name_<?php echo $rs_pl_multitax['id']; ?>" class="bootstyl" data-label-name="<?php echo $rs_pl_multitax['name'].' ('.num_formatt($rs_pl_multitax['percentage'],2,true).' %)'; ?>" data-additional-classes="btn-info" value="<?php echo $rs_pl_multitax['id']; ?>" <?php echo $tax_checked; ?> />
                         <?php
                            }
                         ?>
                          </div>
                        </div>
                   <?php
					   }
					 }
				   ?>
              </div>
              <div class="span6">
                  <textarea name="description" id="description" class="required hidden" rows="15" data-array="12,12,<?php echo $lang_['products']['FIELD_DESCRIPTION']; ?>"><?php echo $rs['description']; ?></textarea>
              </div>
            </div>
          </div>
          <!--- --------------->
          <div class="tab-pane" id="tab_additional_options">
                  <div class="row-fluid">
                    <div class="span6" style="padding:20px 20px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;">
                    <div class="label label-info"><h4><?php echo $lang_['products']['ATTRIBUTE_CONTAINER_TITLE']; ?></h4></div><br/><br/>
                      <div id="general-attributes-container">
						<?php
                          if($array_attributes != '' && count($array_attributes) >= 1){
                          $count_attributes = 1;
                          foreach($array_attributes as $key => $val){
                        ?>
                          <div class="attributes-container">
                           <div class="row-fluid">
                             <div class="span10">
                              <input type="text" class="required-add" alt="nattribute" name="nattribute_<?php echo $count_attributes; ?>" id="nattribute_<?php echo $count_attributes; ?>" value="<?php echo $val['attribute_name']; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_NAME']; ?>" />
                              <input type="text" class="required-add" alt="vattribute" name="vattribute_<?php echo $count_attributes; ?>" id="vattribute_<?php echo $count_attributes; ?>" value="<?php echo $val['attribute_value']; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_VALUE']; ?>" />
                              <span class="span4"><label>&nbsp;</label><input type="checkbox" id="asfilter_<?php echo $count_attributes; ?>" data-icon="icon-ok icon-white" name="asfilter_<?php echo $count_attributes; ?>" alt="asfilter" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_ATTRIBUTE_AS_FILTER']; ?>" data-additional-classes="btn-info" value="1" <?php echo (isset($val['asfilter']) && $val['asfilter']) ? 'checked' : ''; ?> /></span>
                             </div>
                             <div class="span2"><i class="icon32 icon-gray icon-trash delAttributes" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
                           </div>
                          </div>
                        <?php
                            $count_attributes++;
                          }
                          }else{
                        ?>
                              <div class="attributes-container">
                               <div class="row-fluid">
                                 <div class="span10">
                                  <input type="text" class="required-add" alt="nattribute" name="nattribute_1" id="nattribute_1" value="" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_NAME']; ?>" />
                                  <input type="text" class="required-add" alt="vattribute" name="vattribute_1" id="vattribute_1" value="" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_VALUE']; ?>" />
                                  <span class="span4"><label>&nbsp;</label><input type="checkbox" id="asfilter_1" data-icon="icon-ok icon-white" name="asfilter_1" alt="asfilter" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_ATTRIBUTE_AS_FILTER']; ?>" data-additional-classes="btn-info" value="" /></span>
                                 </div>
                                 <div class="span2"><i class="icon32 icon-gray icon-trash delAttributes" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
                               </div>
                              </div>
                        <?php
                          }
                        ?>
                      </div>
                      <span class="btn btn-success" id="addAttributes"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_FILTER']; ?></span>
                    </div>

                    <div class="span6" style="padding:20px 20px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;">
                    <div class="label label-info"><h4><?php echo $lang_['products']['ADDITIONAL_OPTIONS_TITLE']; ?></h4></div><br/><br/>
                      <div id="general-options-container">
                      <?php
					   if(!empty($array_options)){
						 $count_options = 0;
						foreach($array_options as $key => $val){
						 $option_value_count = 0;
					  ?>
                        <div class="options-container well" style="background-color:#dedede;border-bottom:2px solid #ccc;margin-bottom:10px;">
                         <input type="hidden" name="noption[<?php echo $count_options; ?>][name_code]" value="<?php echo $key; ?>" />
                         <div class="row-fluid">
                           <div class="span10">
                            <input type="text" class="option_name" data-option-number="<?php echo $count_options; ?>" name="noption[<?php echo $count_options; ?>][name]" value="<?php echo $val['name']; ?>" data-array="12,6,<?php echo $lang_['products']['FIELD_OPTION_NAME']; ?>" />
                             <div class="span6">
                             <label>&nbsp;</label> <input type="checkbox" id="required_option_<?php echo $count_options; ?>" data-icon="icon-ok icon-white" name="noption[<?php echo $count_options; ?>][required_option]" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_MANDATORY_SELECTION']; ?>" data-additional-classes="btn-info" value="1" <?php echo $val['required_option'] ? 'checked' : ''; ?> />
                             </div>
                           </div>
                           <div class="span2"><i class="icon32 icon-gray icon-trash delOption" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
                         </div>
                         <div class="row-fluid">
                          <div class="span10 offset1 container-option-value">
                           <?php
						     foreach($val['voption'] as $key => $val){
						   ?>
                               <div class="row-fluid subcontainer-option-value well well-small">
                               <input type="hidden" name="noption[<?php echo $count_options; ?>][voption][<?php echo $option_value_count; ?>][value_code]" value="<?php echo $key; ?>" />
	                              <input type="text" class="option_value required" data-option-number="<?php echo $option_value_count; ?>" name="noption[<?php echo $count_options; ?>][voption][<?php echo $option_value_count; ?>][value]" value="<?php echo $val['value']; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_OPTION_VALUE_DESCRIPTION']; ?>" />
				                  <input type="text" class="number required" name="noption[<?php echo $count_options; ?>][voption][<?php echo $option_value_count; ?>][price]" value="<?php echo $val['price']; ?>" data-array="12,2,<?php echo $lang_['products']['FIELD_OPTION_VALUE_PRICE']; ?>" />
                                  <select class="required" name="noption[<?php echo $count_options; ?>][voption][<?php echo $option_value_count; ?>][type]" value="" data-array="12,2,<?php echo $lang_['products']['FIELD_OPTION_VALUE_TYPE']; ?>">
                                    <option value="+" <?php echo $val['type'] == '+' ? 'selected' : ''; ?>>+</option>
                                    <option value="-" <?php echo $val['type'] == '-' ? 'selected' : ''; ?>>-</option>
                                  </select>
				                  <div class="span2"><i class="icon32 icon-gray icon-trash delOptionValue" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
				               </div>
                           <?php
						       $option_value_count++;
							 }
						   ?>
                          </div>
                            <span class="btn btn-success add-value pull-right"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_OPTION_VALUES']; ?></span>
                            <div class="clearfix"></div>
                         </div>
                        </div>
                      <?php
					     $count_options++;
						}
					   }
					  ?>
                      </div>
                      <span class="btn btn-success" id="addOption"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_OPTION']; ?></span>
                    </div>
                  </div>
          </div>
          <!--- --------------->
          <div class="tab-pane" id="tab_images">
              <div class="row-fluid">
               <strong class="alert alert-info span12 text-center"><?php echo $lang_['products']['ALERT_FIRST_IMAGE']; ?></strong>
              </div>
              <div class="row-fluid">
               <div class="span12" id="contaienr_upl" style="position:relative;">
               <!-- -->
                <?php
				 $counter_img = 1;
				 if(!empty($array_images)){
				   foreach($array_images as $key => $val){
				?>
                     <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">
                     <input type="hidden" alt="imgpersist" name="imgpersist_<?php echo $counter_img; ?>" id="imgpersist_<?php echo $counter_img; ?>" value="<?php echo $val['urlimg']; ?>" />
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="fileupload-new thumbnail" style="width:96%;height:250px;overflow:hidden"><img src="<?php echo path_abs_img_products.'/'.$rs['id'].'/600x600/'.$val['urlimg']; ?>"/></div>
                          <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;height:250px;overflow:hidden"></div>
                          <div style="height:60px;">
                            <span class="btn btn-block btn-file"><span class="fileupload-new add_file_new"><i class="icon-picture"></i> <?php echo $lang_['products']['SELECT_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists edit_file_new"><i class="icon-refresh"></i> <?php echo $lang_['products']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" name="upimg_<?php echo $counter_img; ?>" id="upimg_<?php echo $counter_img; ?>" alt="upimg" /></span>
                            <!--<a href="#" class="btn btn-block fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i> Rimuovi Immagine</a>-->
                            <div class="btn btn-danger btn-block deleteupl"><i class="icon-remove icon-white"></i> <?php echo $lang_['products']['REMOVE_IMAGE_TEXT']; ?></div>
                          </div>
                        </div>
                     </div>
                <?php
				    $counter_img++;
				   }
				 }else{
				?>
                     <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">
                        <input type="hidden" alt="imgpersist" name="imgpersist_<?php echo $counter_img; ?>" id="imgpersist_<?php echo $counter_img; ?>" value="" />
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="fileupload-new thumbnail" style="width:96%;height:250px;overflow:hidden"><img src="<?php echo path_img_back; ?>/img_not_found.jpg"/></div>
                          <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;height:250px;overflow:hidden"></div>
                          <div style="height:60px;">
                            <span class="btn btn-block btn-file"><span class="fileupload-new"><i class="icon-picture"></i> <?php echo $lang_['products']['SELECT_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists"><i class="icon-refresh"></i> <?php echo $lang_['products']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" name="upimg_<?php echo $counter_img; ?>" id="upimg_<?php echo $counter_img; ?>" alt="upimg" /></span>
                            <!--<a href="#" class="btn btn-block fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i> Rimuovi Immagine</a>-->
                            <div class="btn btn-danger btn-block deleteupl"><i class="icon-remove icon-white"></i> <?php echo $lang_['products']['REMOVE_IMAGE_TEXT']; ?></div>
                          </div>
                        </div>
                     </div>
                <?php
				 }
				?>
                 <!-- -->
               </div>
              </div>
              <div class="clearfix"></div>
              <div class="row-fluid" style="margin-top:10px;margin-bottom:10px;">
                <div class="span12">
                   <span class="btn btn-success" id="addUpl"><i class="icon-plus icon-white"></i> <?php echo $lang_['products']['BUTTON_ADD_IMAGE']; ?></span>
                </div>
              </div>
          </div>
          <!--- --------------->
          <div class="tab-pane" id="tab_seo">
                  <div class="row-fluid">
                    <input type="text" name="meta_title" id="meta_title" value="<?php echo str_replace(' ','',$rs['meta_title']) != '' ? $rs['meta_title'] : '' ; ?>" data-array="12,6,<?php echo $lang_['products']['FIELD_PAGE_TITLE']; ?>" />
                    <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo str_replace(' ','',$rs['meta_keywords']) != '' ? $rs['meta_keywords'] : '' ; ?>" data-array="12,6,<?php echo $lang_['products']['FIELD_META_KEY']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <div class="span6">
                      <i class="icon-info-sign"></i> <?php echo $lang_['products']['ALERT_PAGE_TITLE']; ?>
                    </div>
                    <div class="span6">
                      <i class="icon-info-sign"></i> <?php echo $lang_['products']['ALERT_META_KEY']; ?>
                    </div>
                  </div>
                  <div class="row-fluid">
                    <input type="text" name="meta_description" id="meta_description" maxlength="150" data-array="12,12,<?php echo $lang_['products']['FIELD_META_DESCRIPTION']; ?>" value="<?php echo str_replace(' ','',$rs['meta_description']) != '' ? $rs['meta_description'] : '' ; ?>" />
                  </div>
                  <div class="row-fluid">
                    <div class="span12">
                      <i class="icon-info-sign"></i> <?php echo $lang_['products']['ALERT_META_DESCRIPTION']; ?>
                    </div>
                  </div>
          </div>
        </div>
      </div>
      <br/><br/>
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span>
   </div>
</form>
<?php
}
?>