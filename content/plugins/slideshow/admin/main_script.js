/********************* ADD AND DELETE FIELDS OR DOM ELEMENTS DINAMICALLY ON THE FLY ************************************/
(function(a){a.fn.addInput=function(b){var c={Container_Cloned:".input_container",Required_Class:"requiredx",Numeric_Class:"numericx",Email_Class:"emailx",Max_item:"",Message_Max_Item:"",Date_Class:"datex",Error_Class:"errorx",Delete_Class:"delete",Button_Add:"#addInput",Start_Attribute:"alt",First_Visible:true,Post_Insert:function(){},Post_Remove:function(){}};var b=a.extend(c,b);return this.each(function(){var c=b;var d=a(this),e=d.find(c.Container_Cloned).length,f=d.find(c.Container_Cloned+":first").html(),g=d.find(c.Container_Cloned)[0].attributes,h=[];for(var i=0;i<g.length;i++){if(g[i].name!=c.Start_Attribute){h.push(g[i].name+'="'+g[i].value+'" ')}}var j=d.find(c.Container_Cloned+":first")[0].tagName;if(c.First_Visible||e>1||d.find(c.Container_Cloned+":first input,"+c.Container_Cloned+":first select,"+c.Container_Cloned+":first textarea").val().replace(/^\s+|\s+$/g,"")!=""){d.find(c.Container_Cloned+":first").find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),d=a(this).attr(c.Start_Attribute),e=a(this);e.attr({id:b+"_1",name:d+"_1"});a("."+c.Delete_Class+":first").css("display","none")})}else{a(c.Container_Cloned+":first").remove()}a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);b.bind("change click keyup",function(){if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.hasClass(c.Email_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){b.addClass("w_error_e")}else{b.removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){b.addClass(c.Error_Class)}else{b.removeClass(c.Error_Class)}})});a(c.Button_Add).click(function(){a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.val().replace(/^\s+|\s+$/g,"")!=""&&b.hasClass(c.Email_Class)){if(!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){a(this).addClass("w_error_e")}else{a(this).removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){a(this).addClass(c.Error_Class);a(c.Container_Cloned).find("."+c.Error_Class).focus();a(c.Container_Cloned).find("."+c.Error_Class+":first").focus()}else{b.removeClass(c.Error_Class)}});var t="";for(var i=0;i<h.length;i++){t+=h[i]}if(c.Max_item!=""&&c.Max_item!="udefined"&&a(c.Container_Cloned).length==c.Max_item){alert(c.Message_Max_Item.replace("{Max_item}",c.Max_item));return false}if(!a(c.Container_Cloned).find("input,textarea,select").hasClass(c.Error_Class)){a("."+c.Delete_Class).css("display","");d.append("<"+j+" "+t+">"+f+"</"+j+">");d.find(c.Container_Cloned+":last input,"+c.Container_Cloned+":last select,"+c.Container_Cloned+":last textarea").val("").each(function(){var b=a(this),e=b.attr(c.Start_Attribute),f=b.attr(c.Start_Attribute),g=d.find(c.Container_Cloned).length;b.attr({id:e+"_"+g,name:f+"_"+g})});c.Post_Insert.call(this)}});a(d).delegate("."+c.Delete_Class,"click",function(){var b=a(this).closest(c.Container_Cloned).prevAll(c.Container_Cloned).length;a(this).closest(c.Container_Cloned).nextAll(c.Container_Cloned).each(function(d){d++;var d=d+b;a(this).find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),e=a(this).attr(c.Start_Attribute);a(this).attr({id:b+"_"+d,name:e+"_"+d})})});if(c.First_Visible){if(a(c.Container_Cloned).length==2){a("."+c.Delete_Class).css("display","none")}if(a(c.Container_Cloned).length>1){a(this).closest(c.Container_Cloned).remove()}}else{a(this).closest(c.Container_Cloned).remove()}c.Post_Remove.call(this)})})}})(jQuery);
/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */	  
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_STATUS'), name : 'active', width : 70, sortable : false, align: 'center'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 300, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];

$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,	
	sortname: $('body').data('sortname'),
	searchitems: false,
	sortorder: $('body').data('sortorder'),
	buttons : [ {
		name : __('FLEX_BTN_CHECK_ALL'),
		bclass : 'selectall',
		onpress : flex_buttons
	}, {
		separator : true
	},{
		name : __('FLEX_BTN_UNCHECK_ALL'),
		bclass : 'unselectall',
		onpress : flex_buttons	
	}, {
		separator : true
	}, {
		name : __('FLEX_BTN_DELETE_SELECTED'),
		bclass : 'deleteselected',
		onpress : flex_deleteselected_buttons_custom		
	}, {
		separator : true
	}, {
		name : __('FLEX_BTN_ADD'),
		bclass : 'add_new',
		onpress : flex_buttons
	} ]		
  });
});
function fisrst_active_slideshow(){
 if($('.action-enable.processed').length <= 0){
	$('.action-enable:first').click(); 
 }
}
function flex_deleteselected_buttons_custom(){
	 if($('#table_scroll tr').find('.check_selezione:checked').length > 0){
	   $('#deletemodal').find('.modal-body').html('<i class="icon-trash"></i> '
		+__('custom_DELETING_MULTIPLE_ITEM_ALERT')+'<br/><br/><br/>\
		<span class="btn btn-success ok-delete"><i class="icon-white icon-ok"></i> '+__('general_CONFIRM_BUTTON')+'</span> \
		<span class="btn btn-danger" data-dismiss="modal"><i class="icon-white icon-remove"></i> '+__('general_DISCARD_BUTTON')+'</span>');
	   $('#deletemodal').find('.ok-delete').click(function(){
		   $.ajax({
			 type:'POST',
			 url: 'del_table.php',
			 data: $('#table_scroll *').serialize()+'&tb='+$('body').data('tb')+'&type=multy',
			 success:function(data){   
			   reload_tab();
			   $('#deletemodal').modal('hide'); 
			   setTimeout(function(){
				   fisrst_active_slideshow();
			   },1000);
			 }
		  }); 				 
	   });
	   $('#deletemodal').modal('show');
	 }else{
	   $('#deletemodal').find('.modal-body').html('<div class="alert alert-error alert-block fade in"><i class="icon icon-color icon-cross"></i> '+__('general_NO_ITEM_SELECTED_ALERT')+'</div>');
	   $('#deletemodal').modal('show');
	 }  	
}
/*/////////////////FUNCTION TO DELETE A UNIQUE ELEMENT//////////////*/
function delete_unique_item_custom(id){
   $('#deletemodal').find('.modal-body').html('<i class="icon-trash"></i> '
	+__('custom_DELETING_SINGLE_ITEM_ALERT')+'<br/><br/><br/>\
	<span class="btn btn-success ok-delete"><i class="icon-white icon-ok"></i> '+__('general_CONFIRM_BUTTON')+'</span> \
	<span class="btn btn-danger" data-dismiss="modal"><i class="icon-white icon-remove"></i> '+__('general_DISCARD_BUTTON')+'</span>');
   $('#deletemodal').find('.ok-delete').click(function(){
	   $.ajax({
		type:'POST',
		url:'del_table.php',
		data: ({id:id,type:'unique',tb:$('body').data('tb')}),
		success:function(data){
		 reload_tab();
		 $('#deletemodal').modal('hide'); 
		 setTimeout(function(){
			 fisrst_active_slideshow();
		 },1000);
		}
	   });				 
   });
   $('#deletemodal').modal('show');
}

$('body').on('click','#table_scroll .action-delete-custom',function(){
 var id = $(this).attr('id');	 
 delete_unique_item_custom(id);			 
});
function post_form_add_function(){	
  upload_images();
}
function post_form_edit_function(){	
  upload_images();
}
/* 
 IMAGES UPLOAD FUNCTION
*/
function upload_images(){
  $('#contaienr_upl').addInput({
	 Button_Add : '#addUpl', 
	 Container_Cloned:'.duplicate_upl',
	 Start_Attribute :'alt', 
	 Delete_Class : 'deleteupl',
	 First_Visible : true,
	 Post_Insert:function(){
	   $('#contaienr_upl').find('.duplicate_upl:last .thumbnail')
	   .css('line-height','20px')
	   .html('<img src="'+$('body').data('admin_path_img')+'/img_not_found.jpg" />');	   	  
	 },
	 Post_Remove : function(){
		$('[id^="visible_"]').each(function(){
			$(this).next('div').attr({
				 'rel': $(this).attr('id'),
				 'onclick': '$(\'#'+$(this).attr('id')+':not([readonly]):not([disabled])\').click();'
			});
		});
	 }
  });
  if($('.duplicate_upl').length > 1) $('.deleteupl').css('display',''); 
  $('.edit_file_new').show();
  $('.add_file_new').hide();
}
/* ACTIVE A SLIDESHOW */  
$('body').on('click','#table_scroll .action-enable,#table_scroll .action-showcase',function(){
	var $id = $(this).attr('id'),
	    $status = 'ToProcess',
		$this = $(this)
  if($this.hasClass('processed')) $status = 'DeleteProcess';    
	$.ajax({
		type : 'POST',
		url : 'process_enable.php',
		data : ({status : $status,id : $id}),
		success: function(data){
		 $('#table_scroll').flexReload();	
		}
	});	   	 
});  