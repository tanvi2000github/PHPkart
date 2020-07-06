<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
$sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
$rs_result = execute($sql);
$rs = mysql_fetch_array($rs_result);
if ($rs[0]){
?>
<form id="add_element_form" method="post">
  <input type="hidden" name="id" id="id" value="<?php echo $rs['id'] ?>" />
     <?php require_once(rel_admin_path.'/include/legend_form.php'); ?>
   <div class="container-fluid" id="conteiner_form_loader">
      <div class="row-fluid">  
        <input type="text" class="required" name="name" id="name" value="<?php echo $rs['name'] ?>" data-array="12,6,<?php echo $lang_['pl_multitax']['FIELD_NAME']; ?>" />
        <input type="text" class="number required" name="percentage" id="percentage" value="<?php echo input_num_formatt($rs['percentage'],2,true) ?>" data-array="12,6,<?php echo $lang_['pl_multitax']['FIELD_PERCENTAGE']; ?>" />
      </div>   
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span>   
   </div> 
</form>
<?php
}
?>