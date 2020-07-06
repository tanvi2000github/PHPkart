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
      <div class="row-fluid">  
        <input type="text" class="required" name="name" id="name" value="<?php echo $rs['name'] ?>" data-array="12,6,<?php echo $lang_['admin_accounts']['FIELD_NAME']; ?>" />
        <input type="text" class="required" name="userid" id="userid" value="<?php echo $rs['userid'] ?>" data-array="12,6,<?php echo $lang_['admin_accounts']['FIELD_USERID']; ?>" />
      </div>   
      <div class="row-fluid"> 
        <input type="password" class="required" name="password" id="password" value="<?php echo $rs['password'] ?>" data-array="12,6,<?php echo $lang_['admin_accounts']['FIELD_PASSWORD']; ?>" />
        <input type="password" class="required" name="password2" id="password2" equalTo="#password" value="<?php echo $rs['password'] ?>" data-array="12,6,<?php echo $lang_['admin_accounts']['FIELD_REPEAT_PASSWORD']; ?>" />                           
      </div>  
      <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span>   
   </div> 
</form>
<?php
}
?>