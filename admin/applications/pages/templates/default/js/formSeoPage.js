/* admin/pages/formSeoItem.js.php v.1.0.0. 25/03/2021 */
$(document).ready(function(){

	$('.checknumchars').on('keyup',function(event) {
		var messagecontainer = $(this).data("messagecontainer");	
		var max = $(this).data("bv-stringlength-max");
		var len = $(this).val().length;
		var char = max - len;
		$('#'+messagecontainer).text(char);	
	});
	
});

$('.submittheform').click(function () {
	$('input:invalid').each(function () {
		// Find the tab-pane that this element is inside, and get the id
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		// Find the link that corresponds to the pane and have it show
		$('.nav a[href="#' + id + '"]').tab('show');
		// Only want to do it once
		return false;
	});
});