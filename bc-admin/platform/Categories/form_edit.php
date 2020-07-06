<?php
/*
 This id html of form to add a new record into database
*/
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
/**********************************/
$sql_scat = 'select * from '.$table_name.' where id = '.$_POST['id'];
$rs_result_scat = execute($sql_scat);
$rs_scat = mysql_fetch_array($rs_result_scat);
$name = $rs_scat['name'];
$level = $rs_scat['level'];
$active = $rs_scat['status'] ? true : false;
$meta_keywords = $rs_scat['meta_keywords'];
$meta_description = $rs_scat['meta_description'];
/*********************************/ 
?>
<div class="errors_control"></div>
<div id="container_form">
  <form id="add_element_form" method="post">    
     <div class="container-fluid" id="conteiner_form_loader">
        <div class="row-fluid" id="categories_list">
          <select type="text" class="required" name="category_tree" id="categories_select" value="" data-array="12,12,<?php echo $lang_['categories']['FIELD_CHOOSE_LEVEL']; ?>">
            <option value="0">--<?php echo $lang_['categories']['NO_PARENT_CATEGORY']; ?>--</option> 
            <?php
              $mptt = new Zebra_Mptt();
              echo $mptt->get_selectables(0,true,$_POST['id']);			  
            ?>		  
          </select>
        </div>     
        <div class="row-fluid">         
          <input type="text" class="required" name="category" id="category" value="<?php echo $name; ?>" data-array="12,8,<?php echo $lang_['categories']['FIELD_CATEGORY']; ?>" />
          <input type="hidden" name="level" value="<?php echo $level; ?>" />	
          <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" />          
          <div class="span4 text-right">  
           <div class="clearfix" style="margin-top:25px;"></div>       
           <input type="checkbox" data-text-checked="<?php echo $lang_['categories']['FIELD_ACTIVE']; ?>" id="status" data-icon="icon-ok icon-white" name="status" class="bootstyl" data-label-name="<?php echo $lang_['categories']['FIELD_NOT_ACTIVE']; ?>" data-additional-classes="btn-success" value="1" <?php echo $active ? 'checked' : ''; ?> />  
          </div>	        
        </div>  
        <div class="row-fluid">   
          <input type="text" name="meta_description" id="meta_description" maxlength="150" data-array="12,12,<?php echo $lang_['categories']['FIELD_META_DESCRIPTION']; ?>" value="<?php echo $meta_description; ?>" />
        </div>  
        <div class="row-fluid">                              
          <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo $meta_keywords; ?>" data-array="12,12,<?php echo $lang_['categories']['FIELD_META_KEY']; ?>" />
          <i class="icon-info-sign" style="margin-top:-20px;"></i> <i class="icon-" style="margin-top:-20px;width:auto;"><?php echo $lang_['categories']['ALERT_META_KEY']; ?></i>
        </div>                  
     </div> 
  </form>
</div>