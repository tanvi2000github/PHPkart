/*
 Set the flexigrid table structure
*/
var model = [       
	  {display: '', name : 'check', width : 15, sortable : false, align: 'left',align_body:'left',listable:false}, /* not change this line */
	  {display: __('FLEX_MAIN_TITLE_NAME'), name : 'name', width : 180, sortable : true, align: 'left'},
	  {display: __('FLEX_MAIN_TITLE_USERID'), name : 'userid', width : 220, sortable : true, align: 'left'},
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
	sortorder: $('body').data('sortorder')
  });
});