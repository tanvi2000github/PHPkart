/********************* ADD AND DELETE FIELDS OR DOM ELEMENTS DINAMICALLY ON THE FLY ************************************/
(function(a){a.fn.addInput=function(b){var c={Container_Cloned:".input_container",Required_Class:"requiredx",Numeric_Class:"numericx",Email_Class:"emailx",Max_item:"",Message_Max_Item:"",Date_Class:"datex",Error_Class:"errorx",Delete_Class:"delete",Button_Add:"#addInput",Start_Attribute:"alt",First_Visible:true,Post_Insert:function(){},Post_Remove:function(){}};var b=a.extend(c,b);return this.each(function(){var c=b;var d=a(this),e=d.find(c.Container_Cloned).length,f=d.find(c.Container_Cloned+":first").html(),g=d.find(c.Container_Cloned)[0].attributes,h=[];for(var i=0;i<g.length;i++){if(g[i].name!=c.Start_Attribute){h.push(g[i].name+'="'+g[i].value+'" ')}}var j=d.find(c.Container_Cloned+":first")[0].tagName;if(c.First_Visible||e>1||d.find(c.Container_Cloned+":first input,"+c.Container_Cloned+":first select,"+c.Container_Cloned+":first textarea").val().replace(/^\s+|\s+$/g,"")!=""){d.find(c.Container_Cloned+":first").find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),d=a(this).attr(c.Start_Attribute),e=a(this);e.attr({id:b+"_1",name:d+"_1"});a("."+c.Delete_Class+":first").css("display","none")})}else{a(c.Container_Cloned+":first").remove()}a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);b.bind("change click keyup",function(){if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.hasClass(c.Email_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){b.addClass("w_error_e")}else{b.removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){b.addClass(c.Error_Class)}else{b.removeClass(c.Error_Class)}})});a(c.Button_Add).click(function(){a(c.Container_Cloned+" input,"+c.Container_Cloned+" select,"+c.Container_Cloned+" textarea").each(function(){var b=a(this);if(b.hasClass(c.Required_Class)){if(b.val().replace(/^\s+|\s+$/g,"")==""){b.addClass("w_error_o")}else{b.removeClass("w_error_o")}}if(b.hasClass(c.Numeric_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&isNaN(b.val())){b.addClass("w_error_n")}else{b.removeClass("w_error_n")}}if(b.hasClass(c.Date_Class)){if(b.val().replace(/^\s+|\s+$/g,"")!=""&&!/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/i.test(b.val())){b.addClass("w_error_d")}else{b.removeClass("w_error_d")}}if(b.val().replace(/^\s+|\s+$/g,"")!=""&&b.hasClass(c.Email_Class)){if(!/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b.val())){a(this).addClass("w_error_e")}else{a(this).removeClass("w_error_e")}}if(b.hasClass("w_error_o")||b.hasClass("w_error_n")||b.hasClass("w_error_d")||b.hasClass("w_error_e")){a(this).addClass(c.Error_Class);a(c.Container_Cloned).find("."+c.Error_Class).focus();a(c.Container_Cloned).find("."+c.Error_Class+":first").focus()}else{b.removeClass(c.Error_Class)}});var t="";for(var i=0;i<h.length;i++){t+=h[i]}if(c.Max_item!=""&&c.Max_item!="udefined"&&a(c.Container_Cloned).length==c.Max_item){alert(c.Message_Max_Item.replace("{Max_item}",c.Max_item));return false}if(!a(c.Container_Cloned).find("input,textarea,select").hasClass(c.Error_Class)){a("."+c.Delete_Class).css("display","");d.append("<"+j+" "+t+">"+f+"</"+j+">");d.find(c.Container_Cloned+":last input,"+c.Container_Cloned+":last select,"+c.Container_Cloned+":last textarea").val("").each(function(){var b=a(this),e=b.attr(c.Start_Attribute),f=b.attr(c.Start_Attribute),g=d.find(c.Container_Cloned).length;b.attr({id:e+"_"+g,name:f+"_"+g})});c.Post_Insert.call(this)}});a(d).delegate("."+c.Delete_Class,"click",function(){var b=a(this).closest(c.Container_Cloned).prevAll(c.Container_Cloned).length;a(this).closest(c.Container_Cloned).nextAll(c.Container_Cloned).each(function(d){d++;var d=d+b;a(this).find("input,select,textarea").each(function(){var b=a(this).attr(c.Start_Attribute),e=a(this).attr(c.Start_Attribute);a(this).attr({id:b+"_"+d,name:e+"_"+d})})});if(c.First_Visible){if(a(c.Container_Cloned).length==2){a("."+c.Delete_Class).css("display","none")}if(a(c.Container_Cloned).length>1){a(this).closest(c.Container_Cloned).remove()}}else{a(this).closest(c.Container_Cloned).remove()}c.Post_Remove.call(this)})})}})(jQuery);
/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_DATE'), name : 'data', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_PROCESSING_DATE'), name : 'process_date', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_CODE'), name : 'code_order', width : 120, align: 'left', sortable : false},
	  {display: __('FLEX_MAIN_TITLE_TOTAL'), name : 'grandtotal', width : 50, sortable : true, align: 'center',valign:'center'},
	  {display: __('FLEX_MAIN_TITLE_PROCESS_STATUS'), name : 'processed_status', width : 50, sortable : true, align: 'center',valign:'center'},
	  {display: __('FLEX_MAIN_TITLE_PAYMENT_STATUS'), name : 'payment_status', width : 50, sortable : true, align: 'center',valign:'center'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 280, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];
/*
 Initialize flexigrid table
*/	
$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,	
	sortname: $('body').data('sortname'),
	sortorder: $('body').data('sortorder')
  });
  $('.add_new').closest('.fbutton').prev('.btnseparator').remove();
  $('.add_new').closest('.fbutton').remove();
});
$('body').on('click','#table_scroll .action-process',function(){
	var $id = $(this).attr('id'),
	    $this = $(this);
  if(!$this.hasClass('processed')){
	 $('#modalprocess').modal('show');	
	  $('#modalprocess').on('click','#btn-process',function(){
		  $.ajax({
			  type : 'POST',
			  url : 'process_orde.php',
			  data : $('input','#modalprocess').serialize()+'&status=ToProcess&id='+$id,
			  success:function(data){
				$('#modalprocess').modal('hide').find('input').val('');
				$('#table_scroll').flexReload();
			  }
		  });	
	  });
  }else{
	$.ajax({
		type : 'POST',
		url : 'process_orde.php',
		data : ({status : 'DeleteProcess',id : $id}),
		success: function(data){
		 $('#table_scroll').flexReload();	
		}
	});		  
  }
});

$('body').on('click','#table_scroll .action-pay:not(".disabled")',function(){
	var $id = $(this).attr('id'),
	    $status = 'ToProcess',
		$this = $(this);
  if($this.hasClass('processed')) $status = 'DeleteProcess';	    
	$.ajax({
		type : 'POST',
		url : 'process_payment.php',
		data : ({status : $status,id : $id}),
		success: function(data){
		 $('#table_scroll').flexReload();	
		}
	});	   	 
});