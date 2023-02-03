$('#submitFormID').on('click',function (e) {
    e.stopPropagation();
    $('input').removeClass('is-invalid');
    $('select').removeClass('is-invalid');  



    $('select:invalid').each(function () {
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('is-invalid');
		return false;
	});
	
	$('input:invalid').each(function () {
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('is-invalid');
		return false;
	});

    return true;
    e.preventDefault();
});