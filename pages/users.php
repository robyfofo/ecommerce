<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * users.php v.1.0.0. 09/03/2021 
*/

$App->moduleName = 'users';
$App->moduleTitle = ucfirst(Config::$localStrings['utenti']);
$App->titles = Utilities::getTitlesPage($App->moduleTitle,$App->modulePageData,$App->moduleTitle,array());

//ToolsStrings::dump($_MY_SESSION_VARS);

switch (Core::$request->method)
{
	case 'upgradeSecurity':
		if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) { ToolsStrings::redirect(URL_SITE."users/login"); }
		ToolsStrings::dump($_POST);
		$posts = array();
		if (!isset($_POST)) ToolsStrings::redirect(URL_SITE.'error/404');	
		if (!isset($_POST['password'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	
		if (!isset($_POST['passwordCK'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	

		if ($_POST['password'] !== $_POST['passwordCK']) {
			$_SESSION['message'] = '1|'.Config::$localStrings['Le due password non corrispondono!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/security");
		}

		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);	
		$post = array('password'=>$password);
		Users::upgrade($_MY_SESSION_VARS['UsersId'],$post,$opz=array());
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['password'],Config::$localStrings['%ITEM% modificata'])).'!';
		ToolsStrings::redirect(URL_SITE.$App->moduleName."/security");
	break;

	case 'security':
		if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) { ToolsStrings::redirect(URL_SITE."users/login"); }
		$breadcrumbsTitle = ucfirst(Config::$localStrings['utente']);	
		$metaTitlePage = $App->titles['titleMeta'].' - '.Config::$localStrings['utente'];
		$App->view = 'security';
	break;

	case 'activate':
		$hash = (isset(Core::$request->param) ? Core::$request->param : '');
		if ($hash == '') {
			$_SESSION['message'] = '1|Non hai specificato un hash!';
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/outputs");
		}
		Users::activate($hash);
		if (Core::$resultOp->error > 0) {
			$_SESSION['message'] = Core::$resultOp->error.'|'.implode('<br>',Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/outputs");
		}
		
		$_SESSION['message'] = '0|'.Config::$localStrings['Attivazione account confermata!'];
		ToolsStrings::redirect(URL_SITE.$App->moduleName."/outputs");
	break;

	case 'checksignup':
		$posts = array();
		if (!isset($_POST)) ToolsStrings::redirect(URL_SITE.'error/404');	
		if (!isset($_POST['username'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		if (!isset($_POST['email'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	
		if (!isset($_POST['password'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	
		if (!isset($_POST['passwordCK'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	

		if ($_POST['password'] !== $_POST['passwordCK']) {
			$_SESSION['message'] = '1|'.Config::$localStrings['Le due password non corrispondono!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/signup");
		}

		if (!isset($_POST['privacy']) || (isset($_POST['privacy']) && $_POST['privacy'] == '')) {
			$_SESSION['message'] = '1|'.Config::$localStrings['Devi autorizzare il trattamento della privacy!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/signup");
		}
		if (!isset($_POST['termsconditions']) || (isset($_POST['termsconditions']) && $_POST['termsconditions'] == '')) {
			$_SESSION['message'] = '1|'.Config::$localStrings['Devi accettare i nostri termini e condizioni!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/signup");
		}
		
		// toglie spazi a username
		$_POST['username'] = preg_replace('/\s+/', '', $_POST['username']);
	

		if (Sql::countRecordQry(Config::$DatabaseTables['users'],'id','username = ?',array($_POST['username'])) > 0) {
			$_SESSION['message'] = '1|'.preg_replace('/%ITEM%/',Core::$localStrings['nome utente'],Core::$localStrings['Il %ITEM% esiste già!']);
			ToolsStrings::redirect(URL_SITE.Core::$request->action.'/signup');
		}

		// controlla email
		if ( Sql::countRecordQry(Config::$DatabaseTables['users'],'id','email = ?',array($_POST['email'])) > 0) {
			$_SESSION['message'] = '1|'.preg_replace('/%ITEM%/',Core::$localStrings['indirizzo email'],Core::$localStrings['Il %ITEM% esiste già!']);
			ToolsStrings::redirect(URL_SITE.Core::$request->action.'/signup');
		}

		$_POST['username'] = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
		$_POST['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);	   
		$_POST['passwordCK'] = filter_var($_POST['passwordCK'], FILTER_SANITIZE_STRING);

		Users::add($_POST,$opt=array());
		if (Core::$resultOp->error > 0) {
			$_SESSION['message'] = Core::$resultOp->error.'|'.implode('<br>',Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/signup");
		}
		
		$_SESSION['message'] = '0|'.Config::$localStrings['Account registrato!'];
		ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
	break;
	case 'signup':
		$breadcrumbsTitle = ucfirst(Config::$localStrings['registrazione']);
        $metaTitlePage = $App->titles['titleMeta'].' - '.ucfirst(Config::$localStrings['registrazione']);
		//$App->confirmUrlPrivacy = URLSITE.'
        $App->view = 'signup';
	break;

	case 'logout':

		$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
		/* Richiamiamo il metodo che distrugge la sessione */
		$my_session->my_session_destroy();
		/* Richiamiamo il metodo che pulire la tabella */
		$my_session->my_session_gc();
		/* cancello il cookie */
		setcookie (DATA_SESSIONS_COOKIE_NAME, "", time()-1);
		session_destroy();
		ToolsStrings::redirect(URL_SITE);
	break;

	case 'upgradeAvatar':
		if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) { ToolsStrings::redirect(URL_SITE."users/login"); }
		list($avatar,$avatar_info) = Users::getAvatarFormData( array() );
		if (Core::$resultOp->error == 1) {
			$_SESSION['message'] = '1|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/modify");
		}
		$post = array('avatar'=>$avatar,'avarat_info'=>$avatar_info);
		Users::upgrade($_MY_SESSION_VARS['UsersId'],$post,$opz=array());
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['immagine'],Config::$localStrings['%ITEM% modificata'])).'!';
		ToolsStrings::redirect(URL_SITE.$App->moduleName);
	break;

	case 'upgrade':
		//ToolsStrings::dump($_POST);
		if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) { ToolsStrings::redirect(URL_SITE."users/login"); }
		Users::upgrade($_MY_SESSION_VARS['UsersId'],$_POST,$opz=array());
		if (Core::$resultOp->error == 1) {
			$_SESSION['message'] = '1|'.implode('<br>',Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/modify");
		}
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['profilo'],Config::$localStrings['%ITEM% modificato'])).'!';
		ToolsStrings::redirect(URL_SITE.$App->moduleName."/modify");
	break;

	case 'modify':
		if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) { ToolsStrings::redirect(URL_SITE."users/login"); }
		Users::$optGetOnlyFromSite = true;
		Users::$optGetOnlyActive = true;
		Users::getUserDetails($_MY_SESSION_VARS['UsersId'],$opz=array());
		$App->user = Users::$details;
		//ToolsStrings::dump($App->user);
        $breadcrumbsTitle = ucfirst(Config::$localStrings['profilo']);
        $metaTitlePage = $App->titles['titleMeta'].' - '.ucfirst(Config::$localStrings['profilo']);
        $App->view = 'modify';
	break;

	case 'checkgetpassword':

		if (!isset($_POST)) ToolsStrings::redirect(URL_SITE.'error/404'); 	
		if (!isset($_POST['email'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/getpassword");		
		if ($_POST['email'] == "") {
			$_SESSION['message'] = '1|'.preg_replace('/%ITEM%/',Config::$localStrings['indirizzo email'],Config::$localStrings['Devi inserire un %ITEM%!']);
			ToolsStrings::redirect(URL_SITE.$App->moduleName.'/getpassword');
		}		
		$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);		
		// prendo dettaglio per email
		Users::getDetails($_POST['email'],array('fields'=>array('id,email,username'),'clause'=>'email = ?'));
		ToolsStrings::dump(Users::$details);

		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		if (!isset(Users::$details->id) || isset(Users::$details->id) && Users::$details->id == 0 ) {
			$_SESSION['message'] = '1|'.ucfirst(Config::$localStrings['indirizzo email inserito non esiste! Vi invitiamo a ripetere la procedura o contattare amministratore del sistema.']);
			ToolsStrings::redirect(URL_SITE.$App->moduleName.'/getpassword');
		}		
		$password = ToolsStrings::setNewPassword('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890',8);
		$criptPassword = password_hash($password, PASSWORD_DEFAULT);			
		// memorizzo nel d		
		$f = array('password');
	   	$fv = array($criptPassword,Users::$details->id);
		Sql::initQuery(Config::$DatabaseTables['users'],$f,$fv,'id = ?');
		Sql::updateRecord();	
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }						
		$titolo = Config::$localStrings['titolo email sezione richiesta password'];
		$testo = Config::$localStrings['testo email sezione richiesta password da frontend'];
		$text_plain = Html2Text\Html2Text::convert($testo);		
		$titolo = Users::parseEmailText($titolo,$opt=array());		
		$testo = Users::parseEmailText($testo,$opt=array('other vars'=>array('%CLEANPASSWORD%'=>$password)));	
		//echo $titolo;	
		//echo $testo; 	
		//die();			
		$opt = array();
		$opt['fromEmail'] = $globalSettings['default email'];
		$opt['fromLabel'] = $globalSettings['default email label'];		
		$opt['sendDebug'] = $globalSettings['send email debug'];
		$opt['sendDebugEmail'] = $globalSettings['email debug'];								
		Mails::sendEmail(Users::$details->email,$titolo,$testo,$text_plain,$opt);		
		if (Core::$resultOp->error > 0) {
			$_SESSION['message'] = '1|'.Config::$localStrings['Errore invio della email! Vi invitiamo a ripetere la procedura o contattare amministratore.'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName.'/getpassword');
		}	
		$_SESSION['message'] = '0|'.Config::$localStrings['La nuova password vi è stata inviata con email indirizzo associato ed è stata memorizzata nel sistema!'];
		ToolsStrings::redirect(URL_SITE.$App->moduleName.'/getpassword');
		die();
	break;

	case 'getpassword':
		if ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] > 0 ) ToolsStrings::redirect(URL_SITE."users");
		$breadcrumbsTitle = ucfirst(Config::$localStrings['password dimenticata?']);	
		$metaTitlePage = $App->titles['titleMeta'].' - '.Config::$localStrings['password dimenticata?'];
		$App->view = 'getpassword';		
	break;	

	case 'checklogin':		
		
		if (!isset($_POST)) ToolsStrings::redirect(URL_SITE.'error/404');	
		if (!isset($_POST['username'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		if (!isset($_POST['password'])) ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");	
		if ($_POST['username'] == "") {
			$_SESSION['message'] = '1|'.preg_replace('/%ITEM%/',Config::$localStrings['nome utente'],Config::$localStrings['Devi inserire un %ITEM%!']);
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		}		
		if ($_POST['password'] == "") {
			$_SESSION['message'] = '1|'.preg_replace('/%ITEM%/',Config::$localStrings['password'],Config::$localStrings['Devi inserire un %ITEM%!']);
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		}
		// sanitizzo
		$email = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);	   
		// guardo se esiste l' username	
		if (Users::checkExists($email,array('clause'=>'username = ? AND active = 1 AND from_site = 1')) == false) {
			$_SESSION['message'] = '1|'.Config::$localStrings['I dati inseriti non corrispondono!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		}		
		// prende i dati
		Users::getDetails($email,array('fields'=>array('id','name','surname','username','password','id_level','template'),'clause'=>'username = ? AND active = 1 AND from_site = 1'));
		if (Core::$resultOp->error > 0) {	ToolsStrings::redirect(URL_SITE.'error/db'); die(); }	
		//ToolsStrings::dump(Users::$details);die();
		
		if (password_verify($password,Users::$details->password)) {		
			$now = $App->nowDateTime;
			$lastLogin = $now;			
			if (isset($_POST['remember-me'])) {
				setcookie(SESSIONS_COOKIE_NAME,$my_session->getSessionId(),time() + (86400 * 90), "/");
			}			
			if ( isset($_COOKIE[DATA_SESSIONS_COOKIE_NAME])) {
				$lastLogin = $_COOKIE[DATA_SESSIONS_COOKIE_NAME];	
			}			
			setcookie(DATA_SESSIONS_COOKIE_NAME, $lastLogin, time() + (86400 * 90), "/"); // 86400 = 1 day
			$my_session->my_session_register('lastLogin',$lastLogin);
			$my_session->my_session_register('UsersId',Users::$details->id);
			$_MY_SESSION_VARS = array();					
			$_MY_SESSION_VARS = $my_session->my_session_read();				
			ToolsStrings::redirect(URL_SITE."users");
		} else {
			$_SESSION['message'] = '1|'.Config::$localStrings['I dati inseriti non corrispondono!'];
			ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
		}
		ToolsStrings::redirect(URL_SITE.$App->moduleName."/login");
	break;

    case 'login':
		$breadcrumbsTitle = ucfirst(Config::$localStrings['login']);	
		$metaTitlePage = $App->titles['titleMeta'].' - '.Config::$localStrings['login'];
		$App->view = 'login';
	break;	
    
	case 'outputs':
		$breadcrumbsTitle = ucfirst(Config::$localStrings['utente']);	
		$metaTitlePage = $App->titles['titleMeta'].' - '.Config::$localStrings['utente'];
		$App->view = 'outputs';
	break;

    default:
        if ( !isset($_MY_SESSION_VARS['UsersId']) || ( isset($_MY_SESSION_VARS['UsersId']) && $_MY_SESSION_VARS['UsersId'] == 0) ) 
        {
            ToolsStrings::redirect(URL_SITE."users/login");
        }
		Users::$optGetOnlyFromSite = true;
		Users::$optGetOnlyActive = true;
		Users::getUserDetails($_MY_SESSION_VARS['UsersId'],$opz=array());
		$App->user = Users::$details;
        $breadcrumbsTitle = ucfirst(Config::$localStrings['profilo']);
        $metaTitlePage = $App->titles['titleMeta'].' - '.ucfirst(Config::$localStrings['profilo']);
        $App->view = '';
    break;	
}


    
//ToolsStrings::dump($_SESSION);

// gestione titolo pagina
$App->titlepage = $metaTitlePage;
// gestione breadcrumbs
$App->breadcrumbs->title = $breadcrumbsTitle;
$App->breadcrumbs->items[] = array('class'=>'active','title'=>$breadcrumbsTitle);	
// SEO
$App->metaTitlePage = $globalSettings['meta tags page']['title ini'].$metaTitlePage.$globalSettings['meta tags page']['title separator'].$globalSettings['meta tags page']['title end'];
$App->metaDescriptionPage .= '';
$App->metaKeywordsPage .= '';



/*
$App->pluginJscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/vendor/jquery.filer/js/jquery.filer.min.js"></script>';
$App->pluginJscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/helpers/hs.focus-state.js"></script>';
$App->pluginJscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/components/hs.file-attachement.js"></script>';
*/
/*
$App->initializationPlugins = "
$(document).on('ready', function () {
	// initialization of forms
	$.HSCore.components.HSFileAttachment.init('.js-file-attachment');
	$.HSCore.helpers.HSFocusState.init();
  });
";
*/

$App->codeJavascriptEndBody = "
// varie
let userDbTable = '".Config::$DatabaseTables['users']."';
// messaes
messages['le due password non corrsipondono'] = '".Config::$localStrings['Le due password non corrispondono!']."';

$(document).on('ready', function () {
	$('#submitFormAvatarID').on('click',function (e) {
		$('input').removeClass('is-invalid');

		if ($('#avatarID').val() == '') {
			//$('#inputGroup').addClass('is-invalid');
			res = showJavascriptConfirm('".ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['immagine'],Config::$localStrings['desideri cancellare il dato %ITEM%?']))."');
			console.log(res);
			if (res == false) return false;
		}

		$('#formAvatarID').submit();
		e.preventDefault();
	});
});	
";

switch ($App->view) {
	case 'security':
		$App->templateApp = 'users-security';
		$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/users_security.js"></script>';
	break;
	case 'outputs':
		$App->outups = 'resultactivation';
		$App->templateApp = 'users-outputs';
	break;
	case 'signup':
		$App->jquerycompatibility = 1;
		$App->templateApp = 'users-signup';
		$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/users_signup.js"></script>';
	break;
	case 'getpassword':
		$App->templateApp = 'users-getpassword';
	break;
	case 'password':
		$App->templateApp = 'users-password';
	break;
	case 'login':
		$App->templateApp = 'users-login';
		$App->codeJavascriptEndBody = "
		$(document).on('ready', function () {
			$('#submitFormLoginID').on('click',function (e) {
				let res;
				$('input').removeClass('is-invalid');
				res = validatehtml5('is-invalid');	
				if (res == false) return false;
			});
		});	
		";
	break;
	case 'modify':
		$App->templateApp = 'users-modify';
		$App->jscript[] = '<script src="'.URL_SITE.'templates/'.$App->templateUser.'/assets/js/pages/users_modify.js"></script>';
	break;
	case 'details':
		$App->templateApp = 'users-details';
	break;
	default:
		$App->templateApp = 'users-profile';
	break;
}
?>