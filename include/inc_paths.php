<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
/*
 -----------------------------------
 ------- NO CHANGE IT PLEASE -------
 -----------------------------------
*/ 
define('path_rel',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT'])); //-> relative path of root (C://...)
define('path_abs',get_root_url());//-> absolute path of root (www....)
$get_url_domain_pathinfo = pathinfo(dirname(__FILE__));
if(mb_substr($get_url_domain_pathinfo['dirname'],0, -1) != '/' && mb_substr($get_url_domain_pathinfo['dirname'],0, -1) != '\\'){
  $get_url_domain_pathinfo = $get_url_domain_pathinfo['dirname'];
}else{
  $get_url_domain_pathinfo = mb_substr($get_url_domain_pathinfo['dirname'],0,-1);
}
$client_path = str_replace(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'',str_replace('\\','/',$get_url_domain_pathinfo.'/'));
if(mb_substr($client_path,0, -1) != '/' && mb_substr($client_path,0, -1) != '\\'){
  $client_path = mb_substr($client_path,0,-1);
}
if(mb_substr($client_path, 1) != '/' && mb_substr($client_path, 1) != '\\'){
  $client_path = mb_substr($client_path,1);
}
$path_img_products = 'content/up-products-images';
$path_products = 'content/products';

define('abs_client_path',($client_path == '' ? get_root_url().$client_path : get_root_url().'/'.$client_path));//-> absolute path of front-end (www....)
define('rel_client_path',str_replace('\\','/',$get_url_domain_pathinfo));//-> relative path of front-end (C://...)

define('abs_admin_path',abs_client_path.'/bc-admin');//-> absolute path of back-end (www....)
define('rel_admin_path',rel_client_path.'/bc-admin');//-> relative path of back-end (C://...)

define('abs_content_path',abs_client_path.'/content');//-> absolute path of content (www....)
define('rel_content_path',rel_client_path.'/content');//-> relative path of content (C://...)

define('abs_plugins_path',abs_client_path.'/content/plugins');//-> absolute path of plugins (www....)
define('rel_plugins_path',rel_client_path.'/content/plugins');//-> relative path of plugins (C://...)

define('abs_updates_path',abs_client_path.'/content/updates');//-> absolute path of updates (www....)
define('rel_updates_path',rel_client_path.'/content/updates');//-> relative path of updates (C://...)

define('abs_uploads_path',abs_client_path.'/content/uploads');//-> absolute path of uploads (www....)
define('rel_uploads_path',rel_client_path.'/content/uploads');//-> relative path of uploads (C://...)

define('theme_abs_path',abs_client_path.'/content/themes/default'); //(www....)
define('theme_rel_path',rel_client_path.'/content/themes/default'); //(C://....)
define('theme_css_path',theme_abs_path.'/css'); //(www....)
define('theme_js_path',theme_abs_path.'/js'); //(www....)
define('theme_img_path',theme_abs_path.'/img'); //(www....)

define('path_img_back',abs_admin_path.'/img'); //(www....)
define('path_img_front',abs_client_path.'/include/img'); //(www....)
define('path_rel_img_back',rel_admin_path.'/img'); //(C://...)
define('path_rel_img_front',rel_client_path.'/include/img'); //(C://...)
define('path_abs_img_products',abs_client_path.'/'.$path_img_products); //(www....)
define('path_rel_img_products',rel_client_path.'/'.$path_img_products); //(C://...)
define('path_abs_products',abs_client_path.'/'.$path_products); //(www....)
define('path_rel_products',rel_client_path.'/'.$path_products); //(C://...)
isset($_SESSION['langCli']) ? define('languageCli',$_SESSION['langCli']) : define('languageCli',$default_client_language);
?>