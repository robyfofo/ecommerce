<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/news/config.inc.php v.1.0.0. 17/02/2021
*/

$App->params = new stdClass();
$App->params->label = "Notizie";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('news'),'name = ?');
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

$App->params->tableRif =  Config::$dbTablePrefix.'news';

$App->params->module_has_config = 1;

/* CONFIGURATIONS */
$App->params->ordersType['conf'] = 'DESC';
$App->params->tables['conf'] =$App->params->tableRif.'_configuration';
$App->params->uploadPaths['conf'] = ADMIN_PATH_UPLOAD_DIR."news/";
$App->params->uploadDirs['conf'] = UPLOAD_DIR."news/";
$App->params->fields['conf'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|1','autoinc'=>false,'primary'=>false),
	'filename'=>array('label'=>'Nome File','searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
	'org_filename'=>array('label'=>'','searchTable'=>true,'required'=>false,'type'=>'varchar|255'),
);	

/* ITEM */
$App->params->ordersType['item'] = 'DESC';
$App->params->uploadPaths['item'] = ADMIN_PATH_UPLOAD_DIR."news/";
$App->params->uploadDirs['item'] = UPLOAD_DIR."news/";

Config::$DatabaseTablesFields['news']['id_user']['defValue'] = $App->userLoggedData->id;
$App->params->tables['item'] = Config::$DatabaseTables['news'];
$App->params->fields['item'] = Config::$DatabaseTablesFields['news'];