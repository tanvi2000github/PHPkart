<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
/*******************************************
************** WARNING!!! *******************
** if you want change this file be sure    **
** save it in UTF-8 because there are      **
** some special chars like polish language **
*********************************************
*********************************************/
/*
 This file will contain some general useful functions
*/

/* create $_SERVER['DOCUMENT_ROOT'] variable */
if (! defined("BASE_PATH")) define('BASE_PATH',
isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] :
substr($_SERVER['PATH_TRANSLATED'],0,
-1*strlen($_SERVER['SCRIPT_NAME'])));
$_SERVER['DOCUMENT_ROOT']=BASE_PATH;
$_SERVER['DOCUMENT_ROOT'] = realpath($_SERVER['DOCUMENT_ROOT']);
/* 
 Get a random code
 $random_length variable is the code length, it it null or blank the code length will be 10
*/
function random_cod($random_length = null){
 $r_num = $random_length != null ? $random_length : 10;
 return substr(md5(rand(0, 1000000)), 0, $r_num);
}

/* Function to create a cookie where a random session will be store (need random_cod function()) */
function get_initial_user_session(){
	  global $cookies_persistence;
	  global $path_products;
	  global $client_path;
	$radom_session = random_cod(20);
  if(!isset($_COOKIE['initial_user_session'])){
	@setcookie('initial_user_session', $radom_session, time()+((3600*24)*$cookies_persistence),'/');
  }
  if(!isset($_SESSION['initial_user_session'])) $_SESSION['initial_user_session'] = $radom_session;	
	 return isset($_COOKIE['initial_user_session']) ? $_COOKIE['initial_user_session'] : $_SESSION['initial_user_session'];
}

/* Function to remove an index from an array */
function array_remove_item($arr,$item){
  if(in_array($item,$arr)){
    unset($arr[array_search($item,$arr)]); 
    return array_values($arr);
  }else{
    return $arr;
  }
}
	   
/* Functon to replace in a string the key of an array with it's value ($array variable must be an array) */	   
function replaceKeyVal($str,$array) { 
   return str_replace(array_keys($array), array_values($array), $str);    
} 
/* Functon to replace in a string the value of an array with it's key ($array variable must be an array) */
function replaceKeyValViceversa($str,$array) { 
   return str_replace(array_values($array), array_keys($array), $str);    
}

/* formatting numbers */
function num_formatt($number,$decimal = false,$without_zero_decimal=false){
 global $thousands_separator,
        $decimal_separator;
 $decimal = !$decimal ? 2 : $decimal;
 $number = @number_format($number,$decimal,$decimal_separator,$thousands_separator);
 if($without_zero_decimal){
	 $number = str_replace($decimal_separator.str_repeat('0',$decimal),'',$number); 
 }
 return $number;  
}

/* formatting numbers for inputs value */
function input_num_formatt($number,$decimal = false,$without_zero_decimal=false){
 $decimal = !$decimal ? 2 : $decimal;
 $number = @number_format($number,$decimal,'.','');
 if($without_zero_decimal){
	 $number = str_replace('.'.str_repeat('0',$decimal),'',$number); 
 }
 return $number; 
}

/* Detect if browser is IE */
function detect_ie(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

/* formatting string for database*/
function str_db($string){	
 $string = str_replace(";","####59;",$string);	
 $string = str_replace('&','&amp;',$string);
 $string = str_replace('´','&acute;',$string); 
 $string = str_replace('˜','&tilde;',$string);
 $string = str_replace('<','&lt;',$string);
 $string = str_replace('>','&gt;',$string);
 $string = str_replace('>','&#96;',$string);
 $string = str_replace('\'','&#39;',$string); 
 $string = str_replace('"','&quot;',$string);
 $string = str_replace("\\","\\\\",$string);
 $string = str_replace("####59;","&#59;",$string);
 return ($string);
}

/* formatting string (written with WYSIWYG editor - tinyMCE) for database */
function str_db_content($string){
 $string = str_replace('\'','&#39;',$string);
 $string = str_replace("\\","\\\\",$string);
 return ($string);
}

/* parserize a string as json to insert into db (for flexigrid plugin) */
function str_json($string){
 $string = str_replace('\\','\\\\',$string);
 $string = str_replace('"','\"',$string);
 return $string;
}

/* parserize an array serialization for db */
function str_serialize($string){
 $string = str_replace('\'','&#39;',$string);
 $string = str_replace('"','&quot;',$string);
 $string = str_replace("\\","&#92;",$string);
 $string = str_replace(":","&#58;",$string);   
 return $string;	
}

/* count total record of a DB table */
function countRec($record,$table,$berore_where=NULL,$where=NULL){
$sql = "SELECT count($record) FROM $table $berore_where $where";
$result = execute($sql);
  while ($row = mysql_fetch_array($result)) {
   return $row[0];
  }
}

/* delete a not empty directory */
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir.'/'.$item)) return false;
    }
    return rmdir($dir);
}

/* 
 * delete all files in a directory 
 * if $array_file is not null
 * delete all files that not stay into array ($array_file must be a string comma separated e.g.: file1,file2,file3 -> these files will be not deleted)
*/
function emptyDirectory($dir,$array_file = null) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
	  if($array_file != null){
        $not_file = explode(',',$array_file);    		
        if ($item == '.' || $item == '..' || in_array($item,$not_file)) continue;
		if (!deleteDirectory($dir.'/'.$item)) return false;
	  }else{
		if ($item == '.' || $item == '..') continue;
		if (!deleteDirectory($dir.'/'.$item)) return false;
	  }        
    }
}

/* delete all files with no extension in array ($exts must be a string comma separated e.g.: jpg,bmp,gif -> this extension will be not deleted) */
function emptyDirectory_ext($dir,$exts) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        $not_ext = explode(',',$exts);
        $ext = explode('.',$item);
        $ext_file = end($ext);
        if ($item == '.' || $item == '..' || in_array($ext_file,$not_ext)) continue;
        if (!deleteDirectory($dir.'/'.$item)) return false;
    }
}

/* format date for DB */
function conv_date_db($data){
	global $date_format;
  $array_d_format = explode('/',$date_format);
  $array_date = explode ('/', $data);
  $final_array = array(substr($array_d_format[0],0,1) => $array_date[0],substr($array_d_format[1],0,1) => $array_date[1],substr($array_d_format[2],0,1) => $array_date[2] );
  foreach($final_array as $key => $val){
   if(strtolower($key) == 'd') $d = $val;
   if(strtolower($key) == 'm') $m = $val;
   if(strtolower($key) == 'y') $y = $val;
  } 
  return "$y/$m/$d";
}

/* language for date and time ($lang_w = language for windows, $lang_u = language for unix)*/
function isWin(){
  $sys = strtoupper(PHP_OS); 
    if(substr($sys,0,3) == "WIN"){
        return TRUE;
    }
    return FALSE;
}
function timestamp_lang($lang_w = null,$lang_u = null){
	if($lang_w == null) $lang_w = 'american';
	if($lang_u == null) $lang_u = 'us_US';
  if(isWin()){
	return setlocale(LC_TIME, $timestamp_lang_w);
  }else{
	return setlocale(LC_TIME, $timestamp_lang);  
  }
}
/*
  Difference between 2 dates 
  A - years Difference.
  M - months Difference.
  S - weeks Difference.
  G - days Difference. 
  
  eg: echo datediff("A", "12/04/1978", "11/12/2009"); //return 31 
*/
function datediff($tipo, $partenza, $fine){
	global $date_format;
	switch ($tipo){
		case "A" : $tipo = 365;
		break;
		case "M" : $tipo = (365 / 12);
		break;
		case "S" : $tipo = (365 / 52);
		break;
		case "G" : $tipo = 1;
		break;
	}
	$arr_partenza = explode("/", $partenza);
	$partenza_gg = $date_format == 'dd/mm/yyyy' ? $arr_partenza[0] : $arr_partenza[1];
	$partenza_mm = $date_format == 'dd/mm/yyyy' ? $arr_partenza[1] : $arr_partenza[0];
	$partenza_aa = $arr_partenza[2];
	$arr_fine = explode("/", $fine);
	$fine_gg = $date_format == 'dd/mm/yyyy' ? $arr_fine[0] : $arr_fine[1];
	$fine_mm = $date_format == 'dd/mm/yyyy' ? $arr_fine[1] : $arr_fine[0];
	$fine_aa = $arr_fine[2];
	$date_diff = mktime(12, 0, 0, $fine_mm, $fine_gg, $fine_aa) - mktime(12, 0, 0, $partenza_mm, $partenza_gg, $partenza_aa);
	$date_diff  = floor(($date_diff / 60 / 60 / 24) / $tipo);
	return $date_diff;
}
/* format date for view */
function view_date($data){
	global $date_format;
  $array_d_format = explode('/',$date_format);
  $f_dat = (strtolower(substr($array_d_format[0],0,1)) == 'd' || strtolower(substr($array_d_format[0],0,1)) == 'm') ? strtolower(substr($array_d_format[0],0,1)) : strtoupper(substr($array_d_format[0],0,1));
  $f_dat .= '/';
  $f_dat .= (strtolower(substr($array_d_format[1],0,1)) == 'd' || strtolower(substr($array_d_format[1],0,1)) == 'm') ? strtolower(substr($array_d_format[1],0,1)) : strtoupper(substr($array_d_format[1],0,1));
  $f_dat .= '/';
  $f_dat .= (strtolower(substr($array_d_format[2],0,1)) == 'd' || strtolower(substr($array_d_format[2],0,1)) == 'm') ? strtolower(substr($array_d_format[2],0,1)) : strtoupper(substr($array_d_format[2],0,1));
  return date($f_dat,strtotime($data));
}

/* unix timestamp to Date ($lang_w = language for windows, $lang_u = language for unix) */
function timestampDataTime($dataTime,$lang_w = null,$lang_u = null) {  
	if($lang_w == null) $lang_w = 'american';
	if($lang_u == null) $lang_u = 'us_US';
  $dataTime = str_replace("/","-",view_date($dataTime));
  timestamp_lang($lang_w,$lang_u); 
  return utf8_encode(strftime("%A %d %B %Y",(strtotime($dataTime)))); 
}
 
/* Checks if it's a valid date */ 
function is_valid_date($i_sDate){ 
 global $date_format;
  $blnValid = true;   
  if ( $i_sDate == "00/00/0000" ) { return $blnValid; }
  $pattern = "/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/";
  if(!preg_match ($pattern , $i_sDate)) {
    $blnValid = false;
  } else {
    $arrDate = explode("/", $i_sDate); // break up date by slash
	if($date_format == 'mm/dd/yyyy'){
	  $intMonth = $arrDate[0];
	  $intDay = $arrDate[1];
	}else{
	  $intMonth = $arrDate[1];
	  $intDay = $arrDate[0];		
	}
    $intYear = $arrDate[2];
     
    $intIsDate = checkdate($intMonth, $intDay, $intYear);
     
    if(!$intIsDate) {
      $blnValid = false;
    }
  }
  return ($blnValid);
} 

/* this function will replace a line in a file if it equals the $text_to_replace parameter else,if $text_to_replace = null, it will delete the line */
function changeLineInFile($filename,$string_replace, $text_to_replace = null)
{
  // split the string up into an array
  $file_array = array();
 
  $file = fopen($filename, 'rt');
  if($file)
  {
    while(!feof($file))
    {
      $val = fgets($file);
      if(is_string($val))
        array_push($file_array, $val);
    }
    fclose($file);
  }
 
  // delete from file
  for($i = 0; $i < count($file_array); $i++)
  {
    if(strstr($file_array[$i], $string_replace))
    {
      if($file_array[$i] == $string_replace . "\n"){ 
       if($text_to_replace){
        $file_array[$i] = $text_to_replace. "\n";
       }else{
        $file_array[$i] = '';
       }
      }
      if($file_array[count($file_array)-1] == $string_replace){ 
       if($text_to_replace){
        $file_array[count($file_array)-1] = $text_to_replace;
       }else{
        $file_array[count($file_array)-1] = '';
       }
      }      
    }
  }
 
  // write it back to the file
  $file_write = fopen($filename, 'wt');
  if($file_write)
  {
    fwrite($file_write, implode("", $file_array));
    fclose($file_write);
  }
}

/* GET IP ADDRESS */
function getIpAddress() {
return (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])?
$_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
}

/* calc max upload size ($unit = null,b,B,KB,MB,GB) -> null = MB*/
function getMaxUploadSize($unit = null){
$unit = $unit != null ? $unit : 'MB';	
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
 switch($unit){
	case 'B':
	 return num_formatt($upload_mb*1048576).' B';
	break;
	case 'KB':
	 return num_formatt($upload_mb*1024).' KB';
	break;
	case 'GB':
	 return num_formatt($upload_mb/1024).' BG';
	break;
	default:
	 return num_formatt($upload_mb).' MB';
	break;			
 }
}

/* get current url */
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

/* get last part of current url */
function last_selfURL(){
  $current_url = explode('/',selfURL());
  return end($current_url);
}

/* get site root url (absolute root path) */
function get_root_url(){
	$domain = $_SERVER['HTTP_HOST'];
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
	$path = str_replace( basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['PHP_SELF'] );
	$url = $protocol.'://'.$domain;
	if(mb_substr($url, -1, 1) == '/' || mb_substr($url, -1, 1) == '\\'){
	  $url = mb_substr($url, 0, -1);
	}
	return $url;
}

/* 
 Removes the HTML tags along with their contents: 
  -------------------------------------------------
  Sample text: 
  $text = '<b>sample</b> text with <div>tags</div>'; 
  -------------------------------------------------  
	Result for strip_tags($text): 
	sample text with tags 
	
	Result for strip_tags_content($text): 
	 text with 
	
	Result for strip_tags_content($text, '<b>'): 
	<b>sample</b> text with 
	
	Result for strip_tags_content($text, '<b>', TRUE); 
	 text with <div>tags</div>   
*/
function strip_tags_content($text, $tags = '', $invert = FALSE) { 
  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
  $tags = array_unique($tags[1]); 
    
  if(is_array($tags) && count($tags) > 0) { 
    if($invert == FALSE) { 
      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
    } 
    else { 
      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
    } 
  } 
  elseif($invert == FALSE) { 
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
  } 
  return $text; 
}
/* 
 Natural order of a multidimentional array: 
  -------------------------------------------------
  Sample text: 
  usort($array, build_sorter($order_key,$order_verse)); 
  -------------------------------------------------  
*/  
function build_sorter($key,$sort=NULL) {
 if(strtolower($sort) == 'desc' || $sort == NULL){
    return function ($a, $b) use ($key) {
		return strnatcmp($b[$key], $a[$key]);
    };
 }else{
    return function ($a, $b) use ($key) {
		 return strnatcmp($a[$key], $b[$key]);
    };	 
 }
}
/* 
 Natural multy order of a multidimentional array: 
  -------------------------------------------------
  Sample text: 
  $users = array( 
	  array('id'=>1, 'firstname'=>'Mario', 'lastname'=>'Rossi'), 
	  array('id'=>2, 'firstname'=>'Paolo', 'lastname'=>'Bianchi'), 
	  array('id'=>3, 'firstname'=>'Luca', 'lastname'=>'Neri'), 
	  array('id'=>4, 'firstname'=>'Clauidia', 'lastname'=>'Bianchi'), 
  ); 
  $users = array_msort($users, array('lastname'=>SORT_ASC, 'firstname'=>SORT_ASC));
  -------------------------------------------------  
*/  
function array_msort($array, $cols) { 
    $colarr = array(); 
    foreach ($cols as $col => $order) { 
        $colarr[$col] = array(); 
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); } 
    } 
    $eval = 'array_multisort('; 
    foreach ($cols as $col => $order) { 
        $eval .= '$colarr[\''.$col.'\'],'.$order.','; 
    } 
    $eval = substr($eval,0,-1).');'; 
    eval($eval); 
    $ret = array(); 
    foreach ($colarr as $col => $arr) { 
        foreach ($arr as $k => $v) { 
            $k = substr($k,1); 
            if (!isset($ret[$k])) $ret[$k] = $array[$k]; 
            $ret[$k][$col] = $array[$k][$col]; 
        } 
    } 
    return $ret; 
} 
/*
  Replace the wrong chars (<,>,\,/,",:,*,|) on creating directories and files for a web friendly URL slug from a string.
*/
function filesystem($str, $options = array()){	
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => false,
		'replacements' => array(),
		'transliterate' => true
	);
	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z',
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;	
}
/*  	
  Break off a string to a certain number of characters
*/
function cutOff($str,$charsnum){
  $str_length = mb_strlen($str);
  $str = mb_substr($str,0,$charsnum);
  return $str.($str_length > $charsnum ? '...' : '');
}
/*  	
  ENCRYPTION FOR PASSWORDS
*/
function encryption($str){
 return function_exists('hash') ? hash('sha256',$str) : md5($str);
}
/*
 GET IF IS A VALID EMAIL ADDRESS
*/
function email_exist($email) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
  elseif (!checkdnsrr(array_pop(explode('@',$email)),'MX')) return false;
  else return true;
}
/*
 ALTER DB TABLE TO ADD A COLUMN
*/
function add_column_if_not_exist($table, $column, $column_attr = null ){
	$column_attr = $column_attr == null ? "VARCHAR( 255 ) NOT NULL" : $column_attr;
    $exists = false;
    $columns = @mysql_query("show columns from $table");
    while($c = @mysql_fetch_assoc($columns)){
        if($c['Field'] == $column){
            $exists = true;
            break;
        }
    }      
    if(!$exists){
        mysql_query("ALTER TABLE ".$table." ADD ".$column." ".$column_attr);
    }
}
/*
 GET IF A DB TABLE EXISTS
*/
function table_exists($table){
    $exists = false;
    $tables = @mysql_query("SHOW TABLES");
    while($c = @mysql_fetch_array($tables)){
        if($c[0] == $table){
            $exists = true;
            break;
        }
    }      
   return $exists;
}
/*
 GET IF A COLUMN EXISTS IN A DB TABLE
*/
function column_exists($table, $column){
    $exists = false;
    $columns = @mysql_query("show columns from $table");
    while($c = @mysql_fetch_array($columns)){
        if($c[0] == $column){
            $exists = true;
            break;
        }
    }      
   return $exists;    
}
/*
 GET BOOTCOMMERCE VERSION
*/
if(table_exists($table_prefix.'version')){
${md5('rs_version')} = mysql_fetch_array(execute('select * from '.$table_prefix.'version'));
  if(${md5('rs_version')} && ${md5('rs_version')}['version'] != ''){
	${md5('bc_version')} =  ${md5('rs_version')}['version'];  
  }else{
	${md5('bc_version')} = '1.0.0';  
  }
}
function bc_version(){
	global ${md5('bc_version')};
 if(isset(${md5('bc_version')}))
   return ${md5('bc_version')};
 else
   return '1.0.0';
}
/* 
 CHECK IF A PLUGIN IS INSTALLED - USE SHORTNAME FOR CHECK
*/
if(table_exists($table_prefix.'plugins')){
  ${md5('sql_taxes')} = execute('select * from '.$table_prefix.'plugins where active = 1');
   while(${md5('rs_taxes')} = mysql_fetch_assoc(${md5('sql_taxes')})){
	 ${md5('arr_plugins')}[${md5('rs_taxes')}['shortname']] = ${md5('rs_taxes')};
   }
}
   function plugin_exsists($plugin_name){
	 global ${md5('arr_plugins')};
	if(!isset(${md5('arr_plugins')})){
		 return false;
	}else if(empty(${md5('arr_plugins')}[$plugin_name])){
		 return false;
	}else{	 
	  $result = false; 
	  $result_dependence = true; 
	  $result_bc_version = false;
	  if(!empty(${md5('arr_plugins')}[$plugin_name]['dependence'])){
		$dep_array = explode(',',${md5('arr_plugins')}[$plugin_name]['dependence']);
		foreach($dep_array as $key => $val){
		  if(!array_key_exists($val,${md5('arr_plugins')})){
			  $result_dependence = false;
			  break;
		  }
		}
	  }
	  if(intval(str_replace('.','',${md5('arr_plugins')}[$plugin_name]['min_bc_version_required'])) <= intval(str_replace('.','',bc_version()))){
		$result_bc_version = true;  
	  }
	  if($result_dependence && $result_bc_version) $result = true;	  
	 return $result;
	}
   }
/* 
 SEARCH FILE INTO DIRECTORY AND ITS SUBDIRECTORY - RETURN FALSE IF FILE NOT EXISTS
*/
function find_file($file,$dir=null){
	$cd=$dir==null?getcwd():$dir;
	if(substr($cd,-1,1)!="/")$cd.="/";
	if(is_dir($cd))
	{
		$dh=opendir($cd);
		while($fn=readdir($dh)){		
			if(is_file($cd.$fn)&&$fn==$file){closedir($dh);return $cd.$fn;}
			if($fn!="."&&$fn!=".."&&is_dir($cd.$fn)){$m=find_file($file,$cd.$fn);if($m){closedir($dh);return $m;}}
		}
		closedir($dh);
	}
	return false;
}
/*
  UPLOAD IMAGE WITH RATIO AND THUMBS
  $tempname  = temp name from php upload
  $filename  = name of picture to save
  $newwidth = various width size comma separated (the script will calc the ratio). eg '600,100,50'
  $uploaddir = destination directory
  $defaultimg = if true the script will save a original image too into destination directory
  $compression = image compression (for jpg and jpeg recommend 60, for png 9), if null will be 60 or 9
  p.s. the script will create the destination forlder if it not exists
*/
function upload_resize_img($tempname,$filename,$newwidth,$uploaddir,$defaultimg = false,$compression = false){		
  if(!file_exists($uploaddir)) Mkdir($uploaddir, 0755, true);	  
		$ext = explode('.',$filename);			
/******* resize images **********/      
		$extension = mb_strtolower(end($ext));
	  if($extension=="jpg" || $extension=="jpeg")
	  {
	  $uploadedfile = $tempname;
	  $src = imagecreatefromjpeg($tempname);
	  }
	  else if($extension=="png")
	  {
	  $uploadedfile = $tempname;
	  $src = imagecreatefrompng($tempname);
	  }
	  else 
	  {
	  $src = imagecreatefromgif($tempname);
	  }	  
	  
	  list($width,$height)=getimagesize($tempname);
	  $arr_width = explode(',',$newwidth);
	  foreach($arr_width as $newwidth){
		${'newwidth'.$newwidth} = $newwidth;
		if(!file_exists($uploaddir.'/'.${'newwidth'.$newwidth}.'x'.${'newwidth'.$newwidth})) Mkdir($uploaddir.'/'.${'newwidth'.$newwidth}.'x'.${'newwidth'.$newwidth}, 0755, true);
		${'newheight'.$newwidth}=($height/$width)*${'newwidth'.$newwidth};
		${'tmp'.$newwidth}=imagecreatetruecolor(${'newwidth'.$newwidth},${'newheight'.$newwidth});
		  //imageantialias(${'tmp'.$newwidth},true);
		  imagealphablending(${'tmp'.$newwidth}, false);
		  imagesavealpha(${'tmp'.$newwidth},true);	
		  imagecopyresampled(${'tmp'.$newwidth},$src,0,0,0,0,${'newwidth'.$newwidth},${'newheight'.$newwidth},$width,$height);	
		  if($extension=="jpg" || $extension=="jpeg" )
		  {	
			if(!$compression) $compression = 60;  
			imagejpeg(${'tmp'.$newwidth},$uploaddir.'/'.${'newwidth'.$newwidth}.'x'.${'newwidth'.$newwidth}.'/'.$filename,$compression);
		  }else if($extension=="png")
		  {
		  if(!$compression) $compression = 9;
			imagepng(${'tmp'.$newwidth},$uploaddir.'/'.${'newwidth'.$newwidth}.'x'.${'newwidth'.$newwidth}.'/'.$filename,$compression);
		  }	
		  imagedestroy(${'tmp'.$newwidth});				  
	  }				
	  if($defaultimg){
		$newwidth=$width;
		$tmp=imagecreatetruecolor($newwidth,$height);
		  //imageantialias($tmp,true);
		  imagealphablending($tmp, false);
		  imagesavealpha($tmp,true);	
		  imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$height,$width,$height);	
		  if($extension=="jpg" || $extension=="jpeg" )
		  {	
			if(!$compression) $compression = 60;   
			imagejpeg($tmp,$uploaddir.'/'.$filename,$compression);				  
		  }else if($extension=="png")
		  {
			if(!$compression) $compression = 9; 
			imagepng($tmp,$uploaddir.'/'.$filename,$compression);
		  }	
		  imagedestroy($tmp); 	
	  }
	  imagedestroy($src);		 
} 
?>