<?php
$page_title = $shop_title;
  if(!isset($coming_soon) || !$coming_soon || isset($_SESSION['Alogged'])){
	header('location:'.abs_client_path); 
	exit();
  }
$page_title = $shop_title;
$B_language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
$B_language = strtolower(substr(chop($B_language[0]),0,2));
$meta_keywords = isset($meta_keywords) ? $meta_keywords : isset($category_id) ? $mptt->get_specific_data($category_id,'meta_keywords') : (isset($shop_meta_keywords) ? $shop_meta_keywords : '') ;
$meta_description = isset($meta_description) ? $meta_description : isset($category_id) ? $mptt->get_specific_data($category_id,'meta_description') : (isset($shop_meta_description) ? preg_replace('#<br\s*?/?>#i', " ",$shop_meta_description) : '');
$meta_robots = isset($meta_robots) ? $meta_robots : 'INDEX,FOLLOW';
?>
<!DOCTYPE html>
<html lang="<?php echo $B_language; ?>" style="background:#EEEEEE;">
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
  </head>
  <body style="background:#EEEEEE;">   
   <section class="container-semifluid" id="main-container" style="margin-top:80px;"> <!-- CONTAINER -->     
     <div class="row-fluid"><!-- BODY ROW --> 
        <div class="span12 text-center">
          <img src="<?php echo theme_img_path.'/coming-soon.png'; ?>" />
        </div>                                  
     </div><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php 
     require_once('include/footer.php');
    ?>     
  </body>
</html>