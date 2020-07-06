<?php
require_once(dirname(dirname(dirname(dirname((__FILE__))))).'/include/inc_load.php');
$category_id = 1;
if(file_exists('inc_array_product.php')){
require_once('inc_array_product.php');
}else{
$arr_container_products = array();
}
require_once(theme_rel_path.'/catalog.php');
?>