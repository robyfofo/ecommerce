<?php
/* wscms/core/password.php v.3.5.4. 31/07/2019 */

//Sql::setDebugMode(1);

/* variabili ambiente */
$App->codeVersion = ' 3.5.4.';
$App->pageTitle = ucfirst($_LocalStrings['password']);
$App->pageSubTitle = preg_replace('/%ITEM%/', $_LocalStrings['password'], $_LocalStrings['modifica la %ITEM%']);
$App->breadcrumb[] = '<li class="active"><i class="icon-user"></i> '.preg_replace('/%ITEM%/', $_LocalStrings['password'], $_LocalStrings['modifica %ITEM%']).'</li>';
$App->templateApp = Core::$request->action.'.html';
$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);
$App->coreModule = true;

switch(Core::$request->method) {
	case 'update':
		if ($_POST) {			
			$password = (isset($_POST['password']) && $_POST['password'] != "") ? SanitizeStrings::stripMagic($_POST['password']) : '';
			$passwordCK = (isset($_POST['passwordCK']) && $_POST['passwordCK'] != "") ? SanitizeStrings::stripMagic($_POST['passwordCK']) : '';
			if ($password != '') {
				if ($password === $passwordCK) {
					$password = password_hash($password, PASSWORD_DEFAULT);
					} else {
						Core::$resultOp->error = 1;
						Core::$resultOp->message = $_LocalStrings['Le due password non corrispondono!'];
						}				
				} else {
					Core::$resultOp->error = 1;
					Core::$resultOp->message = $_LocalStrings['Devi inserire la password!'];			
					}
				
			if (Core::$resultOp->error == 0) {	
				/* (tabella,campi(array),valori campi(array),where clause, limit, order, option , pagination(default false)) */
				Sql::initQuery(Config::$dbTablePrefix.'users',array('password'),array($password,$App->id),"id = ?");	
				//Sql::updateRecord();
				if(Core::$resultOp->error == 0) {
					Core::$resultOp->message = $_LocalStrings['Password modificata correttamente! Sarà effettiva al prossimo login.'];
					}	
				$App->id	 = $_POST['id'];					         	
				}			
		} else {
			Core::$resultOp->error = 1;
			Core::$resultOp->message = $_LocalStrings['Devi inserire tutti i campi richiesti!'];
			}
	
	default:
		if ($App->id > 0) {	
			/* recupera i dati memorizzati */
			$App->item = new stdClass;	
			/* (tabella,campi(array),valori campi(array),where clause, limit, order, option , pagination(default false)) */
			Sql::initQuery(Config::$dbTablePrefix().'users',array('username','password'),array($App->id),"id = ?");	
			$App->item = Sql::getRecord();
			$App->defaultJavascript = "messages['Le due password non corrispondono!'] = '".addslashes($_LocalStrings['Le due password non corrispondono!'])."'";
			} else {
				//ToolsStrings::redirect(URL_SITE_ADMIN."home");
				//die();						
				}
	break;	
	}
	
$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplicationsCore.'/templates/'.$App->templateUser.'/js/password.js" type="text/javascript"></script>';
?>