/*
 Set the flexigrid table structure
*/
var model = [       
	  //{display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 150, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_VERSION'), name : 'version', width : 70, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_DESCRIPTION'), name : 'description', width : 380, sortable : false, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_ACTIONS'), name : 'actions', width : 300, sortable : false, align: 'center',valign:'center',selezioneclick:false}
	  ];
$(function(){  
  $("#table_scroll").flexigrid({
	url: 'json_list.php',
	colModel : model,	
	sortname: $('body').data('sortname'),
	sortorder: $('body').data('sortorder'),
	searchitems: false,
	buttons : ''
  }); 
});
function post_delete_unique_item(id){
	location.reload();
}
$('body').on('click','#table_scroll .action-enable',function(){
	var $id = $(this).attr('id'),	    
		$pl_name = $(this).attr('data-pl-name'),
		$this = $(this);
		$status = 'ToProcess';
    if($this.hasClass('processed')) $status = 'DeleteProcess';    
	$.ajax({
		type : 'POST',
		url : 'process_enable.php',
		data : ({status : $status,id : $id}),
		complete: function(){
		  location.reload();
		},
		success: function(data){
		 $('#table_scroll').flexReload();	
		}
	});	   	 
});