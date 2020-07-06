<?php 
 $link_to_this_path = isset($link_to_path) ? $link_to_path : '';
?>
<div class="accordion-group">
  <div class="accordion-heading">
    <a class="accordion-toggle" href="<?php echo $link_to_this_path; ?>">
      <i class="icon-white <?php echo $icon_menu; ?>"></i> <?php echo $menu_title; ?>
    </a>
  </div>
</div>