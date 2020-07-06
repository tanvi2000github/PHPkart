<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
?>
<script src="<?php echo abs_client_path ?>/include/js/jquery.js"></script>
<script src="<?php echo abs_client_path ?>/include/js/bootstrap.js"></script>
<script src="<?php echo abs_client_path ?>/include/js/bootstrap_extensions.js"></script>