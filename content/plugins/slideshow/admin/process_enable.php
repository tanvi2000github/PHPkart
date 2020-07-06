<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/bc-admin/include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
$status = $_POST['status'] == 'ToProcess' ? 1 : 0;
  execute('update '.$table_prefix.'slideshow set active	= 0');
  execute('update '.$table_prefix.'slideshow set active	= 1  where id = '.$_POST['id']);
?>