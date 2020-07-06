jQuery.fn.extend({
	Vcenter: function(container) {
	 function center($this,container){
		var DIVwidth = $this.outerWidth(true)	
		, DIVheight = $this.outerHeight(true)	  
		, SCREENwidth = container.innerWidth()
		, SCREENheight = container.innerHeight()	
		, SCREENscrolltop = container.scrollTop();
		  $this.hide().css({"left": (SCREENwidth-DIVwidth)/2+"px","top": (SCREENheight-DIVheight)/2+SCREENscrolltop+"px"}).show();		
	 }	
	 return this.each(function() {	 
		 $this = $(this);
		 var position = container.css('position');
		 if(position == 'absolute'){
			 $this.wrap('<div class="center_container_generated_div" style="width:100%;height:100%"></div>');
	         container = $this.closest('div');
		 }
		container.css('position','relative');		 		
		$this.css({"position":"absolute"});		 
         center($this,container);
		$(window).resize(function(){
			center($this,container);
		});
	 });
	}
});