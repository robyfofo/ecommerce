<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/menu/config.inc.php v.1.0.0. 22/03/2021
*/

$App->params = new stdClass();
$App->params->label = "Menu";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('menu'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPaths = array();
$App->params->uploadDirs = array();
$App->params->orderTypes = array();

$App->params->moduleAccessRead = (Permissions::checkIfModulesIsReadable($App->params->name,$App->userLoggedData) === true ? 1 : 0);
$App->params->moduleAccessWrite = (Permissions::checkIfModulesIsWritable($App->params->name,$App->userLoggedData) === true ? 1 : 0);

$App->params->codeVersion = ' 1.0.0.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';

$App->params->tableRif =  Config::$dbTablePrefix.'menu';

/* ITEM */
$App->params->ordersType['item'] = 'ASC';
$App->params->uploadPaths['item'] = ADMIN_PATH_UPLOAD_DIR."menu/";
$App->params->uploadDirs['item'] = UPLOAD_DIR."menu/";
$App->params->tables['item'] = $App->params->tableRif;
$App->params->fields['item'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>true,'primary'=>true),
	'users_id'=>array('label'=>Config::$localStrings['proprietario'],'searchTable'=>false,'required'=>false,'type'=>'int|8','defValue'=>$App->userLoggedData->id),
	'parent'=>array('label'=>'Parent','searchTable'=>false,'required'=>false,'type'=>'int|8','defValue'=>0),
	'type'=>array('label'=>'Tipo','searchTable'=>false,'required'=>false,'type'=>'varchar|50'),
	'ordering'=>array('label'=>Config::$localStrings['ordinamento'],'required'=>false,'type'=>'int|8','validate'=>'int','defValue'=>1),
	'alias'=>array('label'=>'Alias','searchTable'=>true,'required'=>true,'type'=>'varchar|255'),
	'url'=>array('label'=>Config::$localStrings['url'],'searchTable'=>true,'required'=>false,'type'=>'varchar|255'),
	'target'=>array('label'=>Config::$localStrings['target'],'searchTable'=>true,'required'=>false,'type'=>'varchar|20'),
	'filename'								=> array(
		'label'								=> 'File',
		'searchTable'						=> false,
		'required'							=> false,
		'type'								=> 'varchar|255',
		'defValue'							=>	'0'
	),
	'org_filename'							=> array(
		'label'								=> 'Nome Originale',
		'searchTable'						=> true,
		'required' 							=> false,
		'type'								=> 'varchar|255',
		'defValue'							=> ''
	),
	'menu_type_alias'				=> array('label'=>Config::$localStrings['menu'],'searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
	'access_read'=>array('label'=>Config::$localStrings['accesso lettura'],'searchTable'=>false,'required'=>false,'type'=>'text','defValue'=>'none'),
	'access_write'=>array('label'=>Config::$localStrings['accesso scrittura'],'searchTable'=>false,'required'=>false,'type'=>'text','defValue'=>'none'),
	'created'=>array('label'=>Config::$localStrings['creazione'],'searchTable'=>false,'required'=>false,'type'=>'datatime','defValue'=>Config::$nowDateTime,'validate'=>'datetimeiso'),
	'active'=>array('label'=>'Attiva','required'=>false,'type'=>'int','defValue'=>'0')
);		
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$App->params->fields['item']['title_'.$lang] = array('label'=>'Titolo '.$lang,'searchTable'=>true,'required'=>$required,'type'=>'varchar|255');
}
?>