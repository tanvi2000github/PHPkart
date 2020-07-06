<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
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
                <input type="text" name="name_r" id="name_r" value="" data-array="12,12,<?php echo $lang_['products']['FIELD_NAME']; ?>/<?php echo $lang_['products']['FIELD_CODE']; ?>" />
              </div>
              <div class="row-fluid">
                    <select type="text" name="category_r" id="category_r" data-array="12,12,<?php echo $lang_['products']['FIELD_CATEGORY']; ?>">
                      <option value=""><?php echo $lang_['products']['FIRST_OPTION_ON_CATEGORIES_DROPDOWN']; ?></option>
                      <?php
                        $mptt = new Zebra_Mptt();
                        echo $mptt->get_selectables(0,false);
                      ?>
                    </select>
              </div>
              <div class="row-fluid">
                <input type="checkbox" id="uncategorized_r" data-icon="icon-ok icon-white" name="uncategorized_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_UNCATECORIZED']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="offer_r" data-icon="icon-ok icon-white" name="offer_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_OFFER']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
              <br/>
              <div class="row-fluid">
                <input type="checkbox" id="visible_r" data-icon="icon-ok icon-white" name="visible_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_VISIBLE']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="notvisible_r" data-icon="icon-ok icon-white" name="notvisible_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_NOT_VISIBLE']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
              <br/>
              <div class="row-fluid">
                <input type="checkbox" id="showcase_r" data-icon="icon-ok icon-white" name="showcase_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_SHOWCASE']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="notshowcase_r" data-icon="icon-ok icon-white" name="notshowcase_r" class="bootstyl" data-label-name="<?php echo $lang_['products']['SEARCH_LABEL_NOT_SHOWCASE']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
             <?php
         if(plugin_exsists('dgoods')){
       ?>
              <br/>
              <div class="row-fluid">
                <input type="checkbox" id="digital_good_r" data-icon="icon-ok icon-white" name="digital_good_r" class="bootstyl" data-label-name="<?php echo $lang_['pl_dgoods']['SEARCH_LABEL_DIGITAL_GOOD']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="not_digital_good_r" data-icon="icon-ok icon-white" name="not_digital_good_r" class="bootstyl" data-label-name="<?php echo $lang_['pl_dgoods']['SEARCH_LABEL_NOT_DIGITAL_GOOD']; ?>" data-additional-classes="btn-info" value="1" />
              </div>

              <br/><br/>
             <?php
         }
       ?>
              <div calss="row-fluid">
                  <span class="btn btn-small a_search"><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_SEARCH'] ?></span>
                  <span class="btn btn-small btn-primary a_reset"><i class="icon-white icon-refresh"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_RESET'] ?></span>
                  <!-- <span class="btn btn-small btn-danger a_close"><i class="icon-white icon-remove"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_CLOSE'] ?></span> -->
              </div>
            </form>
       </div>
     </div>
   </div>