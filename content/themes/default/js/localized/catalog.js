/* change function on limiter and sort products */
$("#products_limiter_counter,#products_sortby").on('change',function() {
  window.location = $(this).find("option:selected").val();
});
$(function(){
	/* MANAGE LEFT CATEGORIES */
	
	$('#accordion-categories-menu').accordion_menu();
	/* highlight active category and open category menu  */
	var arr_breadcrumb = $('.breadcrumb li.active').attr('data-rel-menu').split('|');
	for(var i = 0;i<=arr_breadcrumb.length-1;i++){
	  $('[data-breadcrumb="'+arr_breadcrumb[i]+'"],#accordion-categories-menu').closest('li').addClass('active').children('ul').css('display','block');	
	}
	$('[data-breadcrumb="'+arr_breadcrumb[arr_breadcrumb.length-1]+'"],#accordion-categories-menu').addClass('last_active');
	  $('li,#accordion-categories-menu').each(function(){		  
		  if($(this).find('ul').length == 0 || $(this).find('ul').html() === '')
		   $('.active_node',$(this)).remove();
	  });
	/* FILTER PRICE SLIDER */			
    $('#slider').slider();
		$('body').data('min-price',$('#slider').data('slider').getValue()[0]);
		$('body').data('max-price',$('#slider').data('slider').getValue()[1]);

	$('.accordion-filters').accordion_menu();
	$('.accordion-filters').each(function() {
        if($('li.active',$(this)).length > 0)
		  $('ul',this).css('display','block');	
    });
});
	$('#btn-filter').on('click',function(){		
		eq = '';
		if($(':checkbox[id^= "filter_"]:checked').length > 0){
		   var arr_filters = $.unique($(':checkbox[id^= "filter_"]:checked').map(function() { return $(this).val(); }).get().join().split(','));		
			for(var i = 0;i <= arr_filters.length-1;i++){
				eq += arr_filters[i];
				if(i != arr_filters.length-1) eq += ',';					
			}
		}
		 q_filters = (eq != '') ? '&filters='+eq : '';
		 q_offers = (($('#offer_filter').prop('checked') == true) ? '&of=true' : '&of=false');
		 pcr = '&prc='+$('#slider').data('slider').getValue()[0]+','+$('#slider').data('slider').getValue()[1];
	     window.location = '?p=1'+q_filters+pcr+q_offers;
	});		

	$('#btn-reset-filter').on('click',function(){	
	  window.location = '?p=1';
	});