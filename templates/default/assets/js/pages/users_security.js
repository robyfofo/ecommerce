/*  assets/js/pages/users_signup.js v.1.0.0. 06/09/2021 */
let resultcheckform;

$(document).on('ready', function () {
	$('#submitFormID').on('click',function (e) {
		e.stopPropagation();
		let res;
		$('input').removeClass('is-invalid');
		$('select').removeClass('is-invalid');

		res = validatehtml5('is-invalid');	
		if (res == false) return false;
		
		if ( $('#passwordID').val() !== $('#passwordCKID').val()) {
			showJavascriptAlert(messages['le due password non corrsipondono']);
			return false;
		}
	});
});