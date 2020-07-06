/*////////////// PLUGIN TO SET A LOADER DURING PAGES TRANSACTIONS ///////////*/
(function($){
  $.loader = function(options){
	 var o = $.extend({
				  imgPath            :   $('body').data('admin_path_img')+'/loader.gif',
				  appendTo           :   '.body-area',
				  containerClass     :   'overlay-loader',
				  imgClass           :   'loaderImg',
				  containerStyle     :   ''
				},options);
	 $(o.appendTo).css('position','relative').append('<div class="'+o.containerClass+'" style="z-index:999999999;'+o.containerStyle+'"><img class="'+o.imgClass+'" src="'+o.imgPath+'" alt="" /></div>');		 
	 $('img.'+o.imgClass).Vcenter($('.'+o.containerClass));	
	 $.loader.hide = function(delay){
		  if(delay != '' && delay != 'undefined' && !isNaN(delay)){
				setTimeout(function(){
					$('.'+o.containerClass).fadeOut('fast',function(){$(this).remove();});
				},delay);			  
		  }else{
			   $('.'+o.containerClass).fadeOut('fast',function(){$(this).remove();});
		  }	
	 };
  };
})(jQuery);	
/*////////////// ADDITIONAL METHOD "classadded" to know what class added to a DOM element ///////////*/
/* e.g.: 
	$('#myElement').on('classadded', function(ev, newClasses) {
		console.log(newClasses + ' added!');
		console.log(this);
		// Do stuff
		// ...
	});
*/
(function($) {
    var ev = new $.Event('classadded'),
        orig = $.fn.addClass;
    $.fn.addClass = function() {
        $(this).trigger(ev, arguments);
        return orig.apply(this, arguments);
    }
})(jQuery);	
/*////////////// ADDITIONAL METHOD "classadded" to know what class deleted to a DOM element ///////////*/
/* e.g.: 
	$('#myElement').on('classadded', function(ev, newClasses) {
		console.log(newClasses + ' added!');
		console.log(this);
		// Do stuff
		// ...
	});
*/
(function($) {
    var ev = new $.Event('classdeleted'),
        orig = $.fn.removeClass;
    $.fn.removeClass = function() {
        $(this).trigger(ev, arguments);
        return orig.apply(this, arguments);
    }
})(jQuery);		       
/*///////////////// FUNCTION WHEN ADD BUTTON IS CLICKED //////////////////////*/
function add_item(){
 $('.flexigrid').fadeOut('fast',   
   function(){
     $('#container_form').remove();
     $('#container_flex').append('<div id="container_form"></div>');
     $.ajax({
       type:'POST',
       url: 'form_add.php',
	   beforeSend: function(){
		   $.loader();
	   }, 
       complete:function(){
            $('#container_form form:first').find('textarea').each(function(){
                $(this).val($(this).val().replace(/<br\/>/g,'\n'))
            }); 
			$('#main_table').find('.box-icon').html('').append('\
			   <span class="btn back_list" rel="tooltip" title="'+__('general_FORM_TOOLTIP_BACK')+'"><i class="icon icon-darkgray icon-carat-1-w"></i></span>\
			   <span class="btn add_item" rel="tooltip" title="'+__('general_FORM_TOOLTIP_ADD_NEW')+'"><i class="icon icon-darkgray icon-plus"></i></span>\
			   <span class="btn save_item" rel="tooltip" title="'+__('general_FORM_TOOLTIP_SAVE')+'"><i class="icon icon-darkgray icon-save"></i></span>\
			');
		    $('#main_table .add_item').click(function(){
			  add_item();
			});
			  if (typeof post_form_add_function == 'function') { 
				post_form_add_function(); 
			  }					
			submit_add_form();			
			$.loader.hide();		
       },
       success:function(data){       
        $('#container_form').html(data).fadeIn('fast'); 
       }
      }); 
   });
}
/*/////////////////FUNCTION TO ADD AN ITEM WHEN FORM IS SUBMITTED//////////////*/
function submit_add_form(){		
/************************/
  $('#container_form form:first').validate();  
  $('#main_table .save_item').click(function(){          
   $('#container_form form:first').submit();
   return false
  });
  $('#container_form form:first input:text,#container_form form:first input:password').on('keydown',function(e){
	if(e.keyCode == 13){
	   $('#container_form form:first').submit();
	   return false;
	}
  });        
  $('#container_form form:first').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('#container_form form:first').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'#conteiner_form_loader'});
	 $('#container_form form:first').find('textarea:visible').each(function(){
	  $(this).val($(this).val().replace(/\n/g,'<br\/>').replace(/\r/g,'<br\/>'))
	 });         	 
	}, 
	complete:function(){
	  $('#container_form form:first').find('textarea:visible').each(function(){
		  $(this).val($(this).val().replace(/<br\/>/g,'\n'))
	  });  
	},   
	url:'add.php',
	dataType:'html',
	//data:$('#container_form form:first').serialize(),
	success:function(data){ 
	$(".content_container").animate({ scrollTop: "0px" });
	  if ($(data).filter('.error_alert').length > 0){		   
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
		  });		  
	  }else{
          $('#container_form form:first').find('.save_item').hide();
		  $('<img src="'+$('body').data('admin_path_img')+'/ajax-loader.gif" alt="" />').insertBefore($('#container_form form:first').find('.save_item'));		  
		  $('#main_table').find('.box-icon').html(''); 
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){
								   add_item();
								   $.loader.hide(); 
								 },1500);
								});
		  });			  
	  }
	}
   });
}
/*///////////////// FUNCTION WHEN EDIT BUTTON IS CLICKED //////////////////////*/
function edit_item(id){  
 $('.flexigrid').fadeOut('fast',   
   function(){
	 $('#container_form').remove();
	 $('#container_flex').append('<div id="container_form"></div>');
	 $.ajax({
	   type:'POST',
	   url: 'form_edit.php',
	   data:({id:id}),
	   beforeSend: function(){
		  $.loader(); 
	   },
	   complete:function(){
			$('#container_form form:first').find('textarea').each(function(){
				$(this).val($(this).val().replace(/<br\/>/g,'\n'))
			}); 
			$('#main_table').find('.box-icon').html('').append('\
			   <span class="btn back_list" rel="tooltip" title="'+__('general_FORM_TOOLTIP_BACK')+'"><i class="icon icon-darkgray icon-carat-1-w"></i></span>\
			   <span class="btn add_item" rel="tooltip" title="'+__('general_FORM_TOOLTIP_ADD_NEW')+'"><i class="icon icon-darkgray icon-plus"></i></span>\
			   <span class="btn save_item" rel="tooltip" title="'+__('general_FORM_TOOLTIP_SAVE')+'"><i class="icon icon-darkgray icon-save"></i></span>\
			');			
			if (typeof post_form_edit_function == 'function') { 
			  post_form_edit_function(); 
			}				 
			submit_edit_form(); 
			$('#main_table .add_item').click(function(){
			  add_item();
			});
			$.loader.hide();	
	   },
	   success:function(data){       
		  $('#container_form').html(data).fadeIn('fast');  
	   }
	  }); 
   });
}
/*/////////////////FUNCTION TO EDIT AN ITEM WHEN FORM IS SUBMITTED//////////////*/
function submit_edit_form(){			
/************************/	
  $('#container_form form:first').validate();
  $('#main_table .save_item').click(function(){
	$('#container_form form:first').submit();
	return false;				             
  });		
  $('#container_form form:first input:text,#container_form form:first input:password').on('keydown',function(e){
	if(e.keyCode == 13){
	   $('#container_form form:first').submit();
	   return false;
	}
  });          
  $('#container_form form:first').ajaxForm({
	type:'POST',
	beforeSubmit:function(){
	 return $('#container_form form:first').validate().form();           
	},
	beforeSerialize:function(){
	 $.loader({appendTo:'#conteiner_form_loader'});
	 $('#container_form form:first').find('textarea:visible').each(function(){
	  $(this).val($(this).val().replace(/\n/g,'<br\/>').replace(/\r/g,'<br\/>'))
	 });          
	}, 
	complete:function(){
	  $('#container_form form:first').find('textarea:visible').each(function(){
		  $(this).val($(this).val().replace(/<br\/>/g,'\n'))
	  }); 			  	         
	},  
	dataType:'html', 
	url:'edit.php',
	//data:$('#container_form form:first').serialize(),
	success:function(data){		
	$(".content_container").animate({ scrollTop: "0px" });
	  if ($(data).filter(".error_alert").length > 0){		   
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-error alert-block fade in">\
			  <button type="button" class="close" data-dismiss="alert">x</button>\
			  <i class="icon icon-color icon-cross"></i> '+$(data).filter(".error_alert").html()+'</div>').slideDown('slow');
			  $.loader.hide();
		  });				
	  }else{
          $('#container_form form:first').find('.save_item').hide();
		  $('<img src="'+$('body').data('admin_path_img')+'/ajax-loader.gif" alt="" />').insertBefore($('#container_form form:first').find('.save_item'));		  
		  $('#main_table').find('.box-icon').html(''); 		  
		  $('.errors_control').slideUp('slow',function(){
			  $('.errors_control').html('<div class="alert alert-success alert-block fade in">\
			  <i class="icon icon-color icon-check"></i> '+__('general_OPERATION_SUCCESSFULLY')+'</div>')
			  .slideDown('slow',function(){
								 setTimeout(function(){
								   $('#container_form').fadeOut('fast',
										function(){
											 $('.flexigrid').fadeIn('fast',
												function(){
												  reload_tab();	
												}
											  );
											 $('#container_form').remove();
											 $.loader.hide(); 
										}
									);
								 },1500);
								});
		  });              
	  }
	}
  });
}
/*/////////////////CALLBACK FUNCTIONS WHEN AN ELEMENT IS SELECTED//////////////*/
function after_Select(){	
   $(this).find('.check_selezione').prop('checked',true);
}
function after_UnSelect(){	
   $(this).find('.check_selezione').prop('checked',false);
}
function after_Select_Single(){
   $('.trSelected').find('.check_selezione').prop('checked',false);
   if($(this).hasClass('trSelected')){
	$(this).find('.check_selezione').prop('checked',true);
   }else{
	$(this).find('.check_selezione').prop('checked',false);
   }
}
/*/////////////////RELOAD FLEXIGRID//////////////*/
function reload_tab(){
   $('#table_scroll').flexReload();
}
/*/////////////////FUNCTION TO DELETE A UNIQUE ELEMENT//////////////*/
function delete_unique_item(id){
   $('#deletemodal').find('.modal-body').html('<i class="icon-trash"></i> '
	+__('general_DELETING_SINGLE_ITEM_ALERT')+'<br/><br/><br/>\
	<span class="btn btn-success ok-delete"><i class="icon-white icon-ok"></i> '+__('general_CONFIRM_BUTTON')+'</span> \
	<span class="btn btn-danger" data-dismiss="modal"><i class="icon-white icon-remove"></i> '+__('general_DISCARD_BUTTON')+'</span>');
   $('#deletemodal').find('.ok-delete').click(function(){
	   $.ajax({
		type:'POST',
		url:'../../del_table.php',
		data: ({id:id,type:'unique',tb:$('body').data('tb')}),
		complete:function(){
			  if (typeof post_delete_unique_item == 'function') { 
				post_delete_unique_item(id); 
			  }				
		},
		success:function(data){
		 reload_tab();
		 $('#deletemodal').modal('hide'); 
		}
	   });				 
   });
   $('#deletemodal').modal('show');
}
/*/////////////////FUNCTION ON LOAD FLEXIGRID//////////////*/
function post_success(){
   $('#table_scroll .action-info').click(function(){
	   var id = $(this).attr('id');
	   $.ajax({
		  type:'POST',
		  url : 'info.php',
		  data : ({id:id}),
		  success:function(data){
			$('#infomodal').find('.modal-body').html($(data).filter('#body'));
			$('#infomodal').find('#infolabel').html($(data).filter('#label'));
		  },
		  complete:function(){
			  $('#infomodal').modal('show');
		  }
	   });
   });
   $('#table_scroll .action-edit').click(function(){
	 var id = $(this).attr('id');
	 edit_item(id);
   });
   $('#table_scroll .action-delete').click(function(){
	 var id = $(this).attr('id');
	 delete_unique_item(id);			 
   });
   /*
    Adjust flexi table height on window resize (important for responsive effect)
   */
   $(window).resize(function(){
	   $('.flexigrid').find('.nDiv,.bDiv').css('height',$('.content_container').height()-150+'px'); 
	   $('.flexigrid').find('.nDiv').css('margin-bottom',$('.content_container').height()-150+'px');
   });
 /*------------------------- FUNCTION TO HIGHLIGHT RESULT OF ADVANCED SEARCH ---------------*/
	/*	
	highlight v3 - Modified by Marshal (beatgates@gmail.com) to add regexp highlight, 2011-6-24	
	Highlights arbitrary terms.	
	<http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html>	
	MIT license.	
	Johann Burkard
	<http://johannburkard.de>
	<mailto:jb@eaio.com>	
	*/
	jQuery.fn.highlight=function(a){function c(a,d){var e=0;if(a.nodeType===3){var f=a.data.search(b);if(f>=0&&a.data.length>0){var g=a.data.match(b);var h=document.createElement("span");h.className="label label-info";var i=a.splitText(f);var j=i.splitText(g[0].length);var k=i.cloneNode(true);h.appendChild(k);i.parentNode.replaceChild(h,i);e=1}}else if(a.nodeType===1&&a.childNodes&&!/(script|style)/i.test(a.tagName)){for(var l=0;l<a.childNodes.length;l++){l+=c(a.childNodes[l],d)}}return e}var b=typeof a==="string"?new RegExp(a,"i"):a;return this.each(function(){c(this,a)})};jQuery.fn.removeHighlight=function(){return this.find("span.highlight").each(function(){this.parentNode.firstChild.nodeName;with(this.parentNode){replaceChild(this.firstChild,this);normalize()}}).end()} 
   $('#advanced_search input:text,#advanced_search select,#advanced_search textarea').each(function(){
	 if($(this)[0].tagName == 'SELECT'){
	  var high = $('#'+$(this).attr('id')+' option:selected').text().split(' ')
	 }else{
	  var high = $(this).val().split(' ')
	 }
	  for(i=0;i<=high.length;i++){
		if (high[i] != '' && high[i] != 'undefined' && high[i] != null)
		 if($(this).attr('abbr_r') != 'undefined' && $(this).attr('abbr_r') != null && $(this).attr('abbr_r') != ''){ 
		  for(ii=0;ii<=$(this).attr('abbr_r').split(' ').length;ii++){
		   $('#table_scroll [abbrn="'+$(this).attr('abbr_r').split(' ')[ii]+'"]').highlight(high[i])
		  }
		 }
		}   
	  });       
}
/*/////////////////FUNCTIONS ON BUTTONS//////////////*/
function flex_buttons(com, grid){
   var check_all = $('.selectall').text()
	  ,uncheck_all = $('.unselectall').text()
	  ,delete_select = $('.deleteselected').text()
	  ,add_new = $('.add_new').text();    
   if(com == check_all){
	$('#table_scroll tr').addClass('trSelected').find('.check_selezione').attr('checked','checked');
   }
   if(com == uncheck_all){
	$('#table_scroll tr').removeClass('trSelected').find('.check_selezione').removeAttr('checked'); 
   }
   if(com == add_new){
	add_item();
   } 
   if(com == delete_select){
	 if($('#table_scroll tr').find('.check_selezione:checked').length > 0){
	   $('#deletemodal').find('.modal-body').html('<i class="icon-trash"></i> '
		+__('general_DELETING_MULTIPLE_ITEM_ALERT')+'<br/><br/><br/>\
		<span class="btn btn-success ok-delete"><i class="icon-white icon-ok"></i> '+__('general_CONFIRM_BUTTON')+'</span> \
		<span class="btn btn-danger" data-dismiss="modal"><i class="icon-white icon-remove"></i> '+__('general_DISCARD_BUTTON')+'</span>');
	   $('#deletemodal').find('.ok-delete').click(function(){
		   $.ajax({
			 type:'POST',
			 url: '../../del_table.php',
			 data: $('#table_scroll *').serialize()+'&tb='+$('body').data('tb')+'&type=multy',
			 success:function(data){   
			   reload_tab();
			   $('#deletemodal').modal('hide'); 
			 }
		  }); 				 
	   });
	   $('#deletemodal').modal('show');
	 }else{
	   $('#deletemodal').find('.modal-body').html('<div class="alert alert-error alert-block fade in"><i class="icon icon-color icon-cross"></i> '+__('general_NO_ITEM_SELECTED_ALERT')+'</div>');
	   $('#deletemodal').modal('show');
	 }  
   } 
}
/*////////// MAIN MENU MANAGEMENT ////////////////*/
function MainMenuAction(el){
 el.find('.accordion-group').each(function(){
	  if($(this).find('.accordion-body').length > 0){
		  if($(this).find('.accordion-body').hasClass('in')){
			  $(this).addClass('active');
			  $(this).find('.accordion-heading').find('i:last').removeClass('icon-chevron-down').addClass('icon-chevron-up');
		  }else{
			  $(this).removeClass('active'); 
			  $(this).find('.accordion-heading').find('i:last').removeClass('icon-chevron-up').addClass('icon-chevron-down');
		  }
	  }
 });			 
}
function MainMenuActionDefault(el){
   el.find('.accordion-group').each(function(){
	  if($(this).find('.accordion-inner a.active').length > 0){
		  $(this).addClass('active');
		  $(this).find('.accordion-body').addClass('in');
	  }
	  if($(this).find('.accordion-body').length > 0){
		 $(this).find('.accordion-heading i.icon-chevron-up,.accordion-heading i.icon-chevron-down,.accordion-heading .clearfix').remove();
		 if($(this).hasClass('active')){
			  $(this).find('.accordion-heading a').append('<span class="pull-right badge badge-info"><i class="icon-chevron-up icon-white"></i></span><div class="clearfix"></div>');
		 }else{
			  $(this).find('.accordion-heading a').append('<span class="pull-right badge badge-info"><i class="icon-chevron-down icon-white"></i></span><div class="clearfix"></div>');
		 }
	  }
   });			   
}
/***** funcitons on load ****/
$(function(){
	/*////////// CHANGE LANGUAGE ON THE FLY ///////*/
/*	 $('#change_language_on_the_fly').change(function(){
		$(this).closest('form').submit();
	 });*/	
	/*////////// BUTTON BACK TO LIST ///////*/       
	$('.back_list').live('click',function(){ 
	$.loader();
	   $('#container_form').fadeOut('fast',function(){
		     $('.flexigrid').fadeIn('fast',
			     function(){
				   reload_tab();
				   $.loader.hide();
				 }
			  );
			 $('#container_form').remove();
			 $('#main_table').find('.box-icon').html('');
	   });      
	});
	/* hack for IE < 9 to fix fluid layout problem */  	
/*	if ( $.browser.msie && $.browser.version < 9){
	 $('.container-fluid').removeClass('container-fluid').addClass('container');
	 $('.row-fluid').removeClass('row-fluid').addClass('row');	
	}*/
	/*////////// ADVANCED SERACH MANAGEMENT ///////*/
	$('#close_search').click(function(){
		  $('#body_search').slideToggle('fast');
		  $(this).find('i').toggleClass('icon-chevron-up').toggleClass('icon-chevron-down');
	});	
   /*////////// MAIN MENU MANAGEMENT ///////*/
   $('#main_menu').find('a').each(function(){
	   if(window.location.href.indexOf($(this).attr('href').replace(/ /g,'%20')) >-1 && $(this).attr('href') != '' && $(this).attr('href') != '#')
	    $(this).addClass('active disabled').css('cursor','default').closest('.accordion-group').addClass('active');
		if($(this).hasClass('active')){
			$(this).click(function(e){
				e.preventDefault();
			});
        }
   });   
   $('#menu-accordion').on('shown hidden',function(){
	  MainMenuAction($(this));
   });
   MainMenuActionDefault($('#menu-accordion'));	
   if($(window).width() < 768){
	   $('#main_menu').show().removeClass('in'); 
   }else{
		$('#main_menu').show().addClass('in');
   }   
   $(window).resize(function(){			 
	   if($(this).width() < 768){		
		   $('#main_menu').css({'height':'0px'});
		   $('#collapse-menu').removeClass('collapsed');		 
		   $('#main_menu').removeClass('in'); 		
	   }else{
		   $('#main_menu').css({'height':'auto'});
		   $('#collapse-menu').addClass('collapsed');
		   $('#main_menu').addClass('in');
	   }
   });    
   $('#menu-accordion > .accordion-group').hover(
	  function(){
		if(!$(this).hasClass('active'))
		$(this).addClass('over');
	  },
	  function(){
		if(!$(this).hasClass('active'))
		$(this).removeClass('over'); 
	  }
   );     
		
   /*////////// ACTIVATION OF TOOLTIP PLUGIN ON ELEMENTS WITH REL-TOOLTIP AND/OR REL ATTRIBUTE = "tooltip" ///////*/   
   $('[rel-tooltip="tooltip"],[rel="tooltip"]').livequery(function(){
	$(this).qtip({
	   position: {
		my: 'bottom center',
		at: 'top center',
		viewport: $(window)
	   },
	   style: {
		classes: 'ui-tooltip-dark ui-tooltip-shadow ui-tooltip-tipsy'
	   }	   
	});		
   });	
   /*////////// STYLE REQUIRED OR NOT FIELDS ///////*/
 
   $('input,select,textarea').livequery(function(){
          /*////////// GENERATE A CONTROL GROUP FOR A FIELDS ///////*/	   
		  /* 
		   * the array is generated by data-array attribute 
		   * array = 
		   * [0]row size of input (span2,3,4... max 12) OR NULL,
		   * [1]row size of container (span2,3,4... max 12) OR NULL,
		   * [2]label text
		   * e.g. 
					   <div class="row-fluid"> 
						  <input type="text" name="name" id="id" value="" data-array="12,6,Name" />
						  <input type="text" name="name2" id="id2" value="" data-array="12,6,Name2" />
					   </div>	 
		   * it'll generate 2 input, each input have a class "span12" and wrapped in a container with class "span6"
		   * P.S. if the placeholder attribute exists the label will be the array[2] value but placeholder will stay original
		   */
			var attr = $(this).attr('data-array'),
				id = $(this).attr('id'),
				$this = $(this);	  
			if($(this).is('[data-array]') && attr !== false && $(this).closest('.control-group').length == 0){
				var arr_input = $(this).attr('data-array').split(','),
				   row_input = '',
				   placeholder = '',
				   row_container = '';
				if(arr_input[0] && arr_input[0].toLowerCase() !== 'null') row_input = 'span'+arr_input[0];
				if(arr_input[1] && arr_input[1].toLowerCase() !== 'null') row_container = 'span'+arr_input[1];
				placeholder = $(this).is('[placeholder]') ? $(this).attr('placeholder') : arr_input[2];					
				$(this)
				  .css('width','100%')
				  .wrap('<div class="control-group '+row_container+'">')
				  .wrap('<div class="controls">')
				  .wrap('<div class="input-prepend no-add row-fluid">')
				  .closest('.control-group').prepend('<label calss="control-label" for="'+id+'" ><strong>'+arr_input[2]+'</strong></label>');
				$(this)
				  .addClass(row_input)
				  .attr('placeholder',placeholder)
				  .find('.input-prepend').append('<br/><span class="error_place"></span>');
				if($(this).hasClass('required')){
					$(this).closest('.control-group').addClass('warning');
				}else if($(this).attr('least-one') == 'true'){
					$(this).closest('.control-group').addClass('inverse');
				}else{
					$(this).closest('.control-group').addClass('info');
				}
				  $(this).on('classadded',function(ev, newClasses) {				  						
						if(newClasses == 'required'){
						    $(this).closest('.control-group').removeClass('inverse info warning').addClass('warning');
						}					
				  });	
				  $(this).closest('.control-group').on('classadded',function(ev, newClasses) {				  						
						if(newClasses == 'error'){
						    $(this).removeClass('inverse info warning');
						}					
				  });	
				  $(this).on('classdeleted',function(ev, newClasses) {				  						
						if(newClasses == 'required'){
						  if($this.attr('least-one') == 'true'){
						    $(this).closest('.control-group').removeClass('inverse info warning').addClass('inverse');
						  }else{
							 $(this).closest('.control-group').removeClass('inverse info warning').addClass('info'); 
						  }
						}					
				  });				  			  				  				  
			}
		   /****************************************************/	 		   	  
       });	
	  /*////////// STYLE CHECKGOX AND RADIO IN A GROUP ///////*/
		  /* 
		   * e.g. 
		    CHECKBOX:
                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                    <input type="checkbox" data-text-checked="some text for checked status1" id="id1" name="name1" data-label-name="Name Button1" data-additional-classes="btn-info" value="value1" checked />  
                    <input type="checkbox" data-text-checked="some text for checked status2" id="id2" name="name2" data-label-name="Name Button2" data-additional-classes="btn-info" value="value2" />
					<input type="checkbox" data-text-checked="some text for checked status3" id="id3" name="name3" data-label-name="Name Button3" data-additional-classes="btn-info" value="value3" />                    
                  </div>
		    RADIO:
                  <div class="checkradio-group" data-icon="icon-ok icon-white">
                    <input type="radio" data-text-checked="some text for checked status1" id="id1" name="nameRadio" data-label-name="Name Button1" data-additional-classes="btn-info" value="value1" checked />  
                    <input type="radio" data-text-checked="some text for checked status2" id="id2" name="nameRadio" data-label-name="Name Button2" data-additional-classes="btn-info" value="value2" />
					<input type="radio" data-text-checked="some text for checked status3" id="id3" name="nameRadio" data-label-name="Name Button3" data-additional-classes="btn-info" value="value3" />                    
                  </div>				  
		   * for each input are important:
		       1) id --> must be unique
			   2) name --> like a normal input
			   3) data-label-name attribute --> this value will be the button name
			   4) data-additional-classes --> to choose the button color and/or size (see twitter bootstrap options)
			       if this atribute will be empty or undefined the button will be like a standard Bootstrap button
			   5) data-icon --> icon class for input checked (ATTENTION: THIS ATTRIBUTE MUST PLASE INTO DIV CONTAINER WITH CLASS "checkradio-group"
			       if this atribute will be empty or undefined the button will be without icon for checked status
			   6) checked_text --> text for checked input 
			       if this atribute will be undefined (not EMPTY) the button label will be the same of data-label-name attribute					   					   
			   P.s. The script detect a checked input and show button on active status
		   */	  
	  $('.checkradio-group').livequery(function(){
		  var type_input = $(this).find(':checkbox').length > 0 ? 'buttons-checkbox' : 'buttons-radio';
		   $('<div class="btn-group" data-toggle="'+type_input+'">').insertAfter($(this));
		  $(this).find(':checkbox,:radio').each(function() {
			  var id = $(this).attr('id'),
			      label_name = $(this).attr('data-label-name'),
				  active_status = $(this).prop('checked') ? ' active' : '',
				  disabled_status = $(this).prop('disabled') || $(this).prop('readonly') ? ' disabled' : '',
				  data_additional_classes = $(this).is('[data-additional-classes]') ? $(this).attr('data-additional-classes') : '',
				  checked_text = $(this).is('[data-text-checked]') ? $(this).attr('data-text-checked') : '',
				  data_icon = $(this).closest('.checkradio-group').is('[data-icon]') ? '<i></i> ' : '';
		     $(this).closest('.checkradio-group').next('.btn-group').append('<div type="button" rel="'+id+'" class="btn '+data_additional_classes+active_status+disabled_status+'" onClick="$(\'#'+id+':not([readonly]):not([disabled])\').click();">'+data_icon+'<span class="lcheck_cont">'+label_name+'</span></div>');
			 $(this).closest('.checkradio-group').css({
				'width': '0px',
				'height':'0px',
				'outline':'none',
				'padding':'0px',
				'margin':'0px',
				'-moz-opacity':'0',
				'filter':'alpha(opacity:0)',
				'opacity':'0'
             });
				  if($(this).is('[data-text-checked]')){
						$(this).prop('checked') == true
						 ? $('div[rel="'+id+'"] span.lcheck_cont').html(checked_text)
						 : $('div[rel="'+id+'"] span.lcheck_cont').html(label_name);	
					$(this).on('click',function(){
					  if($(this).is(':checkbox')){
						$(this).prop('checked') == true
						 ? $('div[rel="'+id+'"] span.lcheck_cont').html(label_name)
						 : $('div[rel="'+id+'"] span.lcheck_cont').html(checked_text);
					  }else{
						  $(this).closest('.checkradio-group').next('.btn-group').find('div.btn').each(function(){
							  var label = $('#'+$(this).attr('rel')).attr('data-label-name');
							  $(this).find('span.lcheck_cont').html(label);
						  });
						  $('div[rel="'+id+'"] span.lcheck_cont').html($(this).attr('data-text-checked'));
					  }
					});
				  }			 
			   if( data_icon != ''){
			     if( $(this).prop('checked') == true ) $('div[rel="'+id+'"] i:first').addClass($(this).closest('.checkradio-group').attr('data-icon'));				  
				  $(this).on('click',function(){
				   if( $(this).prop('disabled') != true && $(this).prop('readonly') != true ){
					 if($(this).is(':checkbox')){
					  $(this).prop('checked') == true
					   ? $('div[rel="'+id+'"] i:first').removeClass($(this).closest('.checkradio-group').attr('data-icon'))
					   : $('div[rel="'+id+'"] i:first').addClass($(this).closest('.checkradio-group').attr('data-icon'));
					 }else{
						 $(this).closest('.checkradio-group').next('.btn-group').find('div.btn i').removeClass($(this).closest('.checkradio-group').attr('data-icon'));
						 $(this).closest('.checkradio-group').next('.btn-group').find('div[rel="'+id+'"] i:first').addClass($(this).closest('.checkradio-group').attr('data-icon'));
					 }
				   }
				  });
			   }
		  });
	  });	
	  /*////////// STYLE separated CHECKBOX ///////*/  
		  /* 	   
		   * e.g. 
		    CHECKBOX:
                 <input type="checkbox" data-text-checked="some text for checked status" id="id1" data-icon="icon-ok icon-white" name="name1" class="bootstyl" data-label-name="Name Button1" data-additional-classes="btn-info" value="value1" checked />  
		   * for each input are important:
		       1) id --> must be unique
			   2) name --> like a normal input
			   3) data-label-name attribute --> this value will be the button name
			   4) class "bootstyl" --> important to initialize plugin
			   5) data-additional-classes --> to choose the button color and/or size (see twitter bootstrap options)
			       if this atribute will be empty or undefined the button will be like a standard Bootstrap button
			   6) data-icon --> icon class for input checked
			       if this atribute will be empty or undefined the button will be without icon for checked status				   
			   7) checked_text --> text for checked input 
			       if this atribute will be undefined (not EMPTY) the button label will be the same of data-label-name attribute				   
			   P.s. The script detect a checked input and show button on active status				 
		  */ 				  	  
	  $(':checkbox.bootstyl').livequery(function(){
			  var id = $(this).attr('id'),
			      label_name = $(this).attr('data-label-name'),
				  active_status = $(this).prop('checked') ? ' active' : '',
				  disabled_status = $(this).prop('disabled') || $(this).prop('readonly') ? ' disabled' : '',
				  checked_text = $(this).is('[data-text-checked]') ? $(this).attr('data-text-checked') : '',
				  data_additional_classes = $(this).is('[data-additional-classes]') ? $(this).attr('data-additional-classes') : '',
				  data_icon = $(this).is('[data-icon]') ? '<i></i> ' : '';
				  $('<div type="button" rel="'+id+'" data-toggle="button" class="btn '+data_additional_classes+active_status+disabled_status+'" onClick="$(\'#'+id+':not([readonly]):not([disabled])\').click();">'+data_icon+ '<span class="lcheck_cont">'+label_name+'</span></div>').insertAfter($(this));				  
				  $(this).css({
					'width': '0px',
					'height':'0px',
					'outline':'none',
					'padding':'0px',
					'margin':'0px',
					'-moz-opacity':'0',
					'filter':'alpha(opacity:0)',
					'opacity':'0'
                  });
				  if($(this).is('[data-text-checked]')){
						$(this).prop('checked') == true
						 ? $('div[rel="'+id+'"] span.lcheck_cont').html(checked_text)
						 : $('div[rel="'+id+'"] span.lcheck_cont').html(label_name);					  
					$(this).on('click',function(){
						$(this).prop('checked') == true
						 ? $('div[rel="'+id+'"] span.lcheck_cont').html(label_name)
						 : $('div[rel="'+id+'"] span.lcheck_cont').html(checked_text);
					});
				  }
				  
				  if( data_icon != ''){					  
					if( $(this).prop('checked') == true ) $('div[rel="'+id+'"] i:first').addClass($(this).attr('data-icon'));  
					$(this).on('click',function(){						
					  if( $(this).prop('disabled') != true && $(this).prop('readonly') != true ){
						$(this).prop('checked') == true
						 ? $('div[rel="'+id+'"] i:first').removeClass($(this).attr('data-icon'))
						 : $('div[rel="'+id+'"] i:first').addClass($(this).attr('data-icon'));
					  }
					});
				  }
	  });
	  /*////////// STYLE DROPDOWN FIELD ///////*/
		  /* 	   
		   * e.g. 
		    <select name="FieldName" id="FieldID" data-additional-classes="btn-info" data-verse="right">
			   <option data-img-before='<img src="SXimgPath.ext" style="someStyleHERE" />' data-img-after='<img src="DXimgPath.ext" style="someStyleHERE" />' value="OptionValue" selected >Option Text</option>
               <option data-img-before='<img src="SXimgPath.ext" style="someStyleHERE" />' data-img-after='<img src="DXimgPath.ext" style="someStyleHERE" />' value="OptionValue" >Option Text</option>
			   <option data-img-before='<img src="SXimgPath.ext" style="someStyleHERE" />' data-img-after='<img src="DXimgPath.ext" style="someStyleHERE" />' value="OptionValue" >Option Text</option>
			   ...
			</select>
		   * for each input are important:	
		       1) id --> must be unique	       
			   2) name --> must be unique like a normal select
			   3) data-img-before --> code for an image to put befor the option text into styled select (pay attention to singlequote and dublequote)
			   4) data-img-after --> code for an image to put after the option text into styled select (pay attention to singlequote and dublequote)
			   5) class "bootstyl" --> important to initialize plugin
			   6) data-additional-classes --> to choose the button color and/or size (see twitter bootstrap options)
			       if this atribute will be empty or undefined the button will be like a standard Bootstrap button	
			   7) data-verse --> to align the dropdown menu. (see twitter bootstrap options) (values "left"/"right")
			       if this atribute will be empty or undefined it will be set on "left" option				   		   
			   P.s. The script detect a selected option and give it ".selected" class to underline it
		  */ 	  
	  $('select.bootstyl').livequery(function(){
		 $arr_option = '';
		var $name = $(this).attr('name'),
		$id = $(this).attr('id'),
		$data_additional_classes = $(this).is('[data-additional-classes]') ? $(this).attr('data-additional-classes') : '',
		$data_verse = $(this).is('[data-verse]') ? 'pull-'+$(this).attr('data-verse') : '',
		$value = $(this).val();		
		$('#'+$id+' option').each(function(){
		   var $val = $(this).val(),
		   $text = $(this).html(),				   
		   $img_before = $(this).is('[data-img-before]') ? $(this).attr('data-img-before') : '',		   
		   $img_after = $(this).is('[data-img-after]') ? $(this).attr('data-img-after') : '';
		   $arr_option += '<li><a href="#" rel="'+$val+'">'+$img_before+$text+$img_after+'</span></a>';		   		    
		});		
		$('<div class="btn-group btn-group-styled" id="styledselect'+$id+'">\
			 <a class="btn dropdown-toggle '+$data_additional_classes+'" data-toggle="dropdown" href="#"><span class="text-select-container"></span> <span class="caret"></span></a>\
			<ul class="dropdown-menu '+$data_verse+'">'+$arr_option+'</ul>\
		  </div>').insertAfter($(this));
		$(this).css({
					'width': '0px',
					'height':'0px',
					'outline':'none',
					'padding':'0px',
					'margin':'0px',
					'-moz-opacity':'0',
					'filter':'alpha(opacity:0)',
					'opacity':'0'
                  }).closest('.input-prepend').removeClass('input-prepend');
	    $('.btn-group-styled > .dropdown-menu').find('a').on('click',function(){
			var $this_select_id = $(this).closest('.btn-group-styled').attr('id').replace('styledselect','');					
			$(this).closest('.btn-group-styled').find('a').removeClass('selected');
			$(this).closest('.btn-group-styled').find('.text-select-container').html($(this).html());		
			$('#'+$this_select_id).val($(this).attr('rel')).change();
			$(this).addClass('selected');
		});
		$('#styledselect'+$id).find('.text-select-container').html($('#styledselect'+$id+' > .dropdown-menu').find('a[rel="'+$value+'"]').html());
	  });	  
	 /*////////// SET DATAPICKER FOR .DATA-P CLASS SELECTOR ///////*/
	 $('.data-p').livequery(function(){
		 $(this).css('z-index','100000').datepicker({dateFormat: "dd/mm/yy"}).addClass('input-small');
	 });	
	 /*///////// EMPTY AJAX REQUEST CACHE ////////////////////////*/
	$.ajaxSetup({
	  cache: false
	});	    
});/* close jquery initialization */
// function to round off a number to 1,2,3,4... digits eg:. arrotonda(2.543543,3) = 2.544 ---- WARNING arrotonda(2.999999,3) = 3 because it is a rounding
 function arrotonda(c,d){
 // math.pow è 10 elevato a d
  d = Math.pow(10,d)
  var c = Math.round(eval(c)*d)/d
  return c
 } 
 
// function to cut off decimals with 1,2,3,4... digits eg:. tronca(2.543543,3) = 2.543
function left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}
 function tronca(c,d){
   c = ''+c+'';
   if(c.indexOf('.')>-1){  
     var x = c.split('.')
         , y = left(x[1],d)
         , c = x[0]+'.'+y;
   }
  return c;
 }
 
// function to format numbers like 1234567.789 into 1.234.567,789 
function format_num(str){
var str = ''+str+''
    , split_str = str.split('.')
    , intero = split_str[0]
    , decimale = split_str[1];
if ((new RegExp(/^\-/)).test(intero)){
  var segno = '-'
      , intero = intero.replace(/\-/g,'');
}else{
  var segno = '';
}
 if(decimale != null){
  if(decimale.length == "1"){
   var decimale = decimale+'0';
  }else{
   var decimale = decimale;
  }
 }else{
   var decimale = '00';
 }  
var intero_inverse = ' '+intero.split("").reverse().join("")
    , x = intero_inverse.split("")
    , y = "";
for ( i= 0 ; i <= intero_inverse.length-1 ; i++ ){ 
   if( i % 3 == 0 && i > 0 && i < intero_inverse.length-1){
   var y = y + x[i]+'.'; 
   }else{
   var y = y + x[i];
   }
}
numero_formattato = y.split("").reverse().join("").replace(/\s+$/,"")+','+decimale;
return segno+numero_formattato;
}