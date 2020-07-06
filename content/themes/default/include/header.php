<?php
  $AdditionalHeadTags = isset($AdditionalHeadTags) && $AdditionalHeadTags != '' ? $AdditionalHeadTags : '';
  $meta_robots = isset($meta_robots) ? $meta_robots : 'INDEX,FOLLOW';
  execute(); // open connection to DB
  require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
  $mptt = new Zebra_Mptt();   
  $meta_keywords = !isset($meta_keywords) ? 
						  isset($category_id) ? strip_tags($mptt->get_specific_data($category_id,'meta_keywords')) :(isset($shop_meta_keywords) ? strip_tags($shop_meta_keywords) : '') 
				   : strip_tags($meta_keywords);
  $meta_description = !isset($meta_description) ? 
                          isset($category_id) ? strip_tags($mptt->get_specific_data($category_id,'meta_description')) : (isset($shop_meta_description) ? strip_tags(html_entity_decode($shop_meta_description)) : '')
				   : strip_tags(preg_replace('/\s+/', ' ',preg_replace('#<br\s*?/?>#i', " ",html_entity_decode($meta_description))));
  $page_title = isset($page_title) ? $page_title : $mptt->get_orizzontal($category_id,'/');
    $B_language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$B_language = strtolower(substr(chop($B_language[0]),0,2));
  switch($prices_on_login){
	case 1:
	 $view_prices = isset($_SESSION['Clogged']) ? 1 : 0;
	break;
	default:
	 $view_prices = 1;
	break;
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $B_language; ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title; ?></title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo str_replace('"','&quot;',$meta_description); ?>">
    <meta name="keywords" content="<?php echo str_replace('"','&quot;',$meta_keywords); ?>">
    <meta name="author" content="<?php echo str_replace('"','&quot;',$company_name); ?>">
    <meta name="robots" content="<?php echo $meta_robots; ?>">
    <meta name="viewport" content="user-scalable=no,initial-scale=1.0, maximum-scale=1.0 width=device-width" />
    <!-- Styles -->
    <?php require_once(rel_client_path.'/include/inc_css_base.php'); ?> 
    <link href="<?php echo theme_css_path ?>/general-style.css" rel="stylesheet">       
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo path_img_front ?>/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo path_img_front ?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo path_img_front ?>/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo path_img_front ?>/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo path_img_front ?>/ico/apple-touch-icon-57-precomposed.png">
    <!--[if lt IE 9]>
        <script src="<?php echo abs_client_path ?>/include/js/html5shiv.js"></script>
    <![endif]--> 
    <?php echo $AdditionalHeadTags; ?>  
  </head>