/// adapt validation plugin for mobile ////
$.extend($.validator.defaults, {
                                  ignore:':hidden',
                                  ignoreTitle: false,
                                  focusInvalid: false,
                                  onfocusout: false,
                                  onkeyup: false,
                                  onclick: false
});
(function($){
           /************ FUNCTION FO CALCULATE OFFSET OF AN ELEMENT IN A PARENT *****************/
          function offset(selector,parent){
           var position = (selector.offset().left - parent.offset().left);
            position = position -((parent.outerWidth()-parent.innerWidth())/2);
           return position;
          };
          /************* FUNCTION TO CALCULATE CURRENT VISIBLE STEP *****************/
            function current_step(selector,form){
              form.find(selector).each(function(i){
               if(offset($(this),$(this).closest(form)) == 0){
                d = i;
               }
              });
              return d;
            };
          /************* FUNCTION TO RIGHT AND LEFT SLIDE STEP AND SEVERAL CONTROLS****************/
          function slide_form(container,selector,form,n_p,highlight_class,slide_delay){

            if(n_p == 'next'){
              np = '-='+selector.outerWidth( true );
            }else{
              np = '+='+selector.outerWidth( true );
            }
            selector.each(function(i){
            i++;
            var inext = i + 1;
            var iprev = i - 1;
             if(offset($(this),$(this).closest(form)) == 0){
               if(n_p == 'next'){
			     var $new_height = selector.eq(current_step(selector,form)).next(selector).height();
                  container.stop().animate({'left' : np}, slide_delay,function(){
                     form.stop().animate({'height':$new_height+'px'},200,function(){
                       selector.eq(current_step(selector,form)).find('input:first').focus();
                       $('[data-steps-rel-form="'+form.attr('id')+'"][data-steps-rel^="fstep_"]').removeClass(highlight_class);
                       $('[data-steps-rel-form="'+form.attr('id')+'"][data-steps-rel="fstep_'+inext+'"]').addClass(highlight_class);
                     });
                     //form.StepizeForm('Rehight',true);
                  });
               }else{
				   var $new_height = $(this).prev(selector).height();
                  container.stop().animate({'left' : np}, slide_delay,function(){
                     form.stop().animate({'height':$new_height+'px'},200,function(){
                       selector.eq(current_step(selector,form)).find('input:first').focus();
                       $('[data-steps-rel-form="'+form.attr('id')+'"][data-steps-rel^="fstep_"]').removeClass(highlight_class);
                       $('[data-steps-rel-form="'+form.attr('id')+'"][data-steps-rel="fstep_'+iprev+'"]').addClass(highlight_class);
                     });
                     //form.StepizeForm('Rehight');
                  });
               }
             }
           });
          };
  var methods = {
     init : function( options ) {

            /***************************************/
            /** List of available default options **/
            /***************************************/
            var defaults = {
            Text_Next         :  'Next &#8594;',//--> text of "NEXT" button
            Text_Prev         :  '&#8592; Prev',//--> text of "PREV" button
            Text_Submit       :  'Send',//--> text of "SUBMIT" button
            Class_Prev        :  '',/**** you can add more class to "PREV" button, give them an interspace eg:. "class1 class2 class3" ****/
            Class_Next        :  '',/**** you can add more class to "NEXT" button, give them an interspace eg:. "class1 class2 class3" ****/
            Class_Submit      :  '',/**** you can add more class to "SUBMIT" button, give them an interspace eg:. "class1 class2 class3" ****/
			Steps_Count       :  '',/**** selector where you whant see the stepsp count ****/
			Selector_Buttons  :  '',/**** selector where you want show the NEXT PREV AND SUBMIT buttons (if empty they'll be append to form) ****/
			Validation_roule  :  {ignore:':hidden'}, /**** option for validation plugin ****/
			Slide_Delay       :  500, /**** deley for steps slide ****/
            Class_Highlight   :  'current-step'//--> class to give "STEP BAR" when you go to the next step
            };

   var options = $.extend(defaults, options);
   /* to call options into a method */
    om = $.extend(defaults, options);

//--------------------------------------------------------------------------////---------------------------------------------------------//
          /************** create the elements for the form *********************/
               var o = options
          ,  $this = $(this)
          ,  $steps = $this.find('[id*="fstep_"]:visible');
          $steps.wrapAll('<div class="container_steps" style="position:relative" />').append('<div style="clear:both"></div>');
          $this.wrap('<div class="container_form" />');
          var $container_steps = $this.find('.container_steps')
          ,   $container_form = $this.closest('.container_form')
		  ,   $total_steps = $this.find('[id*="fstep_"]').length;
		  if(o.Selector_Buttons != ''){
			if($this.find('[id*="fstep_"]').legth == 1){
			 $(o.Selector_Buttons).append('<span type="submit" class="fsubmit '+o.Class_Submit+'">'+o.Text_Submit+'</span><div class="er_choose">You must choose at least one answer for each Question</div>');
			}else{
             $(o.Selector_Buttons).append('<span type="button" class="fprev '+o.Class_Prev+'" style="display:none">'+o.Text_Prev+'</span> '
                                 +'<span type="button" class="fnext '+o.Class_Next+'">'+o.Text_Next+'</span> '
                                 +'<span type="submit" class="fsubmit '+o.Class_Submit+'" style="display:none">'+o.Text_Submit+'</span>');
			}
             var $next_b = $(o.Selector_Buttons).find('.fnext')
             ,   $prev_b = $(o.Selector_Buttons).find('.fprev')
             ,   $submit_b = $(o.Selector_Buttons).find('.fsubmit');
		  }else{
			if($this.find('[id*="fstep_"]').legth == 1){
			 $container_form.append('<span type="submit" class="fsubmit '+o.Class_Submit+'">'+o.Text_Submit+'</span><div class="er_choose">You must choose at least one answer for each Question</div>');
			}else{
             $container_form.append('<span type="button" class="fprev '+o.Class_Prev+'" style="display:none">'+o.Text_Prev+'</span> '
                                 +'<span type="button" class="fnext '+o.Class_Next+'">'+o.Text_Next+'</span> '
                                 +'<span type="submit" class="fsubmit '+o.Class_Submit+'" style="display:none">'+o.Text_Submit+'</span>');
			}
             var $next_b = $container_form.find('.fnext')
             ,   $prev_b = $container_form.find('.fprev')
             ,   $submit_b = $container_form.find('.fsubmit');
		  }


          $this.find('button:submit,input:submit').prop('disabled',true).hide();
          $this.css({'height':$this.find('[id^="fstep_"]:first').height()+'px','overflow':'hidden','width':$this.outerWidth( true ),'border':'none','padding':'0px','margin':'0px'});
          /*** fix overflow in IE ************/
          $this.closest('.container_form').css({'overflow':'hidden','width':$this.outerWidth( true ),'position':'relative'});
          $steps.css({'width':$this.outerWidth( true ),'overflow':'auto','border':'none','padding':'0px','margin':'0px','float':'left'});
          $container_steps.css({'width':($this.outerWidth( true )*$steps.length)+'px','border':'none','padding':'0px','margin':'0px'});
			      if(o.Steps_Count != ''){
                        $(o.Steps_Count)
                        .html('1/'+$total_steps);
				  }
		  var $count_stepsbar = $('[data-steps-rel-form="'+$this.attr('id')+'"]').length/$total_steps;
		  for($i=0;$i<$('[data-steps-rel-form="'+$this.attr('id')+'"]').length;$i+=$total_steps){
			  $('[data-steps-rel-form="'+$this.attr('id')+'"]').eq($i).addClass(o.Class_Highlight);
		  }

      $(window).resize(function(){
        var current_s = $steps.eq(current_step($steps,$this)).index();
        $container_form.css('width','100%');
        $container_form.css('width',$container_form.outerWidth(true));
        $this.css('width',$container_form.outerWidth(true));
        a = $this.outerWidth( true )*current_s;
        $steps.css({'width':$this.outerWidth( true ),'overflow':'auto','border':'none','padding':'0px','margin':'0px','float':'left'});
        $container_steps.css({'width':($this.outerWidth( true )*$steps.length)+'px','border':'none','padding':'0px','margin':'0px','left':-a});

      })
             /**************** validation of form (required jquery plugin "validate") *************/
           if($().validate){
            $this.validate(o.Validation_roule);
           }
		   $this.on('keyup',function(){
			  var current = $steps.eq(current_step($steps,$this));
			  $this.stop().animate({'height':current.height()+'px'},200);
		   });
		   $this.on('click focus blur change','input,textarea,select',function(){
			  var current = $steps.eq(current_step($steps,$this));
			  setTimeout(function(){$this.stop().animate({'height':current.height()+'px'},200); },50);
		   });
            /**************** controls on next click ************************************/
          $next_b.click(function(){
          var current = $steps.eq(current_step($steps,$this));
           if($().validate){
            $this.valid();
           }
           if(current.find('.error:visible').length == 0){
              if ($().validate) $this.validate(o.Validation_roule);
              $steps.eq(current_step($steps,$this)+1).find('label.error').remove();
              $steps.eq(current_step($steps,$this)+1).find('.error').removeClass('error');
              slide_form($container_steps,$steps,$this,'next',o.Class_Highlight,o.Slide_Delay);
              $prev_b.fadeIn('slow');
			      if(o.Steps_Count != ''){
                        $(o.Steps_Count)
                        .html((current_step($steps,$this) + 2)+'/'+$total_steps);
				  }
              if(offset($steps.eq(($steps.length - 2)),$steps.eq(($steps.length - 2)).closest($this)) == 0){
                 $next_b.hide();
                 $submit_b.fadeIn('slow');
              }
           }else{
            $this.stop().animate({'height':current.height()+'px'},200);
           }
          });
          /****************** controls to prev click ***********************************/
          $prev_b.click(function(){
              if ($().validate) $this.validate(o.Validation_roule);
              $steps.eq(current_step($steps,$this)-1).find('label.error').remove();
              $steps.eq(current_step($steps,$this)-1).find('.error').removeClass('error');
              slide_form($container_steps,$steps,$this,'prev',o.Class_Highlight,o.Slide_Delay);
              $next_b.fadeIn('slow');
              $submit_b.hide();
              if(offset($steps.eq(1),$steps.eq(1).closest($this)) == 0){
                 $prev_b.hide();
              }
			      if(o.Steps_Count != ''){
                        $(o.Steps_Count)
                        .html(current_step($steps,$this)+'/'+$total_steps);
				  }
          });
          /***************** prevent action on press tab button **************/
          $steps.find('input,select,textarea').on('keydown',function(){
          var of = $(this).closest($steps).find('input:last,select:last,textarea:last');
           of.keydown(function(e){
           if(e.keyCode == 9){
             if($().validate){
               $this.valid();
             }
             return false;
           }
           });
          });
          /***************** trigger "NEXT" and "SUBMIT" button on press ENTER **************/

          $steps.find('input').on('keydown',function(e){
           if(e.keyCode == 13){
            if(offset($steps.eq(($steps.length-1)),$steps.eq(($steps.length-1)).closest($this)) == 0){
             $submit_b.click();
            }else{
             setTimeout(function(){$next_b.click();},100);
            }
           }
          });
          /****************** managment of slide bar *******************/
          $('[data-steps-rel-form="'+$this.attr('id')+'"]').click(function(el){
			 el.preventDefault();
             if($().validate){
               $this.valid();
             }
             var current = $steps.eq(current_step($steps,$this));
             if($('#'+$(this).attr('data-steps-rel')).prevAll().find('.error:visible').length == 0){
              if ($().validate) $this.validate(o.Validation_roule);
              if($(this).attr('data-steps-rel-form') != 'undefined'){
                $steps.not(current).find('label.error').remove();
                $steps.not(current).find('.error').removeClass('error');
                $('[data-steps-rel-form="'+$this.attr('id')+'"]').removeClass(o.Class_Highlight);
                $('[data-steps-rel-form="'+$this.attr('id')+'"][data-steps-rel="'+$(this).attr('data-steps-rel')+'"]').addClass(o.Class_Highlight);
                el= $('#'+$this.attr('id'));
                var offset_left = $this.find('#'+$(this).attr('data-steps-rel')).prevAll().length * el.outerWidth( true ),
				    new_height = $('#'+$(this).attr('data-steps-rel')).height();
                $container_steps.stop().animate({'left' : -offset_left},o.Slide_Delay,function(){
                   el.stop().animate({'height':new_height+'px'}  ,200,function(){
                      if(offset($steps.eq(0),$steps.eq(0).closest($this)) == 0){
                         $prev_b.hide();
                         $submit_b.hide();
                         $next_b.fadeIn('slow');
                      }else if(offset($steps.eq(($steps.length-1)),$steps.eq(($steps.length-1)).closest($this)) == 0){
                         $next_b.hide();
                         $submit_b.fadeIn('slow');
                         $prev_b.fadeIn('slow');
                      }else{
                         $next_b.fadeIn('slow');
                         $prev_b.fadeIn('slow');
                         $submit_b.hide();
                      }
                      $steps.eq(current_step($steps,$this)).find('input:first').focus();
			          if(o.Steps_Count != ''){
                        $(o.Steps_Count)
                        .html((current_step($steps,$this)+1)+'/'+$total_steps);
				       }
                      //el.StepizeForm('Rehight');
                   });
                   //el.StepizeForm('Rehight',$steps.eq(($steps.length-1)));
                });
               }
              }else{
                    var current_error = $steps.find('.error:visible').closest($steps).attr('id');
                    $('[data-steps-rel-form="'+$this.attr('id')+'"][data-steps-rel="'+current_error+'"]').click();
                    if($().validate){
                      $this.valid(o.Validation_roule);
                    }
              }
          });
          /************** controls of form height on submit **********************/
          $submit_b.click(function(){
          if($().validate){
            $this.valid(o.Validation_roule);
          }
           if($steps.eq(current_step($steps,$this)).find('.error:visible').length == 0){
             $this.submit();
           }else{
            $this.stop().animate({'height':$steps.eq(current_step($steps,$this)).height()+'px'},200);
           }
          });
//--------------------------------------------------------------------------////---------------------------------------------------------//
     },
     Rehight : function() {
        var $this = $(this)
        ,  $steps = $this.find('[id*="fstep_"]:visible');
         setTimeout(function(){
          $this.stop().animate({'height':$steps.eq(current_step($steps,$this)).outerHeight(true)+'px'},200);
         },100);
     },
     Current:function(){
        var $this = $(this)
        ,  $steps = $this.find('[id*="fstep_"]:visible');
       return $steps.eq(current_step($steps,$this)).attr('id');
     },
     Destroy : function() {
        var $this = $(this)
        ,  $steps = $this.find('[id*="fstep_"]');
		$steps.removeAttr('style');
		$this.removeAttr('style');
		$steps.find('div:last').remove();
	   if($(om.Selector_Buttons).length > 0) $(om.Selector_Buttons).empty();
	   else $this.closest('.container_form').find('.fnext,.fprev,.fsubmit').remove();
	   if($this.parent().is(".container_form") ) {
		$this.unwrap() ;
	   }
	   if($steps.eq(0).parent().is(".container_steps") ) {
		$steps.eq(0).unwrap() ;
	   }
	   $this.find('button:submit,input:submit').prop('disabled',false).show();
     }
  };
 $.fn.StepizeForm  = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.steppize' );
    }

  };
})(jQuery);