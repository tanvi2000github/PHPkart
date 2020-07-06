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
    <title>Installation</title>
  </head>
  <body>
  <div style="text-align:center"><img src="bc-admin/img/logo.png" style="width:100%;max-width:296px;" /></div>
   <?php
	  if (!file_exists(dirname(__FILE__) . '/config.php') || !is_writable(dirname(__FILE__).'/content/products') || !is_writable(dirname(__FILE__).'/content/up-products-images') ) {
		  if (!file_exists(dirname(__FILE__) . '/config.php')){
   ?>
            <p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
            <p>You can create a <code>config.php</code> file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file.</p>
            <p><a href="setup-config.php" class="button button-large">Create a Configuration File</a>
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
		  }
       exit();
	  }
	  require_once(dirname(__FILE__) . '/config.php');
	  $conn = @mysql_connect($db_hostname,$db_username,$db_password) or die ("<h1>Connection Error</h1> <p>could not connect to database</p>");
	  mysql_set_charset('utf8');
	  mysql_select_db($db_name);
	  function is_installed(){
		  global $table_prefix,
		  $hostname,
		  $username,
		  $password;
		  $arr_tables = array(
		                       $table_prefix."admin_accounts",
							   $table_prefix."cart",
							   $table_prefix."categories",
							   $table_prefix."clients",
							   $table_prefix."clients_address",
							   $table_prefix."orders",
							   $table_prefix."products",
							   $table_prefix."products_attributes",
							   $table_prefix."settings"
							  );
          $table_exists = false;
		  $result = mysql_query('SHOW TABLES') or die (mysql_error());
		  while($table = mysql_fetch_array($result)) {
			  if(in_array($table[0],$arr_tables)) $table_exists = true;
		  }
		  return $table_exists;
	  }
	  // Let's check to make sure BC isn't already installed.
	  if ( is_installed() ) {
		  die( '<h1>Already Installed</h1><p>You appear to have already installed BootCommerce. To reinstall please clear your old database tables first.</p><p class="step"><a href="bc-admin/login.php" class="button button-large">Log In</a></p>' );
	  }
	  if($table_prefix === '')
	  die( '<h1>Configuration Error</h1><p>Your <code>config.php</code> file has an empty database table prefix, which is not supported.</p>' );
	  $required_php_version = '5.3.0';
	  $required_mysql_version = '5.0';
	  $php_version    = phpversion();
	  $mysql_version  = preg_replace( '/[^0-9.].*/', '', mysql_get_server_info() );
	  $php_compat     = version_compare( $php_version, $required_php_version, '>=' );
	  $mysql_compat   = version_compare( $mysql_version, $required_mysql_version, '>=' );
	  if ( !$mysql_compat && !$php_compat )
		  $compat = sprintf('You cannot install because BootCommerce requires PHP version %1$s or higher and MySQL version %2$s or higher. You are running PHP version %3$s and MySQL version %4$s.', $required_php_version, $required_mysql_version, $php_version, $mysql_version );
	  elseif ( !$php_compat )
		  $compat = sprintf('You cannot install because BootCommerce requires PHP version %1$s or higher. You are running version %2$s.', $required_php_version, $php_version );
	  elseif ( !$mysql_compat )
		  $compat = sprintf('You cannot install because BootCommerce requires MySQL version %1$s or higher. You are running version %2$s.', $required_mysql_version, $mysql_version );
	  if ( !$mysql_compat || !$php_compat ) {
		  die( '<h1>Insufficient Requirements</h1><p>' . $compat . '</p>' );
	  }
	  $step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
	  function selfURL(){
		$protocol = 'http';
		if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
		  $protocol .= 's';
		  $protocol_port = $_SERVER['SERVER_PORT'];
		} else {
		  $protocol_port = 80;
		}
		$host = $_SERVER['HTTP_HOST'];
		$port = $_SERVER['SERVER_PORT'];
		$request = $_SERVER['PHP_SELF'];
		$query = isset($_SERVER['argv']) ? substr($_SERVER['argv'][0], strpos($_SERVER['argv'][0], ';') + 1) : '';
		$pageURL = $protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request . (empty($query) ? '' : '?' . $query);
		return $pageURL;
	  }
	  function display_setup_form( $error = null ) {
		  $timezones_array = array(
			  'Pacific/Midway'       => "(GMT-11:00) Midway Island",
			  'US/Samoa'             => "(GMT-11:00) Samoa",
			  'US/Hawaii'            => "(GMT-10:00) Hawaii",
			  'US/Alaska'            => "(GMT-09:00) Alaska",
			  'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
			  'America/Tijuana'      => "(GMT-08:00) Tijuana",
			  'US/Arizona'           => "(GMT-07:00) Arizona",
			  'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
			  'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
			  'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
			  'America/Mexico_City'  => "(GMT-06:00) Mexico City",
			  'America/Monterrey'    => "(GMT-06:00) Monterrey",
			  'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
			  'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
			  'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
			  'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
			  'America/Bogota'       => "(GMT-05:00) Bogota",
			  'America/Lima'         => "(GMT-05:00) Lima",
			  'America/Caracas'      => "(GMT-04:30) Caracas",
			  'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
			  'America/La_Paz'       => "(GMT-04:00) La Paz",
			  'America/Santiago'     => "(GMT-04:00) Santiago",
			  'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
			  'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
			  'Greenland'            => "(GMT-03:00) Greenland",
			  'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
			  'Atlantic/Azores'      => "(GMT-01:00) Azores",
			  'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
			  'Africa/Casablanca'    => "(GMT) Casablanca",
			  'Europe/Dublin'        => "(GMT) Dublin",
			  'Europe/Lisbon'        => "(GMT) Lisbon",
			  'Europe/London'        => "(GMT) London",
			  'Africa/Monrovia'      => "(GMT) Monrovia",
			  'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
			  'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
			  'Europe/Berlin'        => "(GMT+01:00) Berlin",
			  'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
			  'Europe/Brussels'      => "(GMT+01:00) Brussels",
			  'Europe/Budapest'      => "(GMT+01:00) Budapest",
			  'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
			  'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
			  'Europe/Madrid'        => "(GMT+01:00) Madrid",
			  'Europe/Paris'         => "(GMT+01:00) Paris",
			  'Europe/Prague'        => "(GMT+01:00) Prague",
			  'Europe/Rome'          => "(GMT+01:00) Rome",
			  'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
			  'Europe/Skopje'        => "(GMT+01:00) Skopje",
			  'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
			  'Europe/Vienna'        => "(GMT+01:00) Vienna",
			  'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
			  'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
			  'Europe/Athens'        => "(GMT+02:00) Athens",
			  'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
			  'Africa/Cairo'         => "(GMT+02:00) Cairo",
			  'Africa/Harare'        => "(GMT+02:00) Harare",
			  'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
			  'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
			  'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
			  'Europe/Kiev'          => "(GMT+02:00) Kyiv",
			  'Europe/Minsk'         => "(GMT+02:00) Minsk",
			  'Europe/Riga'          => "(GMT+02:00) Riga",
			  'Europe/Sofia'         => "(GMT+02:00) Sofia",
			  'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
			  'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
			  'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
			  'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
			  'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
			  'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
			  'Asia/Tehran'          => "(GMT+03:30) Tehran",
			  'Europe/Moscow'        => "(GMT+04:00) Moscow",
			  'Asia/Baku'            => "(GMT+04:00) Baku",
			  'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
			  'Asia/Muscat'          => "(GMT+04:00) Muscat",
			  'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
			  'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
			  'Asia/Kabul'           => "(GMT+04:30) Kabul",
			  'Asia/Karachi'         => "(GMT+05:00) Karachi",
			  'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
			  'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
			  'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
			  'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
			  'Asia/Almaty'          => "(GMT+06:00) Almaty",
			  'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
			  'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
			  'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
			  'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
			  'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
			  'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
			  'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
			  'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
			  'Australia/Perth'      => "(GMT+08:00) Perth",
			  'Asia/Singapore'       => "(GMT+08:00) Singapore",
			  'Asia/Taipei'          => "(GMT+08:00) Taipei",
			  'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
			  'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
			  'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
			  'Asia/Seoul'           => "(GMT+09:00) Seoul",
			  'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
			  'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
			  'Australia/Darwin'     => "(GMT+09:30) Darwin",
			  'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
			  'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
			  'Australia/Canberra'   => "(GMT+10:00) Canberra",
			  'Pacific/Guam'         => "(GMT+10:00) Guam",
			  'Australia/Hobart'     => "(GMT+10:00) Hobart",
			  'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
			  'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
			  'Australia/Sydney'     => "(GMT+10:00) Sydney",
			  'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
			  'Asia/Magadan'         => "(GMT+12:00) Magadan",
			  'Pacific/Auckland'     => "(GMT+12:00) Auckland",
			  'Pacific/Fiji'         => "(GMT+12:00) Fiji",
		  );
		  $currencies_array = array(
			  "AUD" => array('&#36;','Australian Dollar'),
			  "CAD" => array('&#36;','Canadian Dollar'),
			  "CHF" => array('&#67;&#72;&#70;','Swiss Franc'),
			  "CZK" => array('&#75;&#269;','Czech Koruna'),
			  "DKK" => array('&#107;&#114;','Danish Krone'),
			  "EUR" => array('&#8364;','Euro'),
			  "GBP" => array('&#163;','Pound Sterling'),
			  "HKD" => array('&#36;','Hong Kong Dollar'),
			  "HUF" => array('&#70;&#116;','Hungary Forint'),
			  "JPY" => array('&#165;','Japanese Yen'),
			  "NOK" => array('&#107;&#114;','Norwegian Krone'),
			  "NZD" => array('&#36;','New Zealand Dollar'),
			  "PLN" => array('&#122;&#322;','Polish Zloty'),
			  "SGD" => array('&#36;','Singapore Dollar'),
			  "SEK" => array('&#107;&#114;','Swedish Krona'),
			  "USD" => array('&#36;','US Dollar')
		  );
		  $user_name = isset($_POST['user_name']) ? trim( $_POST['user_name'] ) : '';
		  $admin_password = isset($_POST['admin_password']) ? trim( $_POST['admin_password'] ) : '';

		  $shop_title = isset( $_POST['shop_title'] ) ? trim( $_POST['shop_title'] ) : '';
		  $shop_url = isset( $_POST['shop_url'] ) ? trim( $_POST['shop_url'] ) : str_replace('/install.php','',selfURL());
		  $admin_email  = isset( $_POST['admin_email']  ) ? trim( $_POST['admin_email'] ) : '';
		  $smtp_email = isset( $_POST['smtp_email']  ) && $_POST['smtp_email'] == '1' ? 1 : 0;
		  $smtp_port = isset( $_POST['smtp_port']  ) ? trim( $_POST['smtp_port'] ) : '25';
		  $smtp_host = isset( $_POST['smtp_host']  ) ? trim( $_POST['smtp_host'] ) : '';
		  $smtp_user = isset( $_POST['smtp_user']  ) ? trim( $_POST['smtp_user'] ) : '';
		  $smtp_password = isset( $_POST['smtp_password']  ) ? trim( $_POST['smtp_password'] ) : '';
		  $smtp_secure = isset( $_POST['smtp_secure']  ) ? trim( $_POST['smtp_secure'] ) : '';

		  $company_name = isset( $_POST['company_name']  ) ? trim( $_POST['company_name'] ) : '';
		  $company_taxcode = isset( $_POST['company_taxcode']  ) ? trim( $_POST['company_taxcode'] ) : '';
		  $company_email = isset( $_POST['company_email']  ) ? trim( $_POST['company_email'] ) : '';
		  $company_address = isset( $_POST['company_address']  ) ? trim( $_POST['company_address'] ) : '';
		  $company_city = isset( $_POST['company_city']  ) ? trim( $_POST['company_city'] ) : '';
		  $company_zipcode = isset( $_POST['company_zipcode']  ) ? trim( $_POST['company_zipcode'] ) : '';
		  $company_phone = isset( $_POST['company_phone']  ) ? trim( $_POST['company_phone'] ) : '';
		  $company_fax = isset( $_POST['company_fax']  ) ? trim( $_POST['company_fax'] ) : '';

	   if ( ! is_null( $error ) ) {
	  ?>
      <p class="message"><?php printf('<strong>ERROR</strong>: %s', $error ); ?></p>
	  <?php } ?>
	  <form id="setup" method="post" action="install.php?step=2">
          <h2>Admin Login Informations</h2>
		  <table class="form-table">
			  <tr>
				  <th scope="row"><label for="user_name">Username</label></th>
				  <td><input name="user_name" type="text" id="user_login" size="25" value="<?php echo $user_name; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="admin_password">Password</label></th>
				  <td><input name="admin_password" type="password" id="pass1" size="25" value="" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="admin_password">Repeat Password</label></th>
				  <td><input name="admin_password2" type="password" id="pass2" size="25" value="" /></td>
			  </tr>
		  </table>
          <h2>System Informations</h2>
		  <table class="form-table">
			  <tr>
				  <th scope="row"><label for="shop_title">Shop Title</label></th>
				  <td><input name="shop_title" type="text" id="shop_title" size="25" value="<?php echo $shop_title; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="shop_url">Shop Url</label></th>
				  <td><input name="shop_url" type="text" id="shop_url" size="25" value="<?php echo $shop_url; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="admin_email">System E-mail</label></th>
				  <td><input name="admin_email" type="text" id="admin_email" size="25" value="<?php echo $admin_email; ?>" />
                  <p>will be used to send alerts or notifications to the clients</p></td>
			  </tr>
			  <tr>
				  <th scope="row"><label>Enable SMTP</label></th>
				  <td>
                    <label style="cursor:pointer" for="smtp_email_yes"><input name="smtp_email" type="radio" id="smtp_email_yes" value="1" <?php echo $smtp_email ? 'checked' : ''; ?> /> Yes</label>
                    <label style="cursor:pointer" for="smtp_email_no"><input name="smtp_email" type="radio" id="smtp_email_no" value="0" <?php echo !$smtp_email ? 'checked' : ''; ?> /> No</label>
                  </td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="smtp_port">SMTP Port</label></th>
				  <td><input name="smtp_port" type="text" id="smtp_port" size="25" value="<?php echo $smtp_port; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="smtp_host">SMTP Host</label></th>
				  <td><input name="smtp_host" type="text" id="smtp_host" size="25" value="<?php echo $smtp_host; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="smtp_user">SMTP UserName</label></th>
				  <td><input name="smtp_user" type="text" id="smtp_user" size="25" value="<?php echo $smtp_user; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="smtp_password">SMTP Password</label></th>
				  <td><input name="smtp_password" type="password" id="smtp_password" size="25" value="<?php echo $smtp_password; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label>SMTP Secure</label></th>
				  <td>
                    <label style="cursor:pointer" for="smtp_secure_ssl"><input name="smtp_secure" type="radio" id="smtp_secure_ssl" value="ssl" <?php echo $smtp_secure == 'ssl' || $smtp_secure == '' ? 'checked' : ''; ?> /> SSL</label>
                    <label style="cursor:pointer" for="smtp_secure_tls"><input name="smtp_secure" type="radio" id="smtp_secure_tls" value="tls" <?php echo $smtp_secure == 'tls' ? 'checked' : ''; ?> /> TLS</label>
                  </td>
			  </tr>
		  </table>
          <h2>Company data</h2>
		  <table class="form-table">
			  <tr>
				  <th scope="row"><label for="company_name">Company name</label></th>
				  <td><input name="company_name" type="text" id="company_name" size="25" value="<?php echo $company_name; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_taxcode">Company Tax code</label></th>
				  <td><input name="company_taxcode" type="text" id="company_taxcode" size="25" value="<?php echo $company_taxcode; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_email">Company e-mail</label></th>
				  <td><input name="company_email" type="text" id="company_email" size="25" value="<?php echo $company_email; ?>" />
                  <p>will be used for the contacts</p></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_address">Company address</label></th>
				  <td><input name="company_address" type="text" id="company_address" size="25" value="<?php echo $company_address; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_city">Company city</label></th>
				  <td><input name="company_city" type="text" id="company_city" size="25" value="<?php echo $company_city; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_zipcode">Company Zip code</label></th>
				  <td><input name="company_zipcode" type="text" id="company_zipcode" size="25" value="<?php echo $company_zipcode; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_phone">Company Phone</label></th>
				  <td><input name="company_phone" type="text" id="company_phone" size="25" value="<?php echo $company_phone; ?>" /></td>
			  </tr>
			  <tr>
				  <th scope="row"><label for="company_fax">Company Fax</label></th>
				  <td><input name="company_fax" type="text" id="company_fax" size="25" value="<?php echo $company_fax; ?>" /></td>
			  </tr>
		  </table>
		  <p class="step"><input type="submit" name="Submit" value="Install BootCommerce" class="button button-large" /></p>
	  </form>
	  <?php
	  } // end display_setup_form()
	  switch($step) {
		  case 0: // Step 1
		  case 1: // Step 1, direct link.
   ?>
			<h1>Information needed</h1>
            <p>Please provide the following information. Don&#8217;t worry, you can always change these settings later.</p>
            <p class="message"><strong>NOTICE: there are many other options, you can configure them from the Admin Panel after installation</strong></p>
   <?php
            display_setup_form();
		  break;
		  case 2:
			  // Fill in the data we gathered
			  $user_name = isset($_POST['user_name']) ? trim( $_POST['user_name'] ) : '';
			  $admin_password = isset($_POST['admin_password']) ? $_POST['admin_password'] : '';
			  $admin_password_check = isset($_POST['admin_password2']) ? $_POST['admin_password2'] : '';

			  $shop_title = isset( $_POST['shop_title'] ) ? trim( $_POST['shop_title'] ) : '';
			  $shop_url = isset( $_POST['shop_url'] ) ? trim( $_POST['shop_url'] ) : str_replace('/install.php','',selfURL());

			  $admin_email  = isset( $_POST['admin_email']  ) ? trim( $_POST['admin_email'] ) : '';
			  $smtp_email = isset( $_POST['smtp_email']  ) && $_POST['smtp_email'] == '1' ? 1 : 0;
			  $smtp_port = isset( $_POST['smtp_port']  ) ? trim( $_POST['smtp_port'] ) : '25';
			  $smtp_host = isset( $_POST['smtp_host']  ) ? trim( $_POST['smtp_host'] ) : '';
			  $smtp_user = isset( $_POST['smtp_user']  ) ? trim( $_POST['smtp_user'] ) : '';
			  $smtp_password = isset( $_POST['smtp_password']  ) ? trim( $_POST['smtp_password'] ) : '';
			  $smtp_secure = isset( $_POST['smtp_secure']  ) ? trim( $_POST['smtp_secure'] ) : '';

			  $company_name = isset( $_POST['company_name']  ) ? trim( $_POST['company_name'] ) : '';
			  $company_taxcode = isset( $_POST['company_taxcode']  ) ? trim( $_POST['company_taxcode'] ) : '';
			  $company_email = isset( $_POST['company_email']  ) ? trim( $_POST['company_email'] ) : '';
			  $company_address = isset( $_POST['company_address']  ) ? trim( $_POST['company_address'] ) : '';
			  $company_city = isset( $_POST['company_city']  ) ? trim( $_POST['company_city'] ) : '';
			  $company_zipcode = isset( $_POST['company_zipcode']  ) ? trim( $_POST['company_zipcode'] ) : '';
			  $company_phone = isset( $_POST['company_phone']  ) ? trim( $_POST['company_phone'] ) : '';
			  $company_fax = isset( $_POST['company_fax']  ) ? trim( $_POST['company_fax'] ) : '';

			  $error = false;
			  if ( empty( $user_name ) ) {
				  display_setup_form('you must provide a valid username' );
				  $error = true;
			  } else if ( $admin_password == '' ) {
				  display_setup_form('you must provide a valid password');
				  $error = true;
			  } else if ( $admin_password != $admin_password_check ) {
				  display_setup_form('your passwords do not match. Please try again');
				  $error = true;
			  } else if ( empty( $shop_title ) ) {
				  display_setup_form('you must provide a valid site name');
				  $error = true;
			  } else if ( empty( $shop_url ) ) {
				  display_setup_form('you must provide a valid site url');
				  $error = true;
			  } else if ( empty( $admin_email ) ) {
				  display_setup_form('you must provide an e-mail address.');
				  $error = true;
			  } else if ( $smtp_email && !filter_var($smtp_port, FILTER_VALIDATE_INT) ) {
				  display_setup_form('SMTP port must be an integer.');
				  $error = true;
			  } else if ( $smtp_email && empty($smtp_host) ) {
				  display_setup_form('you must provide a valid SMTP Host.');
				  $error = true;
			  } else if ( $smtp_email && empty($smtp_user) ) {
				  display_setup_form('you must provide a valid SMTP User Name.');
				  $error = true;
			  } else if ( $smtp_email && empty($smtp_password) ) {
				  display_setup_form('you must provide a valid SMTP Password.');
				  $error = true;
			  } else if ( $smtp_email ) {
				  require("include/lib/phpMailer/class.phpmailer.php");
				  $mail = new PHPMailer();
				  $mail->IsSMTP();
				  $mail->Host = $smtp_host;
				  $mail->SMTPAuth = true;
				  $mail->Username = $smtp_user;
				  $mail->Password = $smtp_password;
				  $mail->Port = $smtp_port;
				  $mail->From = $admin_email;
				  $mail->SMTPSecure = $smtp_secure;
				  $mail->FromName = "BootCommerce SMTP test";
				  $mail->AddAddress($admin_email, "Test");
				  $mail->AddReplyTo($admin_email, "BootCommerce SMTP test");
				  $mail->WordWrap = 50;
				  $mail->IsHTML(false);
				  $mail->Subject = "AuthSMTP Test from BootComemrce";
				  $mail->Body    = "This message is used to test the parameters of the SMTP server!   everything went well!";
				  if(!$mail->Send()){
					  display_setup_form('Message could not be sent to test SMTP.<br/>'.$mail->ErrorInfo);
					  $error = true;
                  }
			  } else if ( !filter_var($admin_email, FILTER_VALIDATE_EMAIL) ) {
				  display_setup_form('that isn&#8217;t a valid e-mail address. E-mail addresses look like: <code>username@example.com</code>' );
				  $error = true;
			  }	else if ( empty( $company_name ) ) {
				  display_setup_form('you must provide a Company name.' );
				  $error = true;
			  } else if ( empty( $company_taxcode ) ) {
				  display_setup_form('you must provide a Company Tax Code.' );
				  $error = true;
			  } else if ( !filter_var($company_email, FILTER_VALIDATE_EMAIL) ) {
				  display_setup_form('that isn&#8217;t a valid Company e-mail address. E-mail addresses look like: <code>username@example.com</code>' );
				  $error = true;
			  } else if ( empty( $company_address ) ) {
				  display_setup_form('you must provide a Company Address.' );
				  $error = true;
			  } else if ( empty( $company_city ) ) {
				  display_setup_form('you must provide a Company City.' );
				  $error = true;
			  } else if ( empty( $company_zipcode ) ) {
				  display_setup_form('you must provide a Company Zip Code.' );
				  $error = true;
			  } else if ( empty( $company_phone ) ) {
				  display_setup_form('you must provide a Company Phone.' );
				  $error = true;
			  }
			  if ( $error === false ) {
		        // create database structure if error false.
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."admin_accounts(
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  name varchar(250) CHARACTER SET utf8 NOT NULL,
				  userid varchar(70) CHARACTER SET utf8 NOT NULL,
				  password varchar(70) CHARACTER SET utf8 NOT NULL,
				  super_admin tinyint(1) NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."cart(
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  id_client bigint(20) NOT NULL,
				  session_client varchar(20) CHARACTER SET utf8 NOT NULL,
				  id_product bigint(20) NOT NULL,
				  options longtext CHARACTER SET utf8 NOT NULL,
				  date datetime NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."categories (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  name varchar(250) CHARACTER SET utf8 NOT NULL,
				  tree_path longtext CHARACTER SET utf8 NOT NULL,
				  level bigint(20) NOT NULL,
				  status tinyint(1) NOT NULL DEFAULT 1,
				  sx bigint(20) NOT NULL,
				  dx bigint(20) NOT NULL,
				  parent bigint(20) NOT NULL,
				  meta_keywords longtext CHARACTER SET utf8 NOT NULL,
				  meta_description longtext CHARACTER SET utf8 NOT NULL,
				  icon varchar(250) CHARACTER SET utf8 NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."clients (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  is_company tinyint(1) NOT NULL,
				  vat varchar(250) CHARACTER SET utf8 NOT NULL,
				  name varchar(250) CHARACTER SET utf8 NOT NULL,
				  lastname varchar(250) CHARACTER SET utf8 NOT NULL,
				  tax_code varchar(250) CHARACTER SET utf8 NOT NULL,
				  email varchar(250) CHARACTER SET utf8 NOT NULL,
				  phone varchar(250) CHARACTER SET utf8 NOT NULL,
				  fax varchar(250) CHARACTER SET utf8 NOT NULL,
				  address longtext CHARACTER SET utf8 NOT NULL,
				  zipcode varchar(250) CHARACTER SET utf8 NOT NULL,
				  city varchar(250) CHARACTER SET utf8 NOT NULL,
				  userid varchar(64) CHARACTER SET utf8 NOT NULL,
				  password varchar(64) CHARACTER SET utf8 NOT NULL,
				  enabled tinyint(1) NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."clients_address (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  phone varchar(250) CHARACTER SET utf8 NOT NULL,
				  fax varchar(250) CHARACTER SET utf8 NOT NULL,
				  address longtext CHARACTER SET utf8 NOT NULL,
				  zipcode varchar(250) CHARACTER SET utf8 NOT NULL,
				  city varchar(250) CHARACTER SET utf8 NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
			   mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."orders (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  products_list longtext CHARACTER SET utf8 NOT NULL,
				  data datetime NOT NULL,
				  id_client bigint(20) NOT NULL,
				  session_client varchar(20) CHARACTER SET utf8 NOT NULL,
				  subtotal decimal(65,10) NOT NULL,
				  grandtotal decimal(65,10) NOT NULL,
				  tax decimal(65,10) NOT NULL,
				  shipping_price decimal(65,10) NOT NULL,
				  payment_method text CHARACTER SET utf8 NOT NULL,
				  paypal_status varchar(250) CHARACTER SET utf8 NOT NULL,
				  paypal_id_transaction varchar(250) CHARACTER SET utf8 NOT NULL,
				  paypal_email_client varchar(250) CHARACTER SET utf8 NOT NULL,
				  paypal_array longtext CHARACTER SET utf8 NOT NULL,
				  payment_price decimal(65,10) NOT NULL,
				  billing_address longtext CHARACTER SET utf8 NOT NULL,
				  shipping_address longtext CHARACTER SET utf8 NOT NULL,
				  code_order varchar(10) CHARACTER SET utf8 NOT NULL,
				  payed tinyint(1) NOT NULL,
				  processed tinyint(1) NOT NULL,
				  process_date datetime NOT NULL,
				  carrier varchar(250) CHARACTER SET utf8 NOT NULL,
				  carrier_link varchar(250) CHARACTER SET utf8 NOT NULL,
				  carrier_tracking varchar(250) CHARACTER SET utf8 NOT NULL,
				  guest tinyint(1) NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
			   mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."products (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  name varchar(250) CHARACTER SET utf8 NOT NULL,
				  file_name varchar(250) CHARACTER SET utf8 NOT NULL,
				  images longtext CHARACTER SET utf8 NOT NULL,
				  url_image varchar(250) CHARACTER SET utf8 NOT NULL,
				  description longtext CHARACTER SET utf8 NOT NULL,
				  categories longtext CHARACTER SET utf8 NOT NULL,
				  code varchar(100) CHARACTER SET utf8 NOT NULL,
				  price decimal(65,10) NOT NULL,
				  offer decimal(65,10) NOT NULL,
				  tax decimal(65,10) NOT NULL,
				  price_with_tax tinyint(1) NOT NULL,
				  availability decimal(65,10) NOT NULL,
				  unlimited_availability tinyint(1) NOT NULL,
				  units varchar(250) CHARACTER SET utf8 NOT NULL,
				  attributes longtext CHARACTER SET utf8 NOT NULL,
				  options longtext CHARACTER SET utf8 NOT NULL,
				  add_data datetime NOT NULL,
				  by_exposure tinyint(1) NOT NULL,
				  meta_title varchar(250) CHARACTER SET utf8 NOT NULL,
				  meta_keywords text CHARACTER SET utf8 NOT NULL,
				  meta_description varchar(250) CHARACTER SET utf8 NOT NULL,
				  visible tinyint(1) NOT NULL,
				  active tinyint(1) NOT NULL DEFAULT 1,
				  showcase tinyint(1) NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
			   mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."products_attributes (
				  id_o bigint(20) NOT NULL AUTO_INCREMENT,
				  id_product bigint(20) NOT NULL,
				  attribute_name varchar(250) CHARACTER SET utf8 NOT NULL,
				  attribute_value varchar(250) CHARACTER SET utf8 NOT NULL,
				  PRIMARY KEY (id_o)
				) DEFAULT CHARSET=utf8") ;
			   mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."settings (
				  system longtext CHARACTER SET utf8 NOT NULL,
				  cart longtext CHARACTER SET utf8 NOT NULL,
				  seo longtext CHARACTER SET utf8 NOT NULL,
				  payments longtext CHARACTER SET utf8 NOT NULL,
				  company_data longtext CHARACTER SET utf8 NOT NULL
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."plugins (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  name varchar(250) CHARACTER SET utf8 NOT NULL,
				  shortname varchar(250) CHARACTER SET utf8 NOT NULL,
				  description longtext CHARACTER SET utf8 NOT NULL,
				  version varchar(10) CHARACTER SET utf8 NOT NULL,
				  dependence longtext CHARACTER SET utf8 NOT NULL,
				  active tinyint(1) NOT NULL DEFAULT 1,
				  system tinyint(1) NOT NULL DEFAULT 0,
				  min_bc_version_required varchar(10) CHARACTER SET utf8 NOT NULL,
				  PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8") ;
				mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."version (
				  version varchar(250) CHARACTER SET utf8 NOT NULL
				) DEFAULT CHARSET=utf8") ;
				function encryption($str){
				 return function_exists('hash') ? hash('sha256',$str) : md5($str);
				}
				function str_db($string){
				 $string = str_replace(";","####59;",$string);
				 $string = str_replace('&','&amp;',$string);
				 $string = str_replace('´','&acute;',$string);
				 $string = str_replace('˜','&tilde;',$string);
				 $string = str_replace('<','&lt;',$string);
				 $string = str_replace('>','&gt;',$string);
				 $string = str_replace('>','&#96;',$string);
				 $string = str_replace('\'','&#39;',$string);
				 $string = str_replace('\'','\'\'',$string);
				 $string = str_replace('"','&quot;',$string);
				 $string = str_replace("\\","\\\\",$string);
				 $string = str_replace("####59;","&#59;",$string);
				 return ($string);
				}
				/* parserize an array serialization for db */
				function str_serialize($string){
				 $string = str_replace('\'','&#39;',$string);
				 $string = str_replace('"','&quot;',$string);
				 $string = str_replace("\\","&#92;",$string);
				 $string = str_replace(":","&#58;",$string);
				 return $string;
				}
				$val = "'".str_db($user_name)."',";
				$val .= "'".str_db($user_name)."',";
				$val .= "1,";
				$val .= "'".encryption(str_db($admin_password))."'";
				$sql = " insert into ".$table_prefix."admin_accounts (name,userid,super_admin,password)";
				$sql .= " VALUES (".$val.")";
				mysql_query($sql);
				$array_system_param = array(
				                        "shop_title" => str_db($shop_title),
										"shop_url" => str_db($shop_url),
										"admin_email" => str_db($admin_email),
										"smtp_email" => str_db($smtp_email),
										"smtp_port" => str_db($smtp_port),
										"smtp_host" => str_db($smtp_host),
										"smtp_user" => str_db($smtp_user),
										"smtp_password" => str_db($smtp_password),
										"smtp_secure" => str_db($smtp_secure),
										"date_format" => 'dd/mm/yyyy',
										"cookies_persistence" => 60,
				                        "time_zone" => 'US/Central',
										"default_admin_language" => "en_US",
										"default_client_language" => "en_US"
				                       );
				$array_seo_param = array(
				                        "shop_meta_description" => '',
										"shop_meta_keywords" => '',
                                        "google_analytics" => ''
				                       );
				$array_cart_param = array(
				                        "days_product_new" => 50,
										"guest_purchases" => 1,
										"registration_type" => 0,
										"coming_soon" => 0,
										"prices_on_login" => 0,
										"products_per_page" => 20,
										"shipping_price" => 7.99,
										"thousands_separator" => ',',
										"decimal_separator" => '.',
										"currency" => '$',
										"currency_position" => 'l',
										"tax_name" => 'VAT',
										"tax_percentage" => '0',
										"units" => ''
				                       );
			   $array_payments_gateways_param = array(
										  "bank_transfer" => array(
															 "status" => true,
															 "orders_prefix" => "BT",
															 "long_name" => "Bank transfer",
															 "surcharge" => 0,
															 "email_message" => ""
															 ),
										  "cash_on_delivery" => array(
															 "status" => true,
															 "orders_prefix" => "COD",
															 "long_name" => "Cash on delivery",
															 "surcharge" => 6.00,
															 "email_message" => ""
															 ),
										  "paypal" => array(
															 "status" => true,
															 "orders_prefix" => "PAYPAL",
															 "long_name" => "Paypal / Credit cards",
															 "surcharge" => 0,
															 "email_message" => "",
															 "currency_code" => "USD",
															 "region" => "US",
															 "sendbox" => true,
															 "ssl" => false,
															 "email" => "business@mrplugins.it",
															 "payment_limit" => 8000
															 )
									   );
				$array_company_data_param = array(
				                        "company_name" => $company_name,
										"company_taxcode" => $company_taxcode,
										"company_email" => $company_email,
										"company_address" => $company_address,
										"company_city" => $company_city,
										"company_zipcode" => $company_zipcode,
										"company_phone" => $company_phone,
										"company_fax" => $company_fax
				                       );
			   $result = mysql_query('select * from '.$table_prefix.'settings');
			   $rs = mysql_fetch_array($result);
			   if($rs){
				 mysql_query("update ".$table_prefix."settings set
				  system = '".serialize(str_serialize($array_system_param))."',
				  cart = '".serialize(str_serialize($array_cart_param))."',
				  seo = '".serialize(str_serialize($array_seo_param))."',
				  payments = '".serialize(str_serialize($array_payments_gateways_param))."',
				  company_data = '".serialize(str_serialize($array_company_data_param))."'");
			   }else{
				 mysql_query("insert into ".$table_prefix."settings
				 (system,cart,seo,payments,company_data)
				 values
				 (
				  '".serialize(str_serialize($array_system_param))."',
				  '".serialize(str_serialize($array_cart_param))."',
				  '".serialize(str_serialize($array_seo_param))."',
				  '".serialize(str_serialize($array_payments_gateways_param))."',
				  '".serialize(str_serialize($array_company_data_param))."'
				  )");
			   }
   ?>
                  <h1>Success!</h1>
                  <p>BootCommerce has been installed.</p>
                  <table class="form-table install-success">
                      <tr>
                          <th>Username</th>
                          <td><?php echo $user_name; ?></td>
                      </tr>
                      <tr>
                          <th>Password</th>
                          <td><?php echo '<code>'. $admin_password .'</code><br />'; ?></td>
                      </tr>
                  </table>
                  <p class="step"><a href="bc-admin/login.php" class="button button-large">Log In</a></p>
   <?php
			  }
		  break;
	  }
	  mysql_close($conn);
   ?>
</body>
</html>