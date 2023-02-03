<?php
/* admin/core/error.php v.1.0.0. 18/03/2021 */

/* variabili ambiente */
$App->pageTitle = 'Error';
$App->pageSubTitle = 'Error';
$App->templateApp = Core::$request->action.'.html';
$App->templateBase = 'struttura.html';
$App->coreModule = true;
$App->codeVersion = ' 4.5.1.';

switch(Core::$request->method) {
	
	case '404':
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Error 404!'];
		$App->error_content = Config::$localStrings['testo errore 404'];
	break;
	
	case 'access':
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Access Error!'];
		$App->error_content = Config::$localStrings['testo errore accesso'];
	break;
	
	case 'mail':
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Mail Error!'];
		$App->error_content = Config::$localStrings['testo errore mail'];
	break;
	
	case 'db':
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Database Error!'];
		$App->error_content = Config::$localStrings['testo errore database'];
		$App->error_contentAlt = (Core::$request->param != '' ? Core::$request->param : '');
	break;
	
	case 'nopm':
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Permissions Error!'];
		$App->error_content = Config::$localStrings['testo errore permessi'];
		$App->error_contentAlt = (Core::$request->param != '' ? Core::$request->param : '');
	break;

	
	default:
		$App->error_title = Config::$localStrings['Errore!'];
		$App->error_subtitle = Config::$localStrings['Internal Server Error!'];
		$App->error_content = Config::$localStrings['testo errore generico'];
	break;
	
	}
?>