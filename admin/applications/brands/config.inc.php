<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/brands/config.inc.php v.1.0.0. 16/02/2021
*/

$App->params = new stdClass();
$App->params->label = "Brands";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('brands'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPaths = array();
$App->params->uploadDirs = array();
$App->params->ordersType = array();

$App->params->moduleAccessRead = (Permissions::checkIfModulesIsReadable($App->params->name,$App->userLoggedData) === true ? 1 : 0);
$App->params->moduleAccessWrite = (Permissions::checkIfModulesIsWritable($App->params->name,$App->userLoggedData) === true ? 1 : 0);

$App->params->codeVersion = ' 1.0.0.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';

$App->params->tableRif =  Config::$dbTablePrefix.'brands';

/* ITEM */
$App->params->ordersType['item'] = 'ASC';
$App->params->uploadPaths['item'] = ADMIN_PATH_UPLOAD_DIR."brands/";
$App->params->uploadDirs['item'] = UPLOAD_DIR."brands/";


Config::$DatabaseTablesFields['brands']['in_footer']['label'] = Config::$localStrings['in footer'];

$App->params->tables['item'] = Config::$DatabaseTables['brands'];
$App->params->fields['item'] = Config::$DatabaseTablesFields['brands'];

?>