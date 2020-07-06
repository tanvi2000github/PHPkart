<?php
/*
 This id html of form to add a new record into database
*/
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/inc_params.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
                $mptt = new Zebra_Mptt();
                $options_categories_select = $mptt->get_selectables(0,false);
?>
<form id="add_element_form" method="post">
   <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>
   <div class="container-fluid" id="conteiner_form_loader">
      <div class="row-fluid">
        <ul class="nav nav-tabs" id="tab_head" style="margin-bottom:0px;">
          <li class="active"><a href="#tab_general" data-toggle="tab"><?php echo $lang_['products']['TABS_GENERAL']; ?></a></li>
          <li><a href="#tab_additional_options" data-toggle="tab"><?php echo $lang_['products']['TABS_ADDITIONAL_OPTIONS']; ?></a></li>
          <li><a href="#tab_images" data-toggle="tab"><?php echo $lang_['products']['TABS_IMAGES']; ?></a></li>
          <li><a href="#tab_seo" data-toggle="tab"><?php echo $lang_['products']['TABS_SEO']; ?></a></li>
          <li class="pull-right">
          <div class="pull-right">
            <label for="product_model"><?php echo $lang_['products']['FIELD_CHOOSE_MODEL']; ?></label>
             <select name="product_model" id="product_model">
                <option value=""></option>
                <?php
				  $sql_p_model = execute('select '.$table_prefix.'products.*,'.$table_prefix.'categories.name as cat_name
				   from '.$table_prefix.'products join '.$table_prefix.'categories on '.$table_prefix.'products.categories = '.$table_prefix.'categories.id group by '.$table_prefix.'products.name,'.$table_prefix.'products.categories order by '.$table_prefix.'products.name desc');
				  while($rs_p_model =  mysql_fetch_array($sql_p_model)){
					 echo '<option value="'.$rs_p_model['id'].'">'.$rs_p_model['name'].($rs_p_model['categories'] != '' ? ' ('.$rs_p_model['cat_name'].')': '').'</option>';
				  }
				?>
             </select>
          </div>
          <div class="pull-right" style="margin-right:15px;">
            <label for="category_filter"><?php echo $lang_['products']['FIELD_CHOOSE_MODEL'].' '.$lang_['products']['FIELD_CATEGORY']; ?></label>
            <select type="text" name="category_filter" id="category_filter">
              <option value=""></option>
              <?php echo $options_categories_select ?>
            </select>
          </div>
          </li>
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
                         <input type="checkbox" id="digital" data-icon="icon-ok icon-white" name="digital" class="bootstyl" data-label-name="<?php echo $lang_['pl_dgoods']['FORM_BUTTON_DIGITAL_PRODUCT']; ?>" data-additional-classes="btn-primary" value="1" />
                        <?php
                         }
                       ?>
                       <input type="checkbox" id="visible" data-icon="icon-ok icon-white" name="visible" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_VISIBLE']; ?>" data-additional-classes="btn-primary" value="1" checked="checked" />
                       <input type="checkbox" id="showcase" data-icon="icon-ok icon-white" name="showcase" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_SHOWCASE']; ?>" data-additional-classes="btn-primary" value="1" />
                       <input type="checkbox" id="by_exposure" data-icon="icon-ok icon-white" name="by_exposure" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_BY_EXPOSURE']; ?>" data-additional-classes="btn-primary" value="1" />
                      </div>
                  </div>
                  <br/>
         <?php
                   if(plugin_exsists('dgoods')){
                 ?>
                  <div class="row-fluid hide" id="digital_file_upload_container">
                    <div class="well well-small span12">
                       <label class="text-error" for="digital_download_not_available">
                         <input type="checkbox" name="digital_download_not_available" id="digital_download_not_available" value="1" /> <?php echo $lang_['pl_dgoods']['FORM_NOT_AVAILABLE_FOR_DOWNLOAD_LABEL']; ?>
                         <i class="icon-info-sign" rel-tooltip="tooltip" title="<?php echo $lang_['pl_dgoods']['TOOLTIP_NOT_AVAILABELE_FOR_DOWNLOAD']; ?>"></i>
                       </label>
                       <br/>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
                            <span class="btn btn-file"><span class="fileupload-new"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_SELECT_FILE']; ?></span>
                            <span class="fileupload-exists"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE']; ?></span>
                            <input type="file" name="digital_good_name" /></span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_DELETE_FILE']; ?></a>
                          </div>
                        </div>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                          <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
                            <span class="btn btn-file"><span class="fileupload-new"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_SELECT_FILE_DEMO']; ?></span>
                            <span class="fileupload-exists"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_CHANGE_FILE_DEMO']; ?></span>
                            <input type="file" name="demo_digital_good_name" /></span>
                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo $lang_['pl_dgoods']['FORM_BUTTON_DELETE_FILE_DEMO']; ?></a>
                          </div>
                        </div>
                    </div>
                  </div>
         <?php
           }
                 ?>
                  <div class="row-fluid">
                    <input type="text" class="required" name="name" id="name" value="" data-array="12,6,<?php echo $lang_['products']['FIELD_NAME']; ?>" />
                    <input type="text" class="required" name="code" id="code" value="" data-array="12,6,<?php echo $lang_['products']['FIELD_CODE']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required number" name="availability" id="availability" value="" data-array="12,4,<?php echo $lang_['products']['FIELD_AVAILABILITY']; ?>" />
                    <div class="span4">
                    <label>&nbsp;</label>
                     <input type="checkbox" id="unlimited_availability" data-icon="icon-ok icon-white" name="unlimited_availability" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_UNLUMITED_AVAILABILITY']; ?>" data-additional-classes="btn-info" value="1" />
                    </div>
                    <input type="text" class="required" name="units" id="units" value="<?php echo $units; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_UNITS']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <select type="text" class="required" name="category" id="category" data-array="12,8,<?php echo $lang_['products']['FIELD_CATEGORY']; ?>">
                      <option value=""><?php echo $lang_['products']['FIRST_OPTION_ON_CATEGORIES_DROPDOWN']; ?></option>
                      <?php echo $options_categories_select ?>
                    </select>
                    <div class="span4"><label>&nbsp;</label><span class="btn btn-info" id="add_category"><?php echo $lang_['products']['BUTTON_ADD_CATEGORY']; ?></span></div>
                  </div>
                  <div class="row-fluid">
                    <div class="span6">
                     <label><strong><?php echo $lang_['products']['FIELD_TYPE_PRICE']; ?>: </strong></label>
                      <div class="checkradio-group" data-icon="icon-ok icon-white">
                        <input type="radio" id="with_vat" name="price_type" data-label-name="<?php echo $lang_['products']['FIELD_PRICE_WITH_VAT']; ?>" data-additional-classes="btn-info" value="1" />
                        <input type="radio" id="without_vat" name="price_type" data-label-name="<?php echo $lang_['products']['FIELD_PRICE_WITHOUT_VAT']; ?>" data-additional-classes="btn-info" value="0" checked />
                      </div>
                   <?php
				     if(plugin_exsists('multitaxes')){
						 echo '<br/><small><strong>'.$lang_['pl_multitax_products_form']['NOTICE_PRICE_TYPE'].'</strong></small><br/><br/>';
					 }
				   ?>
                    </div>
                    <input type="text" class="required number" name="price" id="price" value="" data-array="12,3,<?php echo $lang_['products']['FIELD_PRICE']; ?>" />
                    <input type="text" class="number" name="offer" id="offer" value="" data-array="12,3,<?php echo $lang_['products']['FIELD_OFFER']; ?>" />
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
                    <input type="text" class="required number" name="rprice" id="rprice" value="" data-array="12,3,<?php echo $lang_['products']['FIELD_PRICE']; ?>" />
                    <input type="text" class="number" name="roffer" id="roffer" value="" data-array="12,3,<?php echo $lang_['products']['FIELD_OFFER']; ?>" />
                   </div>
                   <div class="row-fluid">
                    <div class="span6"></div>
                     <input type="text" class="required number" name="rpdiscount" id="rpdiscount" value="" data-array="12,3,discount (%)" />
                     <input type="text" class="number" name="rodiscount" id="rodiscount" value="" data-array="12,3,discount (%)" />
                    </div>
                  </div>
                  <?php
				   }
				  ?>


                  <div class="row-fluid">
                    <input type="text" class="number" name="tax" id="tax" value="<?php echo $tax_percentage; ?>" data-array="12,4,<?php echo $lang_['products']['FIELD_TAX']; ?>" />
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
                            while($rs_pl_multitax = mysql_fetch_array($sql_pl_multitax)){
                         ?>
                            <input type="checkbox" data-icon="icon-ok icon-white" name="multitax[]" id="tax_name_<?php echo $rs_pl_multitax['id']; ?>" class="bootstyl" data-label-name="<?php echo $rs_pl_multitax['name'].' ('.num_formatt($rs_pl_multitax['percentage'],2,true).' %)'; ?>" data-additional-classes="btn-info" value="<?php echo $rs_pl_multitax['id']; ?>" />
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
                  <textarea name="description" id="description" class="required hidden" rows="15" data-array="12,12,<?php echo $lang_['products']['FIELD_DESCRIPTION']; ?>"></textarea>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab_additional_options">
                  <div class="row-fluid">
                    <div class="span6" style="padding:20px 20px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;">
                    <div class="label label-info"><h4><?php echo $lang_['products']['ATTRIBUTE_CONTAINER_TITLE']; ?></h4></div><br/><br/>
                      <div id="general-attributes-container">
                        <div class="attributes-container">
                         <div class="row-fluid">
                           <div class="span10">
                            <input type="text" class="required-add" alt="nattribute" value="" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_NAME']; ?>" />
                            <input type="text" class="required-add" alt="vattribute" value="" data-array="12,4,<?php echo $lang_['products']['FIELD_ATTRIBUTE_VALUE']; ?>" />
                            <span class="span4"><label>&nbsp;</label><input type="checkbox" data-icon="icon-ok icon-white" alt="asfilter" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_ATTRIBUTE_AS_FILTER']; ?>" data-additional-classes="btn-info" value="1" /></span>
                           </div>
                           <div class="span2"><i class="icon32 icon-gray icon-trash delAttributes" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
                         </div>
                        </div>
                      </div>
                      <span class="btn btn-success" id="addAttributes"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_FILTER']; ?></span>
                    </div>

                    <div class="span6" style="padding:20px 20px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;">
                    <div class="label label-info"><h4><?php echo $lang_['products']['ADDITIONAL_OPTIONS_TITLE']; ?></h4></div><br/><br/>
                      <div id="general-options-container">

                        <div class="options-container well" style="background-color:#dedede;border-bottom:2px solid #ccc;margin-bottom:10px;">
                         <div class="row-fluid">
                           <div class="span10">
                            <input type="text" class="option_name" data-option-number="0" name="noption[0][name]" value="" data-array="12,6,<?php echo $lang_['products']['FIELD_OPTION_NAME']; ?>" />
                             <div class="span6">
                             <label>&nbsp;</label> <input type="checkbox" id="required_option_0" data-icon="icon-ok icon-white" name="noption[0][required_option]" class="bootstyl" data-label-name="<?php echo $lang_['products']['FIELD_MANDATORY_SELECTION']; ?>" data-additional-classes="btn-info" value="1" />
                             </div>
                           </div>
                           <div class="span2"><i class="icon32 icon-gray icon-trash delOption" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass('icon-gray icon-color');" onmouseout="$(this).toggleClass('icon-gray icon-color');"></i></div>
                         </div>
                         <div class="row-fluid">
                          <div class="span10 offset1 container-option-value"></div>
                            <span class="btn btn-success add-value pull-right"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_OPTION_VALUES']; ?></span>
                            <div class="clearfix"></div>
                         </div>
                        </div>
                      </div>
                      <span class="btn btn-success" id="addOption"><i class="icon-white icon-plus"></i> <?php echo $lang_['products']['BUTTON_ADD_OPTION']; ?></span>
                    </div>
                  </div>
          </div>
          <div class="tab-pane" id="tab_images">
              <div class="row-fluid">
               <strong class="alert alert-info span12 text-center"><?php echo $lang_['products']['ALERT_FIRST_IMAGE']; ?></strong>
              </div>
              <div class="row-fluid">
               <div class="span12" id="contaienr_upl" style="position:relative;">
                 <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                      <div class="fileupload-new thumbnail" style="width:96%;height:250px;overflow:hidden"><img src="<?php echo path_img_back; ?>/img_not_found.jpg"/></div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;height:250px;overflow:hidden"></div>
                      <div style="height:60px;">
                        <span class="btn btn-block btn-file"><span class="fileupload-new"><i class="icon-picture"></i> <?php echo $lang_['products']['SELECT_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists"><i class="icon-refresh"></i> <?php echo $lang_['products']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" alt="upimg" /></span>
                        <!--<a href="#" class="btn btn-block fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i> Rimuovi Immagine</a>-->
                        <div class="btn btn-danger btn-block deleteupl"><i class="icon-remove icon-white"></i> <?php echo $lang_['products']['REMOVE_IMAGE_TEXT']; ?></div>
                      </div>
                    </div>
                 </div>
               </div>
              </div>
              <div class="clearfix"></div>
              <div class="row-fluid" style="margin-top:10px;margin-bottom:10px;">
                <div class="span12">
                   <span class="btn btn-success" id="addUpl"><i class="icon-plus icon-white"></i> <?php echo $lang_['products']['BUTTON_ADD_IMAGE']; ?></span>
                </div>
              </div>
          </div>
          <div class="tab-pane" id="tab_seo">
                  <div class="row-fluid">
                    <input type="text" name="meta_title" id="meta_title" value="" data-array="12,6,<?php echo $lang_['products']['FIELD_PAGE_TITLE']; ?>" />
                    <input type="text" name="meta_keywords" id="meta_keywords" value="" data-array="12,6,<?php echo $lang_['products']['FIELD_META_KEY']; ?>" />
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
                    <input type="text" name="meta_description" id="meta_description" maxlength="150" data-array="12,12,<?php echo $lang_['products']['FIELD_META_DESCRIPTION']; ?>" value="" />
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