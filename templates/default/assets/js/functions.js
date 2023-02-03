function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatehtml5Tabs() {
	$('select:invalid').each(function () {
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		$('.nav a[href="#' + id + '"]').tab('show');
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('input-no-validate');
		return false;
	});
	
	$('input:invalid').each(function () {
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		$('.nav a[href="#' + id + '"]').tab('show');
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('input-no-validate');
		validation = false;
	});
} 

function validatehtml5(classe) {
	$('select:invalid').each(function () {
		var idf = '#'+$(this).attr('id');
		$(idf).addClass(classe);
		return false;
	});
	
	$('input:invalid').each(function () {
		var idf = '#'+$(this).attr('id');

		let type = $(this).attr('type');
		let name = $(this).attr('name');

		let label =  '';
		let foo = $("label[for='"+$(this).attr("id")+"aaa']");
		if (foo.lenght > 0) label = stripHtml(foo.text());

		let message = '';
		let foo1  = $(idf).attr("data-requirederror");
		if (typeof foo1 !== 'undefined') {
			console.log('foo1: '+foo1)
			message = foo1;
		}

		if (label == '') label = name;
		let mess = 'Il campo '+label+' è obbligatorio';
		if (message != '') mess = message;

		if (type == 'checkbox') {
			showJavascriptAlert(mess);
		}
			
		$(idf).addClass(classe);
		validation = false;
	});
} 

function showJavascriptAlert(mess)
{
    if (typeof bootbox != "undefined") {
        bootbox.alert(mess);
    } else {
        alert(mess);
    }  
}

function showJavascriptConfirm(mess)
{
    if (typeof bootbox != "undefined") {
        bootbox.confirm(mess);
    } else {
        var answer = window.confirm(mess);
		if (answer) {
			console.log('ok');
			return true;
		} else {
			console.log('no');
			return false;
			//some code
		}
    }  
}

function stripHtml(html)
{
	html.replace(/<[^>]*>?/gm, '');
	html.trim();
	//html = 'aaaaaaa';
	return html;
}