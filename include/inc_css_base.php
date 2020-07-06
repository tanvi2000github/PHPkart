<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
?>	 
<link href="<?php echo abs_client_path ?>/include/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo abs_client_path ?>/include/css/bootstrap_extensions.css" rel="stylesheet">
<link href="<?php echo abs_client_path ?>/include/css/addons.css" rel="stylesheet">   
<link href="<?php echo abs_client_path ?>/include/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo abs_client_path ?>/include/css/bootstrap_responsive_extensions.css" rel="stylesheet">
	<!-- lightbox plugin --> 
	<link href="<?php echo abs_client_path ?>/content/themes/default/js/lightbox/themes/default/jquery.lightbox.css" rel="stylesheet">
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php echo abs_client_path ?>/content/themes/default/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
	<![endif]-->
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo abs_client_path ?>/include/js/html5shiv.js"></script>
    <![endif]-->