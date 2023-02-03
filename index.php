<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * index.php v.1.0.1. 20/06/2021
*/
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('PATH','');
define('MAXPATH', str_replace("includes","",dirname(__FILE__)).'');
if(!ini_get('date.timezone')) date_default_timezone_set('GMT');
setlocale(LC_TIME, 'ita', 'it_IT');

include_once(PATH."admin/include/configuration.inc.php");
require_once PATH."admin/classes/vendor/autoload.php";	

Config::setGlobalSettings($globalSettings);
Config::init();
Config::$globalSettings['requestoption']['defaultaction'] = 'home';
Config::$globalSettings['requestoption']['defaultpagesmodule'] = 'products';
Config::$globalSettings['requestoption']['othermodules'] = array('wishes','users','news','pages','error','products','carts','carts1','carts2');
Config::$globalSettings['requestoption']['methods'] = array('dt','ls');
Config::$globalSettings['requestoption']['coremodules'] = array();
Config::$globalSettings['requestoption']['sectionadmin'] = 0;
Config::$globalSettings['requestoption']['isRoot'] = 0;
Config::$globalSettings['requestoption']['getlasturlparam'] = array();
Config::$globalSettings['requestoption']['methods'] = array();
Core::init();

$ToolsUpload = new ToolsUpload();
$Users = new Users();

/* variabili globali */
$App = new stdClass;
$App->templateBase = 'struttura';
$renderTpl = true;
$renderAjax = false;
$App->templateApp = '';

// gestisce la richiesta http parametri get
Core::getRequest();
Config::$localStrings['user'] = 'it';


// cambia la sessione lingua 
if( Core::$request->lang != '' && in_array(Core::$request->lang, Config::$globalSettings['languages'])) {
	$_SESSION['lang'] =  Core::$request->lang;
}
if(!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = Config::$localStrings['user'];
}
//echo '<br>lingua :'.$_SESSION['lang'];
Config::loadLanguageVars($_SESSION['lang']);
Config::initDatabaseTables();

// sessione database
$my_session = new my_session(SESSIONS_TIME, SESSIONS_GC_TIME,SESSIONS_COOKIE_NAME);
$my_session->my_session_start();
$_MY_SESSION_VARS = array();
$_MY_SESSION_VARS = $my_session->my_session_read();

// templates
$App->templateUser = Config::$globalSettings['requestoption']['defaulttemplate'];
if (!isset($_MY_SESSION_VARS['tplusr']) && Core::$request->templateUser != '') {
	$_MY_SESSION_VARS =  $my_session->addSessionsSingleVar($_MY_SESSION_VARS,'tplusr',$App->templateUser);	
}
if (isset($_MY_SESSION_VARS['tplusr']) && Core::$request->templateUser != '' && $_MY_SESSION_VARS['tplusr'] != Core::$request->templateUser) {
	$_MY_SESSION_VARS =  $my_session->addSessionsSingleVar($_MY_SESSION_VARS,'tplusr',Core::$request->templateUser);	
}
if (isset($_MY_SESSION_VARS['tplusr'])) $App->templateUser = $_MY_SESSION_VARS['tplusr'];

//ToolsStrings::dump(Core::$request);//die('step 5');

// include pagine php

// carica la configurazione del singolo template 
if (file_exists(PATH."templates/".$App->templateUser."/configuration.inc.php")) include_once(PATH."templates/".$App->templateUser."/configuration.inc.php");

if (file_exists(PATH."pages/site_init.php")) include_once(PATH."pages/site_init.php");
switch(Core::$request->action) {
	default:		
		if (file_exists(PATH."pages/".Core::$request->action.".php")) {
			$App->templateApp = Core::$request->action;
			include_once(PATH."pages/".Core::$request->action.".php");		
		} else {	
			Core::$request->action = 'error';
			Core::$request->method = '404';
			include_once(PATH."pages/error.php");			
		}					
	break;	
}
// fine include pagine php

if ($renderAjax == true){	
	if (file_exists(PATH."templates/".$App->templateUser."/".Core::$request->action.".html")) {
		include_once(PATH."templates/".$App->templateUser."/".Core::$request->action.".html");	
	}
	$renderTlp = false;
}		
	
$App->mySessionVars = $_MY_SESSION_VARS;
$App->globalSettings = Core::$globalSettings;

/*
echo "<br>: 1: templates/".$App->templateUser;
echo "<br>: 2: ".$App->templateBase;
echo "<br>: 3: ".$App->templateApp;
die();
*/

// controlla se esite il template
/*
if (!file_exists(PATH."templates/".$App->templateUser.'/'.$App->templateApp.'.html')) {	
	Core::$request->action = 'error';
	Core::$request->method = '404';
	include_once(PATH."pages/error.php");
}
*/

if (file_exists(PATH."pages/site_main.php")) include_once(PATH."pages/site_main.php");

if ($renderTpl == true) {
	$App->templateApp .= '.html';
 	$App->templateBase.= '.html';
	$arrayVars = array(
		'App'=>$App,
		'LocalStrings' => Config::$localStrings,
		'URLSITE'=>URL_SITE,
		'URLSITEADMIN'=>URL_SITE_ADMIN,
		'PATHSITE'=>PATH_SITE,
		'PATHSITEADMIN'=>PATH_SITE_ADMIN,
		'UPLOADDIR'=>UPLOAD_DIR,
		'CoreRequest'=>Core::$request,
		'CoreResultOp'=>Core::$resultOp,
		'MySessionVars'=>$_MY_SESSION_VARS,
		'GlobalSettings'=>Core::$globalSettings
	);
	$loader = new \Twig\Loader\FilesystemLoader("templates/".$App->templateUser);
	$twig = new \Twig\Environment($loader, [
		//'cache' => PATH_UPLOAD_DIR.'compilation_cache',
		'autoescape'=>false,
		'debug' => true
	]);
	$twig->addExtension(new \Twig\Extension\DebugExtension());
	$template = $twig->load('strutture/'.$App->templateBase);
	echo $template->render($arrayVars);
}

if ($renderAjax == true){
	if (file_exists($pathApplication.$App->templateApp)) {
		include_once($pathApplication.$App->templateApp);	
	}
}
//print_r($_MY_SESSION_VARS);
die();
?>