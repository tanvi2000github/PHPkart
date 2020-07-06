/********************* ADD AND DELETE FIELDS OR DOM ELEMENTS DINAMICALLY ON THE FLY ************************************/
(function(a){a.fn.addInput=function(b){var c={Container_Cloned:".input_container",Required_Class:"requiredx",Numeric_Class:"numericx",Email_Class:"emailx",Max_item:"",Message_Max_Item:"",Date_Class:"datex",Error_Class:"errorx",Delete_Class:"delete",Button_Add:"#addInput",Start_Attribute:"alt",First_Visible:true,Post_Insert:function(){},Post_Remove:function(){}};var b=a.extend(c,b);return this.each(function(){var c=b;var d=a(this),e=d.find(c.Container_Cloned).length,f=d.find(c.Container_Cloned+":first").html(),g=d.find(c.Container_Cloned)[0].attributes,h=[];for(var i=0;i<g.length;i++){if(g[i].name!=c.Start_Attribute){h.push(g[i].name+'="'+g[i].value+'" ')}}var j=d.find(c.Container_Cloned+":first")[0].tagName;if(c.First_Visible||e>1||d.find(c.Container_Cloned+":first input,"+c.Container_Cloned+":first select,"+c.Container_Cloned+":first textarea").val().replace(/^\s+|\s+$/g,"")!=""){d.find(c.Container_Cloned+":first").find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),d=a(this).attr(c.Start_Attribute),e=a(this);e.attr({id:b+"_1",name:d+"_1"});a("."+c.Delete_Class+":first").css("display","none")})}else{a(c.Container_Cloned+":first").remove()}a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);b.bind("change click keyup",function(){if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.hasClass(c.Email_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){b.addClass("w_error_e")}else{b.removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){b.addClass(c.Error_Class)}else{b.removeClass(c.Error_Class)}})});a(c.Button_Add).click(function(){a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.val().replace(/^\s+|\s+$/g,"")!=""&&b.hasClass(c.Email_Class)){if(!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){a(this).addClass("w_error_e")}else{a(this).removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){a(this).addClass(c.Error_Class);a(c.Container_Cloned).find("."+c.Error_Class).focus();a(c.Container_Cloned).find("."+c.Error_Class+":first").focus()}else{b.removeClass(c.Error_Class)}});var t="";for(var i=0;i<h.length;i++){t+=h[i]}if(c.Max_item!=""&&c.Max_item!="udefined"&&a(c.Container_Cloned).length==c.Max_item){alert(c.Message_Max_Item.replace("{Max_item}",c.Max_item));return false}if(!a(c.Container_Cloned).find("input,textarea,select").hasClass(c.Error_Class)){a("."+c.Delete_Class).css("display","");d.append("<"+j+" "+t+">"+f+"</"+j+">");d.find(c.Container_Cloned+":last input,"+c.Container_Cloned+":last select,"+c.Container_Cloned+":last textarea").val("").each(function(){var b=a(this),e=b.attr(c.Start_Attribute),f=b.attr(c.Start_Attribute),g=d.find(c.Container_Cloned).length;b.attr({id:e+"_"+g,name:f+"_"+g})});c.Post_Insert.call(this)}});a(d).delegate("."+c.Delete_Class,"click",function(){var b=a(this).closest(c.Container_Cloned).prevAll(c.Container_Cloned).length;a(this).closest(c.Container_Cloned).nextAll(c.Container_Cloned).each(function(d){d++;var d=d+b;a(this).find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),e=a(this).attr(c.Start_Attribute);a(this).attr({id:b+"_"+d,name:e+"_"+d})})});if(c.First_Visible){if(a(c.Container_Cloned).length==2){a("."+c.Delete_Class).css("display","none")}if(a(c.Container_Cloned).length>1){a(this).closest(c.Container_Cloned).remove()}}else{a(this).closest(c.Container_Cloned).remove()}c.Post_Remove.call(this)})})}})(jQuery);
/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_IMAGE'), name : 'url_image', width : 70, sortable : false, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_PRICE'), name : 'price', width : 80, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_OFFER'), name : 'offer', width : 80, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_CATEGORIES'), name : 'categories', width : 220, align: 'left', sortable : false},
	  {display: __('FLEX_MAIN_TITLE_CODE'), name : 'code', width : 70, sortable : true, align: 'center',valign:'center'},
	  {display: __('FLEX_MAIN_TITLE_AVAILABILITY'), name : 'availability', width : 50, sortable : true, align: 'center',valign:'center'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 300, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];
/*
 Initialize flexigrid table
*/	
function post_form_add_function(){	
  $('#container_form form:first').submit(function(event){
      /* to save description with tinyMCE with prevent.Default() function */
      tinyMCE.triggerSave();	  
	   /* validate on fields wrapped into a "TAB" style */	  
		if(!$(this).validate().form()){
		 $id=$(this).find('.control-group.error:first').closest('.tab-pane').attr('id');
		 $('#tab_head a[href="#'+$id+'"]').click();
		 $(this).find('.control-group.error:first').find('input,textarea,select').focus();
		 event.preventDefault();	
		}   
  });
  $('#tab_head > li').click(function(){
	  if($('.duplicate_upl').length <= 0) $('#addUpl').click();  	
  });
  upload_images();
  add_remove_filter_options();
  tinyMCEinit();
}
function post_form_edit_function(){	
  $('.edit_file_new').show();
  $('.add_file_new').hide();	
  setTimeout(function(){$('.delAttributes').css('display','');},100);
  $('#container_form form:first').submit(function(event){  
      /* to save description with tinyMCE with prevent.Default() function */
      tinyMCE.triggerSave();
	  /* validate on fields wrapped into a "TAB" style */	
		if(!$(this).validate().form()){
		 $id=$(this).find('.control-group.error:first').closest('.tab-pane').attr('id');
		 $('#tab_head a[href="#'+$id+'"]').click();
		 $(this).find('.control-group.error:first').find('input,textarea,select').focus();
		 event.preventDefault();	
		}
  }); 
  upload_images();
  add_remove_filter_options();
  tinyMCEinit();
}
$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,	
	sortname: $('body').data('sortname'),
	sortorder: $('body').data('sortorder')
  });
});
/* 
 IMAGES UPLOAD FUNCTION
*/
function upload_images(){
  $('#contaienr_upl').addInput({
	 Button_Add : '#addUpl', 
	 Container_Cloned:'.duplicate_upl',
	 Start_Attribute :'alt', 
	 Delete_Class : 'deleteupl',
	 First_Visible : false,
	 Post_Insert:function(){
	   $('#contaienr_upl').find('.duplicate_upl:last .thumbnail')
	   .css('line-height','20px')
	   .html('<img src="'+$('body').data('admin_path_img')+'/img_not_found.jpg" />');	  
	 }
  });
  if($('.duplicate_upl').length > 1) $('.deleteupl').css('display',''); 
  $('.edit_file_new').show();
  $('.add_file_new').hide();
}
/* 
 ADD REMOVE FILTERS' FIELDS
*/
function add_remove_filter_options(){
  $('#general-attributes-container').addInput({
	 Button_Add : '#addAttributes', 
	 Container_Cloned:'.attributes-container',
	 Start_Attribute :'alt', 
	 Delete_Class : 'delAttributes', 
	 Required_Class : 'required-add',
	 First_Visible : true
  });
}
function tinyMCEinit(){
  /*  TINYMCE SESSION FOR PRODUCT DESCRIPTION */  
	tinymce.remove('description');
	setTimeout(function(){
	 tinymce.init({
		selector: "textarea#description",
		theme: "modern",
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
			 "save table contextmenu directionality emoticons template paste textcolor"
	   ],  
	   language :  $('body').data('languageA'), 
	   forced_root_block : false,
	   toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print fullscreen | forecolor backcolor"
	 });   
	},100);	
}
/* 
 ADD REMOVE PRODUCT OPTIONS
*/
$('body').on('click','#addOption',function(){									  
     empty_option = $('.option_name','#general-options-container').filter(function(){
		 return $(this).val().replace(/^\s*(\S*(\s+\S+)*)\s*$/, "$1") == '';
	 });     
	 if(empty_option.length >= 1){
		  empty_option.first().focus();
		  return false;                             
	 }
	var max = $('.option_name','#general-options-container').length <= 0 ? -1 : 0;
	$('.option_name','#general-options-container').each(function(){
		if(parseFloat($(this).attr('data-option-number'))>max){
			 max = parseFloat($(this).attr('data-option-number'));
		}
	});
	$('#general-options-container').append('<div class="options-container well" style="background-color:#dedede;border-bottom:2px solid #ccc;margin-bottom:10px;">\
											   <div class="row-fluid">\
												 <div class="span10">\
												  <input type="text" class="option_name" data-option-number="'+(max+1)+'" name="noption['+(max+1)+'][name]" value="" data-array="12,6,'+__('PRODUCT_OPTION_NAME')+'" />\
												  <div class="span6">\
												    <label>&nbsp;</label> <input type="checkbox" id="required_option_'+(max+1)+'" data-icon="icon-ok icon-white" name="noption['+(max+1)+'][required_option]" class="bootstyl" data-label-name="'+__('PRODUCT_OPTION_MANDATORY_SELECTION')+'" data-additional-classes="btn-info" value="1" />\
												   </div>\
												 </div>\
												 <div class="span2"><i class="icon32 icon-gray icon-trash delOption" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass(\'icon-gray icon-color\');" onmouseout="$(this).toggleClass(\'icon-gray icon-color\');"></i></div>\
											   </div>\
											   <div class="row-fluid">\
											     <div class="span10 offset1 container-option-value"></div>\
												 <span class="btn btn-success add-value pull-right"><i class="icon-white icon-plus"></i> '+__('PRODUCT_ADD_OPTION_VALUES')+'</span>\
												 <div class="clearfix"></div>\
											   </div>\
											  </div>');										
});
$('body').on('click','#general-options-container .options-container .delOption',function(){
 $(this).closest('.options-container').fadeOut('fast',function(){$(this).remove();});
});
/* 
 ADD REMOVE PRODUCT OPTIONS VALUES
*/
$('body').on('click','#general-options-container .options-container .add-value',function(){
	var max = $(this).closest('.options-container').find('.option_value').length <= 0 ? -1 : 0;
	$(this).closest('.options-container').find('.option_value').each(function(){
		if(parseFloat($(this).attr('data-option-number'))>max){
			 max = parseFloat($(this).attr('data-option-number'));
		}
	});	
	var option_number = $(this).closest('.options-container').find('.option_name').attr('data-option-number');
	$(this).closest('.options-container').find('.container-option-value').append('<div class="row-fluid subcontainer-option-value well well-small">\
	            <input type="text" class="option_value required" data-option-number="'+(max+1)+'" name="noption['+option_number+'][voption]['+(max+1)+'][value]" value="" data-array="12,4,'+__('PRODUCT_ADDITIONAL_OPTIONS_VALUE_DESCRIPTION')+'" />\
				<input type="text" class="number required" name="noption['+option_number+'][voption]['+(max+1)+'][price]" value="" data-array="12,2,'+__('PRODUCT_ADDITIONAL_OPTIONS_VALUE_PRICE')+'" />\
				<select class="required" name="noption['+option_number+'][voption]['+(max+1)+'][type]" value="" data-array="12,2,'+__('PRODUCT_ADDITIONAL_OPTIONS_VALUE_TYPE')+'">\
				  <option value="+" selected>+</option>\
				  <option value="-">-</option>\
				</select>\
				<div class="span2"><i class="icon32 icon-gray icon-trash delOptionValue" style="margin-top:20px;cursor:pointer;" onmouseover="$(this).toggleClass(\'icon-gray icon-color\');" onmouseout="$(this).toggleClass(\'icon-gray icon-color\');"></i></div>\
				</div>');
});
$('body').on('click','#general-options-container .options-container .delOptionValue',function(){
 $(this).closest('.subcontainer-option-value').fadeOut('fast',function(){$(this).remove();});
});
$('body').on('click','#unlimited_availability',function(){
   if($(this).prop('checked') == false){
	$('#availability').removeClass('required');	
	$('label[for="availability"].help-inline').css('display','none');
   }else{
	$('#availability').addClass('required');
   }
});
/* 
 CLONE PRODUCT
*/
$('body').on('change','#product_model',function(){
	$.loader();
	$this = $(this),
	$url = $this.val() != '' ? 'form_edit.php' : 'form_add.php';
		$.ajax({
			type:'POST',
			url:$url,
			complete:function(){
			  tinyMCEinit();
			  $.loader.hide();	
			},
			data:{id:$(this).val()},
			success: function(data){				
			  $.each(['#tab_general','#tab_additional_options','#tab_seo'],function(indext,value){
				 $this.closest('#conteiner_form_loader').find(value).html($(data).find('.tab-content '+value).html()); 
			  });
			  add_remove_filter_options();			  	  		  
			}		
		});
});
$('body').on('change','#category_filter',function(){	
    $('#product_model').val('');
       $.loader();
		$.ajax({
			type:'POST',
			url:'model_search.php',
			complete:function(){			  			
			  $.loader.hide();	
			},
			data:{id:$(this).val()},
			success: function(data){				
              $('#product_model').html(data);				  		  	  		  
			}		
		});	
});
/* 
 ADD CATEGORIES ON THE FLY
*/
function add_category(){    
  $('#addmodalcategories').modal('show'); 
  $('form','#addmodalcategories').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('form','#addmodalcategories').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'#addmodalcategories form'});        	 
	},    
	url:'../Categories/add.php',
	dataType:'html',
	success:function(data){ 
	  if ($(data).filter('.error_alert').length > 0){		   
		  $('.errors_control','#addmodalcategories').slideUp('slow',function(){
			  $('.errors_control','#addmodalcategories').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
		  });		  
	  }else{
		  $default_val = $('#category').val();
		  $('#category,#meta_description,#meta_keywords','#container_form form').val('');	  	 
		  $('.errors_control','#addmodalcategories').slideUp('slow',function(){
			  $('.errors_control','#addmodalcategories').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){									 								   
								   $.loader.hide(); 
									$.post(
									  '../Categories/form_add.php',
									  function(data){									   
									   $('#categories_list').html($(data).find('#categories_list').html());
									  }              
									);
									$.post(
									  'form_add.php',
									  function(data){									   
									   $('#category').html($(data).find('select#category').html()).val($default_val);
									   $('#category_filter').html($(data).find('select#category_filter').html()).val('').change();
									  }              
									);																	
									$('.errors_control','#addmodalcategories').slideUp('slow');								   
								 },1500);
								});
		  });			  
	  }
	}
   });	
}
$('#addmodalcategories').on('hidden',function(){
	$('#addmodalcategories').find('.modal-body').html('');
});
$('body').on('click','#add_category',function(){
	   $.ajax({
		  type:'POST',
		  url : '../Categories/form_add.php',		
		  success:function(data){
			$('#addmodalcategories').find('.modal-body').html(data);	
		  },
		  complete:function(){ 			  		  
			  add_category();
		  }
	   }); 
});
  $('#addmodalcategories').on('keydown','form input:text,form input:password',function(e){
	if(e.keyCode == 13){
	   $('form','#addmodalcategories').submit();
	   return false;
	}
  }); 
  $('#addmodalcategories').on('click','.save_item',function(){          
   $('form','#addmodalcategories').submit();
   return false
  }); 
  
$('body').on('click','#table_scroll .action-enable,#table_scroll .action-showcase',function(){
	var $id = $(this).attr('id'),
	    $status = 'ToProcess',
		$this = $(this),
		$type = $this.hasClass('action-showcase') ? 'showcase' : 'enable';
  if($this.hasClass('processed')) $status = 'DeleteProcess';    
	$.ajax({
		type : 'POST',
		url : 'process_enable.php',
		data : ({status : $status,id : $id,type:$type}),
		success: function(data){
		 $('#table_scroll').flexReload();	
		}
	});	   	 
});  