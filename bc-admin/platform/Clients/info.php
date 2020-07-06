<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
 $rs_result = execute($sql);
 $rs = mysql_fetch_array($rs_result);

 echo '<span id="label">'.ucwords($rs['name'].' '.$rs['lastname']).'</span>';
 echo '<div id="body">
<table class="table table-striped table-bordered">
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_TYPE'].'</td>
		<td align="left" valign="top">'.($rs['is_company'] ? $lang_['clients_accounts']['INFO_IS_COMPANY'] : $lang_['clients_accounts']['INFO_IS_NO_COMPANY']).'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_USERID'].'</td>
		<td align="left" valign="top">'.$rs['userid'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_TAXCODE'].'</td>
		<td align="left" valign="top">'.$rs['tax_code'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_EMAIL'].'</td>
		<td align="left" valign="top">'.$rs['email'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_PHONE'].'</td>
		<td align="left" valign="top">'.$rs['phone'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">'.$lang_['clients_accounts']['INFO_FAX'].'</td>
		<td align="left" valign="top">'.$rs['fax'].'</td>
	</tr>
</table>
 <div class="row-fluid">
    <div class="span6">
	     <strong class="text-info">'.$lang_['clients_accounts']['INFO_ADDRESS'].'</strong>
          <address>'
			.$rs['address'].'<br/>'
			.$rs['city'].' - '.$rs['zipcode'].'<br/>
			<abbr title="Telefono">T: </abbr> '.$rs['phone'].'<br/>'.
			($rs['fax'] != '' ? '<abbr title="Fax">F: </abbr> '.$rs['fax'].'<br/>' : '').'
			<abbr title="E-mail">@: </abbr> '.$rs['email'].'
		  </address>	
	</div>
 </div>
</div>';
?>