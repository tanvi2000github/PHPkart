<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
$id_cat = array();

if(isset($_POST['id']) && $_POST['id'] != ''){
  foreach($mptt->get_tree($_POST['id']) as $key => $val){
   $id_cat[] = $val['id'];
  }
  $id_cat[] = $_POST['id'];
 $sql = execute('select '.$table_prefix.'products.*,'.$table_prefix.'categories.name as cat_name
 from '.$table_prefix.'products join '.$table_prefix.'categories on '.$table_prefix.'products.categories = '.$table_prefix.'categories.id where '.$table_prefix.'products.categories IN ('.implode(',',$id_cat).') group by '.$table_prefix.'products.name,'.$table_prefix.'products.categories order by '.$table_prefix.'products.name desc');
}else{
 $sql = execute('select '.$table_prefix.'products.*,'.$table_prefix.'categories.name as cat_name
 from '.$table_prefix.'products join '.$table_prefix.'categories on '.$table_prefix.'products.categories = '.$table_prefix.'categories.id group by '.$table_prefix.'products.name,'.$table_prefix.'products.categories order by '.$table_prefix.'products.name desc');
}
echo '<option value=""></option>';
while($rs = mysql_fetch_array($sql)){
 echo '<option value="'.$rs['id'].'">'.$rs['name'].($rs['categories'] != '' ? ' ('.$rs['cat_name'].')': '').'</option>';	
}
?>
