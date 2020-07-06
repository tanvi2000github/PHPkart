<?php
session_start();
//$_SESSION = array() ;
foreach($_SESSION as $key => $val){
 if(substr($key,0,2) === 'Al')
 unset($_SESSION[$key]);
}
/*
session_destroy() ;*/
header('location:index.php');
?>