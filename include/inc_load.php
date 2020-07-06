<?php
ini_set("session.cookie_httponly", 1);
@session_start();
ini_set('display_errors' , '0');
error_reporting(0);
$is_included = get_included_files();
mb_internal_encoding("UTF-8");
mb_http_input( "UTF-8" );
mb_http_output( "UTF-8" );
include_once('mysql2i.class.php');
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
header('Content-Type: text/html; charset=utf-8');
if (!file_exists(dirname(dirname(__FILE__)) . '/config.php') || !is_writable(dirname(dirname(__FILE__)).'/content/products') || !is_writable(dirname(dirname(__FILE__)).'/content/up-products-images')) {
		$B_language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$B_language = strtolower(substr(chop($B_language[0]),0,2));
	// Set a path for the link to the installer
	if ( strpos($_SERVER['PHP_SELF'], 'bc-admin') !== false ){
	   $arr_admin_path = explode('bc-admin',$_SERVER['PHP_SELF']);
		$path = $arr_admin_path[0];
	}else{
		$path = '';
	}
?>
	<!DOCTYPE html>
	<html lang="<?php echo $B_language; ?>">
	  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="<?php echo $path; ?>include/css/install.css" rel="stylesheet">
	  </head>
	  <body>
        <div style="text-align:center"><img src="<?php echo $path.'bc-admin/img/logo.png'; ?>" style="width:100%;max-width:296px;" /></div>
        <?php
		   if (!file_exists(dirname(dirname(__FILE__)) . '/config.php')){
		?>
            <p>There doesn't seem to be a <code>config.php</code> file. I need this before we can get started.</p>
            <p>You can create a <code>config.php</code> file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file.</p>
            <p><a href="<?php echo $path; ?>setup-config.php" class="button button-large">Create a Configuration File</a>
        <?php
		  }else if (!is_writable(dirname(dirname(__FILE__)).'/content/plugins') ){
			  echo '<p>Sorry, but <code>content/plugins</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if (!is_writable(dirname(dirname(__FILE__)).'/content/updates') ){
			  echo '<p>Sorry, but <code>content/updates</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		  }else if (!is_writable(dirname(dirname(__FILE__)).'/content/uploads') ){
			  echo '<p>Sorry, but <code>content/uploads</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="install.php" class="button button-large">Run the install</a></p>';
		   }else if (!is_writable(dirname(dirname(__FILE__)).'/content/products')){
			  echo '<p>Sorry, but <code>content/products</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="'.$path.'install.php" class="button button-large">Run the install</a></p>';
		   }else if (!is_writable(dirname(dirname(__FILE__)).'/content/up-products-images')){
			  echo '<p>Sorry, but <code>content/products</code> folder has no writable permissions.</p>';
			  echo '<p>Fix the problem manually.</p>';
			  echo '<p>After you&#8217;ve done that, click &#8220;Run the install.&#8221;</p>';
			  echo '<p class="step"><a href="'.$path.'install.php" class="button button-large">Run the install</a></p>';
		   }
		?>
	  </body>
	</html>
<?php
 exit();
}
require_once(dirname(dirname(__FILE__)).'/config.php');
/* database connection */
function execute($rsql=null) {
	global $db_hostname,
	$db_username,
	$db_password,
	$db_name;
	$conn = @mysql_connect($db_hostname,$db_username,$db_password) or die ("Error: could not connect to database");
	@mysql_set_charset('utf8');
	@mysql_select_db($db_name);
	if($rsql != null){
	  $result = @mysql_query($rsql) or die (@mysql_error());
	  return $result;
	  @mysql_close($conn);
	}else{
	  @define('conn',$conn);
	}
}
function is_installed(){
    global $table_prefix;
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
	$result = execute('SHOW TABLES');
	$count_tables = 0;
	while($table = mysql_fetch_array($result)) {
		if(in_array($table[0],$arr_tables)) $count_tables = $count_tables+1;
	}
	if(count($arr_tables) == $count_tables) return true;
	else return false;
}
if(!is_installed()){
	// Set a path for the link to the installer
	if ( strpos($_SERVER['PHP_SELF'], 'admin') !== false ){
	   $arr_admin_path = explode('bc-admin',$_SERVER['PHP_SELF']);
		$path = $arr_admin_path[0];
	}else{
		$path = '';
	}
 header('location:'.$path.'install.php');
 exit();
}
require_once('inc_params.php');
require_once('inc_functions.php');
require_once('inc_paths.php');
/*********** set an Initial Session for Customer *****************/
if(!isset($_COOKIE['initial_user_session']) || !isset($_SESSION['initial_user_session'])) get_initial_user_session();
if ( strpos($_SERVER['PHP_SELF'], 'bc-admin') === false ){
  /* coming soon control */
  if ( strpos($_SERVER['PHP_SELF'], 'coming-soon') === false ){
	if(!isset($_SESSION['Alogged'])){
	  if(!isset($coming_soon) || $coming_soon){
		header('location:'.abs_client_path.'/coming-soon.php');
		exit();
	  }
	}
  }
  /* include language via *.ini file and initialize $lang_client_ variable */
  $lang_client_ = parse_ini_file(rel_client_path.'/lang/'.languageCli.'/'.languageCli.'.ini',true);
 /* include language for each plugin */
 $sql_plu = execute('select * from '.$table_prefix.'plugins where active = 1');
  while($rs_plu = mysql_fetch_array($sql_plu)){
	 if(file_exists(rel_plugins_path.'/'.$rs_plu['shortname'].'/lang/'.languageCli.'/'.languageCli.'.ini')){
		 $parser_language = parse_ini_file(rel_plugins_path.'/'.$rs_plu['shortname'].'/lang/'.languageCli.'/'.languageCli.'.ini',true);
		 $lang_client_ = array_merge($lang_client_,$parser_language);
	 }
	 /* include function file for each plugin */
	 if(file_exists(rel_plugins_path.'/'.$rs_plu['shortname'].'/functions.php')){
		 require_once(rel_plugins_path.'/'.$rs_plu['shortname'].'/functions.php');
	 }
  }
}
 /* set currency position */
$currency_l = !isset($currency_position) || (isset($currency_position) && $currency_position == 'l') ? $currency.' ' : '';
$currency_r = isset($currency_position) && $currency_position == 'r' ? ' '.$currency : '';
/************ set TIME ZONE (NO REMOVE IT) *******/
date_default_timezone_set($time_zone);
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
$paypal_region_array = array(
	"AX" => "Aland Islands",
	"AL" => "Albania",
	"DZ" => "Algeria",
	"AS" => "American Samoa",
	"AD" => "Andorra",
	"AI" => "Anguilla",
	"AQ" => "Antarctica",
	"AG" => "Antigua and Barbuda",
	"AR" => "Argentina",
	"AM" => "Armenia",
	"AW" => "Aruba",
	"AU" => "Australia",
	"AT" => "Austria",
	"AZ" => "Azerbaijan",
	"BS" => "Bahamas",
	"BH" => "Bahrain",
	"BD" => "Bangladesh",
	"BB" => "Barbados",
	"BE" => "Belgium",
	"BZ" => "Belize",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BT" => "Bhutan",
	"BA" => "Bosnia and Herzegovina",
	"BW" => "Botswana",
	"BV" => "Bouvet Island",
	"BR" => "Brazil",
	"IO" => "British Indian Ocean Territory",
	"BN" => "Brunei Darussalam",
	"BG" => "Bulgaria",
	"BF" => "Burkina Faso",
	"CA" => "Canada",
	"CV" => "Cape Verde",
	"KY" => "Cayman Islands",
	"CF" => "Central African Republic",
	"CL" => "Chile",
	"CN" => "China",
	"CX" => "Christmas Island",
	"CC" => "Cocos (Keeling) Islands",
	"CO" => "Colombia",
	"CK" => "Cook Islands",
	"CR" => "Costa Rica",
	"CY" => "Cyprus",
	"CZ" => "Czech Republic",
	"DK" => "Denmark",
	"DJ" => "Djibouti",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"EC" => "Ecuador",
	"EG" => "Egypt",
	"SV" => "El Salvador",
	"EE" => "Estonia",
	"FK" => "Falkland Islands (Malvinas)",
	"FO" => "Faroe Islands",
	"FJ" => "Fiji",
	"FI" => "Finland",
	"FR" => "France",
	"GF" => "French Guiana",
	"PF" => "French Polynesia",
	"TF" => "French Southern Territories",
	"GA" => "Gabon",
	"GM" => "Gambia",
	"GE" => "Georgia",
	"DE" => "Germany",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GR" => "Greece",
	"GL" => "Greenland",
	"GD" => "Grenada",
	"GP" => "Guadeloupe",
	"GU" => "Guam",
	"GG" => "Guernsey",
	"GN" => "Guinea",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HM" => "Heard Island and McDonald Islands",
	"VA" => "Holy See (Vatican City State)",
	"HN" => "Honduras",
	"HK" => "Hong Kong",
	"HU" => "Hungary",
	"IS" => "Iceland",
	"IN" => "India",
	"ID" => "Indonesia",
	"IE" => "Ireland",
	"IM" => "Isle of Man",
	"IL" => "Israel",
	"IT" => "Italy",
	"JM" => "Jamaica",
	"JP" => "Japan",
	"JE" => "Jersey",
	"JO" => "Jordan",
	"KZ" => "Kazakhstan",
	"KI" => "Kiribati",
	"KR" => "Republic of Korea",
	"KW" => "Kuwait",
	"KG" => "Kyrgyzstan",
	"LV" => "Latvia",
	"LS" => "Lesotho",
	"LI" => "Liechtenstein",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"MO" => "Macao",
	"MK" => "Macedonia",
	"MG" => "Madagascar",
	"MW" => "Malawi",
	"MY" => "Malaysia",
	"MT" => "Malta",
	"MH" => "Marshall Islands",
	"MQ" => "Martinique",
	"MR" => "Mauritania",
	"MU" => "Mauritius",
	"YT" => "Mayotte",
	"MX" => "Mexico",
	"FM" => "Federated States of Micronesia",
	"MD" => "Republic of Moldova",
	"MC" => "Monaco",
	"MN" => "Mongolia",
	"ME" => "Montenegro",
	"MS" => "Montserrat",
	"MA" => "Morocco",
	"MZ" => "Mozambique",
	"NA" => "Namibia",
	"NR" => "Nauru",
	"NP" => "Nepal",
	"NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
	"NC" => "New Caledonia",
	"NZ" => "New Zealand",
	"NI" => "Nicaragua",
	"NE" => "Niger",
	"NU" => "Niue",
	"NF" => "Norfolk Island",
	"MP" => "Northern Mariana Islands",
	"NO" => "Norway",
	"OM" => "Oman",
	"PW" => "Palau",
	"PS" => "Palestinian Territory, Occupied",
	"PA" => "Panama",
	"PY" => "Paraguay",
	"PE" => "Peru",
	"PH" => "Philippines",
	"PN" => "Pitcairn",
	"PL" => "Poland",
	"PT" => "Portugal",
	"PR" => "Puerto Rico",
	"QA" => "Qatar",
	"RE" => "Reunion",
	"RO" => "Romania",
	"RS" => "Republic of Serbia",
	"RU" => "Russian Federation",
    "RW" => "Rwanda",
	"SH" => "Saint Helena, Ascension and Tristan da Cunha",
	"KN" => "Saint Kitts and Nevis",
	"LC" => "Saint Lucia",
	"PM" => "Saint Pierre and Miquelon",
	"VC" => "Saint Vincent and the Grenadines",
	"WS" => "Samoa",
	"SM" => "San Marino",
	"ST" => "Sao Tome and Principe",
	"SA" => "Saudi Arabia",
	"SN" => "Senegal",
	"SC" => "Seychelles",
	"SG" => "Singapore",
	"SK" => "Slovakia",
	"SI" => "Slovenia",
	"SB" => "Solomon Islands",
	"ZA" => "South Africa",
	"GS" => "South Georgia and the South Sandwich Islands",
	"ES" => "Spain",
	"SR" => "Suriname",
	"SJ" => "Svalbard and Jan Mayen",
	"SZ" => "Swaziland",
	"SE" => "Sweden",
	"CH" => "Switzerland",
	"TW" => "Taiwan, Province of China",
	"TZ" => "United Republic of Tanzania",
	"TH" => "Thailand",
	"TL" => "Timor-Leste",
	"TG" => "Togo",
	"TK" => "Tokelau",
	"TO" => "Tonga",
	"TT" => "Trinidad and Tobago",
	"TN" => "Tunisia",
	"TR" => "Turkey",
	"TM" => "Turkmenistan",
	"TC" => "Turks and Caicos Islands",
	"TV" => "Tuvalu",
	"UG" => "Uganda",
	"UA" => "Ukraine",
	"AE" => "United Arab Emirates",
	"GB" => "United Kingdom",
	"US" => "United States",
	"UM" => "United States Minor Outlying Islands",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VU" => "Vanuatu",
	"VE" => "Bolivarian Republic of Venezuela",
	"VN" => "Viet Nam",
	"VG" => "Virgin Islands, British",
	"VI" => "Virgin Islands, U.S.",
	"WF" => "Wallis and Futuna",
	"EH" => "Western Sahara",
	"ZM" => "Zambia"
);
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
?>