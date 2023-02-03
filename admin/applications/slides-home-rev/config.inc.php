<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/slides-home-rev/config.inc.php v.1.0.0. 20/03/2021
*/

$App->params = new stdClass();
$App->params->label = "Slides Home Rev";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('slides-home-rev'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPaths = array();
$App->params->uploadDirs = array();
$App->params->ordersType = array();

$App->params->moduleAccessRead = (Permissions::checkIfModulesIsReadable($App->params->name,$App->userLoggedData) === true ? 1 : 0);
$App->params->moduleAccessWrite = (Permissions::checkIfModulesIsWritable($App->params->name,$App->userLoggedData) === true ? 1 : 0);

$App->params->codeVersion = ' v.1.0.0.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';

$App->slide_types = array();
if (isset(Config::$localStrings['slide types'])) $App->slide_types = Config::$localStrings['slide types'];

$App->layer_types = array();
if (isset(Config::$localStrings['layer types'])) $App->layer_types = Config::$localStrings['layer types'];

Config::$DatabaseTablesFields['slides home rev']['user_id']['defValue'] = $App->userLoggedData->id;
Config::$DatabaseTablesFields['slides home rev']['user_id']['forceValue'] = $App->userLoggedData->id;

Config::$DatabaseTablesFields['slides home rev layers']['slide_id']['label'] = Config::$localStrings['layer'];

/* ITEM */
$App->params->ordersTypes['item'] = 'ASC';
$App->params->uploadPaths['item'] = ADMIN_PATH_UPLOAD_DIR."slides-home-rev/";
$App->params->uploadDirs['item'] = UPLOAD_DIR."slides-home-rev/";
$App->params->tables['item'] = Config::$DatabaseTables['slides home rev'];
$App->params->fields['item'] = Config::$DatabaseTablesFields['slides home rev'];
	
/* LAYER */
$App->params->ordersTypes['laye'] = 'ASC';
$App->params->uploadPaths['laye'] = ADMIN_PATH_UPLOAD_DIR."slides-home-rev/";
$App->params->uploadDirs['laye'] = UPLOAD_DIR."slides-home-rev/";
$App->params->tables['laye'] = Config::$DatabaseTables['slides home rev layers']; 
$App->params->fields['laye'] = Config::$DatabaseTablesFields['slides home rev layers'];


//ToolsStrings::dump($App->params->fields['laye']);
?>

