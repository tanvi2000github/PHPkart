<?php
require_once('include/inc_load.php');
@session_start();
foreach($_SESSION as $key => $val){
 if(substr($key,0,1) === 'C')
 unset($_SESSION[$key]);
}
header('location:'.abs_client_path);
?>