/* fade for quick view button fade */ 
/*$('.product-container-grid,.product-container-list').on({
	mouseenter: function(){
	  $('.productc-img a.quick-view',this).fadeTo(500,1);
	},
	mouseleave: function(){
	  $('.productc-img a.quick-view',this).fadeTo(500,0);
	}	  
});*/	
/* scroll background for ACTION CONTAINER BUTTONS */  
$('.action-container a').not('.disabled').on({
	mouseenter: function(){
	   $(this).stop().animate({
		  'background-position-y': '-30px'
	   },300);
	},
	mouseleave: function(){
	   $(this).stop().animate({
		  'background-position-y': '0px'
	   },300);  
	}	  
});
/* scroll background for SCROLL TO TOP button */  
$('#go-to-top').on({
	mouseenter: function(){
	   $(this).stop().animate({
		  'background-position-y': '-50px'
	   },300);
	},
	mouseleave: function(){
	   $(this).stop().animate({
		  'background-position-y': '0px'
	   },300);  
	}	  
});
/* scroll background for LIST and GRID VIEW into catalog */
$('#view-mode .grid-view,#view-mode .list-view').on({
	mouseenter: function(){
	 if(!$(this).hasClass('active')){
	   $(this).stop().animate({
		  'background-position-y': '-20px'
	   },300);
	 }
	},
	mouseleave: function(){
	 if(!$(this).hasClass('active')){
	   $(this).stop().animate({
		  'background-position-y': '0px'
	   },300);  
	 }
	}	  
});  
/* scroll background for SORT BUTTON */
$('#filter-bar #order-by .sort-by').on({
	mouseenter: function(){
	 $y_position = $(this).hasClass('asc') ? '-25px' : '-50px';
	   $(this).stop().animate({
		  'background-position-y': $y_position
	   },300);
	},
	mouseleave: function(){
	 $y_position = $(this).hasClass('asc') ? '0px' : '-25px';
	   $(this).stop().animate({
		  'background-position-y': $y_position
	   },300);  
	}	  
}); 
/* not responsive structure */
$(function(){  		  
  $('.container-semifluid').removeClass('.container-semifluid').addClass('container');
  $('.container').css('width','1960px');
});