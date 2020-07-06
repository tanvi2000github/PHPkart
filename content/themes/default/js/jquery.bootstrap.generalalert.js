(function($){  
  var methods = {
     init : function( options ) {          
 
            /***************************************/
            /** List of available default options **/
            /***************************************/
            var defaults = {  
			  LabelText         :  '',//--> TITLE TEXT
			  TypeLabel         :  '',//--> EMPTY,success,warning,error,info,inverse (if it will be empty it will have "label" class)
              ID                :  'general-modal',//--> MUST BE UNIQUE INTO DOM
			  AppendTo          :  'body',//--> IT COULD BE A SELECTOR LIKE '#some-id' or '.some-class'
			  BodyText          :  '',//--> BODY TEXT/HTML
			  TypeBody          :  '',//--> EMPTY,success,warning,error,info
			  Remote            :  false,//--> If a remote url is provided, content will be loaded via jQuery's load method and injected into the .modal-body
			  Backdrop          :  true,//--> "true/false/static", Includes a modal-backdrop element. Alternatively, specify "static" for a backdrop which doesn't close the modal on click.
			  Keyboard          :  true,//--> "true,false", Closes the modal when escape key is pressed
			  BackdropBg        :  '',//--> Backdrop Back Ground (it must be a color like #000 or green)
			  DelayShow         :  0,//--> DELAY TO SHOW THE ALERT (IN ms)
			  DelayHide         :  0,//--> DELAY TO HIDE THE ALERT (IN ms)
			  AutoHide          :  false,//--> AUTO HIDE MODAL ALERT (it will be active only if "DelayHide" > 0)
			  DestroyOnHidden   :  true,//-->"true,false", Delete from DOM after modal hide
			  Show              :  function(){},//--> function/s fire/s immediately when the show instance method is called.
			  Shown             :  function(){},//--> function/s is/are fired when the modal has been made visible to the user (will wait for css transitions to complete).
			  Hide              :  function(){},//--> function/s fire/s immediately when the hide instance method is called.
			  Hidden            :  function(){}//--> function/s is/are fired when the modal has finished being hidden from the user (will wait for css transitions to complete).			  
            }; 
            
   var options = $.extend(defaults, options); 
              
//--------------------------------------------------------------------------////---------------------------------------------------------//    
               var o = options
			   if (o.TypeLabel == 'error') o.TypeLabel = 'important';
			   if (o.Delay == '') o.Delay = 0;
			   if(o.ID == ''){
				  console.warn('option "ID" is empty');
				  return;			         
			   }
			   if(o.AppendTo == ''){
				  console.warn('option "AppendTo" is empty');
				  return;			         
			   }	
			   if($('#'+o.ID).length <= 0){
			   $(o.AppendTo).append('\
			   <div id="'+o.ID+'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="'+o.ID+'-modallabel" aria-hidden="true">\
                   <div class="modal-header">\
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><li class="icon-remove"></li></button>\
                      <span id="'+o.ID+'-modallabel"><strong class="label label-'+o.TypeLabel+'">'+o.LabelText+'</strong></span>\
                   </div>\
                   <div class="modal-body"><div '+(o.TypeBody != '' ? 'class="alert alert-'+o.TypeBody+'"' : '')+'>'+(o.Remote ? '' : o.BodyText)+'</div></div>\
               </div>');
			   }
			   setTimeout(function(){
			     $('#'+o.ID).modal('show');
				 if(o.BackdropBg != ''){
				  $('#'+o.ID).next('.modal-backdrop').css('background-color',o.BackdropBg);   
				 }				 
			   },o.DelayShow);
			   if(o.AutoHide && o.DelayHide > 0){
				   setTimeout(function(){
					 if($('#'+o.ID).is(':visible')) $('#'+o.ID).modal('hide');			 
				   },o.DelayHide);				   
			   }
			   $('#'+o.ID).modal({
				    backdrop:o.Backdrop,
					keyboard:o.Keyboard,
					remote:o.Remote
			   });
			   $(o.AppendTo).on('show','#'+o.ID,function(){				   
				   o.Show.call(this);
			   });
			   $('#'+o.ID).bind('shown',function(){
			       o.Shown.call(this);
			   });
			   $(o.AppendTo).on('hide','#'+o.ID,function(){				   
				   o.Hide.call(this);
			   });
			   $('#'+o.ID).bind('hidden',function(){
				   if(o.DestroyOnHidden) $('#'+o.ID).remove();
			       o.Hidden.call(this);
			   });			   
//--------------------------------------------------------------------------////---------------------------------------------------------//
     }
  };
 $.bootalert  = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.steppize' );
    }    
  
  }; 
})(jQuery);