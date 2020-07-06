<?php
    $B_language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$B_language = strtolower(substr(chop($B_language[0]),0,2));
	include_once('include/mysql2i.class.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $B_language; ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="include/css/install.css" rel="stylesheet">
    <title>Setup Configuration</title>
  </head>
  <body>
   <div style="text-align:center"><img src="bc-admin/img/logo.png" style="width:100%;max-width:296px;" /></div>
   <?php
	  if ( ! file_exists( dirname(__FILE__) . '/config-sample.php' ) )
	   die('Sorry, I need a config-sample.php file to work from. Please re-upload this file from your BootCommerce installation.');

	  $config_file = file(dirname(__FILE__) . '/config-sample.php');

	  if (file_exists(dirname(__FILE__) . '/config.php') )
	   die('<p>The file \'config.php\' already exists. If you need to reset any of the configuration items in this file, please delete it first.You may try <a href="install.php">installing now.</a></p>');

	  $step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
	  switch($step) {
		  case 0:
   ?>
          <p>Welcome to BootCommerce. Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p>
          <ol>
              <li>Database name</li>
              <li>Database username</li>
              <li>Database password</li>
              <li>Database host</li>
              <li>Table prefix (if you want to run more than one BootCommerce in a single database)</li>
          </ol>
          <p><strong>If for any reason this automatic file creation doesn&#8217;t work, don&#8217;t worry. All this does is fill in the database information to a configuration file. You may also simply open <code>config-sample.php</code> in a text editor, fill in your information, and save it as <code>config.php</code></strong></p>
          <p>In all likelihood, these items were supplied to you by your Web Host. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready&hellip;</p>
          <p class="step"><a href="setup-config.php?step=1" class="button button-large">Let&#8217;s go!</a></p>
   <?php
         break;
	     case 1:
   ?>
          <form method="post" action="setup-config.php?step=2">
              <p>Below you should enter your database connection details. If you&#8217;re not sure about these, contact your host.</p>
              <table class="form-table">
                  <tr>
                      <th scope="row"><label for="db_name">Database Name</label></th>
                      <td><input name="db_name" id="db_name" type="text" size="25" value="bootcommerce" /></td>
                      <td>The name of the database you want to run BootCommerce in.</td>
                  </tr>
                  <tr>
                      <th scope="row"><label for="db_username">User Name</label></th>
                      <td><input name="db_username" id="db_username" type="text" size="25" value="<?php echo htmlspecialchars('username', ENT_QUOTES ); ?>" /></td>
                      <td>Your MySQL username</td>
                  </tr>
                  <tr>
                      <th scope="row"><label for="db_password">Password</label></th>
                      <td><input name="db_password" id="db_password" type="text" size="25" value="<?php echo htmlspecialchars('password', ENT_QUOTES ); ?>" /></td>
                      <td>&hellip;and your MySQL password.</td>
                  </tr>
                  <tr>
                      <th scope="row"><label for="db_hostname">Database Host</label></th>
                      <td><input name="db_hostname" id="db_hostname" type="text" size="25" value="localhost" /></td>
                      <td>You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
                  </tr>
                  <tr>
                      <th scope="row"><label for="table_prefix">Table Prefix</label></th>
                      <td><input name="table_prefix" id="table_prefix" type="text" value="bc_" size="25" /></td>
                      <td>If you want to run multiple BootCommerce installations in a single database, change this.</td>
                  </tr>
              </table>
              <p class="step"><input name="submit" type="submit" value="<?php echo htmlspecialchars('Submit', ENT_QUOTES ); ?>" class="button button-large" /></p>
          </form>
   <?php
		 break;
		 case 2:
		  foreach ( array( 'db_name', 'db_username', 'db_password', 'db_hostname', 'table_prefix' ) as $key )
			  @$$key = trim( stripslashes( $_POST[ $key ] ) );
		      $tryagain_link = '<p class="step"><a href="setup-config.php?step=1" onclick="javascript:history.go(-1);return false;" class="button button-large">Try again</a></p>';

		  if ( empty( $table_prefix ) )
			  die('<p><strong>ERROR</strong>: "Table Prefix" must not be empty.</p>' . $tryagain_link );

		  // Validate $table_prefix: it can only contain letters, numbers and underscores.
		  if ( preg_match( '|[^a-z0-9_]|i', $table_prefix ) )
			  die('<p><strong>ERROR</strong>: "Table Prefix" can only contain numbers, letters, and underscores.</p>' . $tryagain_link );
		// Test the db connection.
		$error_connection = false;
		$connection = @mysql_connect($db_hostname, $db_username, $db_password);
		if (!$connection) $error_connection = true;
		if ($connection && !$db_name) $error_connection = true;
		if ($db_name) {
		   $dbcheck = @mysql_select_db($db_name);
		   if (!$dbcheck) $error_connection = true;
		}
		@mysql_close($connection);
		if($error_connection){
   ?>
        <h1>Error establishing a database connection</h1>
        <p>This either means that the username and password information in your <code>config.php</code> file is incorrect or we can't contact the database server at <code><?php echo $db_hostname; ?></code>. This could mean your host's database server is down.</p>
        <ul>
          <li>Are you sure you have the correct username and password?</li>
          <li>Are you sure that you have typed the correct hostname?</li>
          <li>Are you sure that the database server is running?</li>
        </ul>
        <p>If you're unsure what these terms mean you should probably contact your host.</p>
        <?php echo $tryagain_link; ?>
   <?php
		}else{
		  foreach( $config_file as $line_num => $line ) {
			  $match = explode('=',$line);
			  if(isset($match[1])){
				$var_name = str_replace('$','',trim($match[0]));
				if(isset(${$var_name})){
					if(is_numeric(trim(str_replace(';','',$match[1])))){
				      $config_file[$line_num] = trim($match[0]).' = '.${$var_name}.';'."\r\n";
					}else{
					  $config_file[$line_num] = trim($match[0]).' = \''.${$var_name}.'\';'."\r\n";
					}
				}
			  }
		  }
		  if ( ! is_writable(dirname(__FILE__)) ){
   ?>
				<p>Sorry, but I can&#8217;t write the <code>config.php</code> file.</p>
				<p>You can create the <code>config.php</code> manually and paste the following text into it.</p>
                <textarea id="bc-config" style="width:100%" rows="15" class="code" readonly><?php
                          foreach( $config_file as $line ) {
                              echo htmlentities($line, ENT_COMPAT, 'UTF-8');
                          }
                  ?></textarea>
                <p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>
                <p class="step"><a href="install.php" class="button button-large">Run the install</a></p>
				<script>
                (function(){
				  var el=document.getElementById('bc-config');
				  el.focus();
				  el.select();
                })();
                </script>
   <?php
		  }else if ( ! is_writable(dirname(__FILE__).'/content/plugins') ){
			  echo '<p>Sorry, but <code>content/plugins</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if ( ! is_writable(dirname(__FILE__).'/content/updates') ){
			  echo '<p>Sorry, but <code>content/updates</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if ( ! is_writable(dirname(__FILE__).'/content/uploads') ){
			  echo '<p>Sorry, but <code>content/uploads</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if ( ! is_writable(dirname(__FILE__).'/content/products') ){
			  echo '<p>Sorry, but <code>content/products</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if ( ! is_writable(dirname(__FILE__).'/content/up-products-images') ){
			  echo '<p>Sorry, but <code>content/up-products-images</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else{
			 // create config.php file
			  $handle = fopen(dirname(__FILE__) . '/config.php', 'w');
			  foreach( $config_file as $line ) {
				  fwrite($handle, $line);
			  }
			  fclose($handle);
			 chmod(dirname(__FILE__) . '/config.php', 0666);
   ?>
			  <p>All right sparky! You&#8217;ve made it through this part of the installation. BootCommerce can now communicate with your database. If you are ready, time now to&hellip;</p>
			  <p class="step"><a href="install.php" class="button button-large">Run the install</a></p>
   <?php
		  }
		}
	    break;
	  }
   ?>
  </body>
</html>