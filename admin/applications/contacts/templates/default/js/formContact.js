/* admin/contacts/formContact.js v.1.0.0. 25/06/2021 */
$(document).ready(function(){
	setvalidatefields();
	validateForm(validatefields,'#applicationForm');
});

function setvalidatefields() {
	let x = 0;
	validatefields[x] = ['datibase','telephone','telephoneID'];
	x++;
	validatefields[x] = ['datibase','emptytinyMCE','messageID'];
}