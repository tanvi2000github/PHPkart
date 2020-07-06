<footer>
  <div class="container-fluid" id="footer">
   <div class="container-semifluid">
      <div class="row-fluid">
        <div class="span6">
          <img src="<?php echo abs_uploads_path ?>/bc_logo_footer.png" alt="<?php echo $shop_url; ?>" width="30%" />
          <p style="text-align:justify">
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
          </p>
         <div class="clearfix"></div>
        </div>
        <div class="span3">
          <div class="box-header">
            <span class="header-text" style="white-space:nowrap;"><?php echo $lang_client_['general']['TEXT_YOUR_ACCOUNT']; ?></span>
          </div>
         <ul class="menu-vertical-indicator">
           <li><a href="<?php echo abs_client_path; ?>/account.php"><?php echo $lang_client_['footer']['TEXT_LINK_GENERAL_INFO']; ?></a></li>
           <li><a href="<?php echo abs_client_path; ?>/account.php?type=address"><?php echo $lang_client_['footer']['TEXT_LINK_ADDRESS']; ?></a></li>
           <li><a href="<?php echo abs_client_path; ?>/account.php?type=orders"><?php echo $lang_client_['footer']['TEXT_LINK_ORDERS']; ?></a></li>
           <li><a href="<?php echo abs_client_path; ?>/contacts.php"><?php echo $lang_client_['general']['TEXT_CONTACT_US']; ?></a></li>
         </ul>
         <div class="clearfix"></div>
        </div>
        <div class="span3">
          <div class="box-header">
            <span class="header-text" style="white-space:nowrap;"><?php echo $lang_client_['general']['TEXT_CONTACT_US']; ?></span>
          </div>
         <ul>
           <li><img src="<?php echo theme_img_path; ?>/footer-phone.png" /><?php echo $company_phone; ?></li>
           <li><img src="<?php echo theme_img_path; ?>/footer-email.png" /> <?php echo $company_email; ?></li>
           <li><img src="<?php echo theme_img_path; ?>/footer-marker.png" /> <?php echo $company_address.', '.$company_zipcode.' '.$company_city; ?></li>
         </ul>
         <div class="clearfix"></div>
        </div>

      </div>
      <div class="row-fluid">
        <div class="span12 text-center" style="border-top:1px solid #ccc;line-height:50px;">
          <span class="pull-left"><?php echo $company_name; ?> - <?php echo $company_taxcode; ?></span>
          <span class="pull-right"><?php echo str_replace('{shop_name}',$shop_title,str_replace('{date}',date("Y"),$lang_client_['footer']['COPYRIGHT'])); ?></span>
          <div class="clearfix"></div>
        </div>
      </div>
   </div>
  </div>
</footer>
<!-- retrieve user's data form -->
<div id="retrieve-data-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="retrieve-data-modallabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><li class="icon-remove"></li></button>
    <span id="retrieve-data-modallabel"><strong class="label label-info"><?php echo $lang_client_['footer']['TEXT_RETRIEVE_DATA_TITLE']; ?></strong></span>
  </div>
  <div class="modal-body">
     <div id="retrieve-result" class="hide"></div>
     <form method="post" action="<?php echo abs_client_path; ?>/retrieve-data.php" accept-charset="UTF-8" id="retrieve-data-form">
         <div class="control-group">
           <div class="controls">
            <label for="userid-retrieve"><?php echo $lang_client_['footer']['TEXT_USERID']; ?></label>
             <div class="input-prepend">
              <span class="add-on"><i class="icon-user"></i></span>
              <input type="text" name="userid_retrieve" id="userid-retrieve" class="leastoneinput" placeholder="<?php echo $lang_client_['footer']['TEXT_USERID']; ?>" value="" />
             </div>
           </div>
         </div>
         <div class="control-group">
           <div class="controls">
             <label for="email-retrieve"><?php echo $lang_client_['footer']['TEXT_EMAIL']; ?></label>
             <div class="input-prepend">
              <span class="add-on"><i class="icon-envelope"></i></span>
              <input type="text" name="email_retrieve" id="email-retrieve" class="leastoneinput email" placeholder="<?php echo $lang_client_['footer']['TEXT_EMAIL']; ?>" value="" />
            </div>
          </div>
        </div>
        <span class="btn btn-info unbordered solid squared pull-right" id="btn-retrieve-data"><i class="icon-white icon-wrench"></i> <?php echo $lang_client_['footer']['BUTTON_RETRIEVE_DATA']; ?></span>
        <div class="clearfix"></div>
       </form>
  </div>
</div>
<!-- / retrieve user's data form -->
<a id="go-to-top"></a>
</div><!-- CLOSE CONTAINER FOR NICESCROLL PLUGIN (FIX CHROME SCROLLING PROBLEM), div opened into body-header.php file -->
    <!-- ========================== Javascript ======================== -->
	<?php
	echo html_entity_decode(str_replace('<br>','',str_replace('<br/>','',str_replace('&#39;',"'",$google_analytics))));
	/**** detect IE version for responsive menu ****/
    if(preg_match('/(?i)msie [2-8]/',$_SERVER['HTTP_USER_AGENT'])) {
       $mediaquery = false;
     } else {
       $mediaquery = true;
     }
    ?>
    <?php require_once(rel_client_path.'/include/inc_js_base_client.php'); ?>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/jsin.1.2.min.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/lang/<?php echo languageCli.'/'.languageCli; ?>.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/validate.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/ajaxForm.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/livequery.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/center-div.js"></script>
    <script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/loader.js"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.nicescroll.js"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.bootstrap.generalalert.js"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/restyle-checkbox.js"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/restyle-radio.js"></script>
      <!-- lightbox plugin -->
		<script type="text/javascript" src="<?php echo theme_js_path ?>/lightbox/jquery.lightbox.min.js"></script>
      <!-- carousel plugin -->
        <script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.carouFredSel-6.2.1.js"></script>
		<script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.touchSwipe.min.js"></script>
		<script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.transit.min.js"></script>
		<script type="text/javascript" src="<?php echo theme_js_path ?>/jquery.ba-throttle-debounce.min.js"></script>
      <!-- /carousel plugin -->
    <?php
    if(plugin_exsists('businesstype')){
  ?>
       <script type="text/javascript" src="<?php echo abs_plugins_path ?>/businesstype/lang/<?php echo languageCli.'/'.languageCli; ?>.js"></script>
       <script type="text/javascript" src="<?php echo abs_plugins_path ?>/businesstype/b2b.js"></script>
    <?php
    }
  ?>
	<script type="text/javascript">
    $(function(){
	  /*$("html").niceScroll({cursorcolor:'#3A87AD',cursorborder:'1px solid #fff'});*/
	  $('body').data('theme_path','<?php echo theme_abs_path; ?>');
	  $('body').data('theme_img_path','<?php echo theme_img_path; ?>');
	  $('body').data('abs_client_path','<?php echo abs_client_path; ?>');
	  $('body').data('thousands_separator','<?php echo $thousands_separator; ?>');
	  $('body').data('decimals_separator','<?php echo $decimal_separator; ?>');
	  <?php
	    if($mediaquery){
	  ?>
	      $("html").niceScroll({cursorcolor:'#3A87AD',cursorborder:'0px solid #fff',cursorwidth:8});
		  $('.responsiveHead').on('click', function(e) {
			  e.preventDefault();
			  if(!$(this).next('.responsiveMenu').is(':visible')){
				  $(this).next('.responsiveMenu').slideDown('fast');
			  }else{
				  $(this).next('.responsiveMenu').slideUp('fast');
			  }
		  });
	  <?php
		}
	  ?>
    });
    </script>
    <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo theme_js_path ?>/ie_hacks.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo theme_js_path ?>/main_script.js"></script>
<?php @mysql_close(conn); // close connection to DB ?>