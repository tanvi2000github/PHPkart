<?php 
 $link_to_this_path = isset($link_to_path) ? $link_to_path : '';
?>
<div class="accordion-group">
  <div class="accordion-heading">
    <a class="accordion-toggle" data-toggle="collapse" data-parent="#menu-accordion" href="#collapseOne">
      <i class="icon icon-white <?php echo $icon_menu; ?>"></i> <?php echo $menu_title; ?> 
    </a>
  </div>
  <div id="collapseOne" class="accordion-body collapse">
    <div class="accordion-inner">
      <ul class="unstyled">
        <li><a href="<?php echo abs_admin_path.'/platform/Settings/index.php?type=system'; ?>" style="width:100%"><i class="icon icon-wrench icon-white"></i> <?php echo $lang_['settings']['MENU_SYSTEM']; ?></a></li>
        <li><a href="<?php echo abs_admin_path.'/platform/Settings/index.php?type=cart'; ?>"><i class="icon icon-cart icon-white"></i> <?php echo $lang_['settings']['MENU_CART']; ?></a></li>        
        <li><a href="<?php echo abs_admin_path.'/platform/Settings/index.php?type=payments'; ?>"><i class="icon icon-attachment icon-white"></i> <?php echo $lang_['settings']['MENU_PAYMENTS']; ?></a></li>
        <?php
		 if(plugin_exsists('multitaxes')){
		?>
          <li><a href="<?php echo abs_plugins_path.'/multitaxes/admin'; ?>"><i class="icon-list-alt icon-white"></i> <?php echo $lang_['pl_multitax']['MENU_VOICE']; ?></a></li>
        <?php
		 }
		?>
        <li><a href="<?php echo abs_admin_path.'/platform/Settings/index.php?type=company_data'; ?>"><i class="icon icon-profile icon-white"></i> <?php echo $lang_['settings']['MENU_COMPANY_DATA']; ?></a></li>
        <li><a href="<?php echo abs_admin_path.'/platform/Settings/index.php?type=seo'; ?>"><i class="icon icon-web icon-white"></i> <?php echo $lang_['settings']['MENU_SEO']; ?></a></li>
        <?php
		 if(plugin_exsists('slideshow')){
		?>        
          <li><a href="<?php echo abs_plugins_path.'/slideshow/admin'; ?>"><i class="icon-picture icon-white"></i> <?php echo $lang_['pl_slideshow']['MENU_VOICE']; ?></a></li>
        <?php
		 }
		?>        
      </ul>
    </div>
  </div>
</div>  