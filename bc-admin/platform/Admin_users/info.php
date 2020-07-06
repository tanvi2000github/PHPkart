<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
 $sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
 $rs_result = execute($sql);
 $rs = mysql_fetch_array($rs_result);

 echo '<span id="label">CLIENTE: '.ucwords($rs['nome']).'</span>';
 echo '<div id="body">
<table class="table table-striped table-bordered">
	<tr>
		<td align="left" valign="top">Nome</td>
		<td align="left" valign="top">'.$rs['nome'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">Codice Fiscale</td>
		<td align="left" valign="top">'.$rs['cod_fisc'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">UserID</td>
		<td align="left" valign="top">'.$rs['userid'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">Telefono</td>
		<td align="left" valign="top">'.$rs['telefono'].'</td>
	</tr>
	<tr>
		<td align="left" valign="top">Cellulare</td>
		<td align="left" valign="top">'.$rs['cellulare'].'</td>
	</tr>	
	<tr>
		<td align="left" valign="top">E-Mail</td>
		<td align="left" valign="top">'.$rs['email'].'</td>
	</tr>	
</table>
</div>';
?>