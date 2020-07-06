/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
if(!$().throttle && !$().debounce){  
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
}
/* DISCOUNT CALCULATION */
function rprice_calc() {	
  var price = $('#price').val(),
      discount = (price*$('#rpdiscount').val())/100,
	  result = parseFloat(price) - parseFloat(discount);	   
		    if(!isNaN(result)){
				 $('#rprice').val(tronca(result,2));
			}
};
function discount_calc() {	
  var price = $('#price').val(),
      discount_percentage = ((parseFloat(price) - parseFloat($('#rprice').val()))/price)*100;
	   if(!isNaN(discount_percentage)){
		  $('#rpdiscount').val(tronca(discount_percentage,2));
	   }
};
function price_calc() {	
  var price = $('#price').val(),
      discount = (price*$('#rpdiscount').val())/100,
      discount_percentage = ((parseFloat(price) - parseFloat($('#rprice').val()))/price)*100,
	  result = parseFloat(price) - parseFloat(discount);
	  if($('#rpdiscount').val() == '' && $('#rprice').val() != ''){
	    if(!isNaN(discount_percentage)){
		  $('#rpdiscount').val(tronca(discount_percentage,2));
	    }
	  }else if($('#rpdiscount').val() != '' && $('#rprice').val() == ''){
		if(!isNaN(result)){
			 $('#rprice').val(tronca(result,2));
		}
	  }else if($('#rpdiscount').val() != '' && $('#rprice').val() != ''){
		if(!isNaN(result)){
			 $('#rprice').val(tronca(result,2));
		}		  
	  }
};


$('body').on( 'keyup','#rpdiscount', $.debounce( 250, rprice_calc ) );
$('body').on( 'keyup','#rprice', $.debounce( 250, discount_calc ) );
$('body').on( 'keyup','#price', $.debounce( 250, price_calc ) );

function roffer_calc() {	
  var price = $('#offer').val(),
      discount = (price*$('#rodiscount').val())/100,
	  result = parseFloat(price) - parseFloat(discount);
	    if(!isNaN(result)){
		    $('#roffer').val(tronca(result,2));
		}
};
function offer_discount_calc() {	
  var price = $('#offer').val(),
      discount_percentage = ((parseFloat(price) - parseFloat($('#roffer').val()))/price)*100;
	   if(!isNaN(discount_percentage)){
		  $('#rodiscount').val(tronca(discount_percentage,2));
	   }

};
function offer_calc() {	
  var price = $('#offer').val(),
      discount = (price*$('#rodiscount').val())/100,
      discount_percentage = ((parseFloat(price) - parseFloat($('#roffer').val()))/price)*100,
	  result = parseFloat(price) - parseFloat(discount);
	  if($('#rodiscount').val() == '' && $('#roffer').val() != ''){
	    if(!isNaN(discount_percentage)){
		  $('#rodiscount').val(tronca(discount_percentage,2));
	    }
	  }else if($('#rodiscount').val() != '' && $('#roffer').val() == ''){
		if(!isNaN(result)){
			 $('#roffer').val(tronca(result,2));
		}
	  }else if($('#rodiscount').val() != '' && $('#roffer').val() != ''){
		if(!isNaN(result)){
			 $('#roffer').val(tronca(result,2));
		}		  
	  }
};

$('body').on( 'keyup','#rodiscount', $.debounce( 250, roffer_calc ) );
$('body').on( 'keyup','#roffer', $.debounce( 250, offer_discount_calc ) );
$('body').on( 'keyup','#offer', $.debounce( 250, offer_calc ) );	