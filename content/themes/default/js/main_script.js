/* FUNCTION TO PARSERIZE QUERY_STRING (USED FOR PRICE FILTER) */
function getQueryStrings() {
  var assoc  = {};
  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
  var queryString = location.search.substring(1);
  var keyValues = queryString.split('&');

  for(var i in keyValues) {
    var key = keyValues[i].split('=');
    if (key.length > 1) {
      assoc[decode(key[0])] = decode(key[1]);
    }
  }
  return assoc;
}
/*
var qs = getQueryStrings();
var myParam = qs["myParam"];
*/
/* ADDITIONAL METHOD "classadded" to know what class added to a DOM element */
(function($) {
    var ev = new $.Event('classadded'),
        orig = $.fn.addClass;
    $.fn.addClass = function() {
        $(this).trigger(ev, arguments);
        return orig.apply(this, arguments);
    }
})(jQuery);
/* ADDITIONAL METHOD "classadded" to know what class deleted to a DOM element */
(function($) {
    var ev = new $.Event('classdeleted'),
        orig = $.fn.removeClass;
    $.fn.removeClass = function() {
        $(this).trigger(ev, arguments);
        return orig.apply(this, arguments);
    }
})(jQuery);
/* FUNCTION TO FORMAT NUMBERS LIKE 1234567.789 INTO 1.234.567,789  */
function format_num(str,thousands_separator,decimals_separator){
	var thousands_separator = thousands_separator == null ? '.' : thousands_separator,
	decimals_separator = decimals_separator == null ? ',' : decimals_separator,
	str = ''+str+''
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
	   var y = y + x[i]+thousands_separator;
	   }else{
	   var y = y + x[i];
	   }
	}
	numero_formattato = y.split("").reverse().join("").replace(/\s+$/,"")+decimals_separator+decimale;
	return segno+numero_formattato;
}
/* function to cut off decimals with 1,2,3,4... digits eg:. tronca(2.543543,3) = 2.543 */
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

/* FUNCTION TO UPDATE PRICE CHANGING OPTIONS INTO PRODUCT  SHEET */
function update_product_price()
{
	if($('#product-sheet').length > 0)
	{
		var price_container = $('[data-price-value]');

		price_container.each(function(){
			var $this = $(this),
				new_price = $this.data('price-value'),
				container_price_val = $this.find('.container_price_val');

			$('.select-of-options').each(function(){
				var option_selected = $('option:selected',this);
				var option_selected_sign = option_selected.data('value-type');
				var option_selected_val = option_selected.data('value');
				if(option_selected_sign === '-') new_price -= option_selected_val;
				else new_price += option_selected_val;
			});
			container_price_val.html(new_price.toFixed(2));
		});

	}
}

update_product_price();
$('body').on('change','.select-of-options',function(){
  update_product_price();
});

/* FUNCTION TO UPDATE DROPDOWN CART */
function update_top_cart(){
		   $.ajax({
			   type: 'POST',
			   url: $('body').data('abs_client_path')+'/index.php',
			   success: function(data){
				   $header = $(data).find('.topcart li.dropdown .dropdown-toggle').html();
				   $body_products = $(data).find('.topcart li.dropdown ul.dropdown-menu .product-tbody-container').html();
				   $body_counts = $(data).find('.topcart li.dropdown ul.dropdown-menu .counts-container').html();
				   $('.topcart li.dropdown .dropdown-toggle').html($header);
				   $('.topcart li.dropdown ul.dropdown-menu .product-tbody-container').html($body_products);
				   $('.counts-container').html($body_counts);
			   }
		   });
}
/* FUNCTION TO UPDATE CART */
function update_cart(){
		   $.ajax({
			   type: 'POST',
			   url: $('body').data('abs_client_path')+'/cart.php',
			   success: function(data){
				   $product_list = $(data).find('.cart-page .product-tbody-container').html();
				   $report = $(data).find('.cart-page .counts-container').html();
				   $('.cart-page .product-tbody-container').html($product_list);
				   $('.cart-page .counts-container').html($report);
			   }
		   });
}
/* PLUGIN TO RESET POINTER TO CURRENT POSITION WHEN TYPING IN INPUTS OR TEXTAREAS */
$.fn.extend({
	resetCursorPosition: function(begin, end) {
		if (this.length == 0) return;
		if (typeof begin == 'number') {
			end = (typeof end == 'number') ? end : begin;
			return this.each(function() {
				if (this.setSelectionRange) {
					this.focus();
					this.setSelectionRange(begin, end);
				} else if (this.createTextRange) {
					var range = this.createTextRange();
					range.collapse(true);
					range.moveEnd('character', end);
					range.moveStart('character', begin);
					range.select();
				}
			});
		} else {
			if (this[0].setSelectionRange) {
				begin = this[0].selectionStart;
				end = this[0].selectionEnd;
			} else if (document.selection && document.selection.createRange) {
				var range = document.selection.createRange();
				begin = 0 - range.duplicate().moveStart('character', -100000);
				end = begin + range.text.length;
			}
			return { begin: begin, end: end };
		}
	}
});
/* GET THE CURRENT POSITION OF POINTER INTO AN INPUT OR TEXTAREA */
(function($) {
$.fn.getCursorPosition = function() {
    var pos = 0;
    var el = $(this).get(0);
    // IE Support
    if (document.selection) {
        el.focus();
        var Sel = document.selection.createRange();
        var SelLength = document.selection.createRange().text.length;
        Sel.moveStart('character', -el.value.length);
        pos = Sel.text.length - SelLength;
    }
    // Firefox support
    else if (el.selectionStart || el.selectionStart == '0')
        pos = el.selectionStart;

    return pos;
}
})(jQuery);

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
		   */
			var attr = $(this).attr('data-array'),
				id = $(this).attr('id')
				$this = $(this);
			if($(this).is('[data-array]') && attr !== false && $(this).closest('.control-group').length == 0){
				var arr_input = $(this).attr('data-array').split(',');
				  var row_input = '',
					  row_container = '';
				if(arr_input[0] && arr_input[0].toLowerCase() !== 'null') row_input = 'span'+arr_input[0];
				if(arr_input[1] && arr_input[1].toLowerCase() !== 'null') row_container = 'span'+arr_input[1];
				$(this)
				  .css('width','100%')
				  .wrap('<div class="control-group '+row_container+'">')
				  .wrap('<div class="controls">')
				  .wrap('<div class="input-prepend no-add row-fluid">')
				  .closest('.control-group').prepend('<label calss="control-label" for="'+id+'" ><strong>'+arr_input[2]+'</strong></label>');
				$(this)
				  .addClass(row_input)
				  .attr('placeholder',arr_input[2])
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
	     /*//////// DISABLE AUTOCOMPLETE FOR EACH FIELDS ///////////*/
		 var autocomplete_off = function(){
		  $(this).attr('autocomplete','off')
		 }
		 $('input:text,textarea').livequery(autocomplete_off);
		  /*////////// HOVER ACTION FOR PRODUCTS HIGHLIGHTS ////////////////*/
		  $('[data-exstend-link="on"]').on({
			  mouseenter: function(){
				$(this).addClass('on');
			  },
			  mouseleave: function(){
				$(this).removeClass('on');
			  }
		  });
		 /*///////// STYLE FOR RESPONSIE MENU //////////////////*/
		  $('#accordion-categories-menu .accordion-group').on('show',function(){
			$(this).addClass('accordion-opened');
			$('i',$(this)).addClass('icon-minus').removeClass('icon-plus');
		  });
		  $('#accordion-categories-menu .accordion-group').on('hide',function(){
			$(this).removeClass('accordion-opened');
			$('i',$(this)).removeClass('icon-minus').addClass('icon-plus');
		  });
		  /*///////// CLICK ON MAIN CATEGORY LINK (PREVENTING ACCORDION ACTION) //////////////////*/
		  $('.accordion-heading').find('.link').on('click',function(e){
			e.preventDefault();
			window.location = $(this).attr('data-href');
			return false;
		  });
		  $('.accordion-heading.toggle-link').on('click',function(e){
			e.preventDefault();
			window.location = $(this).find('.link').attr('data-href');
			return false;
		  });
		  /*/////// IMAGES GALLERY WITH CAROUSEL (PRODUCT SHEET) /////////*/
		  $('.img-carousel').on('click','.thumbnail',function(e){
			  e.preventDefault();
			  $(this).closest('.img-carousel').find('.thumbnail').removeClass('active');
			  $(this).addClass('active');
			  $src = $('img',this).attr('data-img');
			  $src_default_size = $('img',this).attr('data-default-size-img');
			  $('#img-preview').css({'height':$('#img-preview img:first').height()+'px','overflow':'hidden'});
			  $('#img-preview img').fadeOut('fast',function(){
				  $('#img-preview').append('<img data-default-size-img="'+$src_default_size+'" style="cursor:zoom-in; display:none;" class="lazy lightbox" data-original="'+$src+'" src="'+$src+'" />');
				  $('#img-preview img:last').fadeIn('fast',function(){
					  $('#img-preview img:first').remove();
					  $('#img-preview').css('height',$('#img-preview img:first').height()+'px');
				  });
			  });
		  });
		   /////////////////////////////////--> Convert , -> .
		   $('body').on('keyup','.number',function(){
			      cur_pos = $(this).getCursorPosition(),
			      get_last_digit = $(this).val().substring(cur_pos-1, cur_pos),
			      regexp = new RegExp(get_last_digit, "g");
				  if($(this).val().search(/^\d+\.?\d*$/) == -1){
					 var cur_pos = $(this).getCursorPosition();
					 $(this).val($(this).val().replace(regexp,''));
					 $(this).resetCursorPosition(cur_pos,cur_pos-1);
				  }
		   });
		   //////////////////////////////////--> replace white spaces
		   $('body').on('keyup','.no_space',function(){
			if($(this).val().indexOf(' ')>-1){
			   var cur_pos = $(this).getCursorPosition()-1;
			   $(this).val($(this).val().replace(/ /g,""));
			   $(this).resetCursorPosition(cur_pos,cur_pos);
			}
		   });
		   $(function(){
		  /*////// ONLY IN HOME PAGE //////*/
			  if($().eislideshow){
				 /* slideshow */
				$('#ei-slider').eislideshow({
					easing		: 'easeOutExpo',
					titleeasing	: 'easeOutExpo',
					titlespeed	: 1200
				});
			  }
		  /*////// MY ACCOUNT ACTIONS ////////*/
		  	 /***** show change password inputs too *****/
			 $('#menu-step-change-data-account li a','.myaccount-page').on('click',function(e){
				e.preventDefault();
			  if(!$(this).closest('li').hasClass('active')){
				var step_to_show = $(this).attr('data-rel');
				$('#menu-step-change-data-account li.active','.myaccount-page').removeClass('active');
				$(this).closest('li').addClass('active');
				$('.account-part:visible','.myaccount-page').fadeOut('fast',function(){
					$(step_to_show).fadeIn('fast');
					$(step_to_show+' form').validate({ignore:'.ignored,:hidden'});
				});
			  }
			 });
			 $('#change-password','.myaccount-page').on('click',function(){
				 if($(this).prop('checked') == true){
					 $('.change-password-container','.myaccount-page').slideUp('slow',function(){
						$('.change-password-container input','.myaccount-page').addClass('ignored');
					 });
				 }else{
					 $('.change-password-container','.myaccount-page').slideDown('slow',function(){
						$('.change-password-container input','.myaccount-page').removeClass('ignored');
					 });
				 }
			 });
				$('form .btn-save','.myaccount-page').click(function(){
				  $(this).closest('form').submit();
				  return false;
				});
				$('form input:text,form input:password','.myaccount-page').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $(this).closest('form').submit();
					 return false;
				  }
				});
				$('.myaccount-page form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('.myaccount-page form:visible').validate({ignore:'.ignored,:hidden'}).form();
				  },
				  beforeSerialize:function(){
				   if($('.myaccount-page form:visible').validate({ignore:'.ignored,:hidden'}).form())
				    $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  complete:function(){

				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/account-actions.php',
				  success:function(data){
				      $.loader.hide();
					  $.bootalert({
						  ID        : 'alert-login-modal',
						  LabelText : $(data).filter('.return').attr('data-label'),
						  TypeLabel : $(data).filter('.return').attr('data-label-type'),
						  BodyText  : $(data).filter('.return').html(),
						  TypeBody  : $(data).filter('.return').attr('data-label-type')
					  });
				  }
				});
				$('.myaccount-page #step-my-orders .info-order').on('click',function(){
					 $id = $(this).attr('data-id');
					  $.ajax({
						type:'POST',
						beforeSend:function(){
						 $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
						},
						data: {id:$id},
						url:$('body').data('abs_client_path')+'/info-order.php',
						success:function(data){
							$.loader.hide();
							$.bootalert({
								ID        : 'alert-info-order-modal',
								LabelText : $(data).filter('.return').attr('data-label'),
								TypeLabel : $(data).filter('.return').attr('data-label-type'),
								BodyText  : $(data).filter('.return').html(),
								TypeBody  : ''
							});
						}
					  });
				});
			   $('body').on('click','.pay-btn',function(){
				     $id = $(this).attr('data-id');
					  $.ajax({
						 type: 'POST',
						 url: $('body').data('abs_client_path')+'/paypal-pay.php',
						 beforeSend:function(){
						   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
						 },
						 success: function(data){
							 $('body').append(data);
						 },
						 complete: function(){
						  $('form#pay_pp').submit().remove();
						 },
						 data:{id_order:$id}
					  });
			   });
		  /*////// CAROUSEL CATEGORIES AND PRODUCTS ////////*/
			$(".carousel_wrapper").each(function(){
				$prev = '#'+$(this).find('.carousel-prev').attr('id');
				$next = '#'+$(this).find('.carousel-next').attr('id');
				$align = $(this).closest('.horizontal-category').length > 0 ? 'left' : 'center';
				$('ul',this).carouFredSel({
					circular: false,
					infinite: false,
					width:'100%',
					align:$align,
					height: "variable",
					items: {
						visible: "variable",
						width: "variable",
						height: "variable"
					},
					scroll: {
						items: 1
					},
					auto: false,
					prev: $prev,
					next: $next,
					swipe: {
						onMouse: false,
						onTouch: true
					},
					mousewheel: false
				});

			});
		   /*////////// initialize bootstrap tooltip (LIVE METHOD) ///////////*/
			$('body').tooltip({
				selector: '.tooltiped'
			});
		  /*/////////// lazy load on product images ////////////*/
		  $('.lazy').lazyload({
			   effect : "fadeIn"
		   });
			   /*///// TOP LOGIN (validation and submit) /////*/
			   $('#btn-login-link').click(function(e){
				   e.preventDefault();
				   $('.welcome-message').fadeOut('fast',function(){
					   $('#top-login-form-container').fadeIn('fast');
				   });
			   });
				$('#top-login-form').validate();
				$('#top-btn-login').click(function(){
				  $(this, 'form').submit();
				  return false;
				});
				$('#top-login-form input:text,#top-login-form input:password').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $('#top-login-form').submit();
					 return false;
				  }
				});
				$('#top-login-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#top-login-form').validate().form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  complete:function(){

				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/check.php',
				  success:function(data){
					  if(data != 'logged'){
						  $.loader.hide();
					    switch(data){
						  case 'not_logged':
							$.bootalert({
								ID        : 'alert-login-modal',
								LabelText : __('general_error_title'),
								TypeLabel : 'error',
								BodyText  : __('wrong_login_message'),
								TypeBody  : 'error'
							});
								$('#top-passwordLog','#top-login-form').val('');
						  break;
						  case 'need_confirmation':
							$.bootalert({
								ID        : 'alert-login-modal',
								LabelText : __('general_warning_title'),
								TypeLabel : 'warning',
								BodyText  : __('account_not_confirmed_message'),
								TypeBody  : 'warning'
							});
								$('input:text,input:password','#top-login-form').val('');
						  break;
						  default:
						  break;
						}
					  }else{
						  setTimeout(function(){
							window.location.reload();
						  },1500);
					  }
				  }
				});
			   /*///// REGULAR LOGIN (validation and submit) /////*/
			   $('#btn-login-link').click(function(e){
				   e.preventDefault();
				   $('.welcome-message').fadeOut('fast',function(){
					   $('#top-login-form-container').fadeIn('fast');
				   });
			   });
				$('#login-form').validate();
				$('#btn-login').click(function(){
				  $(this, 'form').submit();
				  return false;
				});
				$('#login-form input:text,#login-form input:password').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $('#login-form').submit();
					 return false;
				  }
				});
				$('#login-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#login-form').validate().form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  complete:function(){

				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/check.php',
				  success:function(data){
					  if(data != 'logged'){
						  $.loader.hide();
					    switch(data){
						  case 'not_logged':
						    var $body_text = '<strong>'+__('general_error_title')+'</strong><br/>'+__('wrong_login_message'),
								$body_type = 'alert-error';
								$('#top-passwordLog','#login-form').val('');
						  break;
						  case 'need_confirmation':
						    var $body_text = '<strong>'+__('general_warning_title')+'</strong><br/>'+__('account_not_confirmed_message'),
								$body_type = 'alert-warning';
								$('input:text,input:password','#login-form').val('');
						  break;
						  default:
						  break;
						}
						  $('#result-login').html('<div class="alert '+$body_type+' alert-block fade in">\
					           <button type="button" class="close" data-dismiss="alert">x</button>'+$body_text+'</div>')
					           .slideDown('slow');
					  }else{
						  setTimeout(function(){
							window.location.reload();
						  },1500);
					  }
				  }
				});
			   /*///// REGISTRATION FORM /////*/
			   $('#registration-form').validate({
				    rules:{
					  userid:{
						 remote:{
							url:$('body').data('abs_client_path')+'/registration-control.php',
							type: 'POST',
							data:{
							  type:function(){
								 return 'userid';
							  }
							}
						 }
					  },
					  email:{
						 remote:{
							url:$('body').data('abs_client_path')+'/registration-control.php',
							type: 'POST',
							data:{
							  type:function(){
								 return 'email';
							  }
							}
						 }
					  }
					},
					messages:{
					  userid: {remote:__('userid_exists')},
					  email: {remote:__('email_exists')}
					},
					ignore:':hidden'
				});
				/*** read Privacy in modal ***/
				$('#read_privacy').css('cursor','pointer').on('click',function(){
							$.bootalert({
								ID        : 'read_modal_privacy',
								LabelText : 'Privacy',
								TypeLabel : 'info',
								Remote    : $('body').data('abs_client_path')+'/privacy.php'
							});
				});
				/*** choose private or company form ***/
				$(':radio[name="is_company"]','#registration-form').on('click',function(){
					if($(this).prop('checked') == false && $(this).val() == 'private'){
					  	$('#lastname,#lastnames','#registration-form').val('').closest('.control-group').closest('.row-fluid').show();
						$('#tax_code','#registration-form').closest('.control-group').closest('.row-fluid').addClass('hidden');
					}
					if($(this).prop('checked') == false && $(this).val() == 'company'){
						$('#lastname,#lastnames','#registration-form').val('').closest('.control-group').closest('.row-fluid').hide();
						$('[for="tax_code"]:first','#registration-form').html('<strong>'+__('vat')+'*</strong>');
						$('#tax_code','#registration-form').attr('placeholder',__('vat')+'*').closest('.control-group').closest('.row-fluid').removeClass('hidden');
					}
				});
				$('#registration-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#registration-form').validate({ignore:':hidden'}).form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  complete:function(){
					$.loader.hide();
				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/registration.php',
				  success:function(data){
				    if ($(data).filter('.error_alert').length > 0){
					  $.bootalert({
						  ID        : 'registration_error',
						  LabelText : __('general_error_title'),
						  TypeLabel : 'error',
						  BodyText  : $(data).filter('.error_alert').html(),
						  TypeBody  : 'error'
					  });
					}else{
					  $('.default-status-registration-form').fadeOut('fast',
					  function(){
						  $('#result-registration').fadeIn('fast');
					  });
					}
   				    $('#captcha_image').attr('src',$('body').data('abs_client_path')+'/include/lib/cool-php-captcha/captcha.php?'+Math.random());
					$('#captcha').val('');
				  }
				});
			   /*///// RETRIEVED PASSWORD /////*/
			   $('#retrieved-password-form').validate();
				$('#btn-retrieve-password').on('click',function(){
				  $(this, 'form').submit();
				  return false;
				});
				$('#retrieved-password-form input').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $('#retrieved-password-form').submit();
					 return false;
				  }
				});
				$('#retrieved-password-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#retrieved-password-form').validate().form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  complete:function(){
					$.loader.hide();
				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/change-password.php',
				  success:function(data){
					$('.retrieve-password-form-container').fadeOut('fast',function(){
						 $('.login-container-form').fadeIn('fast');
					});
				  }
				});
				/*///// RETRIEVE USER's DATA ///////*/
				$('#btn-retrieve-data').on('click',function(){
				  $(this, 'form').submit();
				  return false;
				});
				$('#retrieve-data-form input:text').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $('#retrieve-data-form').submit();
					 return false;
				  }
				});
				$('#retrieve-data-form').validate({
					rules:{
						userid_retrieve:{require_from_group: [1,".leastoneinput"]},
						email_retrieve:{require_from_group: [1,".leastoneinput"]}
					}
				});
				$('.retrieve-data').on('click',function(e){
					 e.preventDefault();
					 $('#retrieve-data-modal').modal('show');
				});
				$('#retrieve-data-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#retrieve-data-form').validate().form();
				  },
				  beforeSerialize:function(){
						  $('.modal-body','#retrieve-data-modal')
						  .css('position','relative')
						  .append('<div class="container-loader-retrieve overlay-loader" style="z-index:9;width:100%;height:100%;background:#fff;"></div>\
						  <div style="position:absolute;z-index:9999;visibility:hidden" class="loader-retrieve"><img src="'+$('body').data('theme_img_path')+'/loader.gif" alt="" /></div>');
						  setTimeout(function(){
							  $('.loader-retrieve',$('.modal-body','#retrieve-data-modal')).Vcenter($('.modal-body','#retrieve-data-modal')).css('visibility','');
						  },100);
				  },
				  complete:function(){
						   setTimeout(function(){
							 $('.container-loader-retrieve,.loader-retrieve').fadeOut('fast',function(){
								 $(this).remove();
							 });
						   },1500);
				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/retieve-data.php',
				  success:function(data){
					switch(data){
					  case 'data-not-exist':
						var $body_text = '<strong>'+__('general_error_title')+'</strong><br/>'+__('retieve_data_not_match'),
							$body_type = 'alert-error';
							$('input:text,input:password','#retrieve-data-form').val('');
					  break;
					  default:
						var $body_text = '<strong>'+__('general_success_title')+'</strong><br/>'+__('retieve_data_success').replace('{data}',data),
							$body_type = 'alert-success';
							$('input:text,input:password','#retrieve-data-form').val('');
					}
					$('#retrieve-result').html('<div class="alert '+$body_type+' alert-block fade in">\
							 <button type="button" class="close" data-dismiss="alert">x</button>'+$body_text+'</div>')
							 .slideDown('slow');
				  }
				});
				/*///// SCROLL TO TOP /////*/
				$("#go-to-top").hide();
				$(window).scroll(function () {
				   clearTimeout($.data(this, 'scrollTimer'));
					$.data(this, 'scrollTimer', setTimeout(function() {
						$('#go-to-top').fadeOut();
					}, 5000));
					if ($(this).scrollTop() > 100) {
					  $('#go-to-top').fadeIn();
					} else {
					  $('#go-to-top').fadeOut();
					}

				});
				$('#go-to-top').click(function(e){
				  e.preventDefault();
				  $('body,html').animate({scrollTop: 0}, 800);
				});
				/*///// SEND ORDER /////*/
				if($('#guest').length > 0){
				   $('#checkout-form').validate({
					 rules:{
					  useridreg:{
						 remote:{
							url:$('body').data('abs_client_path')+'/registration-control.php',
							type: 'POST',
							data:{
							  guest:function(){
								return $('#guest').length > 0 ? $('#guest').prop('checked') == true ? 'true' : 'false' : 'false';
							  },
							  type:function(){
								 return 'useridreg';
							  }
							}
						 }
					  },
					  email:{
						 remote:{
							url:$('body').data('abs_client_path')+'/registration-control.php',
							type: 'POST',
							data:{
							  guest:function(){
								return $('#guest').length > 0 ? $('#guest').prop('checked') == true ? 'true' : 'false' : 'false';
							  },
							  type:function(){
								 return 'email';
							  }
							}
						 }
					  }
					},
					messages:{
					  useridreg: {remote:__('userid_exists')},
					  email: {remote:__('email_exists')}
					},
					ignore:':hidden'
				   });
				}
				$('#checkout-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#checkout-form').validate({ignore:':hidden'}).form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/save-order.php',
				  success:function(data){
				   $.loader.hide();
				   $('tr.container-error,tr.container-warning','.checkout-page .products-list-table').hide();
				   $('tr','.checkout-page .products-list-table').removeClass('warning tr_error');
				   if($(data).filter('#products_to_update').length > 0 || $(data).filter('#products_to_delete').length > 0){
					  $.bootalert({
						  ID        : 'cart-correction-alert',
						  LabelText : __('general_warning_title'),
						  TypeLabel : 'warning',
						  BodyText  : '<strong>'+__('send_order_not_success_cart_updated')+'</strong>',
						  TypeBody  : 'warning'
					  });
					 if($(data).filter('#products_to_update').length > 0){
					  var product_to_update = $.parseJSON($(data).filter('#products_to_update').html());
					  $.each(product_to_update, function() {
							$('#product_'+this['id']+' tr.container-warning','.checkout-page .products-list-table').show()
							.find('td').html('<strong class="text-warning">'+__('availability_update_after_order').replace('{availability}',this['availability'])+'</strong>');
							$('#product_'+this['id']+' tr:not(:first)','.checkout-page .products-list-table').addClass('warning');
					  });
					 }
					 if($(data).filter('#products_to_delete').length > 0){
					  var product_to_delete = $.parseJSON($(data).filter('#products_to_delete').html());
					  $.each(product_to_delete, function() {
							$('#product_'+this['id']+' tr.container-error','.checkout-page .products-list-table').show()
							.find('td').html('<strong class="text-error">'+__('product_removed_from_cart_because_not_available')+'</strong>');
							$('#product_'+this['id']+' tr:not(:first)','.checkout-page .products-list-table').addClass('tr_error');
					  });
						var new_report_data = $.parseJSON($(data).filter('#new_report_data').html());
						$.each(new_report_data, function() {
							  $('.subtotal-container','.checkout-page .products-list-table').html(this['subtotal']);
                              $('.tax-container','.checkout-page .products-list-table').html(this['tax']);
							  $('.grandtotal','.checkout-page .products-list-table').html(this['grandtotal']).attr('data-grandtotal',this['grandtotal_unformatted']);
						});
						/* MULTITAXES PLUGIN */
						if($(data).filter('#multi_tax_div_container').length > 0){
						  $('.multi_tax_container').removeClass('.multi_tax_container').addClass('multi_tax_container_old');
						  var multi_tax_div_container = $.parseJSON($(data).filter('#multi_tax_div_container').html());
						   table_multitaxes = '';
						  $.each(multi_tax_div_container, function() {
							    $tax_data = this['tax_data'].split('__');
							    table_multitaxes += '<tr class="multi_tax_container">\
								<td class="text-right" colspan="0">'+$tax_data[0]+'</td>\
								<td class="text-right">'+$tax_data[1]+'</td>\
								</tr>';
						  });
						  $('.multi_tax_container_old:last').after(table_multitaxes);
						  $('.multi_tax_container_old').remove();
						}
						/* /MULTITAXES PLUGIN */
					 }
					 $('#checkout-form').StepizeForm('Rehight');
				   }else if($(data).filter('.error_alert').length > 0){
					  $.bootalert({
						  ID        : 'send_order_error',
						  LabelText : __('general_error_title'),
						  TypeLabel : 'error',
						  BodyText  : $(data).filter('.error_alert').html(),
						  TypeBody  : 'error'
					  });
				   }else if($(data).filter('#cart_empty').length > 0){
						window.location.reload();
				   }else{
					 $('.checkout-page').fadeOut('fast',function(){
						 $('.order-success').fadeIn('fast');
						 if($('.payment_paypal').prop('checked') == true){
							 $(' <span data-id="'+$(data).filter('#order_id').html()+'" class="pay-btn btn btn-info squared unbordered solid btn-large">'+__('pay_now_button')+'</span>').insertAfter($('.return-shopping','.order-success'));
						 }else{
						   $('.pay-btn','.order-success').remove();
						 }
					 });
				   }
				  }
				});
				/*///// INFO FOR BY_EXPOSURED PRODUCTS ///////*/
				$('.by_exposure').on('click',function(e){
				 e.preventDefault();
					$.bootalert({
						ID        : 'by-exsposure-alert',
						LabelText : __('alert_title_for_product_not_saleable_online'),
						TypeLabel : 'info',
						BodyText  : __('alert_message_for_product_not_saleable_online').replace('{link}',$('body').data('abs_client_path')+'/contacts.php'),
						TypeBody  : 'info'
					});
				});
			    /*//// SEND CONTACT FORM ///////*/
				$('#contacts-form').validate();
				$('#btn-send-contact-form').click(function(){
				  $(this, 'form').submit();
				  return false;
				});
				$('#contacts-form input:text').on('keydown',function(e){
				  if(e.keyCode == 13){
					 $('#contacts-form').submit();
					 return false;
				  }
				});
				$('#contacts-form').ajaxForm({
				  type:'POST',
				  beforeSubmit:function(){
				   return $('#contacts-form').validate().form();
				  },
				  beforeSerialize:function(){
				   $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				  },
				  dataType:'html',
				  url:$('body').data('abs_client_path')+'/send-email-contact.php',
				  success:function(data){
					 $.loader.hide();
				    if ($(data).filter('.error_alert').length > 0){
					  $.bootalert({
						  ID        : 'registration_error',
						  LabelText : __('general_error_title'),
						  TypeLabel : 'error',
						  BodyText  : $(data).filter('.error_alert').html(),
						  TypeBody  : 'error'
					  });
					}else{
					  $.bootalert({
						  ID        : 'contact-result-mesasge',
						  LabelText : __('general_success_title'),
						  TypeLabel : 'success',
						  BodyText  : __('contact_form_message_success'),
						  TypeBody  : 'success'
					  });
					  $('input,textarea','#contacts-form').val('');
					}
   				    $('#captcha_image').attr('src',$('body').data('abs_client_path')+'/include/lib/cool-php-captcha/captcha.php?'+Math.random());
					$('#captcha').val('');
				  }
				});
				/*//// HOVER ACTION ON CATEGORIES MENU ///////*/
				var horizontal_category = function(active_item){
					var indicator = $('.categories_menu_indicator');
					var offset = Math.floor(active_item.position().left) ;
					var width = Math.floor(active_item.outerWidth())-1;
					indicator.css({
						'left' : offset,
						'width' : width
					});

				};
				$('.horizontal-category ul li a').hover(function(){
						// set indicator
						horizontal_category($(this));
				}, function(){
						// reset indicator
						$('.categories_menu_indicator').width(0);
				});
			/*///// RELOAD CAPTCHA /////*/
			$('#reload-captcha').on('click',function(){
				$('#captcha_image').attr('src',$('body').data('abs_client_path')+'/include/lib/cool-php-captcha/captcha.php?'+Math.random());
			});
		   });//-> END OF DOM READY $(function(){
		    /*///// LOAD SECOND THUMB ON MOUSE OVER IF IT EXSISTS INTO PRODUCT BOX /////*/
			  $('.product-container-grid,.product-container-list').on({
				  mouseenter: function(){
							   if($('.second-thumb',$(this)).length > 0){
								   var $src = $('.second-thumb',$(this)).html();
								   $('.img-thumb',$(this)).find('img').stop().fadeOut('fast',function(){
									   $(this).attr('src',$src).fadeIn();
								   });
							   }
				  },
				  mouseleave: function(){
							   if($('.second-thumb',$(this)).length > 0){
								   var $src = $('.first-thumb',$(this)).html();
								   $('.img-thumb',$(this)).find('img').stop(true,true).fadeOut('fast',function(){
									   $(this).attr('src',$src).fadeIn('fast');
								   });
							   }
				  }
			  });
			/*///// ADD TO CART ///////*/
			$('.add-to-cart-small.disabled,.add-to-cart.disabled').on('click',function(e){
			 e.preventDefault();
				$.bootalert({
					ID        : 'not-available-modal',
					LabelText : __('general_warning_title'),
					TypeLabel : 'warning',
					BodyText  : __('product_not_available'),
					TypeBody  : 'warning'
				});
			});
			$('.add-to-cart-small.without-options,.add-to-cart').on('click',function(e){
				e.preventDefault();
				if($(this).hasClass('disabled')) return false;
					$type = $(this).hasClass('add-to-cart-small') ? 'small' : 'normal';
					$img = $(this).attr('data-rel-img');
					$name = $(this).attr('data-rel-name');
					$id_product = $(this).attr('data-id');
					$serialize = $('select[data-rel="option_'+$id_product+'"]').length > 0 ? $('select[data-rel="option_'+$id_product+'"]').serialize()+'&' : '';
					$qta = $('#qta-product[data-rel="qta_'+$id_product+'"]').length > 0 ? $('#qta-product[data-rel="qta_'+$id_product+'"]').val() : 1;
					$serialize += 'id_product='+$id_product+'&action=add&qta='+$qta;
					if($qta == 0){
					  $.bootalert({
						  ID        : 'not-available-modal',
						  LabelText : __('general_warning_title'),
						  TypeLabel : 'warning',
						  BodyText  : __('wrong_quantity_purchased'),
						  TypeBody  : 'warning'
					  });
					  return false;
					}
					$.ajax({
						type:'POST',
						url: $('body').data('abs_client_path')+'/cart-control.php',
						beforeSend:function(){
						  $.loader({
							   contentToLoad:'<div class="add-to-cart-loader"><img src="'+$('body').data('theme_img_path')+'/loader.gif" /></div>',
							   appendTo:'body'
							   });
						},
						complete:function(){
						   setTimeout(function(){
								 $.loader.hide();
						   },1500);
								 setTimeout(function(){
								 $.loader({
									  //* language-part *//
									  contentToLoad:'<div class="add-to-cart-loader text-center">\
									  '+__('alert_product_add_to_cart')
									  .replace(/{name}/g,$name)
									  .replace('{url_img}',$img)
									  .replace('{link_cart}',$('body').data('abs_client_path')+'/cart.php')+'\
									  <div class="clearfix"></div></div>',
									  appendTo:'body'});
									  $('.close-add-to-cart-loader').on('click',function(){
										 $.loader.hide();
									  });
								 },1800);
						},
						data:$serialize,
						success:function(){
						 update_top_cart();
						}
					});
			});
			/*//////// PREVENT DEFAULT ON SHOPPING CART PRODUCTS LIST /////////////*/
			$('.topcart .dropdown-menu').on('click',function (e) {
				  e.stopPropagation();
			 });
            /*//// REMOVE FROM CART //////*/
			$('.product-tbody-container').on('click','.remove-from-cart',function(e){
				e.preventDefault();
				$id_product = $(this).attr('data-id-item');
				$option_product = $(this).attr('data-options-item');
				$this = $(this);
			    $.ajax({
				   type: 'POST',
				   url: $('body').data('abs_client_path')+'/cart-control.php',
				   beforeSend:function(){
					 $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				   },
				   complete: function(){
					$this.closest('tr').fadeOut('fast',function(){
					 $.loader.hide();
					 $(this).remove();
					   if($('.product-tbody-container').find('tr').length <= 0){
						 $('.products-list-div').fadeOut('fast',function(){
							 $('.alert-cart-container').fadeIn('fast');
						 });
					   }
					});
				   },
				   success: function(){
					   update_top_cart();
				   },
				   data:{id_product:$id_product,option_product:$option_product,action:'delete'}
			    });
			});
			/*//// UPDATE ENTIRE CART //////*/
			$('.btn-refresh-cart').on('click',function(){
				var input_zero = $('[id^="qta-product_"]').filter(function(){
				   return ($(this).val() == 0 || (isNaN($(this).val()) && isNaN(parseFloat($(this).val()))));
				}).length;
				 if(input_zero > 0){
				  $.bootalert({
					  ID        : 'not-available-modal',
					  LabelText : __('general_warning_title'),
					  TypeLabel : 'warning',
					  BodyText  : __('wrong_quantity_purchased'),
					  TypeBody  : 'warning'
				  });
				  return false;
				 }
				 var arr = {},
				     arr_options_code = {},
					 $last_id = '';
				 $('[name^="qta-product"]').each(function(){
					var $id_product = $(this).attr('data-rel-id-product'),
					    $value_qta = $(this).val(),
						$option_selected = $(this).attr('data-rel-options-product');
						if($last_id != $id_product) arr_options_code = {};
						arr_options_code[$option_selected] = $value_qta;
					    arr[$id_product] = arr_options_code;
						$last_id = $id_product;
				 });
			    $.ajax({
				   type: 'POST',
				   url: $('body').data('abs_client_path')+'/cart-control.php',
				   beforeSend:function(){
					 $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				   },
				   complete: function(){
					 $.loader.hide();
				   },
				   success: function(data){
					   update_cart();
				   },
				   data:{action:'update-all',arr_qta:arr}
			    });
			});
            /*//// UPDATE CART FOR ONE PRODUCT //////*/
			$('.product-tbody-container').on('click','.update-cart',function(e){
				e.preventDefault();
				$id_product = $(this).attr('data-id-item');
				$option_product = $(this).attr('data-options-item');
				$this = $(this);
				$qta = $('input[data-rel="qta_'+$option_product+'"]').length > 0 ? $('input[data-rel="qta_'+$option_product+'"]').val() : 1;
				if($qta == 0){
				  $.bootalert({
					  ID        : 'not-available-modal',
					  LabelText : __('general_warning_title'),
					  TypeLabel : 'warning',
					  BodyText  : __('wrong_quantity_purchased'),
					  TypeBody  : 'warning'
				  });
				  return false;
				}
			    $.ajax({
				   type: 'POST',
				   url: $('body').data('abs_client_path')+'/cart-control.php',
				   beforeSend:function(){
					 $.loader({imgPath:$('body').data('theme_img_path')+'/loader.gif',appendTo:'body'});
				   },
				   complete: function(){
					 $.loader.hide();
				   },
				   success: function(){
					   update_cart();
				   },
				   data:{id_product:$id_product,option_product:$option_product,action:'update',qta:$qta}
			    });
			});
			/*//// INCREASE/DECREASE QTA INTO CART VIA BUTTONS "PLUS AND MINUS" //////*/
			$('#products-list-table').on('click','table .increase-qta',function(){
				var $input_qta = $('input#qta-product_'+$(this).attr('data-products-to-increase'));
				$input_qta.val(parseFloat($input_qta.val())+1);
			});
			$('#products-list-table').on('click','table .decrease-qta',function(){
				var $input_qta = $('input#qta-product_'+$(this).attr('data-products-to-decrease'));
				if($input_qta.val() > 1) $input_qta.val(parseFloat($input_qta.val())-1);
			});
			/*//// CHECKOUT ////*/
			function get_address($name,$lastname,$address,$city,$zipcode,$email,$phone,$fax){
				  function ucwords (str) {
					  return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
						  return $1.toUpperCase();
					  });
				  }
				var address = __('client_address').replace('{name}',ucwords($name+' '+$lastname))
						 .replace('{address}',$address)
						 .replace('{city}',$city)
						 .replace('{zip_code}',$zipcode)
						 .replace('{phone}',$phone)
						 .replace('{email}',$email)
						 .replace('{fax}',($fax != '' ? '<abbr title="Fax">F: </abbr> '+$fax+'<br/>' : ''));
				return '<address>'+address+'</address>';
			}
			$('[data-steps-rel^="fstep_"]:last').on('classadded', function(ev, newClasses) {
			  if(newClasses == 'current-step'){
				  var $billing_address = get_address($('#name').val(),$('#lastname').val(),$('#address').val(),$('#city').val(),$('#zipcode').val(),$('#email').val(),$('#phone').val(),$('#fax').val()),
				      $payment_address = get_address($('#names').val(),$('#lastnames').val(),$('#addresss').val(),$('#citys').val(),$('#zipcodes').val(),$('#emails').val(),$('#phones').val(),$('#faxs').val());
				  $('.billing-address-container').html($billing_address);
				  $('.shipping-address-container').html($payment_address);
				/* Rehight the form for stepize plugin */
				$('#checkout-form').StepizeForm('Rehight');
			  }
			});
			/*** SCROLL TOP TO LOGIN ON CHECKUOT PAGE WHEN PURCHASES WITHOUT REGISTRATION WAS ACTIVATED ***/
			$('body').on('click','#login_scroll_top',function(e){
				  e.preventDefault();
				  $('body,html').animate({scrollTop: 0}, 100,function(){
				    $('#btn-login-link').click();
						$("#top-login-form-container").animate({
							opacity:"0"
						}, 100, function() {
							$("#top-login-form-container").animate({
								opacity:"1"
							}, 100,function(){
							  $("#top-login-form-container").animate({
								  opacity:"0"
							  }, 100,function(){
								$("#top-login-form-container").animate({
									opacity:"1"
								}, 100)
						      });
						    });
						});
				  });
			});
			/*** ADD REMOVE PASSWORD AND USERID FIELDS FOR GUEST/REGISTER MODALITY AND ANABLE RELATIVE PAYMENT METHOD ***/
			$('#guest,[for="guest"]').on('click',function(){
				if($('#guest').prop('checked') == false){
					$('.choose_credentials').fadeOut('fast');
/*					$('.only_for_register').addClass('hide');
					$('.for_guest_and_register,.only_for_register').removeClass('selected');
					$('.for_guest_and_register').find('input:radio').click();*/
				}
			});
			$('#register,[for="register"]').on('click',function(){
				if($('#register').prop('checked') == false){
					$('.choose_credentials').fadeIn('fast');
/*					$('.only_for_register').removeClass('hide');
					$('.for_guest_and_register,.only_for_register').removeClass('selected');
					$('.only_for_register:first').find('input:radio').click();	*/
				}
			});
			/*** choose private or company form ***/
/*			$(':radio[name="is_company"]','#checkout-form').on('click',function(){
				if($(this).prop('checked') == false && $(this).val() == 'private'){
					$('#lastname,#lastnames','#checkout-form').val('').closest('.control-group').show();
					$('[for="tax_code"]:first','#checkout-form').html('<strong>'+__('tax_code')+'*</strong>');
					$('#tax_code','#checkout-form').attr('placeholder',__('tax_code')+'*');
				}
				if($(this).prop('checked') == false && $(this).val() == 'company'){
					$('#lastname,#lastnames','#checkout-form').val('').closest('.control-group').hide();
					$('[for="tax_code"]:first','#checkout-form').html('<strong>'+__('vat')+'*</strong>');
					$('#tax_code','#checkout-form').attr('placeholder',__('vat')+'*');
				}
			});	*/
				$(':radio[name="is_company"]','#checkout-form').on('click',function(){
					if($(this).prop('checked') == false && $(this).val() == 'private'){
					  	$('#lastname,#lastnames','#checkout-form').val('').closest('.control-group').show();
						$('#tax_code','#checkout-form').closest('.control-group').closest('.row-fluid').addClass('hidden');
					}
					if($(this).prop('checked') == false && $(this).val() == 'company'){
						$('#lastname,#lastnames','#checkout-form').val('').closest('.control-group').hide();
						$('[for="tax_code"]:first','#checkout-form').html('<strong>'+__('vat')+'*</strong>');
						$('#tax_code','#checkout-form').attr('placeholder',__('vat')+'*').closest('.control-group').closest('.row-fluid').removeClass('hidden');
					}
				});
			/*** COPY BILLING ADDRESS INTO SHIPPING ADDRESS ***/
			$('#same-address').on('click',function(){
				 if($(this).prop('checked')){
					$('input','.same_address').val('');
				 }else{
					$('input','#checkout-form .principal_address').each(function(){
						 $id = $(this).attr('id'),
						 $val = $(this).val();
						$('#'+$id+'s','#checkout-form .same_address').val($val);
					});
					$('#checkout-form').valid();
				 }
			});
			/*** Choose payment method and flag it as selected ***/
			$(function(){
				if($('.payment_method input:radio').length > 0){
					$('.payment_method input:radio:checked').click();
				}
				if($('.payment_method input:radio:checked').length <= 0){
					$('.payment_method input:radio:first').click();
				}
			});
			$('.payment_method input:radio').on('click',function(){
			  if($(this).prop('checked') == true) $('.checkout-page .payment_method label.selected').toggleClass('selected');
			  $(this).closest('label').toggleClass('selected');
			  /*** update grandtotal+payment price ***/
			  var $payment_price = $('.value-payment',$(this).closest('label')).val(),
			      $new_price = parseFloat($('.grandtotal').attr('data-grandtotal'))+parseFloat($payment_price);
			  $('.grandtotal').html(format_num(tronca($new_price,2),$('body').data('thousands_separator'),$('body').data('decimals_separator')));
			  $('.payment_price').html(format_num(tronca($payment_price,2),$('body').data('thousands_separator'),$('body').data('decimals_separator')));
			  $('#payment_price').val(tronca($payment_price,2));
			});
	        /*/// CHANGE LANGUAGE ON THE FLY ///*/
		   $('.change_language_on_the_fly').change(function(){
             $.post($('body').data('abs_client_path')+'/change-language.php',{'lang':$(this).val()},function(){
			  window.location.reload();
			 });
		   });
		   /*/// EMPTY AJAX REQUEST CACHE ///*/
		  $.ajaxSetup({
			cache: false
		  });