<?php
 $page_title = $lang_client_['contacts']['PAGE_TITLE'];
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
     <section class="row-fluid contacts-page"><!-- BODY ROW -->
	    <div class="span12">
          <div class="row-fluid">
            <div class="span12 map-container">
              <div class="map"></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="span6">
            <br/>
              <form method="post" action="<?php echo abs_client_path; ?>/send-email-contact.php" accept-charset="UTF-8" id="contacts-form">
                  <div class="row-fluid">
                    <input type="text" class="required" name="name" id="name" value="" data-array="12,6,<?php echo $lang_client_['contacts']['FIELD_LABEL_NAME']; ?>*" />
                    <input type="text" name="lastname" id="lastname" value="" data-array="12,6,<?php echo $lang_client_['contacts']['FIELD_LABEL_LASTNAME']; ?>" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required number" name="phone" id="phone" value="" data-array="12,12,<?php echo $lang_client_['contacts']['FIELD_LABEL_PHONE']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required email" name="email" id="email" value="" data-array="12,12,<?php echo $lang_client_['contacts']['FIELD_LABEL_EMAIL']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <textarea name="message" id="message" class="required squared" rows="10" data-array="12,12,<?php echo $lang_client_['contacts']['FIELD_LABEL_MESSAGE']; ?>*"></textarea>
                  </div>
                  <div class="row-fluid">
                    <div class="span6">
                       <label id="reload-captcha" class="btn btn-link btn-small"><i class="icon-repeat"></i> <?php echo $lang_client_['contacts']['RELOAD_CAPTCHA']; ?></label>
                       <img src="<?php echo abs_client_path; ?>/include/lib/cool-php-captcha/captcha.php" id="captcha_image" style="width:100%;height:50px;" />
                    </div>
                    <input type="text" class="required" name="captcha" id="captcha" value="" data-array="12,6,<?php echo $lang_client_['contacts']['ENTER_CAPTCHA_CODE']; ?>*" />
                  </div>
                  <strong class="text-info pull-right"><small><?php echo $lang_client_['contacts']['NOTICE_FIELDS_MANDATORY']; ?></small></strong>
                  <div class="clearfix"></div>
                  <div class="row-fluid">
                    <span id="btn-send-contact-form" class="span12 btn btn-info btn-large btn-blok squared solid unbordered"><i class="icon-white icon-envelope"></i> <?php echo $lang_client_['contacts']['SEND_MESSAGE_BUTTON']; ?></span>
                  </div>
              </form>
            </div>
            <div class="span6">
            <br/>
             <label for="where"><?php echo $lang_client_['contacts']['MAP_LABEL_WHERE_ARE_YOU']; ?></label>
              <input type="text" id="where" name="where" class="input-xlarge" value="" style="height:27px;padding-top: 10px;font-size: 15px;font-weight: bold;-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;" /><span id="calcRoute" style="margin-top:-10px;" class="btn btn-info btn-large squared solid unbordered"><i class="icon-white icon-map-marker"></i> <?php echo $lang_client_['contacts']['CALCULATE_ROUTE_BUTTON']; ?></span>
               <br/>
              <div id="panel"></div>
            </div>
          </div>
       </div>
     </section><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php
     require_once('include/footer.php');
    ?>
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?<?php echo isset($gmap_key) && trim($gmap_key) != '' ? 'key='.$gmap_key.'&' : '' ?>sensor=true&language=<?php echo $B_language; ?>&libraries=places"></script>
    <script type="text/javascript" src="<?php echo theme_js_path ?>/map_contact_form.js"></script>
    <script type="text/javascript">
      $(function(){
		//* language-part *//
        $('.map').JQMap({
          FromField:'#where',
          CalculateButton:'#calcRoute',
          Destination:'<?php echo $company_address.', '.$company_zipcode.' '.$company_city; ?>',
          TexInfoWindow:'<div class="text-center"><img src="<?php echo abs_uploads_path ?>/bc_logo.png" width="140px" /></div>\
		     <address>\
                <?php echo $company_address; ?><br/>\
                <?php echo $company_zipcode.' '.$company_city; ?><br/>\
                <abbr title="Phone">T:</abbr> <?php echo $company_phone; ?><br/>\
                <abbr title="E-mail">@:</abbr> <?php echo $company_email; ?>',
          RoutePanel :'#panel',
          DistanceContainer:'#calc_distance',
		  container_geolocation : '#where',
		  scrollwheel : false,
          ZoomStartPoint : 17
        });
        $('#calcRoute').click(function(){
         if($('#where').val() != '')
         $('#panel').slideDown('slow');
        });
      });
    </script>
  </body>
</html>