<?php
 if(isset($_SESSION['Clogged'])){
  $sql = execute('select * from '.$table_prefix.'clients where id = '.$_SESSION['Cid']);
  $rs = mysql_fetch_array($sql);
 }
 $page_title = $lang_client_['client_account']['PAGE_TITLE'];
 require_once('include/header.php');
?>
 <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?>
     <section class="row-fluid"><!-- BODY breadcrumb -->
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <li class="active"><?php echo $page_title; ?></li>
        </ul>
     </section><!-- / BODY breadcrumb -->
	<?php
     if(isset($_SESSION['Clogged'])){
    ?>
      <div class="box-header">
           <span class="header-text"><i class="icon icon-black icon-user"></i> <?php echo $page_title; ?></span>
      </div>
     <section class="row-fluid myaccount-page"><!-- BODY ROW -->
         <aside class="left-sidebar span3">
          <nav>
              <ul class="nav nav-tabs nav-stacked" id="menu-step-change-data-account">
                <li<?php echo (!isset($_GET['type']) || $_GET['type'] == 'account_info' ? ' class="active"' : ''); ?>><a href="<?php echo abs_client_path; ?>/account.php" data-rel="#step-change-data-account"><?php echo $lang_client_['client_account']['MENU_INFO']; ?></a></li>
                <li<?php echo (isset($_GET['type']) && $_GET['type'] == 'address' ? ' class="active"' : ''); ?>><a href="<?php echo abs_client_path; ?>/account.php?type=address" data-rel="#step-change-address"><?php echo $lang_client_['client_account']['MENU_ADDRESS']; ?></a></li>
         <?php
                 if(plugin_exsists('dgoods')){
               ?>
                  <li<?php echo (isset($_GET['type']) && $_GET['type'] == 'downloads' ? ' class="active"' : ''); ?>><a href="<?php echo abs_client_path; ?>/account.php?type=downloads" data-rel="#step-my-downloads"><?php echo $lang_client_['pl_dgoods']['MENU_DOWNLOADS']; ?></a></li>
         <?php
         }
               ?>
                <li<?php echo (isset($_GET['type']) && $_GET['type'] == 'orders' ? ' class="active"' : ''); ?>><a href="<?php echo abs_client_path; ?>/account.php?type=orders" data-rel="#step-my-orders"><?php echo $lang_client_['client_account']['MENU_ORDERS']; ?></a></li>
              </ul>
              <?php
				if(plugin_exsists('businesstype')){
					switch(get_client_info_status()){
						case '1':
						echo '<span id="send_retailer_request_pop" class="btn btn-info">'.$lang_client_['pl_businesstype']['BTN_RETAILER_REQUEST'].'</span>';
						break;
						case '2':
						echo '<div class="well well-small"><strong class="lead">'.$lang_client_['pl_businesstype']['ACCOUNT_TITLE_RETAILER_STATUS'].'</strong><br/><br/>';
						echo '<strong class="text-success"><i class="icon-ok"></i> '.$lang_client_['pl_businesstype']['RETAILER_REQUEST_SUCCESS'].'</strong><br/><br/>';
						echo '</div>';
						break;
						case '3':
						echo '<div class="well well-small"><strong class="lead">'.$lang_client_['pl_businesstype']['ACCOUNT_TITLE_RETAILER_STATUS'].'</strong><br/><br/>';
						echo '<strong class="text-info"><i class="icon-info-sign"></i> '.$lang_client_['pl_businesstype']['RETAILER_REQUEST_STATUS_IN_PROCESS'].'</strong><br/><br/>';
						echo '</div>';
						break;
						case '4':
						echo '<div class="well well-small"><strong class="lead">'.$lang_client_['pl_businesstype']['ACCOUNT_TITLE_RETAILER_STATUS'].'</strong><br/><br/>';
						echo '<strong class="text-error"><i class="icon-remove"></i> '.$lang_client_['pl_businesstype']['RETAILER_REQUEST_STATUS_DENIED'].'</strong><br/><br/>';
						echo '<strong class="text-error">'.$_SESSION['Cretailer_denied_message'].'</strong>';
						echo '</div>';
						break;
					}
				}
/*
			     if(plugin_exsists('businesstype') && get_client_business_bc()){
				  if($rs['retailer_request'] || $rs['retailer']){
				   echo '<div class="well well-small">';
					 echo '<strong class="lead">'.$lang_client_['pl_businesstype']['ACCOUNT_TITLE_RETAILER_STATUS'].'</strong><br/><br/>';
					 if($rs['retailer_request'] && $rs['retailer']){
						 echo '<strong class="text-success">'.$lang_client_['pl_businesstype']['RETAILER_REQUEST_SUCCESS'].'</strong><br/><br/>';
					 }else if($rs['retailer_request'] && !$rs['retailer']){
						 echo '<strong class="text-info">'.$lang_client_['pl_businesstype']['RETAILER_REQUEST_STATUS_IN_PROCESS'].'</strong><br/><br/>';
					 }
				   echo '</div>';
				  }else{
					echo '<span id="send_retailer_request_pop" class="btn btn-info">'.$lang_client_['pl_businesstype']['BTN_RETAILER_REQUEST'].'</span>';
				  }
				 }*/
			  ?>
          </nav>
         </aside>
        <div class="span9">

         <div id="step-change-data-account" class="account-part active<?php echo (!isset($_GET['type']) || $_GET['type'] == 'account_info' ? '' : ' hide'); ?>">
           <div class="unbordered alert alert-info squared alert-blok solid"><?php echo $lang_client_['client_account']['ALERT_INFO']; ?></div>
           <form id="form-change-your-data" method="post" action="<?php echo abs_client_path; ?>/account-actions.php" accept-charset="UTF-8">
           <input type="hidden" name="type-change[]" id="type-change-data" value="change_data" />
            <div class="row-fluid">
              <input type="text" class="required" name="name" id="name" value="<?php echo $rs['name']; ?>" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_NAME']; ?>*" />
              <?php echo $rs['is_company'] ? '' : '<input type="text" class="required" name="lastname" id="lastname" value="'.$rs['lastname'].'" data-array="12,4,'.$lang_client_['client_account']['FIELD_LABEL_LASTNAME'].'*" />'; ?>
            </div>
            <div class="row-fluid">
              <input type="text" class="required email" name="email" id="email" value="<?php echo $rs['email']; ?>" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_EMAIL']; ?>*" />
              <input type="text" class="required number" name="phone" id="phone" value="<?php echo $rs['phone']; ?>" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_PHONE']; ?>*" />
              <input type="text" class="number" name="fax" id="fax" value="<?php echo $rs['fax']; ?>" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_FAX']; ?>" />
            </div>
            <div class="row-fluid">
              <div class="span12">
                <input type="checkbox" id="change-password" data-icon="icon-ok icon-white" name="change-password" class="bootstyl" data-label-name="<?php echo $lang_client_['client_account']['FIELD_LABEL_CHANGE_PASSWORD']; ?>" data-additional-classes="btn-info" value="1" />
              </div>
            </div>
            <br/>
            <div class="change-password-container hide row-fluid">
              <div class="span12">
                  <div class="row-fluid">
                    <input type="password" class="required ignored" name="old-password" id="old-password" value="" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_CURRENT_PASSWORD']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="password" class="required ignored" name="password" id="password" value="" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_NEW_PASSWORD']; ?>*" />
                    <input type="password" class="required ignored" name="password2" id="password2" equalTo="#password" value="" data-array="12,4,<?php echo $lang_client_['client_account']['FIELD_LABEL_REPEAT_PASSWORD']; ?>*" />
                  </div>
              </div>
            </div>
            <span class="btn btn-info btn-large squared unbordered solid pull-right btn-save"><?php echo $lang_client_['general']['BUTTON_SAVE']; ?></span>
            <div class="clearfix"></div>
           </form>
         </div>
         <div id="step-change-address" class="account-part <?php echo (isset($_GET['type']) && $_GET['type'] == 'address' ? '' : ' hide'); ?>">
           <div class="unbordered alert alert-info squared alert-blok solid"><?php echo $lang_client_['client_account']['ALERT_ADDRESS']; ?></div>
           <form id="form-change-your-address" method="post" action="<?php echo abs_client_path; ?>/account-actions.php" accept-charset="UTF-8">
           <input type="hidden" name="type-change[]" id="type-change-address" value="change_address" />
            <div class="row-fluid">
              <input type="text" class="required" name="address" id="address" value="<?php echo $rs['address']; ?>" data-array="12,12,<?php echo $lang_client_['client_account']['FIELD_LABEL_ADDRESS']; ?>*" />
            </div>
            <div class="row-fluid">
              <input type="text" class="required number" name="zipcode" id="zipcode" value="<?php echo $rs['zipcode']; ?>" data-array="12,6,<?php echo $lang_client_['client_account']['FIELD_LABEL_ZIPCODE']; ?>*" />
              <input type="text" class="required" name="city" id="city" value=<?php echo $rs['city']; ?> data-array="12,6,<?php echo $lang_client_['client_account']['FIELD_LABEL_CITY']; ?>*" />
            </div>
            <span class="btn btn-info btn-large squared unbordered solid pull-right btn-save"><?php echo $lang_client_['general']['BUTTON_SAVE']; ?></span>
            <div class="clearfix"></div>
           </form>
         </div>
         <?php
     if(plugin_exsists('dgoods')){
     $download_rows = '';
       $sql_dw = execute('select '.$table_prefix.'products.*,
                  '.$table_prefix.'customers_downloads.id_order as dw_id_order,
                  '.$table_prefix.'customers_downloads.download_code as dw_download_code,
                  '.$table_prefix.'customers_downloads.file_name as dw_file_name,
                  '.$table_prefix.'customers_downloads.id_client as dw_id_client,
                  '.$table_prefix.'customers_downloads.available as dw_available
                  from '.$table_prefix.'products right join '
                  .$table_prefix.'customers_downloads on '
                  .$table_prefix.'products.pl_digital_code = '.$table_prefix.'customers_downloads.download_code
                  where ('.$table_prefix.'customers_downloads.id_client = '.$rs['id'].')
                  or '.$table_prefix.'products.pl_digital_code IS NULL order by '.$table_prefix.'customers_downloads.id desc');
      while ($rs_dw = mysql_fetch_array($sql_dw)){
        $img = ($rs_dw['url_image'] != '' ? path_abs_img_products.'/'.$rs_dw['id'].'/300x300/1_'.$rs_dw['url_image'] : theme_img_path.'/img_not_available.jpg');
      $download_rows .= '<tr>
                         <td style="text-align:left;width:70px;" data-title=""><img class="lazy" data-original="'.$img.'" src="'.$img.'" alt="'.str_replace('"',"'",$rs_dw['name']).'" /></td>
                         <td style="text-align:left;" data-title="'.$lang_client_['pl_dgoods']['TABLE_CONTENT_TITLE_FILE_NAME'].'"><strong>'.(!$rs_dw['pl_digital_code'] ? '<strong>'.$rs_dw['dw_file_name'].'</strong>' : '<a href="'.path_abs_products.'/'.$rs_dw['id'].'-'.($rs_dw['file_name'] == '' ? filesystem($rs_dw['name']) : $rs_dw['file_name']).'.php">'.$rs_dw['name'].'</a>').'</td>
                 <td style="text-align:left;" data-title="'.$lang_client_['pl_dgoods']['TABLE_CONTENT_TITLE_ACTIONS'].'">'.
                 (!$rs_dw['pl_digital_code'] ?
                 '<strong class="text-warning">'.$lang_client_['pl_dgoods']['WARNONG_DOWNLOAD_FILE_DELETED'].'</strong>'
                 :
                  ($rs_dw['pl_digital_not_available'] ?
                 '<strong class="text-warning">'.$lang_client_['pl_dgoods']['WARNONG_DOWNLOAD_FILE_NOT_AVAILABLE'].'</strong>' :
                 (!$rs_dw['dw_available'] ?
                  '<strong class="text-warning">'.$lang_client_['pl_dgoods']['NOTICE_UNPAID_ORDER_NO_DOWNLOAD_AVAILABLE'].'</strong>'
                  :
                     '<a href="'.abs_plugins_path.'/dgoods/download_purchase.php?cid='.$rs_dw['dw_id_client'].'&dcode='.$rs_dw['dw_download_code'].'" class="btn btn-info btn-medium">'.$lang_client_['pl_dgoods']['TABLE_CONTENT_DOWNLOAD_BUTTON'].'</a>')))
                 .'</td>
              </tr>';
      }
     ?>
         <div id="step-my-downloads" class="account-part <?php echo (isset($_GET['type']) && $_GET['type'] == 'downloads' ? '' : ' hide'); ?>">
           <div class="unbordered alert alert-info squared alert-blok solid"><?php echo $lang_client_['pl_dgoods']['ALERT_DOWNLOADS']; ?></div>
             <?php
        if($download_rows != ''){
       ?>
              <table class="table-striped table-condensed">
                  <tbody class="downloads-tbody-container">
                      <?php echo $download_rows; ?>
                  </tbody>
              </table>
             <?php
        }else{
        echo '<div class="alert alert-warning alert-block squared solid unbordered"><h4>'.$lang_client_['pl_dgoods']['ALERT_NO_DOWNLOADS'].'</h4></div>
                    <br/>
                    <a class="btn btn-info btn-large squared solid unbordered pull-right" href="'.abs_client_path.'">'.$lang_client_['general']['BUTTON_RETURN_TO_SHOPPING'].'</a>';
        }
       ?>
         </div>
         <?php
     }
     ?>
         <?php
		 $orders_rows = '';
		  $sql_or = execute('select * from '.$table_prefix.'orders where id_client = '.$rs['id'].' order by data desc');
		  while ($rs_or = mysql_fetch_array($sql_or)){
			$orders_rows .= '<tr>
			                   <td data-title="'.$lang_client_['client_account']['TABLE_CONTENT_TITLE_ORDER'].'">'.$rs_or['code_order'].'</td>
							   <td data-title="'.$lang_client_['client_account']['TABLE_CONTENT_TITLE_DATE'].'">'.view_date($rs_or['data']).'</td>
							   <td data-title="'.$lang_client_['client_account']['TABLE_CONTENT_TITLE_TOTAL'].'">'.num_formatt($rs_or['grandtotal']).'</td>
							   <td data-title="'.$lang_client_['client_account']['TABLE_CONTENT_TITLE_ORDER_STATUS'].'">'.($rs_or['processed'] ? 'Processed' : 'Working').'</td>
							   <td data-title="'.$lang_client_['client_account']['TABLE_CONTENT_TITLE_ACTIONS'].'">
							     <span style="margin-bottom:5px;" class="btn btn-info squared unbordered solid info-order" data-id="'.$rs_or['id'].'"><i class="icon-white icon-info-sign"></i></span>
								 '.(!$rs_or['payed'] && $paypal['status'] && $rs_or['payment_method'] == 'PAYPAL' ? ' <span data-id="'.$rs_or['id'].'" style="margin-bottom:5px;" class="pay-btn btn btn-info squared unbordered solid">'.$lang_client_['client_account']['BUTTON_PAY_NOW'].'</span>' : '' ).'
							   </td>
							</tr>';
		  }
		 ?>
         <div id="step-my-orders" class="account-part <?php echo (isset($_GET['type']) && $_GET['type'] == 'orders' ? '' : ' hide'); ?>">
           <div class="unbordered alert alert-info squared alert-blok solid"><?php echo $lang_client_['client_account']['ALERT_ORDERS']; ?></div>
             <?php
			  if($orders_rows != ''){
			 ?>
              <table class="table-striped table-condensed">
                  <thead>
                      <tr>
                          <th><?php echo $lang_client_['client_account']['TABLE_CONTENT_TITLE_ORDER']; ?></th>
                          <th><?php echo $lang_client_['client_account']['TABLE_CONTENT_TITLE_DATE']; ?></th>
                          <th class="numeric"><?php echo $lang_client_['client_account']['TABLE_CONTENT_TITLE_TOTAL']; ?></th>
                          <th><?php echo $lang_client_['client_account']['TABLE_CONTENT_TITLE_ORDER_STATUS']; ?></th>
                          <th><?php echo $lang_client_['client_account']['TABLE_CONTENT_TITLE_ACTIONS']; ?></th>
                      </tr>
                  </thead>
                  <tbody class="product-tbody-container">
                      <?php echo $orders_rows; ?>
                  </tbody>
              </table>
             <?php
			  }else{
				echo '<div class="alert alert-warning alert-block squared solid unbordered"><h4>'.$lang_client_['client_account']['ALERT_NO_ORDERS'].'</h4></div>
                    <br/>
                    <a class="btn btn-info btn-large squared solid unbordered pull-right" href="'.abs_client_path.'">'.$lang_client_['general']['BUTTON_RETURN_TO_SHOPPING'].'</a>';
			  }
			 ?>
         </div>

       </div>
     </section><!-- /BODY ROW -->
	<?php
	 }else{
       require_once('include/registration-form.php');
	 }
	?>
   </section> <!-- /CONTAINER -->
	<?php
     require_once('include/footer.php');
    ?>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.stepize.js"></script>
    <script type="text/javascript">
	 $(function(){
		    $('#registration-form').StepizeForm({
			   Steps_Count : '#count_step',
			   Text_Submit:'<i class="icon-white icon-plus"></i> <?php echo $lang_client_['general']['BUTTON_SIGN_UP']; ?>',
			   Text_Next: '<?php echo $lang_client_['general']['STEPPIZED_FORM_NEXT_BUTTON']; ?>',
			   Text_Prev: '<?php echo $lang_client_['general']['STEPPIZED_FORM_PREV_BUTTON']; ?>',
			   Selector_Buttons:'#form-btn',
			   Class_Prev:'btn btn-info squared unbordered solid',
			   Class_Next:'btn btn-info squared unbordered solid',
			   Class_Submit:'btn btn-info squared unbordered solid'
			});
		$(window).resize(function(){
			$('#registration-form').StepizeForm('Destroy');
		    $('#registration-form').StepizeForm({
			   Steps_Count : '#count_step',
			   Text_Submit:'<i class="icon-white icon-plus"></i> <?php echo $lang_client_['general']['BUTTON_SIGN_UP']; ?>',
			   Text_Next: '<?php echo $lang_client_['general']['STEPPIZED_FORM_NEXT_BUTTON']; ?>',
			   Text_Prev: '<?php echo $lang_client_['general']['STEPPIZED_FORM_PREV_BUTTON']; ?>',
			   Selector_Buttons:'#form-btn',
			   Class_Prev:'btn btn-info squared unbordered solid',
			   Class_Next:'btn btn-info squared unbordered solid',
			   Class_Submit:'btn btn-info squared unbordered solid'
			});
		});
	 });
	</script>
  </body>
</html>