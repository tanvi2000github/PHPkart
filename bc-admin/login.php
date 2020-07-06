<?php
session_start();
if(IsSet($_SESSION['Alogged'])){
header('location:index.php'); 
}
require_once('include/inc_load.php');
require_once(rel_client_path.'/include/inc_params.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="user-scalable=no,initial-scale=1.0, maximum-scale=1.0 width=device-width" />
    <!-- Styles -->
    <?php require_once(rel_client_path.'/include/inc_css_base.php'); ?> 
    <link href="<?php echo abs_admin_path ?>/css/admin.css" rel="stylesheet">
    <style>
      body {
		height:100%;
      }
	  html{
		height:100%;  
	  }
    </style>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo path_img_front ?>/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo path_img_front ?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo path_img_front ?>/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo path_img_front ?>/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo path_img_front ?>/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

<div class="container-fluid" style="height:100%;"> <!-- CONTAINER -->
   <div class="text-left center-div">
    <!-- LOGIN form --> 
    <form id="form_signin" action="<?php echo abs_admin_path ?>/check.php" method="post">
         <div class="controllo_errori"></div> 
         <div class="control-group">
           <div class="controls">
             <div class="input-prepend">
              <span class="add-on"><i class="icon-user"></i></span>
              <input type="text" name="useridLog" id="useridLog" class="span3 required" placeholder="UserID" value="" />   
             </div> 
           </div>
         </div>
         <div class="control-group">
           <div class="controls">   
             <div class="input-prepend">                          
              <span class="add-on"><i class="icon icon-black icon-key"></i></span>
              <input type="password" name="passwordLog" id="passwordLog" class="span3 required" placeholder="Password" value="" />
            </div>
          </div>                      
        </div>                                                
       <button type="submit" class="btn submit_login"><i class="icon icon-black icon-unlocked"></i> Login</button>
    </form> 
    <!-- /LOGIN form -->  
   </div>                           
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
	  /********************* MISCELLANEUS *********************/
		 $('.center-div').css('width','90%').Vcenter($('.container-fluid'));
	  /********************* LOGIN ****************************/	
	     $('#form_signin').find('input:first').focus();
	     $('#form_signin').validate(); 	
         $('.submit_login').click(function(e){
          e.preventDefault();
          $('#form_signin').submit();
         });		   
         $('#form_signin').ajaxForm({
          type:'POST',
          beforeSubmit:function(){
           return $('#form_signin').validate().form();           
          },
          beforeSerialize:function(){                     
           $('#form_signin input').prop('readonly',true).click(function(e){
            e.preventDefault();
           }).dblclick(function(e){
			e.preventDefault(); 
		   });
           $('.submit_login')
		    .append('<img class="loader" src="<?php echo path_img_back?>/ajax-loader.gif" alt="" style="margin-left:10px;vertical-align:middle"/>')
			.addClass('disabled');
			$('#btn_registrati').addClass('disabled');    
          }, 
          url:'<?php echo abs_admin_path?>/check.php',
          dataType:'html',
          data:$('#form_signin').serialize(),
          complete:function(){
		   setTimeout(function(){
			   $('.submit_login,#btn_registrati').removeClass('disabled');
			   $('.loader').remove();  
				$('#form_signin input').prop('readonly',false).unbind('click');
		   },1500);
          },
          success:function(data){  
            if(data == 'logged'){   
			  setTimeout(function(){  
                window.open('<?php echo abs_admin_path?>/index.php', '_self', null);       
			  },1500);
            }else{   
					$('.controllo_errori').slideUp('slow',function(){
					$('.controllo_errori').html('<div class="alert alert-error alert-block fade in">\
					  <button type="button" class="close" data-dismiss="alert">x</button>'+__('LOGIN_ERROR_CREDENTIALS')+'</div>').slideDown('slow');  
					});  				 
				$('#passwordLog').val('');
				$('input:first').focus();			  
            }
          }         
         });
	  /********************************************************/		
	 });
	</script>
  </body>
</html>
