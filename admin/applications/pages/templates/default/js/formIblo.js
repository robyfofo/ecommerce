/* wscms/pages/formIblo.js v.3.5.4. 28/03/2019 */
$(document).ready(function() { 
	$('.submittheform').click(function () {
		var content = tinyMCE.get('content_itID').getContent(); // msg = textarea id
		if( content == "" || content == null) {
			alert(messages['Devi inserire un contenuto!']);
			$('a[href="#datibaseit-tab"]').tab('show');
			return false;
		} else {				
			//alert('altri controlli');
			$('input:invalid').each(function () {
				// Find the tab-pane that this element is inside, and get the id
				var $closest = $(this).closest('.tab-pane');
				var id = $closest.attr('id');
				// Find the link that corresponds to the pane and have it show
				$('.nav a[href="#' + id + '"]').tab('show');
				// Only want to do it once
				//alert('altri cmapi con errore');
				return false;
			});	
		}
	$('form#applicationForm').submit();
	});
});