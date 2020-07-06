<div class="scroll_body_container"><!-- CONTAINER FOR NICESCROLL PLUGIN (FIX CHROME SCROLLING PROBLEM) this will be closed into footer.php file -->
<section class="container-fluid" id="top-bar"> <!-- CONTAINER -->
  <div class="container-semifluid" > 
    <div class="row-fluid">
         <div class="span7">
           <div id="top-login-form-container">
              <form method="post" action="check.php" accept-charset="UTF-8" id="top-login-form">
                     <div class="control-group pull-left">
                       <div class="controls">
                         <div class="input-prepend">
                          <span class="add-on"><i class="icon-user"></i></span>
                          <input type="text" name="useridLog" id="top-useridLog" class="required" placeholder="UserID" value="" />   
                         </div> 
                       </div>                       
                     </div>
                     <div class="control-group pull-left">
                       <div class="controls">   
                         <div class="input-prepend">                          
                          <span class="add-on"><i class="icon icon-black icon-key"></i></span>
                          <input type="password" name="passwordLog" id="top-passwordLog" class="required" placeholder="Password" value="" />
                        </div>
                      </div>                                           
                    </div>  
                    <span class="btn btn-info unbordered solid squared" id="top-btn-login"><i class="icon icon-white icon-unlocked"></i> <?php echo $lang_client_['general']['TEXT_LOGIN']; ?></span> 
                    <div class="clearfix"></div>                                                                                                   
              </form> 
              <a href="#" class="retrieve-data"><?php echo $lang_client_['general']['TEXT_DATA_FORGOTTEN']; ?></a> | <a href="<?php echo abs_client_path ?>/register.php"><?php echo $lang_client_['general']['TEXT_SIGN_UP']; ?></a>
           </div>
           <?php
		    if(!isset($_SESSION['Clogged'])){
		   ?>
              <div class="welcome-message"><?php echo str_replace('{shop_name}',$shop_title,$lang_client_['header']['WELCOME_MESSAGE_OFFLINE']); ?> <a href="#" id="btn-login-link"><?php echo $lang_client_['general']['TEXT_LOGIN']; ?></a> | <a href="<?php echo abs_client_path ?>/register.php"><?php echo $lang_client_['general']['TEXT_SIGN_UP']; ?></a></div>
           <?php
			}else{
		   ?>
              <div class="welcome-message"><?php echo str_replace('{client_name}',ucwords($_SESSION['Cname'].' '.$_SESSION['Clastname']),$lang_client_['header']['WELCOME_MESSAGE_ONLINE']); ?>&nbsp;|&nbsp;<a href="<?php echo abs_client_path ?>/log-out.php"><i class="icon-off"></i> <?php echo $lang_client_['general']['TEXT_LOG_OUT']; ?></a></div>              
           <?php
			}
		   ?>
       </div> 
       <div class="span1 text-right">  
      	 <?php 
           require_once(theme_rel_path.'/include/language_select.php'); 
         ?> 
       </div>       
       <div class="span4">
		 <?php 
           if(basename(selfURL()) !== 'cart.php' && basename(selfURL()) !== 'check-out.php') require_once('top-cart.php'); 
         ?>        
       </div>                     
    </div>
  </div> 
</section> 
<header> 
 <section class="container-semifluid" id="header-container"> <!-- CONTAINER -->  
  <div class="row-fluid"> <!-- ROW --> 
    <div class="span4 logo-container">
      <a href="<?php echo abs_client_path ?>"><img src="<?php echo abs_uploads_path ?>/bc_logo.png" alt="<?php echo $shop_url; ?>" /></a>
    </div>
    <div class="span3 text-right">
      <?php 
	    if(basename(selfURL()) !== 'search.php'){
	  ?>
        <form id="search-form" class="form-search form-horizontal" action="<?php echo abs_client_path; ?>/search.php" method="get">
            <div class="input-append span12">
                <input type="text" name="sp" class="search-query" placeholder="<?php echo $lang_client_['search']['FIELD_LABEL_SEARCH']; ?>...">
                <button type="submit"><i class="icon-search"></i></button>
            </div>
        </form> 
      <?php
		}
	  ?>
    </div>
    <div class="span5 main-menu-container text-right">
      <a href="<?php echo abs_client_path ?>/account.php"><i class="icon-user"></i> <?php echo $lang_client_['general']['TEXT_YOUR_ACCOUNT']; ?></a>
      <a href="<?php echo abs_client_path ?>/cart.php"><i class="icon-shopping-cart"></i> <?php echo $lang_client_['general']['TEXT_SHOPPING_CART']; ?></a>
      <a href="<?php echo abs_client_path ?>/check-out.php"><i class="icon-ok-circle"></i> <?php echo $lang_client_['general']['BUTTON_CHECKOUT']; ?></a> 
      <a href="<?php echo abs_client_path ?>/contacts.php"><i class="icon icon-black icon-contacts"></i> <?php echo $lang_client_['general']['TEXT_CONTACT_US']; ?></a>      
    </div> 
  </div> <!-- /ROW -->  
 </section>    
</header> 
<div class="clearfix"></div>