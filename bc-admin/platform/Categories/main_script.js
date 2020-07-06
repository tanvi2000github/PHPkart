/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 120, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_LEVEL'), name : 'level', width : 60, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_TREE_PATH'), name : 'tree_path', width : 480, sortable : true, align: 'left'},		
	  {display: __('FLEX_MAIN_TITLE_STATUS'), name : 'status', width : 50, sortable : true, align: 'center'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 150, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];	  
/*
 Initialize flexigrid table actions
*/	
/* submition add/edit form */
  $('#container_form form:first input:text,#container_form form:first input:password').on('keydown',function(e){
	if(e.keyCode == 13){
	   $('#container_form form:first').submit();
	   return false;
	}
  }); 
  $('#editmodal,#addmodal').on('click','.save_item',function(){          
   $('#container_form form:first:visible').submit();
   return false
  });  
function add_category(){    
  $('#addmodal').modal('show'); 
  $('#container_form form:first').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('#container_form form:first').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'#conteiner_form_loader'});        	 
	},    
	url:'add.php',
	dataType:'html',
	success:function(data){ 
	  if ($(data).filter('.error_alert').length > 0){		   
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
		  });		  
	  }else{
		  $('#category,#meta_description,#meta_keywords','#container_form form').val('');	  	 
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){									 								   
								   $.loader.hide(); 
									$.post(
									  'form_add.php',
									  function(data){
									   $('#categories_list').html($(data).find('#categories_list').html());
									  }              
									);
									reload_tab();
									$('.errors_control').slideUp('slow');								   
								 },1500);
								});
		  });			  
	  }
	}
   });	
}
$('#addmodal').on('hidden',function(){
	$('#addmodal').find('.modal-body').html('');
});
$('#editmodal').on('hidden',function(){
	$('#editmodal').find('.modal-body').html('');
});
function flex_add_buttons_custom(){
	   $.ajax({
		  type:'POST',
		  url : 'form_add.php',		
		  success:function(data){
			$('#addmodal').find('.modal-body').html(data);	
		  },
		  complete:function(){ 			  		  
			  add_category();
		  }
	   });   	
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
			 }
		  }); 				 
	   });
	   $('#deletemodal').modal('show');
	 }else{
	   $('#deletemodal').find('.modal-body').html('<div class="alert alert-error alert-block fade in"><i class="icon icon-color icon-cross"></i> '+__('general_NO_ITEM_SELECTED_ALERT')+'</div>');
	   $('#deletemodal').modal('show');
	 }  	
}

$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,	
	sortname: $('body').data('sortname'),
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
		onpress : flex_add_buttons_custom
	} ]		
  });
});
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
		}
	   });				 
   });
   $('#deletemodal').modal('show');
}

$('body').on('click','#table_scroll .action-delete-custom',function(){
 var id = $(this).attr('id');	 
 delete_unique_item_custom(id);			 
});

/*/////////////// FUNCTION TO EDIT ITEM /////////////////////////*/
	  
function edit_category(){    
  $('#editmodal').modal('show');
  $('#container_form form:first').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('#container_form form:first').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'#conteiner_form_loader'});        	 
	},    
	url:'edit.php',
	dataType:'html',
	success:function(data){ 
	  if ($(data).filter('.error_alert').length > 0){		   
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
		  });		  
	  }else{		  		  	 
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){									 								   
								   $.loader.hide(); 
									$.post(
									  'form_edit.php',{id:$('#container_form form:first').find('input[name="id"]').val()},
									  function(data){
									   $('#categories_list').html($(data).find('#categories_list').html());
									   $('[name="level"]').val($(data).find('[name="level"]').val());
									  }              
									);
									reload_tab();
									$('.errors_control').slideUp('slow');								   
								 },1500);
								});
		  });			  
	  }
	}
   });
}
$('body').on('click','#table_scroll .action-edit-custom',function(){
 var id = $(this).attr('id');	 
 $.ajax({
	type:'POST',
	url : 'form_edit.php',	
	data:({id:id}),		
	success:function(data){
	  $('#editmodal').find('.modal-body').html(data);	
	},
	complete:function(){ 			  		  
		edit_category();
	}
 });  		 
});
$('body').on('click','#table_scroll .action-enable:not(".disabled")',function(){
	var $id = $(this).attr('id'),
	    $status = 'ToProcess',
		$this = $(this);
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