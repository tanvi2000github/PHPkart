<?php
session_start();
if(!IsSet($_SESSION['Alogged'])){
header('location:login.php'); 
}else{
header('location:platform/Admin_users'); 
}
?>