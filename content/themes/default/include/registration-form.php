     <section class="row-fluid registration-page"><!-- BODY ROW -->               
	    <section class="span6 login-container">
          <?php
		  /*** code for registration last step ***/ 
		   if(!isset($_GET['cod'])){
             echo '<h3>'.$lang_client_['client_registration']['TEXT_REGISTERED_CUSTOMERS'].'</h3>';
		   }else{
			 $sql = execute('select id,userid,email,name,enabled from '.$table_prefix.'clients');
			 while($rs = mysql_fetch_array($sql)){
				if(mb_substr(encryption($rs['id'].$rs['userid'].$rs['email'].$rs['name']),0,15) == $_GET['cod']){
					$find_client = true;
				   if($rs['enabled']){
					echo '<div class="alert alert-warning">'.$lang_client_['client_registration']['ALERT_ACCOUNT_ALREADY_CONFIRMED'].'</div>';					   
				   }else{
					execute('update '.$table_prefix.'clients set enabled = 1 where id = '.$rs['id']);
					echo '<div class="alert alert-success">'.$lang_client_['client_registration']['ALERT_VALIDATED_SUCCESSFULLY'].'</div>';
				   }
				}
			 }
			 if(!isset($find_client)){
               echo '<div class="alert alert-error">'.$lang_client_['client_registration']['ALERT_DETECTED_VIOLATION'].'</div>';
			 }
		   }
		  ?>
           <h4><?php echo $lang_client_['client_registration']['TEXT_EITHER_LOG']; ?></h4> 
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
                    <span class="btn btn-info unbordered solid squared" id="btn-login"><i class="icon icon-white icon-unlocked"></i> <?php echo $lang_client_['general']['TEXT_LOGIN']; ?></span>&nbsp;&nbsp; <a href="#" class="retrieve-data"><?php echo $lang_client_['general']['TEXT_DATA_FORGOTTEN']; ?></a>
                    <div class="clearfix"></div>              
           </form>
       </section>

        <section class="span6 registration-container"> 
           <h3><?php echo $lang_client_['client_registration']['TEXT_NEW_COSTUMER']; ?></h3>
           <h4><?php echo $lang_client_['general']['TEXT_SIGN_UP']; ?></h4>
           <?php echo $lang_client_['client_registration']['NOTICE_TO_NEW_COSTUMER']; ?>                      
           <br/><br/>
           <h4 class="default-status-registration-form"><?php echo $lang_client_['client_registration']['TEXT_FILL_IN_THE_FORM']; ?> <span id="count_step" class="badge badge-info"></span></h4>
           <div id="result-registration" class="alert alert-success">
		      <?php 
			  if(isset($registration_type)){
				 switch($registration_type){
					case 0:
					  echo $lang_client_['client_registration']['ALERT_FIRST_PHASE_REGISTRATION_COMPLETED'];
					break;
					case 1:
					  echo $lang_client_['client_registration']['ALERT_IMMEDIATE_REGISTRATION_COMPLETED'];
					break;
					case 2:
					  echo $lang_client_['client_registration']['ALERT_REGISTRATION_BY_ADMIN'];
					break; 
				 }
			  }else{
			    echo $lang_client_['client_registration']['ALERT_FIRST_PHASE_REGISTRATION_COMPLETED']; 
			  }
			  ?>
           </div>
          <div style="padding:15px;border:1px solid #fff;border-radius:6px;background:#e5e5e5" class="default-status-registration-form">
           <form method="post" action="<?php echo abs_client_path; ?>/registration.php" accept-charset="UTF-8" id="registration-form">
             <div id="fstep_1">            
                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                    <input type="radio" id="private" name="is_company" data-label-name="<?php echo $lang_client_['client_registration']['FIELD_LABEL_PRIVATE_TYPE']; ?>" data-additional-classes="btn-info squared unbordered solid" value="private" checked />  
                    <input type="radio" id="company" name="is_company" data-label-name="<?php echo $lang_client_['client_registration']['FIELD_LABEL_COMPANY_TYPE']; ?>" data-additional-classes="btn-info squared unbordered solid" value="company" />
                  </div>             
                  <div class="row-fluid">  
                    <input type="text" class="required" name="name" id="name" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_NAME']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="lastname" id="lastname" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_LASTNAME']; ?>*" /> 
                  </div> 
                  <div class="row-fluid hidden">
                    <input type="text" class="required" name="tax_code" id="tax_code" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_TAX_CODE']; ?>*" /> 
                  </div>                              
                  <div class="row-fluid">  
                    <input type="text" class="required email" name="email" id="email" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_EMAIL']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="phone" id="phone" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_PHONE']; ?>*" /> 
                  </div>
                  <div class="row-fluid">
                    <input type="text" name="fax" id="fax" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_FAX']; ?>" /> 
                  </div>
             </div> 
             <div id="fstep_2">
                  <div class="row-fluid">  
                    <input type="text" class="required" name="address" id="address" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_ADDRESS']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="zipcode" id="zipcode" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_ZIPCODE']; ?>*" /> 
                  </div>
                  <div class="row-fluid">
                    <input type="text" class="required" name="city" id="city" value="" data-array="12,12,<?php echo $lang_client_['client_registration']['FIELD_LABEL_CITY']; ?>*" /> 
                  </div> 
             </div> 
             <div id="fstep_3">
                  <div class="row-fluid">  
                    <input type="text" class="required" name="userid" id="userid" value="" data-array="12,12,<?php echo $lang_client_['general']['TEXT_USERID']; ?>*" />
                  </div>
                  <div class="row-fluid">
                    <input type="password" class="required" name="password" id="password" value="" data-array="12,12,<?php echo $lang_client_['general']['TEXT_PASSWORD']; ?>*" /> 
                  </div>
                  <div class="row-fluid">
                    <input type="password" class="required" name="password2" id="password2" equalTo="#password" value="" data-array="12,12,<?php echo $lang_client_['general']['TEXT_REPEAT_PASSWORD']; ?>*" /> 
                  </div> 
                  <div class="row-fluid">
                    <div class="span6">  
                       <label id="reload-captcha" class="btn btn-link btn-small"><i class="icon-repeat"></i> <?php echo $lang_client_['contacts']['RELOAD_CAPTCHA']; ?></label>                
                       <img src="<?php echo abs_client_path; ?>/include/lib/cool-php-captcha/captcha.php" id="captcha_image" style="width:100%;height:50px;" />
                    </div>
                    <input type="text" class="required" name="captcha" id="captcha" value="" data-array="12,6,<?php echo $lang_client_['contacts']['ENTER_CAPTCHA_CODE']; ?>*" />
                  </div>                    
                  <!--<div class="row-fluid">
                    <input type="checkbox" class="required" name="privacy" id="privacy" value="1" /> Accetta le condizioni sulla <span class="text-info" id="read_privacy">Privacy</span>
                  </div>-->                   
             </div>                              
           </form>          
           <div id="form-btn" class="text-right"></div>  
           <br/>        
           <strong class="text-info"><small><?php echo $lang_client_['client_registration']['NOTICE_FIELDS_MANDATORY']; ?></small></strong>
          </div>
           <div class="clearfix"></div>
        </section>
               
     </section><!-- /BODY ROW -->     