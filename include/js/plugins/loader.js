/*////////////// PLUGIN TO SET A LOADER DURING PAGES TRANSACTIONS ///////////*/
(function($){
   function re_position(content){
	  var DIVwidth = $(content).width()	
	  , DIVheight = $(content).height()	  
	  , SCREENwidth = $(window).width()
	  , SCREENheight = $(window).height()	
	  , SCREENscrolltop = $(window).scrollTop();

        $(content).hide().css({"left": (SCREENwidth-DIVwidth)/2+"px","top": (SCREENheight-DIVheight)/2+SCREENscrolltop+"px"}).show();
   }
  $.loader = function(options){
	 var o = $.extend({
				  imgPath            :   '/loader.gif',
				  appendTo           :   '.body-area',
				  containerClass     :   'overlay-loader',
				  imgClass           :   'loaderImg',
				  contentToLoad      :   '',
				  containerStyle     :   ''
				},options);
				$toLoad = '<img style="position:absolute;z-index:9999" class="'+o.imgClass+'" src="'+o.imgPath+'" alt="" />';
				if(o.contentToLoad != ''){
				  $toLoad = '<div class="'+o.imgClass+'" style="position:absolute;z-index:9999">'+o.contentToLoad+'</div>';	
				}
	 $(o.appendTo).css('position','relative').append('<div class="'+o.containerClass+'" style="z-index:1045;width:100%;height:100%;'+o.containerStyle+'"></div>'+$toLoad);
	 re_position('.'+o.imgClass);
	 $("."+o.containerClass).css({"top":$(window).scrollTop()+"px"});
	 $(window).scroll(function(){
		var SCREENscrolltop = $(window).scrollTop();
		$("."+o.containerClass)
		.css({"top":SCREENscrolltop+"px",'width':'100%'});
	 });  
	 $(window).resize(function(){
		$("."+o.containerClass).css({'width':'100%'});
		re_position('.'+o.imgClass);
	 }); 
	 $(window).scroll(function(){
		re_position('.'+o.imgClass);
	 }); 	 	 		 
	 //$('img.'+o.imgClass).Vcenter($('.'+o.containerClass));	
	 $.loader.hide = function(delay){
		  if(delay != '' && delay != 'undefined' && !isNaN(delay)){
				setTimeout(function(){
					$('.'+o.containerClass+',.'+o.imgClass).fadeOut('fast',function(){$(this).remove();});
				},delay);			  
		  }else{
			   $('.'+o.containerClass+',.'+o.imgClass).fadeOut('fast',function(){$(this).remove();});
		  }	
	 };
  };
})(jQuery);	