<?php
$is_included = get_included_files();
if( $is_included[0] == (__FILE__) ) die('You have no permission for direct access to this file');
?>
<script type="text/javascript" src="<?php echo abs_client_path ?>/include/js/plugins/jsin.1.2.min.js"></script> 
<script type="text/javascript" src="<?php echo abs_admin_path.'/lang/'.languageAdmin.'/'.languageAdmin.'.js'; ?>"></script> 
<script src="<?php echo abs_client_path ?>/include/js/jquery.js"></script>
<script src="<?php echo abs_client_path ?>/include/js/bootstrap.js"></script>
<script src="<?php echo abs_client_path ?>/include/js/bootstrap_extensions.js"></script>
<script type="application/javascript">
/*************************************** RESETTARE IL PUNTATORE AD UNA DATA POSIZIONE DURANTE LA DIGITAZIONE IN INPUT O TEXTAREA ************************************************/

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

/*************************************** OTTENERE LA POSIZIONE ATTUALE DEL PUNTATORE ALL'INTERNO DI UN INPUT O TEXTAREA ************************************************/

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
$(function(){
 /////////////////////////////////--> CONVERT COMMA INTO POINT FOR NU MERIC FIELDS, AND DELETE BLANK SPACES
 $('body').on('focus','.number',function(){
	 $(this).addClass('no_space')
 });
 $('body').on('keyup','.number',function(){
  if($(this).val().indexOf(',')>-1){
     var cur_pos = $(this).getCursorPosition()
     $(this).val($(this).val().replace(/\,/g,'.'))
     $(this).resetCursorPosition(cur_pos,cur_pos)
  }
 });  
 //////////////////////////////////--> REPLACE BLANK SPACES WRITING INTO FIELDS WITH  "no_space" CLASS
 $('body').on('keyup','.no_space',function(){
  if($(this).val().indexOf(' ')>-1){
     var cur_pos = $(this).getCursorPosition()-1
     $(this).val($(this).val().replace(/ /g,""))
     $(this).resetCursorPosition(cur_pos,cur_pos)
  } 
 }); 
});
</script>