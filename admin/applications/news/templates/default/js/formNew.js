/* admin/news/formNew.js v.1.0.0. 25/03/2021 */

$(document).ready(function() {

	$('.checknumchars').on('keyup',function(event) {
		var messagecontainer = $(this).data("messagecontainer");	
		var max = $(this).data("bv-stringlength-max");
		var len = $(this).val().length;
		var char = max - len;
		$('#'+messagecontainer).text(char);	
	});

	$('#datatimeinsPikID').datetimepicker({
		locale: charset_lang,
		format: 'L HH:mm',
		defaultDate: defaultDatatimeins
	});

	$('#datatimescainiPikID').datetimepicker({
		locale: charset_lang,
		format: 'L HH:mm',
		defaultDate: defaultDatatimescaini
	});

	$('#datatimescaendPikID').datetimepicker({
		locale: charset_lang,
		format: 'L HH:mm',
		defaultDate: defaultDatatimescaend
	});
	
	if ($('#scadenzaID').prop('checked') == true) {
		$('#datescadenzeID').css("visibility","visible");
	} else {
		$('#datescadenzeID').css("visibility","hidden");
	}
 	setvalidatefields();

	console.log('datatimeins: '+$('#datatimeinsID').val());
	validateForm(validatefields,'#applicationForm');
});

// attiva disattiva il pannello scadenze
$('#scadenzaID').on('click', function(e) {
	if ($('#scadenzaID').prop('checked') == true) {
		$('#datescadenzeID').css("visibility","visible");
		setvalidatefields();
	} else {
		$('#datescadenzeID').css("visibility","hidden");
		setvalidatefields();
	}
});


function setvalidatefields() {
	let x = 0;
	validatefields[x] = ['data','dataita','datatimeinsID'];	
	if ($('#scadenzaID').prop('checked') == true) {
		x++;
		validatefields[x] = ['data','dataita','datatimescainiID'];
		x++;
		validatefields[x] = ['data','dataita','datatimescaendID'];
		x++;
		validatefields[x] = ['data','datainterval','datatimescainiID','datatimescaendID','DD/MM/YYYY'];
	}
	x++;
	validatefields[x] = ['datibaseit','maxchar','title_itID','255'];
	x++;
	validatefields[x] = ['datibaseen','maxchar','title_enID','255'];
	x++;
	validatefields[x] = ['datibaseit','maxchar','summary_itID','255'];
	x++;
	validatefields[x] = ['datibaseen','maxchar','summary_enID','255'];
	
}