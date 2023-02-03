/* admin/templates/functions.js v.1.0.0. 25/06/2021 */
let validatefields = [];
let validation = true;

function validateForm(validatefields,elementform) 
{
    
    $('.submittheform').click(function () {
        //console.log('inizia la validazione');
        // togli la classe input-no-validate a tutti
        var elements = document.getElementsByClassName('input-no-validate');
        while(elements.length > 0){
            elements[0].classList.remove('input-no-validate');
        }
        // controllo tab dopo validazione  html5 
        controlloTabHTML5();
        console.log('continua la validazione');

        validateFieldByID(validatefields);

        console.log(validation);

        if (validation == true) {
            console.log('validazione ok');
            $(elementform).submit();
            return true;
        } else {
            console.log('validazione fallita');
            return false;
        }

        //return false;
    });

}

function controlloTabHTML5()
{
    console.log('aggiunge il controllo tab html5')
    $('input:invalid').each(function () {
        var $closest = $(this).closest('.tab-pane');
        var id = $closest.attr('id');
        $('.nav a[href="#' + id + '"]').tab('show');
        var idf = '#'+$(this).attr('id');
        $(idf).addClass('input-no-validate');
    });

    $('select:invalid').each(function () {
		var $closest = $(this).closest('.tab-pane');
		var id = $closest.attr('id');
		$('.nav a[href="#' + id + '"]').tab('show');
		var idf = '#'+$(this).attr('id');
		$(idf).addClass('input-no-validate');
	});
}
     
function validateFieldByID(validatefields) 
{
    
    let validate;
    let validateTab;
    let validateType;
    let validateElement;
    let el;
    let mess;
    let value;

    for (i = 0; i < validatefields.length; i++) {
        validate = validatefields[i];
        validateTab = validatefields[i][0]; // la tab da attivare
        validateType = validatefields[i][1]; // il campo
        validateElement = validatefields[i][2];
        console.log(validateTab);
        console.log(validateType);
        console.log(validateElement);

      switch(validateType) {
            case 'currency':
                el = validatefields[i][2];
                value = document.querySelector('#'+validateElement).value;
                console.log('valore per il campo '+validateElement+' '+value);
                if (!checkCurrency(value)) { 
                    mess = 'Devi inserire un valore valuta';
                    showValidationErrorMessage(validateTab,validateElement,mess);
                    console.log('valore errato per il campo '+validateElement+' '+value);
                    validation = false;
                } else {
                    console.log('valore valido per il campo '+validateElement+' '+value);
                    validation = true;
                }
            break;

            case 'telephone':
                el = validatefields[i][2];
                value = document.querySelector('#'+validateElement).value;
                if (!validateTelephone(value)) { 
                    mess = 'Numero di telefono non valido';
                    showValidationErrorMessage(validateTab,validateElement,mess);
                    validation = false;
                }
            break;

            case 'maxchar':
                el = validatefields[i][2];
                let char = validatefields[i][3];
                value = document.querySelector('#'+validateElement).value;
                if (value.length > char) {      
                    mess = 'Superato il numero massimo di caratteri consentito';
                    showDataErrorMessage(validateTab,validateElement,mess);
                    validation = false;
                }
            break;

            case 'dataita':
                let datatimeins = $('#'+validateElement).val();
                if (datatimeins == '') {
                    mess = 'Devi inserire una data valida!';
                    showDataErrorMessage(validateTab,validateElement,mess);               
                    validation = false;
                }
            break;

            case 'datainterval':
                let dataformat = validatefields[i][4];
                //console.log('dataformat: '+dataformat);
                let dataini = validatefields[i][2];
                let dataend = validatefields[i][3];
                vdataini = document.querySelector('#'+dataini).value;
                vdataend = document.querySelector('#'+dataend).value;
                //console.log('dataini: '+dataini);
                //console.log('dataend: '+dataend);
                let data = moment(vdataini, dataformat);
                let datainiIso = data.format('YYYY-MM-DD');           
                let data1 = moment(vdataend, dataformat);
                let dataendIso = data1.format('YYYY-MM-DD'); 
                //console.log('datainiiso: '+datainiIso);
                //console.log('dataendiso: '+dataendIso);

                if (moment(dataendIso).isSameOrAfter(datainiIso) == false) {
                    bootbox.alert(messages['Intervallo tra le due date errato!']);
                    $('.nav a[href="#'+validateTab+'"]').tab('show');

                    el = document.querySelector('#'+dataini);
                    el.classList.add('input-no-validate');
                    el1 = document.querySelector('#'+dataend);
                    el1.classList.add('input-no-validate');
                    validation = false;
                }

            break;

            case 'emptytinyMCE':
                var content = tinyMCE.get(validateElement).getContent();
	            if( content == "" || content == null) {
		            mess = messages['Devi inserire un contenuto!'];
                    showDataErrorMessage(validateTab,validateElement,mess);
		            validation = false;
	            }
            break;

            default:
            break;
        }
        
        if (validation == false) break;
        
    }
}

function showDataErrorMessage(validateTab,validateElement,mess)
{
    mess = getDataErrorMessage(mess,validateElement);
    showErrorMessage(mess);
    $('.nav a[href="#'+validateTab+'"]').tab('show');
    const el = document.querySelector('#'+validateElement);
    el.classList.add('input-no-validate');
}

function showValidationErrorMessage(validateTab,validateElement,mess)
{
    mess = getValidationErrorMessage(mess,validateElement);
    showErrorMessage(mess);
    $('.nav a[href="#'+validateTab+'"]').tab('show');
    const el = document.querySelector('#'+validateElement);
    el.classList.add('input-no-validate');
}

function getDataErrorMessage(mess,element)
{
    const el = document.querySelector('#'+element);
    if (typeof el.dataset.errormessage !== 'undefined') { 
        messdata = el.dataset.errormessage;
        if (messdata != '') mess = messdata;
    }
    return mess;
}

function getValidationErrorMessage(mess,element)
{
    const el = document.querySelector('#'+element);
    if (typeof el.dataset.errorvalidationmessage !== 'undefined') { 
        messdata = el.dataset.errorvalidationmessage;
        if (messdata != '') mess = messdata;
    }
    return mess;
}

/* obsoleta */
function showErrorMessage(mess)
{
    if (typeof bootbox != "undefined") {
        bootbox.alert(mess);
    } else {
        alert(mess);
    }  
}


function showJavascriptAlert(mess)
{
    if (typeof bootbox != "undefined") {
        bootbox.alert(mess);
    } else {
        alert(mess);
    }  
}

function validateEmail(email) 
{
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateTelephone(telephone) 
{
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
    return re.test(telephone);
}

function checkCurrency(value) {
    console.log('currency: '+value)
	let res1 = checkCurrencyEuro(value);
	let res2 = checkCurrencyOthers(value);
	if (null == res1 && null == res2) {
        console.log('currency: falso');
		return false;
	} else {
        console.log('currency: vero');
		return true;
	}
}

function checkCurrencyEuro(value) {
	var regex = /^[1-9]\d*(((.\d{3}){1})?(\,\d{0,2})?)$/;
	if (regex.test(value))
	{
		var twoDecimalPlaces = /\,\d{2}$/g;
		var oneDecimalPlace = /\,\d{1}$/g;
		var noDecimalPlacesWithDecimal = /\,\d{0}$/g;

		if(value.match(twoDecimalPlaces ))
		{
			return value;
		}
		if(value.match(noDecimalPlacesWithDecimal))
		{
			return value+'00';
		}
		if(value.match(oneDecimalPlace ))
		{
			return value+'0';
		}
		return value+",00";
	}
	return null;
}

function checkCurrencyOthers(value) {
    //var value= $("#field1").val();
    var regex = /^[0-9]\d*(((,\d{3}){1})?(\.\d{0,2})?)$/;
    if (regex.test(value))
    {
        //Input is valid, check the number of decimal places
        var twoDecimalPlaces = /\.\d{2}$/g;
        var oneDecimalPlace = /\.\d{1}$/g;
        var noDecimalPlacesWithDecimal = /\.\d{0}$/g;
        
        if(value.match(twoDecimalPlaces ))
        {
            //all good, return as is
            return value;
        }
        if(value.match(noDecimalPlacesWithDecimal))
        {
            //add two decimal places
            return value+'00';
        }
        if(value.match(oneDecimalPlace ))
        {
            //ad one decimal place
            return value+'0';
        }
        //else there is no decimal places and no decimal
        return value+".00";
    }
    return null;
};
