<?php 
if(!IsSet($_SESSION['Alogged'])){
exit();
}
?>
              <div id="logo-area">        
                <img src="<?php echo abs_uploads_path; ?>/bc_logo.png" alt="" />                      
              </div>  
              <div class="accordion" id="menu-accordion">
                <!--<div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" href="<?php echo abs_admin_path; ?>/index.php">
                      <i class="icon icon-white icon-home"></i> HOME
                    </a>
                  </div>
                </div>-->
                <!-- include all file named menu.php found in each directory in "platform" path -->
				  <?php      
				      $array_menu_list = array();          
                      $directory = rel_admin_path.'/platform/';
                      foreach (scandir($directory) as $item) {
                       if((!is_dir($item))&($item!=".")&($item!="..")){						 
                         if(file_exists($directory.$item.'/menu.php')){
						   require($directory.$item.'/general_tags.php');
						   $array_menu_list[] = $menu_title;
						   $array_menu[$menu_title] = array(
						     "link" => abs_admin_path.'/platform/'.$item,
							 "required_tag" => $directory.$item.'/general_tags.php',
							 "required_menu" => $directory.$item.'/menu.php'
						   );
                         }
                       }
                      }
					  asort($array_menu_list);
					  foreach($array_menu_list as $menu){
						 $link_to_path = $array_menu[$menu]["link"];
						 require($array_menu[$menu]["required_tag"]);
                         require($array_menu[$menu]["required_menu"]);				 
					  }
                  ?>	
                                                                     
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" href="<?php echo abs_client_path; ?>/index.php" target="_blank">
                      <i class="icon-white icon-globe"></i> <?php echo $lang_['menu']['GO_SITE']; ?>
                    </a>
                  </div>
                </div> 
                
                                                                                                                                                                                            
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" href="<?php echo abs_admin_path; ?>/log_out.php">
                      <i class="icon-off icon-white"></i> LogOut
                    </a>
                  </div>
                </div> 
                
              <?php 
				echo '<div class="text-right muted" style="padding:5px;"><i class="icon-white icon-info-sign"></i> <strong>V. '.bc_version().'</strong></div>';
			  ?>                                   

              </div>