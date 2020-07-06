/*
 choose-setting buttons
*/
$('.choose-setting.active').addClass('active btn-success').removeClass('btn-info');
$('.choose-setting').click(function(){
	if(!$(this).hasClass('active')){
	  $('.choose-setting').removeClass('active btn-success').addClass('btn-info');
	  $(this).addClass('active btn-success').removeClass('btn-info');	
	}
});
$('.choose-setting').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});
/*
 validate smtp fields
*/
if($('#smtp_email_yes').prop('checked') == true) $('#smtp_port,#smtp_host,#smtp_user,#smtp_password').addClass('required');	
$('body').on('click','#smtp_email_yes',function(){
	$('#smtp_port,#smtp_host,#smtp_user,#smtp_password').addClass('required');	
	$('label[for="smtp_port"].help-inline,label[for="smtp_host"].help-inline,label[for="smtp_user"].help-inline,label[for="smtp_password"].help-inline').css('display','');
});
$('body').on('click','#smtp_email_no',function(){

	$('#smtp_port,#smtp_host,#smtp_user,#smtp_password').removeClass('required');	
	$('label[for="smtp_port"].help-inline,label[for="smtp_host"].help-inline,label[for="smtp_user"].help-inline,label[for="smtp_password"].help-inline').css('display','none');

});
/*
 validate and send form
*/
$('#form_setting').submit(function(){
	if(!$('#form_setting').validate().form()){
	 $id=$(this).find('.control-group.error:first').closest('.tab-pane').attr('id');//.find('input,textarea,select').focus();
	 $('#menu_settings a[href="#'+$id+'"]').click();
	 $(this).find('.control-group.error:first').find('input,textarea,select').focus();
	 return false;	
	}
});
  $('#form_setting').validate();  
  $('#form_setting .save_item').click(function(){          
   $('#form_setting').submit();
   return false
  });
  $('#form_setting input:text,#form_setting input:password').bind('keydown',function(e){
	if(e.keyCode == 13){
	   $('#form_setting').submit();
	   return false;
	}
  }); 
  
  $('#form_setting').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('#form_setting').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'.body-area'});
	 $('#form_setting').find('textarea:visible').each(function(){
	  $(this).val($(this).val().replace(/\n/g,'<br\/>').replace(/\r/g,'<br\/>'))
	 });         	 
	}, 
	complete:function(){
	  $('#form_setting').find('textarea:visible').each(function(){
		  $(this).val($(this).val().replace(/<br\/>/g,'\n'))
	  });  
	},   
	url:'edit_settings.php',
	dataType:'html',	
	success:function(data){ 
	$(".content_container").animate({ scrollTop: "0px" });
	  if ($(data).filter('.error_alert').length > 0){	
	      $('#form_setting').find('.save_item').hide();
		  $('<img src="'+$('body').data('admin_path_img')+'/ajax-loader.gif" alt="" />').insertBefore($('#form_setting').find('.save_item'));	   
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
			  $('#form_setting').find('.save_item').show().prev('img').remove();
		  });		  
	  }else{
          $('#form_setting').find('.save_item').hide();
		  $('<img src="'+$('body').data('admin_path_img')+'/ajax-loader.gif" alt="" />').insertBefore($('#form_setting').find('.save_item'));		  
		  $('#main_table').find('.box-icon').html(''); 
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){								   
								   $.loader.hide(); 
								   $('#form_setting').find('.save_item').show().prev('img').remove();
								   window.location.reload(); 
								 },1500);
								 setTimeout(function(){								   								   
								   $('.errors_control > .alert').alert('close');
								 },3000);								 
								});
		  });			  
	  }
	}
   }); 