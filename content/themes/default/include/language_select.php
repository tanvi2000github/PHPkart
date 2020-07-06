<?php 
$dir_language = rel_client_path.'/lang/';  
function read_dir($dir_language){
  if (is_dir($dir_language)) {  
	  if ($directory_handle = opendir($dir_language)) {
		  while (($file = readdir($directory_handle)) !== false) {
			  if((!is_dir($file))&($file!=".")&($file!="..")){		
				$arr_lang[] = $file;		 			 			  
			  }
		  }
		  closedir($directory_handle);
		  return $arr_lang;
	  }  
  }	
}
?>
<select id="default_client_language" class="change_language_on_the_fly bootstyl text-left solid unbordered" name="default_client_language" data-verse="right">
  <?php
  foreach(read_dir($dir_language) as $lang){
    $dir_language = rel_client_path.'/lang/'.$lang; 
    if (is_dir($dir_language)) {  
        if ($directory_handle = opendir($dir_language)) {
            while (($file = readdir($directory_handle)) !== false) {
                if((!is_dir($file))&($file!=".")&($file!="..")){		
                  $file_ext = explode('.',$file);
                  if(end($file_ext) == 'png'){
                      $img = $file;
                      $name = $val = $file_ext[0];
                      echo '<option data-img-before=\'<img src="'.abs_client_path.'/lang/'.$lang.'/'.$img.'" style="margin-right:10px;" />\' value="'.$val.'" '.($val == languageCli ? 'selected' : '').'></option>';
                  }
                }
            }
            closedir($directory_handle);
        }  
    }
  }
  ?>
</select>
