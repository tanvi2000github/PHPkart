<?php
require_once('include/inc_load.php');
  execute('update '.$table_prefix.'clients set password = "'.encryption(str_db($_POST['password-retrieved'])).'" 
  where password = "'.str_db($_POST['old_password']).'" and email = "'.str_db($_POST['email-for-retrive']).'"');
?>