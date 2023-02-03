/* admin/pages/formSeoNew.js.php v.1.0.0. 26/03/2021 */
$(document).ready(function(){

	$('.checknumchars').on('keyup',function(event) {
		var messagecontainer = $(this).data("messagecontainer");	
		var max = $(this).data("bv-stringlength-max");
		var len = $(this).val().length;
		var char = max - len;
		$('#'+messagecontainer).text(char);	
		});

});

validateForm();
