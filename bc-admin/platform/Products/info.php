<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
require_once('general_tags.php');
require_once(rel_client_path.'/include/lib/Zebra_Mptt.php');
$mptt = new Zebra_Mptt();
 $sql = 'select * from '.$table_name.' where id = '.$_POST['id'];
 $rs_result = execute($sql);
 $rs = mysql_fetch_array($rs_result);
 $img_product = $rs['url_image'] != '' ? path_abs_img_products.'/'.$rs['id'].'/300x300/1_'.$rs['url_image'] : path_img_back.'/img_not_available.jpg';
 echo '<span id="label">'.ucwords($rs['name']).'</span>';
 echo '<div id="body">
 <div class="row-fluid">
   <div class="span4 text-center" style="margin-bottom:10px;"><img src="'.$img_product.'" alt="" width="75%" /></div>
   <div class="span8">
	  <table class="table table-striped table-bordered table-condensed table-hover">
		  <tr>
			  <td align="left" valign="top">'.$lang_['products']['INFO_CODE'].'</td>
			  <td align="left" valign="top">'.$rs['code'].'</td>
		  </tr>
		  <tr>
			  <td align="left" valign="top">'.$lang_['products']['INFO_AVAILABILITY'].'</td>
			  <td align="left" valign="top">'.(!$rs['unlimited_availability'] ? num_formatt($rs['availability'],2,true).' '.$rs['units'] : 'Unlimited').'</td>
		  </tr>
		  <tr>
			  <td align="left" valign="top">'.$lang_['products']['INFO_CATEGORY'].'</td>
			  <td align="left" valign="top">'.$mptt -> get_orizzontal($rs['categories'],' > ').'</td>
		  </tr>
		  <tr>
			  <td align="left" valign="top">'.$lang_['products']['INFO_PRICE'].'</td>
			  <td align="left" valign="top">'.num_formatt($rs['price']).'</td>
		  </tr>
		  <tr>
			  <td align="left" valign="top">'.$lang_['products']['INFO_OFFER'].'</td>
			  <td align="left" valign="top">'.($rs['offer'] > 0 ? num_formatt($rs['offer']) : '').'</td>
		  </tr>
		  <tr>
			  <td align="left" valign="top">'.$tax_name.'</td>
			  <td align="left" valign="top">'.num_formatt($rs['tax'],2,true).'%</td>
		  </tr>';
				if(plugin_exsists('multitaxes')){
				 if($rs['pl_multitax'] != ''){
					foreach(explode(',',$rs['pl_multitax']) as $id){
						echo '<tr>
							<td align="left" valign="top">'.get_tax_param($id,'name').':</td>
							<td align="left" valign="top">'.num_formatt(get_tax_param($id,'percentage'),2,true).'%</td>
						</tr>';
					}
				 }
				}
	  echo '</table>
   </div>
 </div>';
 if($rs['attributes'] != ''){
?>
  <strong class="text-info"><?php echo $lang_['products']['INFO_ATTRIBUTES']; ?></strong>
            <table class="table table-striped table-condensed table-bordered table-hover" >
                <tbody>
                 <?php
				  foreach(unserialize($rs['attributes']) as $key => $val){
			     ?>
                    <tr>
                        <td><strong><?php echo $val['attribute_name']; ?></strong></td>
                        <td><?php echo $val['attribute_value']; ?></td>
                    </tr>
                 <?php
				  }
				 ?>
                </tbody>
            </table>
</div>
<?php
 }
	  if(plugin_exsists('dgoods') && $rs['pl_digital']){
			  echo '<a href="'.abs_plugins_path.'/dgoods/download_admin.php?Adcode='.$rs['pl_digital_code'].'" class="btn btn-info btn-medium">'.$lang_['pl_dgoods']['DOWNLOAD_LINK'].'</a>';
	  }
?>