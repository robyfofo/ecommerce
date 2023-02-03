<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/index.php v.1.0.1. 20/06/2021
*/
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('PATH','');
define('MAXPATH', str_replace("includes","",dirname(__FILE__)).'');
if(!ini_get('date.timezone')) date_default_timezone_set('GMT');
setlocale(LC_TIME, 'ita', 'it_IT');

include_once(PATH."include/configuration.inc.php");
require_once PATH."classes/vendor/autoload.php";	

Config::setGlobalSettings($globalSettings);
Config::init();

Core::$globalSettings['requestoption']['coremodules'] = array('moduleassociated','login','logout','account','password','profile','nopassword','nousername','moduleassociated','error');
Core::$globalSettings['requestoption']['othermodules'] = array_merge(array('help'),Core::$globalSettings['requestoption']['coremodules']);
Core::$globalSettings['requestoption']['defaultaction'] = 'home';
Core::$globalSettings['requestoption']['defaultpagesmodule'] = 'home';
Core::$globalSettings['requestoption']['sectionadmin'] = 1;
Core::$globalSettings['requestoption']['methods'] = array();
Core::$globalSettings['requestoption']['isRoot'] = 0;
Core::$globalSettings['requestoption']['getlasturlparam'] = false;

Core::init();

$ToolsUpload = new ToolsUpload();

/* variabili globali */
$App = new stdClass;
$App->templateBase = 'struttura.html';
$renderTpl = true;
$renderAjax = false;
$App->templateApp = '';
$App->templateUser = 'default';	
$App->pathApplications = 'applications/';
$App->pathApplicationsCore = 'applications/core/';
$App->templateUser = Core::$globalSettings['requestoption']['defaulttemplate'];

// avvio sessione
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,AD_SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();
//ToolsStrings::dump($_MY_SESSION_VARS);die('vedi sessione MY_SESSION');

// carica dati utente loggato
$App->userLoggedData = new stdClass();
if (isset($_MY_SESSION_VARS['idUser'])) {	
	Sql::initQuery(Config::$dbTablePrefix.'users',array('*'),array($_MY_SESSION_VARS['idUser']),'active = 1 AND id = ?','');
	$App->userLoggedData = Sql::getRecord();
	$App->userLoggedData->is_root = intval($App->userLoggedData->is_root);
}
//ToolsStrings::dump($App->userLoggedData);

Core::getRequest();
//ToolsStrings::dump(Config::$modules);
//ToolsStrings::dump(Config::$userModules);
//ToolsStrings::dump(Config::$userLevels);

//Sql::setDebugMode(1);

Config::loadLanguageVarsAdmin($currentlanguage = 'it');
//ToolsStrings::dump(Config::$localStrings);
setlocale(LC_TIME,Config::$localStrings['lista lingue abbreviate'][Config::$localStrings['user']], Config::$localStrings['charset date']);

Config::initDatabaseTables('../');

//ToolsStrings::dump(Core::$request);
if (!isset($_MY_SESSION_VARS['idUser'])){
	//echo 'login';
	if (Core::$request->action != "nopassword" && Core::$request->action != "nousername") Core::$request->action = 'login';
}
//ToolsStrings::dump(Core::$request);

// controlla permessi per accesso modulo
$App->user_first_module_active = Core::$globalSettings['requestoption']['defaultaction'];
$App->user_modules_active = Core::$globalSettings['requestoption']['defaultaction'];
if (Permissions::checkIfModulesIsReadable(Core::$request->action,$App->userLoggedData) == false) {
	//echo '<br>accesso negato';
	Core::$request->action = $App->user_first_module_active;	
} else {
	//echo '<br>accesso consentito';
}
//ToolsStrings::dump(Core::$request);

if (file_exists(PATH."app.ini.php")) include_once(PATH."app.ini.php");

$pathApplications = $App->pathApplications;
$action = Core::$request->action;
$index = '/index.php';
$App->coreModule = false;
if (in_array(Core::$request->action,Core::$globalSettings['requestoption']['coremodules']) == true) {
	$App->coreModule = true;
	$pathApplications = $App->pathApplicationsCore;
	$action = '';
	$index = Core::$request->action.'.php';
}

//echo '<br>reindirizzamento: '.PATH.$pathApplications.$action.$index;
//echo '<br>reindirizzamento 1: '.PATH.$pathApplications.$App->user_first_module_active."/index.php";
if (file_exists(PATH.$pathApplications.$action.$index)) {
	include_once(PATH.$pathApplications.$action.$index);
} else {
	Core::$request->action = $App->user_first_module_active;
	include_once(PATH.$pathApplications.$App->user_first_module_active."/index.php");
}

if (file_exists(PATH."app.end.php")) include_once(PATH."app.end.php");

if ($App->coreModule == true) {
	$pathtemplateApp = PATH_SITE_ADMIN.$pathApplications .= "templates/".$App->templateUser."/";
} else {
	$pathtemplateApp = PATH_SITE_ADMIN.$pathApplications.Core::$request->action."/templates/".$App->templateUser.'/';
}
		
$pathtemplateBase = PATH_SITE_ADMIN."templates/".$App->templateUser;

// controlla se esite il template
if (!file_exists($pathtemplateApp.$App->templateApp)) {
}

/* genera il template */
if ($renderTpl == true && $App->templateApp != '') {
	$arrayVars = array(
		'App'=>$App,
		'LocalStrings'=>Config::$localStrings,
		'URLSITE'=>URL_SITE,
		'URLSITEADMIN'=>URL_SITE_ADMIN,
		'PATHSITE'=>PATH_SITE,
		'PATHSITEADMIN'=>PATH_SITE_ADMIN,
		'PATHDOCUMENT'=>PATH_DOCUMENT,
		'UPLOADDIR'=>UPLOAD_DIR,
		'CoreRequest'=>Core::$request,
		'CoreResultOp'=>Core::$resultOp,
		'MySessionVars'=>$_MY_SESSION_VARS,
		'Session'=>$_SESSION,
		'GlobalSettings'=>Config::$globalSettings
		);
	
	

	$loader = new \Twig\Loader\FilesystemLoader($pathtemplateBase);
	$loader->addPath($pathtemplateApp);
	$twig = new \Twig\Environment($loader, [
		//'cache' => PATH_UPLOAD_DIR.'compilation_cache',
		'autoescape'=>false,
		'debug' => true
	]);

	$twig->addExtension(new \Twig\Extension\DebugExtension());
	$template = $twig->load($App->templateBase);
	echo $template->render($arrayVars);
	
	} else { if ($renderAjax != true) echo 'No templateApp found!';}
	
if ($renderAjax == true){
	if (file_exists($pathApplications.$App->templateApp)) {
		include_once($pathApplications.$App->templateApp);	
		}
	}
//print_r($_MY_SESSION_VARS);
die();
?>