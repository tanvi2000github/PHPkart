/* change function on limiter and sort products */
$("#products_limiter_counter,#products_sortby").on('change',function() {
  window.location = $(this).find("option:selected").val();
});
$(function(){	
	/* FILTER PRICE SLIDER */			
	$('.accordion-filters').accordion_menu();
	$('.accordion-filters').each(function() {
        if($('li.active',$(this)).length > 0)
		  $('ul',this).css('display','block');	
    });
});
    $('[for="sp_ew"]').on('click',function(){
		$('#sp_d,#sp_mk').prop({'checked':false,'disabled':true});
		$('[for="sp_d"],[for="sp_mk"]').addClass('disabled').removeClass('checked');
	});
    $('[for="sp_w"]').on('click',function(){
		$('#sp_d,#sp_mk').prop({'disabled':false});
		$('[for="sp_d"],[for="sp_mk"]').removeClass('disabled');
	});	    
	$('#btn-refine-search').on('click',function(){		
		eq = '';
		if($(':checkbox[id^= "filter_"]:checked').length > 0){
		   var arr_filters = $.unique($(':checkbox[id^= "filter_"]:checked').map(function() { return $(this).val(); }).get().join().split(','));		
			for(var i = 0;i <= arr_filters.length-1;i++){
				eq += arr_filters[i];
				if(i != arr_filters.length-1) eq += ',';					
			}
		}
		 q_cat = $('#cat').val() != '' ? '&cat='+$('#cat').val() : '';
		 q_filters = (eq != '') ? '&filters='+eq : '';
		 q_offers = (($('#offer_filter').prop('checked') == true) ? '&of=true' : '&of=false');		 
		 q_sp_w = (($('#sp_w').prop('checked') == true) ? '&sp_w=t' : '');
		 q_sp_ew = (($('#sp_ew').prop('checked') == true) ? '&sp_ew=t' : '');
		 if(q_sp_w == '' && q_sp_ew == '') q_sp_w = '&sp_w=t';		 
		 q_sp_n = (($('#sp_n').prop('checked') == true) ? '&sp_n=t' : '');
		 q_sp_t = (($('#sp_t').prop('checked') == true) ? '&sp_t=t' : '');
		 q_sp_d = (($('#sp_d').prop('checked') == true) ? '&sp_d=t' : '');
		 q_sp_mk = (($('#sp_mk').prop('checked') == true) ? '&sp_mk=t' : '');		 
		 if(q_sp_n == '' && q_sp_t == '' && q_sp_d == '' && q_sp_mk == '') q_sp_n = '&sp_n=t';		 
	     window.location = '?p=1&sp='+$('#sp').val()+q_filters+q_offers+q_cat+q_sp_n+q_sp_t+q_sp_d+q_sp_mk+q_sp_w+q_sp_ew;
	});
	$('#sp').on('keydown',function(e){	  
	  if(e.keyCode == 13){
		 $('#btn-refine-search').click();		 
	  }
	}); 	