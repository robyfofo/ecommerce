<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/news/index.php v.1.0.0. 25/03/2021
*/

//Core::setDebugMode(1);

include_once(PATH.$App->pathApplications.Core::$request->action."/lang/".Config::$localStrings['user'].".inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/config.inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/classes/class.module.php");

$App->sessionName = Core::$request->action;
$App->codeVersion = $App->params->codeVersion;
$App->breadcrumb[] = $App->params->breadcrumb;
$App->pageTitle = $App->params->pageTitle;

$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);



$App->AddPluginCss[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/tempusdominus/tempusdominus-bootstrap-4.min.css" rel="stylesheet" type="text/css">';
$App->AddPluginJscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/tempusdominus/tempusdominus-bootstrap-4.min.js"></script>';

$App->defaultDatatimeins = Config::$nowDateTimeIso;
$App->defaultDatatimescaini = Config::$nowDateTimeIso;
$App->defaultDatatimescaend  = Config::$nowDateTimeIso;
switch(substr(Core::$request->method,-4,4)) {	
	case 'Conf':	
		$App->sessionName = 'config';
		$Module = new Module($App->params->tables['conf']);		
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));
		include_once(PATH.$App->pathApplications.Core::$request->action."/configuration.php");
	break;

	default:
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));
		$Module = new Module(Core::$request->action,$App->params->tables['item']);
		include_once(PATH.$App->pathApplications.Core::$request->action."/news.php");
		$App->css[] = '<link href="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/css/news.css" rel="stylesheet">';		
	
	
		$App->defaultJavascript = "
			messages['La data di scadenza non può essere prima della data di inserimento!'] = '".Config::$localStrings['La data di scadenza non può essere prima della data di inserimento!']."';
			messages['Devi inserire una data valida!'] = '".Config::$localStrings['Devi inserire una data valida!']."';
			messages['Intervallo tra le due date errato!'] = '".Config::$localStrings['La data di scadenza non può essere prima della data di inserimento!']."';
		
			var defaultDatatimeins = '".$App->defaultDatatimeins."';
			var defaultDatatimescaini = '".$App->defaultDatatimescaini."';
			var defaultDatatimescaend = '".$App->defaultDatatimescaend."';
		
			";	

	break;							
}	


?>
