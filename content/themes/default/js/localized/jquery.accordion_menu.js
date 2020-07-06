/**
*	@name							Accordion
*	@descripton						This Jquery plugin makes creating accordions pain free
*	@version						1.4
*	@requires						Jquery 1.2.6+
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*/

(function(jQuery){
     jQuery.fn.extend({
         accordion_menu: function(options) {    

var defaults = {   
                accordionAll:true 		    
}
   var options = $.extend(defaults, options);
            return this.each(function() {
            	
            	var $ul						= $(this),
					elementDataKey			= 'accordiated',
					activeClassName			= 'active',
					activationEffect 		= 'slideToggle',
					panelSelector			= 'ul, div',
					activationEffectSpeed 	= 'fast',					
					itemSelector			= 'li';
            	
				if($ul.data(elementDataKey))
					return false;
													
				$.each($ul.find('ul, li>div'), function(){
					$(this).data(elementDataKey, true);
					$(this).hide();
				});
		
				$.each($ul.find('span.active_node'), function(e){
					$(this).click(function(e){
						e.preventDefault();						
						activate($(this).closest('a'), activationEffect,options.accordionAll);
						return void(0);
					});
					
					$(this).bind('activate-node', function(){
						$ul.find( panelSelector ).not($(this).parents()).not($(this).siblings()).slideUp( activationEffectSpeed );
						activate(this,'slideDown',options.accordionAll);
					});
				});
				
				var active = (location.hash)?$ul.find('a[href=' + location.hash + ']')[0]:$ul.find('li.current a')[0];

				if(active){
					activate(active, false,options.accordionAll);
				}
				
				function activate(el,effect,accordionAll){
				   /** add class active before slide **/
				   
					$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
	if(accordionAll)
					$(el).parent( itemSelector ).siblings().removeClass(activeClassName).children( panelSelector ).slideUp( activationEffectSpeed );
					
					$(el).siblings( panelSelector )[(effect || activationEffect)](((effect == "show")?activationEffectSpeed:false),function(){
						
						if($(el).siblings( panelSelector ).is(':visible')){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						} else {
							$(el).parent( itemSelector ).removeClass(activeClassName);
						}
						
						if(effect == 'show'){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						}
					
						$(el).parents().show();
					
					});
					
				}
				
            });
        }
    }); 
})(jQuery);