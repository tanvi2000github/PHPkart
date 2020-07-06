/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_PERCENTAGE'), name : 'percentage', width : 220, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 160, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];
/*
 Initialize flexigrid table
*/	
$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,
	sortname: $('body').data('sortname'),
	sortorder: $('body').data('sortorder'),
	searchitems: false,
	buttons : [ {
		name : __('FLEX_BTN_ADD'),
		bclass : 'add_new',
		onpress : flex_buttons
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
		data: ({id:id}),
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