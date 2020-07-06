<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
if(!isset($_GET['type']) || ($_GET['type'] != 'seo' && $_GET['type'] != 'system' && $_GET['type'] != 'cart' && $_GET['type'] != 'company_data' && $_GET['type'] != 'payments') ) header('location: ?type=system');
require_once('general_tags.php');
switch($_GET['type']){
 case 'system':
   $page_title = $part_title = $lang_['settings']['MENU_SYSTEM'];
   $part_icon = '<i class="icon32 icon-wrench icon-white"></i>';
 break;
 case 'cart':
   $page_title = $part_title = $lang_['settings']['MENU_CART'];
   $part_icon = '<i class="icon32 icon-cart icon-white"></i>';
 break;
 case 'company_data':
   $page_title = $part_title = $lang_['settings']['MENU_COMPANY_DATA'];
   $part_icon = '<i class="icon32 icon-profile icon-white"></i>';
 break;
 case 'seo':
   $page_title = $part_title = $lang_['settings']['MENU_SEO'];
   $part_icon = '<i class="icon32 icon-web icon-white"></i>';
 break;
 case 'payments':
   $page_title = $part_title = $lang_['settings']['MENU_PAYMENTS'];
   $part_icon = '<i class="icon32 icon-attachment icon-white"></i>';
 break;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $lang_['settings']['FLEX_MAIN_MENU_TITLE'].' - '.$page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="user-scalable=no,initial-scale=1.0, maximum-scale=1.0 width=device-width" />
    <!-- Styles -->
    <?php require_once(rel_client_path.'/include/inc_css_base.php'); ?>
    <link href="<?php echo abs_admin_path ?>/css/admin.css" rel="stylesheet">


    <!-- icons -->
    <link rel="shortcut icon" href="<?php echo path_img_front ?>/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo path_img_front ?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo path_img_front ?>/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo path_img_front ?>/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo path_img_front ?>/ico/apple-touch-icon-57-precomposed.png">
	<style type="text/css">
    table p {
        font-size: 12px;
		font-weight:bold;
    }
    </style>
  </head>

  <body>
<div class="container-fluid Hfill"> <!-- CONTAINER -->
  <div class="row-fluid resp-nav-btn"><!-- ROW-button responsive menu -->
     <?php require_once(rel_admin_path.'/responsive_menu.php'); ?>
  </div><!-- /ROW-button responsive menu -->
  <div class="row-fluid Hfill general_menu_container">
    <div class="span2 Hfill-menu menu-area">
      <!-- menu -->
      <div class="container-fluid Hfill">
        <div class="row-fluid">
         <div class="collapse in" id="main_menu">
           <?php require_once(rel_admin_path.'/menu.php'); ?>
         </div>
        </div>
      </div>
      <!-- /menu -->
    </div>
    <!-- main area -->
    <div class="span10 Hfill body-area" style="position:relative;">
             <!-- Breadcrumbs -->
             <div class="container-fluid">
               <div class="row-fluid">
                <div class="span12 breadcrumb-container">
                   <?php echo str_replace($lang_['settings']['FLEX_MAIN_MENU_TITLE'],$lang_['settings']['FLEX_MAIN_MENU_TITLE'].' - '.ucwords($_GET['type']),$breadcrumb); ?>
                </div>
               </div>
             </div>
             <!-- /Breadcrumbs -->
        <div class="main_container Hfill">
          <div class="content_container">
             <!-- Body general area -->
             <div class="container-fluid">
              <div class="row-fluid">
               <div class="box" id="main_table">
                 <div class="box-header well">
                   <h2><?php echo $box_title; ?></h2>
                   <div class="box-icon"></div>
                 </div>
                 <form id="form_setting" class="system_form" method="post" action="edit_settings.php">
                 <input type="hidden" name="type" id="type" value="<?php echo $_GET['type']; ?>" />
                   <div style="padding:20px 20px 0px 20px;">
                     <div class="errors_control sapn12"></div>
                   </div>
                   <div class="box-content tab-content" id="container_flex" style="padding:0px;">
                      <div class="label label-info" style="padding-left:20px;"><h3><?php echo $part_icon.' '.$part_title; ?></h3></div>
                        <div style="padding:20px;">
                          <?php
						   if($_GET['type'] == 'system'){
							  $dir_language_admin = rel_admin_path.'/lang/';
							  $dir_language_client = rel_client_path.'/lang/';
							  function read_dir($dir_language){
								if (is_dir($dir_language)) {
									if ($directory_handle = opendir($dir_language)) {
										while (($file = readdir($directory_handle)) !== false) {
											if((!is_dir($file))&($file!=".")&($file!="..")){
											  $arr_lang[] = $file;
											}
										}
										closedir($directory_handle);
										return $arr_lang;
									}
								}
							  }
						  ?>
                          <table class="table table-striped table-hover">
                            <tr>
                                <th scope="row" style="width:300px;"><label for="default_admin_language"><?php echo $lang_['settings']['FIELD_ADMIN_LANGUAGE']; ?></label></th>
                                <td>
                                  <select id="default_admin_language" class="bootstyl text-left" name="default_admin_language">
                                    <?php
                                    foreach(read_dir($dir_language_admin) as $lang){
                                      $dir_language = rel_admin_path.'/lang/'.$lang;
                                      if (is_dir($dir_language)) {
                                          if ($directory_handle = opendir($dir_language)) {
                                              while (($file = readdir($directory_handle)) !== false) {
                                                  if((!is_dir($file))&($file!=".")&($file!="..")){
                                                    $file_ext = explode('.',$file);
                                                    if(end($file_ext) == 'ini'){
                                                        $img = $file_ext[0].'.png';
                                                        $name = $val = $file_ext[0];
                                                        echo '<option data-img-before=\'<img src="'.abs_admin_path.'/lang/'.$lang.'/'.$img.'" style="margin-right:10px;" />\' value="'.$val.'" '.($val == $default_admin_language ? 'selected' : '').'>'.$val.'</option>';
                                                    }
                                                  }
                                              }
                                              closedir($directory_handle);
                                          }
                                      }
                                    }
                                    ?>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="default_client_language"><?php echo $lang_['settings']['FIELD_CLIENT_LANGUAGE']; ?></label></th>
                                <td>
                                  <select id="default_client_language" class="bootstyl text-left" name="default_client_language">
                                    <?php
                                    foreach(read_dir($dir_language_client) as $lang){
                                      $dir_language = rel_client_path.'/lang/'.$lang;
                                      if (is_dir($dir_language)) {
                                          if ($directory_handle = opendir($dir_language)) {
                                              while (($file = readdir($directory_handle)) !== false) {
                                                  if((!is_dir($file))&($file!=".")&($file!="..")){
                                                    $file_ext = explode('.',$file);
                                                    if(end($file_ext) == 'ini'){
                                                        $img = $file_ext[0].'.png';
                                                        $name = $val = $file_ext[0];
                                                        echo '<option data-img-before=\'<img src="'.abs_admin_path.'/lang/'.$lang.'/'.$img.'" style="margin-right:10px;" />\' value="'.$val.'" '.($val == $default_client_language ? 'selected' : '').'>'.$val.'</option>';
                                                    }
                                                  }
                                              }
                                              closedir($directory_handle);
                                          }
                                      }
                                    }
                                    ?>
                                  </select>
                                </td>
                            </tr>
                          <?php
						    if(plugin_exsists('businesstype')){
						  ?>
                              <tr>
                                  <th scope="row"><label><?php echo $lang_['pl_businesstype']['FROM_LABEL_TITLE_OPTION']; ?></label></th>
                                  <td>
                                    <div class="checkradio-group" data-icon="icon-ok icon-white">
                                      <input type="radio" id="business_type_b2b_b2c" name="business_type" data-label-name="<?php echo $lang_['pl_businesstype']['FORM_BUTTON_OPTION_B2B_B2C']; ?>" data-additional-classes="btn-info" value="bc" <?php echo (isset($business_type) && $business_type == 'bc' ? 'checked' : ''); ?> />
                                      <input type="radio" id="business_type_b2c" name="business_type" data-label-name="<?php echo $lang_['pl_businesstype']['FORM_BUTTON_OPTION_B2C']; ?>" data-additional-classes="btn-info" value="c" <?php echo (!isset($business_type) || (isset($business_type) && $business_type == 'c') ? 'checked' : ''); ?> />
                                    </div>
                                  </td>
                              </tr>
                          <?php
							}
						  ?>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="shop_title"><?php echo $lang_['settings']['FIELD_SHOP_TITLE']; ?></label></th>
                                <td><input name="shop_title" class="required" type="text" id="shop_title" value="<?php echo $shop_title; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="shop_url"><?php echo $lang_['settings']['FIELD_SHOP_URL']; ?></label></th>
                                <td><input name="shop_url" class="required websitehttp" type="text" id="shop_url" value="<?php echo $shop_url; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_SYSTEM_SHOP_URL']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="admin_email"><?php echo $lang_['settings']['FIELD_SYSTEM_EMAIL']; ?></label></th>
                                <td><input class="required email" name="admin_email" type="text" id="admin_email" size="25" value="<?php echo $admin_email; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_SYSTEM_EMAIL']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_SMTP']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="smtp_email_yes" name="smtp_email" data-label-name="<?php echo $lang_['settings']['GENERAL_YES_TEXT']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($smtp_email ? 'checked' : ''); ?> />
                                    <input type="radio" id="smtp_email_no" name="smtp_email" data-label-name="<?php echo $lang_['settings']['GENERAL_NO_TEXT']; ?>" data-additional-classes="btn-info" value="0" <?php echo (!$smtp_email ? 'checked' : ''); ?>/>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="smtp_port"><?php echo $lang_['settings']['FIELD_SMTP_PORT']; ?></label></th>
                                <td><input class="number" name="smtp_port" type="text" id="smtp_port" size="25" value="<?php echo $smtp_port; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="smtp_host"><?php echo $lang_['settings']['FIELD_SMTP_HOST']; ?></label></th>
                                <td><input name="smtp_host" type="text" id="smtp_host" size="25" value="<?php echo $smtp_host; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="smtp_user"><?php echo $lang_['settings']['FIELD_SMTP_USER']; ?></label></th>
                                <td><input name="smtp_user" type="text" id="smtp_user" size="25" value="<?php echo $smtp_user; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="smtp_password"><?php echo $lang_['settings']['FIELD_SMTP_PASSWORD']; ?></label></th>
                                <td><input name="smtp_password" type="password" id="smtp_password" size="25" value="<?php echo $smtp_password; ?>" data-array="12,12," /></td>
                            </tr>

                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_SMTP_SECURE']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="smtp_secure_ssl" name="smtp_secure" data-label-name="SSL" data-additional-classes="btn-info" value="ssl" <?php echo ($smtp_secure == 'ssl' ? 'checked' : ''); ?> />
                                    <input type="radio" id="smtp_secure_tls" name="smtp_secure" data-label-name="TLS" data-additional-classes="btn-info" value="tls" <?php echo ($smtp_secure == 'tls' ? 'checked' : ''); ?>/>
                                  </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="time_zone"><?php echo $lang_['settings']['FIELD_TIME_ZONE']; ?></label></th>
                                <td>
                                  <select name="time_zone" id="time_zone" class="required">
                                    <?php
                                      foreach($timezones_array as $key => $val){
                                        echo '<option value="'.$key.'"'.($time_zone == $key ? ' selected' : '').'>'.$val.'</option>';
                                      }
                                    ?>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="date_format"><?php echo $lang_['settings']['FIELD_DATE_FORMAT']; ?></label></th>
                                <td>
                                  <select class="required" name="date_format" id="date_format">
                                   <option value="mm/dd/yyyy"<?php echo ($date_format == 'mm/dd/yyyy' || $date_format == '' ? ' selected' : ''); ?>>mm/dd/yyyy</option>
                                   <option value="dd/mm/yyyy"<?php echo ($date_format == 'dd/mm/yyyy' ? ' selected' : ''); ?>>dd/mm/yyyy</option>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="cookies_persistence"><?php echo $lang_['settings']['FIELD_COOKIES_PERSISTENCE']; ?></label></th>
                                <td><input class="required number" name="cookies_persistence" type="text" id="cookies_persistence" value="<?php echo $cookies_persistence; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_COOKIES_PERSISTANCE']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="gmap_key"><?php echo $lang_['settings']['FIELD_GMAP_KEY']; ?></label></th>
                                <td><input class="required" name="gmap_key" type="text" id="gmap_key" value="<?php echo $gmap_key; ?>" data-array="12,12," /></td>
                            </tr>
                           </table>
                          <?php
						   }else if($_GET['type'] == 'cart'){
						  ?>
                          <table class="table table-striped table-hover">
                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_SHOP_OPENED_TO_CUSTOMERS']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="coming_soon_yes" name="coming_soon" data-label-name="<?php echo $lang_['settings']['GENERAL_YES_TEXT']; ?>" data-additional-classes="btn-info" value="0" <?php echo (@!$coming_soon ? 'checked' : ''); ?> />
                                    <input type="radio" id="coming_soon_no" name="coming_soon" data-label-name="<?php echo $lang_['settings']['GENERAL_NO_TEXT']; ?>" data-additional-classes="btn-info" value="1" <?php echo (@$coming_soon ? 'checked' : ''); ?> />
                                  </div>
                                  <p><?php echo $lang_['settings']['NOTICE_COMING_SOON']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_SHOP_ALLOW_PURCHASES_WITHOUT_REGISTRATION']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="guest_purchases_yes" name="guest_purchases" data-label-name="<?php echo $lang_['settings']['GENERAL_YES_TEXT']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($guest_purchases ? 'checked' : ''); ?> />
                                    <input type="radio" id="guest_purchases_no" name="guest_purchases" data-label-name="<?php echo $lang_['settings']['GENERAL_NO_TEXT']; ?>" data-additional-classes="btn-info" value="0" <?php echo (!$guest_purchases ? 'checked' : ''); ?> />
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_CUSTOMER_REGISTRATION_TYPE_LABEL']; ?></label></th>
                                <td>
                                  <select class="required" name="registration_type" id="registration_type" data-array="12,12,">
                                    <option value="0"<?php echo ((isset($registration_type) && $registration_type == 0) || !isset($registration_type) ? ' selected' : ''); ?>><?php echo $lang_['settings']['FIELD_CUSTOMER_REGISTRATION_TYPE_VALUE_BY_EMAIL']; ?></option>
                                    <option value="1"<?php echo ((isset($registration_type) && $registration_type == 1) ? ' selected' : ''); ?>><?php echo $lang_['settings']['FIELD_CUSTOMER_REGISTRATION_TYPE_VALUE_IMMEDIATE']; ?></option>
                                    <option value="2"<?php echo ((isset($registration_type) && $registration_type == 2) ? ' selected' : ''); ?>><?php echo $lang_['settings']['FIELD_CUSTOMER_REGISTRATION_TYPE_VALUE_BY_ADMIN']; ?></option>
                                  </select>
                                </td>
                            </tr>
                          <?php
                if(plugin_exsists('dgoods')){
              ?>
                              <tr>
                                  <th scope="row"><label><?php echo $lang_['pl_dgoods']['FROM_SETTINGS_LABEL_DGOODS_DEADLINE']; ?></label></th>
                                  <td>
                                    <input class="number" name="dgoodg_link_deadline" type="text" id="dgoodg_link_deadline" value="<?php echo isset($dgoodg_link_deadline) ? $dgoodg_link_deadline : 0; ?>" data-array="12,12," />
                                    <p><?php echo $lang_['pl_dgoods']['NOTICE_DGOODS_DEADLINE']; ?></p>
                                  </td>
                              </tr>
                          <?php
              }
              ?>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="days_product_new"><?php echo $lang_['settings']['FIELD_NEW_PRODUCTS']; ?></label></th>
                                <td><input class="required number" name="days_product_new" type="text" id="days_product_new" value="<?php echo $days_product_new; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_NEW_PRODUCT']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="products_per_page"><?php echo $lang_['settings']['FIELD_PRODUCTS_PER_PAGE']; ?></label></th>
                                <td><input class="required number" name="products_per_page" type="text" id="products_per_page" value="<?php echo $products_per_page; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php echo $lang_['settings']['FIELD_PRICES_VISIBLE']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="visible_prices" name="prices_on_login" data-label-name="<?php echo $lang_['settings']['GENERAL_YES_TEXT']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($prices_on_login ? 'checked' : ''); ?> />
                                    <input type="radio" id="not_visible_prices" name="prices_on_login" data-label-name="<?php echo $lang_['settings']['GENERAL_NO_TEXT']; ?>" data-additional-classes="btn-info" value="0" <?php echo (!$prices_on_login ? 'checked' : ''); ?>/>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="currency"><?php echo $lang_['settings']['FIELD_CURRENCY']; ?></label></th>
                                <td>
                                  <select class="required" name="currency" id="currency" data-array="12,12,">
                                    <?php
                                      foreach($currencies_array as $key => $val){
                                        echo '<option value="'.$val[0].'"'.($currency === html_entity_decode($val[0], ENT_QUOTES, "UTF-8") ? ' selected' : '').'>'.$key.' - '.$val[1].' ('.$val[0].')</option>';
                                      }
                                    ?>
                                  </select>
                                  <br/>
                                  <table>
                                    <tr>
                                      <td>
                                        <strong><?php echo $lang_['settings']['FIELD_CURRENCY_POSITION']; ?></strong>
                                      </td>
                                      <td>
                                        <div class="checkradio-group" data-icon="icon-ok icon-white">
                                          <input type="radio" id="currency_position_left" name="currency_position" data-label-name="<?php echo $lang_['settings']['FIELD_CURRENCY_ON_LEFT']; ?>" data-additional-classes="btn-info" value="l" <?php echo (!isset($currency_position) || $currency_position == 'l' ? 'checked' : ''); ?> />
                                          <input type="radio" id="currency_position_right" name="currency_position" data-label-name="<?php echo $lang_['settings']['FIELD_CURRENCY_ON_RIGHT']; ?>" data-additional-classes="btn-info" value="r" <?php echo ($currency_position == 'r' ? 'checked' : ''); ?>/>
                                        </div>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="tax_name"><?php echo $lang_['settings']['FIELD_TAX_GENERAL']; ?></label></th>
                                <td>
                                  <table width="100%">
                                    <tr>
                                      <td>
                                       <input class="required" name="tax_name" type="text" id="tax_name" value="<?php echo $tax_name; ?>" data-array="12,12,<?php echo $lang_['settings']['FIELD_TAX_NAME']; ?>" />
                                      </td>
                                      <td>
                                        <input class="required number" name="tax_percentage" type="text" id="tax_percentage" value="<?php echo $tax_percentage; ?>" data-array="12,12,<?php echo $lang_['settings']['FIELD_TAX_VALUE']; ?>" />
                                        <p><?php echo $lang_['settings']['NOTICE_DEFAULT_TAX_VALUE']; ?></p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="shipping_price"><?php echo $lang_['settings']['FIELD_SHIPPING_COST']; ?></label></th>
                                <td><input class="required number" name="shipping_price" type="text" id="shipping_price" value="<?php echo $shipping_price; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_SHIPPING_COST']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="thousands_separator"><?php echo $lang_['settings']['FIELD_THOUSANDS_SEPARATOR']; ?></label></th>
                                <td>
                                  <select class="required" name="thousands_separator" id="thousands_separator" data-array="12,12,">
                                   <option value=","<?php echo ($thousands_separator == ',' || $thousands_separator == '' ? ' selected' : ''); ?>>,</option>
                                   <option value="."<?php echo ($thousands_separator == '.' ? ' selected' : ''); ?>>.</option>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="decimal_separator"><?php echo $lang_['settings']['FIELD_DECIMAL_SEPARATOR']; ?></label></th>
                                <td>
                                  <select class="required" name="decimal_separator" id="decimal_separator" data-array="12,12,">
                                   <option value="."<?php echo ($decimal_separator == '.' || $decimal_separator == '' ? ' selected' : ''); ?>>.</option>
                                   <option value=","<?php echo ($decimal_separator == ',' ? ' selected' : ''); ?>>,</option>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="units"><?php echo $lang_['settings']['FIELD_UNITS']; ?></label></th>
                                <td><input name="units" type="text" id="units" value="<?php echo $units; ?>" data-array="12,12," /></td>
                            </tr>
                           </table>
                          <?php
						   }else if($_GET['type'] == 'payments'){
							  foreach($param['payments'] as $key => $val){
								foreach($val as $k => $v){
								  $$k = $v;
								}
						  ?>
                           <h4 class="alert alert-blok alert-info"><?php echo $long_name; ?></h4>
                           <input name="<?php echo $key; ?>[orders_prefix]" type="hidden" value="<?php echo $orders_prefix; ?>" />
                           <table class="table table-striped table-hover">
                            <tr>
                                <th scope="row" style="width:300px;"><label><?php echo $lang_['settings']['FIELD_STATUS']; ?></label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="status_<?php echo $orders_prefix;?>_yes" name="<?php echo $key; ?>[status]" data-label-name="<?php echo $lang_['settings']['FIELD_STATUS_ACTIVE']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($status ? 'checked' : ''); ?> />
                                    <input type="radio" id="status_<?php echo $orders_prefix;?>_no" name="<?php echo $key; ?>[status]" data-label-name="<?php echo $lang_['settings']['FIELD_STATUS_NO_ACTIVE']; ?>" data-additional-classes="btn-info" value="0" <?php echo (!$status ? 'checked' : ''); ?> />
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="long_name_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_NAME']; ?></label></th>
                                <td>
                                    <input class="required" name="<?php echo $key; ?>[long_name]" type="text" id="long_name_<?php echo $orders_prefix;?>" value="<?php echo $long_name; ?>" data-array="12,12," />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="surcharge_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_SURCHARGE']; ?></label></th>
                                <td>
                                    <input class="required number" name="<?php echo $key; ?>[surcharge]" type="text" id="surcharge_<?php echo $orders_prefix;?>" value="<?php echo $surcharge; ?>" data-array="12,12," />
                                </td>
                            </tr>
							  <?php
                               if($key == 'paypal'){
                              ?>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="currency_code_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_PAYPAL_CURRENCY']; ?></label></th>
                                <td>
                                  <select class="required" name="<?php echo $key; ?>[currency_code]" id="currency_code_<?php echo $orders_prefix;?>" data-array="12,12,">
                                    <?php
                                      foreach($currencies_array as $keyc => $valc){
                                        echo '<option value="'.$keyc.'"'.($currency_code === $keyc ? ' selected' : '').'>'.$keyc.' - '.$valc[1].' ('.$valc[0].')</option>';
                                      }
                                    ?>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="region_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_PAYPAL_REGION']; ?></label></th>
                                <td>
                                  <select class="required" name="<?php echo $key; ?>[region]" id="region_<?php echo $orders_prefix;?>" data-array="12,12,">
                                    <?php
                                      foreach($paypal_region_array as $keyr => $valr){
                                        echo '<option value="'.$keyr.'"'.($region == $keyr ? ' selected' : '').'>'.$valr.' ('.$keyr.')</option>';
                                      }
                                    ?>
                                  </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label>Sendbox</label></th>
                                <td>
                                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                                    <input type="radio" id="sendbox_<?php echo $orders_prefix;?>_yes" name="<?php echo $key; ?>[sendbox]" data-label-name="<?php echo $lang_['settings']['FIELD_STATUS_ACTIVE']; ?>" data-additional-classes="btn-info" value="1" <?php echo ($sendbox ? 'checked' : ''); ?> />
                                    <input type="radio" id="sendbox_<?php echo $orders_prefix;?>_no" name="<?php echo $key; ?>[sendbox]" data-label-name="<?php echo $lang_['settings']['FIELD_STATUS_NO_ACTIVE']; ?>" data-additional-classes="btn-info" value="0" <?php echo (!$sendbox ? 'checked' : ''); ?> />
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="email_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_PAYPAL_EMAIL']; ?></label></th>
                                <td>
                                    <input class="required email" name="<?php echo $key; ?>[email]" type="text" id="email_<?php echo $orders_prefix;?>" value="<?php echo $email; ?>" data-array="12,12," />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="payment_limit_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_PAYPAL_LIMIT']; ?></label></th>
                                <td>
                                    <input class="required number" name="<?php echo $key; ?>[payment_limit]" type="text" id="payment_limit_<?php echo $orders_prefix;?>" value="<?php echo $payment_limit; ?>" data-array="12,12," />
                                </td>
                            </tr>
                          <?php
							   }
						  ?>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="email_message_<?php echo $orders_prefix;?>"><?php echo $lang_['settings']['FIELD_MESASGE_TO_CLIENT']; ?></label></th>
                                <td>
                                  <textarea style="height:200px;" class="required" name="<?php echo $key; ?>[email_message]" id="email_message_<?php echo $orders_prefix;?>" data-array="12,12,"><?php echo preg_replace('#<br\s*?/?>#i', "\n",$email_message);?></textarea>
                                  <p><?php echo $lang_['settings']['NOTICE_PAYMENT_MESSAGE']; ?></p>
                                </td>
                            </tr>
							 </table>
                          <?php
							  }
						   }else if($_GET['type'] == 'company_data'){
						  ?>
                           <table class="table table-striped table-hover">
                            <tr>
                                <th scope="row" style="width:300px;"><label for="company_name"><?php echo $lang_['settings']['FIELD_COMPANY_NAME']; ?></label></th>
                                <td><input class="required" name="company_name" type="text" id="company_name" value="<?php echo $company_name; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="company_name"><?php echo $lang_['settings']['UPLOAD_LABEL_LOGO_HEADER']; ?></label></th>
                                <td>
                                   <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">
                                      <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width:96%;overflow:hidden"><img src="<?php echo abs_uploads_path.'/bc_logo.png'; ?>"/></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;overflow:hidden"></div>
                                        <div>
                                          <span class="btn btn-block btn-file"><span class="fileupload-new add_file_new"><i class="icon-picture"></i> <?php echo $lang_['settings']['CHANGE_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists edit_file_new"><i class="icon-refresh"></i> <?php echo $lang_['settings']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" name="upimg_logo_header" id="upimg_logo_header" /></span>
                                        </div>
                                      </div>
                                   </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="company_name"><?php echo $lang_['settings']['UPLOAD_LABEL_LOGO_FOOTER']; ?></label></th>
                                <td>
                                   <div class="span4 duplicate_upl" style="margin-left:0px;margin-right:10px;margin-bottom:10px;border:2px solid #dedede;padding:5px;">
                                      <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width:96%;overflow:hidden"><img src="<?php echo abs_uploads_path.'/bc_logo_footer.png'; ?>"/></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="line-height:20px;width:96%;overflow:hidden"></div>
                                        <div>
                                          <span class="btn btn-block btn-file"><span class="fileupload-new add_file_new"><i class="icon-picture"></i> <?php echo $lang_['settings']['CHANGE_IMAGE_TEXT']; ?></span><span class="btn-block fileupload-exists edit_file_new"><i class="icon-refresh"></i> <?php echo $lang_['settings']['CHANGE_IMAGE_TEXT']; ?></span><input type="file" name="upimg_logo_footer" id="upimg_logo_footer" /></span>
                                        </div>
                                      </div>
                                   </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_taxcode"><?php echo $lang_['settings']['FIELD_COMPANY_TAX_CODE']; ?></label></th>
                                <td><input class="required" name="company_taxcode" type="text" id="company_taxcode" value="<?php echo $company_taxcode; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_email"><?php echo $lang_['settings']['FIELD_COMPANY_EMAIL']; ?></label></th>
                                <td><input class="required email" name="company_email" type="text" id="company_email" value="<?php echo $company_email; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_COMPANY_EMAIL']; ?></p></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_address"><?php echo $lang_['settings']['FIELD_COMPANY_ADDRESS']; ?></label></th>
                                <td><input class="required" name="company_address" type="text" id="company_address" value="<?php echo $company_address; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_city"><?php echo $lang_['settings']['FIELD_COMPANY_CITY']; ?></label></th>
                                <td><input class="required" name="company_city" type="text" id="company_city" value="<?php echo $company_city; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_zipcode"><?php echo $lang_['settings']['FIELD_COMPANY_ZIPCODE']; ?></label></th>
                                <td><input class="required number" name="company_zipcode" type="text" id="company_zipcode" value="<?php echo $company_zipcode; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_phone"><?php echo $lang_['settings']['FIELD_COMPANY_PHONE']; ?></label></th>
                                <td><input class="required number" name="company_phone" type="text" id="company_phone" value="<?php echo $company_phone; ?>" data-array="12,12," /></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="company_fax"><?php echo $lang_['settings']['FIELD_COMPANY_FAX']; ?></label></th>
                                <td><input class="number" name="company_fax" type="text" id="company_fax" value="<?php echo $company_fax; ?>" data-array="12,12," /></td>
                            </tr>
                           </table>
                          <?php
						   }else if($_GET['type'] == 'seo'){
						  ?>
                           <table class="table table-striped table-hover">
                            <tr>
                                <th scope="row" style="width:300px;"><label for="shop_meta_keywords"><?php echo $lang_['settings']['FIELD_SHOP_SEO_KEYWORDS']; ?></label></th>
                                <td><input name="shop_meta_keywords" type="text" id="shop_meta_keywords" value="<?php echo $shop_meta_keywords; ?>" data-array="12,12," />
                                <p><?php echo $lang_['settings']['NOTICE_SYSTEM_SEO_KEYWORDS']; ?></p> </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="shop_meta_description"><?php echo $lang_['settings']['FIELD_SHOP_SEO_DESCRIPTION']; ?></label></th>
                                <td>
                                  <input type="text" name="shop_meta_description" id="shop_meta_description" maxlength="150" data-array="12,12," value="<?php echo $shop_meta_description;?>" />
                                  <p><?php echo $lang_['settings']['NOTICE_SYSTEM_SEO_DESCRIPTION']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" style="width:300px;"><label for="google_analytics"><?php echo $lang_['settings']['FIELD_SHOP_SEO_GOOGLE_ANALYTICS']; ?></label></th>
                                <td>
                                  <textarea style="height:180px;" name="google_analytics" id="google_analytics" data-array="12,12,"><?php echo preg_replace('#<br\s*?/?>#i', "\n",$google_analytics);?></textarea>
                                  <p><?php echo $lang_['settings']['NOTICE_SYSTEM_SEO_GOOGLE_ANALYTICS']; ?></p>
                                </td>
                            </tr>
                           </table>
                          <?php
						   }
						  ?>
                        </div>
                   </div>
                   <div style="padding:20px;">
                     <span class="btn btn-info save_item"><i class="icon icon-white icon-save"></i> <?php echo $lang_['table']['FORM_BTN_SAVE']; ?></span>
                   </div>
                 </form>
               </div>
              </div>
             </div>
             <!-- /Body general area -->
          </div>
        </div>
    </div>
    <!-- /main area -->
  </div> <!-- /ROW -->
</div> <!-- /CONTAINER -->

    <!-- ========================== Javascript ======================== -->
    <?php require_once(rel_client_path.'/include/inc_js_base_admin.php'); ?>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/center-div.js"></script>
	<script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/validate.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/ajaxForm.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/qTip2-tooltip.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/livequery.js"></script>
    <script type="text/javascript" src="<?php echo abs_admin_path ?>/js/general_functions.js"></script>
    <script type="text/javascript">
	 $(function(){
		 /* set some global variables for this section (PLEASE NOT DELETE THEM!!!) */
		 $('body').data('admin_path_img','<?php echo path_img_back; ?>');
		 $('body').data('FIELD_TAX_NAME','<?php echo $lang_['settings']['FIELD_TAX_NAME']; ?>');
		 $('body').data('FIELD_TAX_VALUE','<?php echo $lang_['settings']['FIELD_TAX_VALUE']; ?>');
		 $('body').data('NOTICE_DEFAULT_TAX_VALUE','<?php echo $lang_['settings']['NOTICE_DEFAULT_TAX_VALUE']; ?>');
	 });
	</script>
    <?php
	  /****** set language with JS file in LANG Directory (NO CHANGE IT PLEASE!!!)*******/
	  if(file_exists(dirname(__FILE__).'/lang/'.languageAdmin.'/'.languageAdmin.'.js'))
	   echo '<script type="text/javascript" src="lang/'.languageAdmin.'/'.languageAdmin.'.js"></script>';
	  /****** initialize a main js file for this section (NO CHANGE IT PLEASE!!!)*******/
	  if(file_exists(dirname(__FILE__).'/main_script.js'))
	   echo '<script type="text/javascript" src="main_script.js"></script>';
	?>
  </body>
</html>
