/* admin/warehouse/formProduct.js v.1.0.0. 08/03/2021 */
$(document).ready(function(){	
	refreshValuesFromPrice();
	
	$('#price_unityID').on('keyup',function(event) {
		refreshValuesFromPrice();
	});
	
	$('#taxID').on('keyup',function(event) {
		refreshValuesFromTax();
	});

	setvalidatefields();
	validateForm(validatefields,'#applicationForm');
});

function setvalidatefields() {
	let x = 0;
	validatefields[x] = ['datibase','currency','price_unityID'];
}

$('#price_unityID').on('change',function (e) {
	if ( false == checkCurrency($('#price_unityID').val()) ){
		showJavascriptAlert(messages['Il prezzo deve essere in formato valuta!']);
		return false;
	}
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

function refreshValuesFromPrice() {	
	let price_unity = $('#price_unityID').val();
	let tax = $('#taxID').val();
	if (price_unity == '') price_unity = '0.00';
	if (tax == '') tax ='22';
	price_unity = parseFloat(price_unity);
	tax = parseInt(tax);
	price_tax = (price_unity * tax) / 100;
	price_total = price_unity + price_tax;	
	$('#price_taxID').html(price_tax.toFixed(2));
	$('#price_totalID').html(price_total.toFixed(2));
}
	
function refreshValuesFromTax() {
	let price_unity = $('#price_unityID').val();
	let tax = $('#taxID').val();
	if (price_unity == '') price_unity = '0.00';
	if (tax == '') tax ='22';
	price_unity = parseFloat(price_unity);
	tax = parseInt(tax);
	price_tax = (price_unity * tax) / 100;
	price_total = price_unity + price_tax;
	$('#price_taxID').html(price_tax.toFixed(2));
	$('#price_totalID').html(price_total.toFixed(2));
}