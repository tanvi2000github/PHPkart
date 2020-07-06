/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_USERID'), name : 'userid', width : 200, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_EMAIL'), name : 'email', width : 200, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_PHONE'), name : 'phone', width : 120, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_STATUS'), name : 'enabled', width : 50, sortable : true, align: 'center'},
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
});
function submit_form($url){
			   /*///// REGISTRATION FORM /////*/
			   $('#container_form form:first').validate({
				    rules:{
					  userid:{
						 remote:{
							url:'registration-control.php',
							type: 'POST',
							data:{
							  type:function(){
								 return 'userid';  
							  },
							  action:function(){
								 return $url == 'add.php' ? 'add' : 'edit';  
							  }
							}
						 }
					  },
					  email:{
						 remote:{
							url:'registration-control.php',
							type: 'POST',
							data:{
							  type:function(){
								 return 'email';  
							  },
							  action:function(){
								 return $url == 'add.php' ? 'add' : 'edit';  
							  }							  
							}
						 }						  
					  }
					},
					messages:{
					  userid: {remote:__('INSERT_UPDATE_DUPLICATE_USERID_ERROR')},
					  email: {remote:__('INSERT_UPDATE_DUPLICATE_EMAIL_ERROR')}
					},
					ignore:'.ignore'
				});	
				/*** choose private or company form ***/
				 setTimeout(function(){
					if($(':radio[name="is_company"]:checked','#container_form form:first').attr('id') == 'company'){
						$('#lastname','#container_form form:first').val('').addClass('ignore').closest('.control-group').hide();
						$('#tax_code','#container_form form:first').removeClass('ignore').closest('.control-group').closest('.row-fluid').removeClass('hidden');
					}					
				 },100);
				$(':radio[name="is_company"]','#container_form form:first').on('click',function(){
					if($(this).prop('checked') == false && $(this).val() == 'private'){
					  	$('#lastname','#container_form form:first').val('').removeClass('ignore').closest('.control-group').show();
						$('#tax_code','#container_form form:first').addClass('ignore').closest('.control-group').closest('.row-fluid').addClass('hidden');
					}
					if($(this).prop('checked') == false && $(this).val() == 'company'){
						$('#lastname','#container_form form:first').val('').addClass('ignore').closest('.control-group').hide();
						$('#tax_code','#container_form form:first').removeClass('ignore').closest('.control-group').closest('.row-fluid').removeClass('hidden');
					}					
				});
				$('#container_form form:first').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#container_form form:first').validate({ignore:'.ignore'}).form();           
				  },
				  dataType:'html', 
				  url:$url				  
				});		
}
function post_form_add_function(){	
    submit_form('add.php');
}
function post_form_edit_function(){
	submit_form('edit.php');
}
$('body').on('click','#table_scroll .action-enable',function(){
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