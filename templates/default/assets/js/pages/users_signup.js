/*  assets/js/pages/users_signup.js v.1.0.0. 06/09/2021 */
let resultcheckform;

$(document).on('ready', function () {

	$('#usernameID').on('change',function (e) {
        e.stopPropagation();
        checkusername();
        $('#usernameMessageID').removeClass('text-warning');
        $('#usernameMessageID').removeClass('text-success');
        if (typeof resultcheckform.result != "undefined" && resultcheckform.result == 1) {
            $('#usernameMessageID').addClass('text-warning');
        } else {
            $('#usernameMessageID').addClass('text-success');
        }
        $('#usernameMessageID').html(resultcheckform.message);
        e.preventDefault();
    });

	$('#emailID').on('change',function (e) {
        e.stopPropagation();
        checkemail();
        $('#emailMessageID').removeClass('text-warning');
        $('#emailMessageID').removeClass('text-success');
        if (typeof resultcheckform.result != "undefined" && resultcheckform.result == 1) {
            $('#emailMessageID').addClass('text-warning');
        } else {
            $('#emailMessageID').addClass('text-success');
        }
        $('#emailMessageID').html(resultcheckform.message);
        e.preventDefault();
    });

	$('#aasubmitFormID').on('click',function (e) {
		e.stopPropagation();
		let res;
		$('input').removeClass('is-invalid');
		$('select').removeClass('is-invalid');

		res = validatehtml5('is-invalid');	
		if (res == false) return false;
		
		if ( $('#passwordID').val() !== $('#passwordCKID').val()) {
			showJavascriptAlert('le password non corrispondono');
			return false;
		}
	});
});


function checkusername() {
    var fieldRif = 'username';

	var valueRif = $('#'+fieldRif+'ID').val();
	var id = $('#idID').val();
	let table = userDbTable;
	let fieldId = 'id';
	let field = fieldRif;
	let fieldLabel = fieldRif;
	let fieldsValue = [valueRif];
	let matchType = '=';
	let customClause = '';
	if (id > 0) {
		fieldsValue.push(id);
		customClause = customClause + ' AND id <> ?';
	}
	$.ajax({
		method: "POST",
		url: siteUrl+'ajax/checkIfItemExistInDb.php',
		async: false,
		data: {
			'table' 		: table,
			'fieldId' 		: fieldId,
			'field' 		: field,
			'fieldLabel' 	: fieldLabel,
			'fieldsValue' 	: fieldsValue,
			'matchType'		: matchType,
			'customClause'  : customClause,
		},
		format: "json"
	})
	.done(function(data) {
		var obj = jQuery.parseJSON( data );    
		resultcheckform = obj;   
	})
	.fail(function() {
		alert( "Error get request ajax check username");
	});      
}

function checkemail() {
    var fieldRif = 'email';

	var valueRif = $('#'+fieldRif+'ID').val();
	var id = $('#idID').val();
	let table = userDbTable;
	let fieldId = 'id';
	let field = fieldRif;
	let fieldLabel = fieldRif;
	let fieldsValue = [valueRif];
	let matchType = '=';
	let customClause = '';
	if (id > 0) {
		fieldsValue.push(id);
		customClause = customClause + ' AND id <> ?';
	}
	$.ajax({
		method: "POST",
		url: siteUrl+'ajax/checkIfItemExistInDb.php',
		async: false,
		data: {
			'table' 		: table,
			'fieldId' 		: fieldId,
			'field' 		: field,
			'fieldLabel' 	: fieldLabel,
			'fieldsValue' 	: fieldsValue,
			'matchType'		: matchType,
			'customClause'  : customClause,
		},
		format: "json"
	})
	.done(function(data) {
		var obj = jQuery.parseJSON( data );    
		resultcheckform = obj;   
	})
	.fail(function() {
		alert( "Error get request ajax check email");
	});      
}