<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/pages/index.php v.1.0.0. 22/03/2021
*/

//Core::setDebugMode(1);

include_once(PATH.$App->pathApplications.Core::$request->action."/lang/".Core::$localStrings['user'].".inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/config.inc.php");
include_once(PATH.$App->pathApplications.Core::$request->action."/classes/class.module.php");

$App->sessionName = Core::$request->action;
$App->codeVersion = $App->params->codeVersion;
$App->breadcrumb[] = $App->params->breadcrumb;
$App->pageTitle = $App->params->pageTitle;

$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);

switch(substr(Core::$request->method,-4,4)) {
	case 'Iimg':
		$App->sessionName = $App->sessionName.'-images';
		$App->params->tables['resources'] = $App->params->tableRif.'_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/item-images.php");	
	break;
	case 'Ifil':
		$App->sessionName = $App->sessionName.'-files';
		$App->params->tables['resources'] = $App->params->tableRif.'_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/item-files.php");	
	break;
	case 'Igal':
		$App->sessionName = $App->sessionName.'-gallery';
		$App->params->tables['resources'] = $App->params->tableRif.'_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/item-gallery.php");	
	break;
	case 'Ivid':
		$App->sessionName = $App->sessionName.'-videos';
		$App->params->tables['resources'] = $App->params->tableRif.'_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		$App->params->fields['resources']['filename']['required'] = false;
		$App->params->fields['resources']['org_filename']['required'] = false;
		$App->params->fields['resources']['code']['required'] = true;
		include_once(PATH.$App->pathApplications.Core::$request->action."/item-videos.php");	
	break;

	case 'Bimg':
		$App->sessionName = $App->sessionName.'-images';
		$App->params->tables['resources'] = $App->params->tableRif.'_blocks_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/block-images.php");	
	break;
	case 'Bfil':
		$App->sessionName = $App->sessionName.'-files';
		$App->params->tables['resources'] = $App->params->tableRif.'_blocks_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/block-files.php");	
	break;
	case 'Bvid':
		$App->sessionName = $App->sessionName.'-videos';
		$App->params->tables['resources'] = $App->params->tableRif.'_blocks_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>'','id_owner'=>'0'));
		$Module = new Module($App->params->tables['resources'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		$App->params->fields['resources']['filename']['required'] = false;
		$App->params->fields['resources']['org_filename']['required'] = false;
		$App->params->fields['resources']['code']['required'] = true;
		include_once(PATH.$App->pathApplications.Core::$request->action."/block-videos.php");	
	break;

	case 'Iblo':
		$App->sessionName = $App->sessionName.'-blocks';
		$App->params->tables['resources'] = $App->params->tableRif.'_blocks_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));
		$Module = new Module($App->params->tables['iblo'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/blocks.php");	
	break;
	default;
		$App->sessionName = $App->sessionName;
		$App->params->tables['resources'] = $App->params->tableRif.'_resources';
		if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'25','srcTab'=>''));
		$Module = new Module($App->params->tables['item'],Core::$request->action,$_MY_SESSION_VARS[$App->sessionName]);	
		include_once(PATH.$App->pathApplications.Core::$request->action."/pages.php");		
	break;	
	}
	
$App->defaultJavascript = "messages['Devi inserire un contenuto!'] = '".preg_replace('/%ITEM%/',Core::$localStrings['contenuto'],Core::$localStrings['Devi inserire un %ITEM%!'])."';";

?>
