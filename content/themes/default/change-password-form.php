<?php 
 if(isset($_SESSION['Clogged'])) header('location:'.abs_client_path); 
 $page_title = $lang_client_['change_password_form']['PAGE_TITLE'];  
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
      <div class="box-header">
		<span class="header-text"><i class="icon icon-black icon-key"></i> <?php echo $page_title; ?></span>   
      </div>      
     <section class="row-fluid change-password-page"><!-- BODY ROW -->               
	    <section class="span12" style="padding:20px 20px;border:1px solid #ccc;border-radius:6px;margin-bottom:10px;">
          <?php
		   $password_client = '';
		   $email_client = '';
		   if(!isset($_GET['pas']) || !isset($_GET['em'])){
             echo '<div class="alert alert-error">'.$lang_client_['change_password_form']['ALERT_VIOLATION'].'</div>';
		   }else{
			 $sql = execute('select password,email from '.$table_prefix.'clients');
			 while($rs = mysql_fetch_array($sql)){
				if(mb_substr(encryption($rs['password']),0,15) == $_GET['pas'] && mb_substr(encryption($rs['email']),0,15) == $_GET['em']){
					$find_client = true;
					$password_client = $rs['password'];
					$email_client = $rs['email'];
				}
			 }
			 if(!isset($find_client)){
               echo '<div class="alert alert-error">'.$lang_client_['change_password_form']['ALERT_VIOLATION'].'</div>';
			 }else{
		 ?>
         <div class="login-container-form hide">
           <h4><?php echo $lang_client_['general']['TEXT_SIGN_UP']; ?></h4> 
           <div class="alert alert-success alert-block fade in"><?php echo $lang_client_['change_password_form']['ALERT_PASSWORD_CHANGE_SUCCESFULLY']; ?></div>
           <div id="result-login" class="hide"></div>       
           <form method="post" action="<?php echo abs_client_path; ?>/check.php" accept-charset="UTF-8" id="login-form">    		  
                     <div class="control-group">
                       <div class="controls">
                         <div class="input-prepend">
                          <span class="add-on"><i class="icon-user"></i></span>
                          <input type="text" name="useridLog" id="useridLog" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_USERID']; ?>" value="" />   
                         </div> 
                       </div>                       
                     </div>
                     <div class="control-group">
                       <div class="controls">   
                         <div class="input-prepend">                          
                          <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                          <input type="password" name="passwordLog" id="passwordLog" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_PASSWORD']; ?>" value="" />
                        </div>
                      </div>                                           
                    </div>   
                    <span class="btn btn-info unbordered solid squared" id="btn-login"><i class="icon icon-white icon-unlocked"></i> <?php echo $lang_client_['general']['TEXT_LOGIN']; ?></span>
                    <div class="clearfix"></div>              
           </form>         
         </div>
         <div class="retrieve-password-form-container">
           <h4><?php echo $lang_client_['change_password_form']['TEXT_ENTER_NEW_PASSWORD']; ?></h4>                  
           <form method="post" action="<?php echo abs_client_path; ?>/change-password.php" accept-charset="UTF-8" id="retrieved-password-form">
                     <input type="hidden" name="old_password" id="old_password" value="<?php echo $password_client; ?>" />
                     <input type="hidden" name="email-for-retrive" id="email-for-retrive" value="<?php echo $email_client; ?>" />
                     <div class="control-group">
                       <div class="controls">
                        <label for="password-retrieved"><?php echo $lang_client_['general']['TEXT_PASSWORD']; ?></label>
                         <div class="input-prepend">
                          <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                          <input type="text" name="password-retrieved" id="password-retrieved" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_PASSWORD']; ?>" value="" />   
                         </div> 
                       </div>                       
                     </div>
                     <div class="control-group">
                       <div class="controls">  
                         <label for="password-retrieved2"><?php echo $lang_client_['general']['TEXT_REPEAT_PASSWORD']; ?></label> 
                         <div class="input-prepend">                          
                          <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                          <input type="password" name="password-retrieved2" id="password-retrieved2" equalTo="#password-retrieved" class="required" placeholder="<?php echo $lang_client_['general']['TEXT_REPEAT_PASSWORD']; ?>" value="" />
                        </div>
                      </div>                                           
                    </div>   
                    <span class="btn btn-info unbordered solid squared" id="btn-retrieve-password"><i class="icon-edit icon-white"></i> <?php echo $lang_client_['general']['BUTTON_SAVE']; ?></span>
                    <div class="clearfix"></div>              
           </form>         
         <?php 
			 }
		   }
		  ?>
         </div>
       </section>        
               
     </section><!-- /BODY ROW -->
   </section> <!-- /CONTAINER -->
	<?php 
     require_once('include/footer.php');
    ?>     
  </body>
</html>