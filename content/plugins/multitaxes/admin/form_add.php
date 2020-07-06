<?php
/*
 This id html of form to add a new record into database
*/
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
?>
<form id="add_element_form" method="post">	
     <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>

   <div class="container-fluid" id="conteiner_form_loader">
      <div class="row-fluid">  
        <input type="text" class="required" name="name" id="name" value="" data-array="12,6,<?php echo $lang_['pl_multitax']['FIELD_NAME']; ?>" />
        <input type="text" class="number required" name="percentage" id="percentage" value="" data-array="12,6,<?php echo $lang_['pl_multitax']['FIELD_PERCENTAGE']; ?>" />
      </div>    
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span> 
   </div> 
</form>
