<?php
 require_once('include/inc_load.php');
 $_SESSION['langCli'] = $_POST['lang'];
 @define('languageCli',$_SESSION['langCli']);
?>