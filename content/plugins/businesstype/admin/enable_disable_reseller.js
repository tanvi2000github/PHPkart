$('body').on('click','#enable_resell_request',function(){
   if($(this).prop('checked') == false){
	  if($('#denied_resell_request').prop('checked') == true){
		  $('[rel="denied_resell_request').click();
		  $('[rel="denied_resell_request').prop('checked',false);							
	  }
	  $('#message_resell_request_denied_container').slideUp();
   }
});
$('body').on('click','#denied_resell_request',function(){
   if($(this).prop('checked') == false){
	  if($('#enable_resell_request').prop('checked') == true){
		  $('[rel="enable_resell_request').click();
		  $('[rel="enable_resell_request').prop('checked',false);
	  }
	  $('#message_resell_request_denied_container').slideDown()
   }else{
	  $('#message_resell_request_denied_container').slideUp(); 
   }
});