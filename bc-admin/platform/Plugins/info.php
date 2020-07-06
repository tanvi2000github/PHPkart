<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
 $rs_result = execute($sql);
 $rs = mysql_fetch_array($rs_result);

 echo '<span id="label">'.$rs['name'].'</span>';
 echo '<div id="body">
<table class="table table-striped table-bordered">
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_NAME'].'</td>
		<td align="left" valign="top">'.$rs['name'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_SHORTNAME'].'</td>
		<td align="left" valign="top">'.$rs['shortname'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_VERSION'].'</td>
		<td align="left" valign="top">'.$rs['version'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_DEPENDENCE'].'</td>
		<td align="left" valign="top">'.$rs['dependence'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_BC_VERSION_REQUIRED'].' BootCommerce</td>
		<td align="left" valign="top">'.$rs['min_bc_version_required'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">'.$lang_['plugins']['INFO_DESCRIPTION'].'</td>
		<td align="left" valign="top">'.$rs['description'].'</td>
	</tr>	
</table>';
if($rs['system']){
  echo '<br/><br/>
  <span class="alert alert-info alert-block">You cannot delete this plugin because it\'s a plugin of system</span>';
}
echo '</div>';
?>