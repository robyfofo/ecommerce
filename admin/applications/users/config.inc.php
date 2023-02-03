<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/users/config.inc.php v.1.0.0. 17/03/2021
*/

$App->params = new stdClass();
$App->params->label = "Utenti";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('users'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj; 

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPathDirs = array();
$App->params->uploadDirs = array();
$App->params->ordersType = array();

$App->params->moduleAccessRead = (Permissions::checkIfModulesIsReadable($App->params->name,$App->userLoggedData) === true ? 1 : 0);
$App->params->moduleAccessWrite = (Permissions::checkIfModulesIsWritable($App->params->name,$App->userLoggedData) === true ? 1 : 0);

$App->params->codeVersion = ' 1.0.0.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';
$App->params->tables['item'] = Config::$DatabaseTables['users'];
$App->params->fields['item'] = Config::$DatabaseTablesFields['users'];

$App->params->tables['nations'] = Config::$dbTablePrefix.'location_nations';
$App->params->tables['province'] = Config::$dbTablePrefix.'location_province';
$App->params->tables['comuni'] = Config::$dbTablePrefix.'location_comuni';

//ToolsStrings::dump($App->params->fields['item']);
?>