<?php
/*
 This id html of form to add a new record into database
*/
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/inc_params.php');
require_once('general_tags.php');
?>
<form id="add_element_form" method="post">	
   <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>
   <div class="container-fluid" id="conteiner_form_loader">
              <div class="row-fluid"> 
                <div class="span3">
                 <input type="checkbox" id="active" name="active" data-icon="icon-ok icon-white" class="bootstyl" data-label-name="<?php echo $lang_['pl_slideshow']['FIELD_ACTIVE']; ?>" data-additional-classes="btn-primary btn-block" value="1" checked="checked" />              
                </div>                 
              </div>
              <div class="row-fluid">  
                <input type="text" class="required" name="name" id="name" value="" data-array="12,6,<?php echo $lang_['pl_slideshow']['FIELD_NAME']; ?>" />               
              </div>    
              <div class="row-fluid">             
               <div class="span12" id="contaienr_upl" style="position:relative;">       
                 <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">    
                    <div class="fileupload fileupload-new" data-provides="fileupload">                     
                      <div class="fileupload-new thumbnail" style="width:96%;height:250px;overflow:hidden"><img src="<?php echo path_img_back; ?>/img_not_found.jpg"/></div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;height:250px;overflow:hidden"></div>
                      <div style="height:60px;">
                        <span class="btn btn-block btn-file"><span class="fileupload-new"><i class="icon-picture"></i> <?php echo $lang_['pl_slideshow']['SELECT_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists"><i class="icon-refresh"></i> <?php echo $lang_['pl_slideshow']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" alt="upimg" /></span>
                        <div class="btn btn-danger btn-block deleteupl"><i class="icon-remove icon-white"></i> <?php echo $lang_['pl_slideshow']['REMOVE_IMAGE_TEXT']; ?></div>  
                        <input type="text" alt="position" class="number required" value="1" data-array="12,6,<?php echo $lang_['pl_slideshow']['FIELD_POSITION']; ?>" /> 
                        <div class="span5" style="margin-top:5px;">
                         <input type="checkbox" alt="visible" name="visible" data-icon="icon-ok icon-white" class="bootstyl" data-label-name="<?php echo $lang_['pl_slideshow']['FIELD_VISIBLE']; ?>" data-additional-classes="btn-primary btn-block" value="1" checked="checked" />              
                        </div>
                      </div>              
                    </div>           
                 </div>          
               </div>
              </div>
              <div class="clearfix"></div>
              <div class="row-fluid" style="margin-top:10px;margin-bottom:10px;"> 
                <div class="span12">
                   <span class="btn btn-success" id="addUpl"><i class="icon-plus icon-white"></i> <?php echo $lang_['pl_slideshow']['BUTTON_ADD_IMAGE']; ?></span>
                </div>
              </div>  
      <br/><br/>
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span> 
   </div> 
</form>