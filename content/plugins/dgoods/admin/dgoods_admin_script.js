$('body').on('click','#digital',function(){
   if($(this).prop('checked') == true){
	  $('#digital_file_upload_container').slideUp();
   }else{
	  $('#digital_file_upload_container').slideDown(); 
   }
});
$('body').on('click','#pl_delete_demo',function(){
   if($(this).prop('checked') == true){
	  $('#digital_demo_file_upload_container').slideUp();
   }else{
	  $('#digital_demo_file_upload_container').slideDown(); 
   }
});