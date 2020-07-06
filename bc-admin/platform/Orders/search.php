<?php
require_once('../../include/inc_load.php');
require_once(rel_admin_path.'/control_login.php');
?>

   <div class="box span3 hide" id="advanced_search">
     <div class="box-header well">
       <h2><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TITLE'] ?></h2>
       <div class="box-icon">
         <span class="btn a_close" style="margin-right:5px;" rel="tooltip" title="<?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TOOLTIP_CLOSE'] ?>"><i class="icon-remove"></i></span>
         <span class="btn" id="close_search" rel="tooltip" title="<?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_TOOLTIP_HIDE_SHOW'] ?>"><i class="icon-chevron-up"></i></span>                     
       </div>                   
     </div>
     <div class="box-content" id="body_search">
       <div class="container-fluid">
            <form id="form_ad_search">
              <strong class="text-info">Date Format: <?php echo $date_format; ?></strong>
              <div class="row-fluid">            
                <input type="text" class="date" name="datefrom_r" id="datefrom_r" value="" data-array="12,6,<?php echo $lang_['orders']['SEARCH_LABEL_DATE'].' '.$lang_['orders']['SEARCH_LABEL_DATE_FROM'] ?>" />
                <input type="text" class="date" name="dateto_r" id="dateto_r" value="" data-array="12,6,<?php echo $lang_['orders']['SEARCH_LABEL_DATE_TO'] ?>" />                
              </div>
              <div class="row-fluid">            
                <input type="text" class="date" name="processingdatefrom_r" id="processingdatefrom_r" value="" data-array="12,6,<?php echo $lang_['orders']['SEARCH_LABEL_PROCESSING_DATE'].' '.$lang_['orders']['SEARCH_LABEL_DATE_FROM'] ?>" />
                <input type="text" class="date" name="processingdateto_r" id="processingdateto_r" value="" data-array="12,6,<?php echo $lang_['orders']['SEARCH_LABEL_DATE_TO'] ?>" />
              </div>                              
              <div class="row-fluid">            
                <input type="text" name="code_r" id="code_r" value="" data-array="12,12,<?php echo $lang_['orders']['SEARCH_LABEL_CODE'] ?>" />
              </div>  
              <div class="row-fluid">
                    <select type="text" name="client_r" id="client_r" data-array="12,12,<?php echo $lang_['orders']['SEARCH_LABEL_CLIENT']; ?>">
                      <option value=""><?php echo $lang_['orders']['SEARCH_LABEL_CLIENT']; ?></option> 
                      <?php
					    $sql_c = execute('select * from '.$table_prefix.'clients');
                        while($rs_c = mysql_fetch_array($sql_c)){
						  echo '<option value="'.$rs_c['id'].'"	>'.ucwords($rs_c['name'].' '.$rs_c['last_name']).'</option>';
						}
                      ?>		  
                    </select>                                         
              </div>  
              <div class="row-fluid">            
                <input type="checkbox" id="processed_r" data-icon="icon-ok icon-white" name="processed_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_PROCESSED']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="notprocessed_r" data-icon="icon-ok icon-white" name="notprocessed_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_NOT_PROCESSED']; ?>" data-additional-classes="btn-info" value="1" />
              </div> 
              <br/>
              <div class="row-fluid">            
                <input type="checkbox" id="payed_r" data-icon="icon-ok icon-white" name="payed_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_PAYED']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="notpayed_r" data-icon="icon-ok icon-white" name="notpayed_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_NOT_PAYED']; ?>" data-additional-classes="btn-info" value="1" />
              </div> 
              <br/>
              <div class="row-fluid">            
                <input type="checkbox" id="guest_r" data-icon="icon-ok icon-white" name="guest_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_GUEST']; ?>" data-additional-classes="btn-info" value="1" />
                <input type="checkbox" id="notguest_r" data-icon="icon-ok icon-white" name="notguest_r" class="bootstyl" data-label-name="<?php echo $lang_['orders']['SEARCH_LABEL_NOT_GUEST']; ?>" data-additional-classes="btn-info" value="1" />
              </div>                            
              <br/><br/>                                                                     
              <div calss="row-fluid">
                  <span class="btn btn-small a_search"><i class="icon-search"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_SEARCH'] ?></span>   
                  <span class="btn btn-small btn-primary a_reset"><i class="icon-white icon-refresh"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_RESET'] ?></span>
                  <!-- <span class="btn btn-small btn-danger a_close"><i class="icon-white icon-remove"></i> <?php echo $lang_['table']['FLEX_ADVANCED_SEARCH_BTN_CLOSE'] ?></span> -->              
              </div>  
            </form>
       </div> 
     </div>
   </div>