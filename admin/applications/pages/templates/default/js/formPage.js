/* admin/pages/formPages.js v.1.0.0. 01/07/2021 */
$(document).ready(function() { 
	pprefresh();
	
	$('.checknumchars').on('keyup',function(event) {
		var messagecontainer = $(this).data("messagecontainer");	
		var max = $(this).data("bv-stringlength-max");
		var len = $(this).val().length;
		var char = max - len;
		$('#'+messagecontainer).text(char);	
	});
	
});
	
$('.submittheform').click(function () {
	$('form input').removeClass('input-no-validate');
	$('form select').removeClass('input-no-validate');

	$('input:invalid').each(function () {
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		$('.nav a[href="#' + id + '"]').tab('show');
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('input-no-validate');
		return false;
	});
	$('select:invalid').each(function () {
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		$('.nav a[href="#' + id + '"]').tab('show');
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('input-no-validate');
		return false;
	});
});

function pprefresh(){
	if(!jQuery('#lightbox').length) { lightbox.init(); }
}