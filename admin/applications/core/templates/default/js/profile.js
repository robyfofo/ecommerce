/* admin/core/profile.js v.1.0.0. 17/03/2021 */
$(document).ready(function() {

	let province_id = $('#location_province_idID').val();
    let comuni_id = $('#comune_selected_idID').val();
    createSelectComuni(province_id,comuni_id);

    $('.selectpicker-provincie').on('change',function(event) {
        let province_id = $('#location_province_idID').val();
        createSelectComuni(province_id,comuni_id);
    });

    $('.selectpicker-comuni').on('change', function(){
        let comuni_id = $('.selectpicker-comuni').val()
        $.ajax({
            url: siteUrl+'ajax/getComuneDetailsFromDbId.php',
            async: "true",
            cache: "false",
            type: "POST",
            data: {'comuni_id':comuni_id},
            dataType: 'json'
        })
        .done(function(data) {
            //console.log(data); //Get the multiple values selected in an array
            $('#zip_codeID').val(data.cap);
            $('#location_nations_idID').val(data.location_nations_id);
            $('#location_nations_idID').selectpicker('refresh');
        })
        .fail(function() {
            alert("Ajax failed to fetch data article for comuni cap");
        })
    });
	
});

function createSelectComuni(province_id,comuni_id) 
{
	$.ajax({
		url             : siteUrl+'ajax/getJsonComuniFromDbId.php',
		async           : false,
		cache           : false,
		type            : 'POST',
		data            : {
			province_id     : province_id
		},
		dataType: 'json'
	})
	.done(function(data) {
		let selectOptions = '';
		let selected= '';
		$.each(data, function( index, value ) {
			selected = '';
			if (value.id === comuni_id ) selected = ' selected="selected"';
			selectOptions = selectOptions + '<option'+ selected + ' value="' + value.id + '">' + value.nome +'</option>';
		});
		$('#location_comuni_idID').find('option').remove().end().append(selectOptions);
		$('#location_comuni_idID').val(comuni_id);
		$('#location_comuni_idID').selectpicker('refresh');      
	})
	.fail(function() {
		alert("Ajax failed to fetch list comuni");
	})
}

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