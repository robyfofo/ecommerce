/* admin/ecommerce/formCategory.js v.4.5.1. 11/05/2020 */
$(document).ready(function(){	
	validateForm('','#applicationForm');
});

$('#selectTagsAll').click(function() {
	$('select#id_tagsID option').prop('selected', true);
});   

$('#deselectTagsAll').click(function() {
	$('select#id_tagsID option').prop('selected', false);
});
	
$('#selectAssociationsAll').click(function() {
	$('select#id_associationsID option').prop('selected', true);
});   

$('#deselectAssociationsAll').click(function() {
	$('select#id_associationsID option').prop('selected', false);
});
