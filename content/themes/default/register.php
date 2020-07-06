<?php
 if(isset($_SESSION['Clogged'])) header('location:'.abs_client_path);
 $page_title = $lang_client_['client_registration']['PAGE_TITLE_REGISTRATION'];
 require_once('include/header.php');
?>
 <body>
  <?php require_once('include/body-header.php'); ?>
   <section class="container-semifluid" id="main-container"> <!-- CONTAINER -->
   <?php require_once('include/horizontal-categories.php');?>
     <section class="row-fluid"><!-- BODY breadcrumb -->
        <ul class="breadcrumb">
          <li><a href="<?php echo abs_client_path ?>"><?php echo $lang_client_['general']['HOME_TEXT']; ?></a> <span class="divider">/</span></li>
          <li class="active"><?php echo !isset($_GET['cod']) ? $lang_client_['client_registration']['PAGE_TITLE_REGISTRATION'] : $lang_client_['client_registration']['PAGE_TITLE_CONFIRMATION']; ?></li>
        </ul>
     </section><!-- / BODY breadcrumb -->
      <div class="box-header">
          <?php
		   if(!isset($_GET['cod'])){
			   echo '<span class="header-text"><i class="icon icon-black icon-locked"></i> '.$lang_client_['general']['TEXT_LOGIN'].'</span>';
		   }else{
			   echo '<span class="header-text"><i class="icon icon-black icon-unlocked"></i>&nbsp;'.$lang_client_['client_registration']['CONFIRM_ACCOUNT_TEXT'].'</span>';
		   }
		  ?>
      </div>
        <?php require_once('include/registration-form.php'); ?>
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
	 });
	</script>
  </body>
</html>