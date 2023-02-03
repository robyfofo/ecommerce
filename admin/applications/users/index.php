<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/users/index.php v.1.0.0. 17/03/2021
*/

//Core::setDebugMode(1);

include_once(PATH.$App->pathApplications.Core::$request->action."/lang/".Core::$localStrings['user'].".inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/config.inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/classes/class.module.php");



$App->sessionName = Core::$request->action;
$App->codeVersion = $App->params->codeVersion;
$App->breadcrumb[] = $App->params->breadcrumb;
$App->pageTitle = $App->params->pageTitle;

$App->userLevels = Config::$userLevels;
$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);

if (!isset($_SESSION[$App->sessionName]['formTabActive'])) $_SESSION[$App->sessionName]['formTabActive'] = 1;
	
switch(substr(Core::$request->method,-4,4)) {		
	default:
		$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>';

		$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/css/ajax-bootstrap-select.min.css" rel="stylesheet">';
		
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/js/ajax-bootstrap-select.min.js"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/js/locale/ajax-bootstrap-select.'.Core::$localStrings['charset'].'.min.js"></script>';
		$App->sessionName .= '';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));
		$Module = new Module($App->sessionName,$App->params->tables['item']);
		include_once(PATH.$App->pathApplications.Core::$request->action."/users.php");
		$App->defaultJavascript = "
		messages['password not match'] = '".addslashes(Core::$localStrings['Le due password non corrispondono!'])."';
		";
	break;
}
?>