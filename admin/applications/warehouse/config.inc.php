<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/config.inc.php v.1.0.0. 26/03/2021
*/

$App->params = new stdClass();
$App->params->label = "Magazzino";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('warehouse'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

/* configurazione */
$App->params->applicationName = Core::$request->action;

$App->params->databases = array();
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

$App->params->uploadPaths['base'] = ADMIN_PATH_UPLOAD_DIR."warehouse/";
$App->params->uploadDirs['base'] = UPLOAD_DIR."warehouse/";


$App->params->tableRif =  Config::$dbTablePrefix.'warehouse_';

/* configurazione */
$App->params->ordersType['conf'] = 'DESC';
$App->params->tables['conf'] =$App->params->tableRif.'_configuration';
$App->params->uploadPaths['conf'] = ADMIN_PATH_UPLOAD_DIR."warehouse/";
$App->params->uploadDirs['conf'] = UPLOAD_DIR."warehouse/";
$App->params->fields['conf'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>false,'primary'=>false),
	'filename'=>array('label'=>Config::$localStrings['immagine'].' Top','searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
	'org_filename'=>array('label'=>Config::$localStrings['nome file originale'].' Top','searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
);	
	
// categorie e subcategorie */
$App->params->ordersType['cate'] = 'ASC';
$App->params->uploadPaths['cate'] = ADMIN_PATH_UPLOAD_DIR."warehouse/categories/";
$App->params->uploadDirs['cate'] = UPLOAD_DIR."warehouse/categories/";
Config::$DatabaseTablesFields['warehouse categories']['users_id']['defValue'] = $App->userLoggedData->id;
//Config::$DatabaseTablesFields['warehouse categories']['users_id']['forceValue'] = $App->userLoggedData->id;
$App->params->tables['cate'] = Config::$DatabaseTables['warehouse categories'];
$App->params->fields['cate'] = Config::$DatabaseTablesFields['warehouse categories'];

/* PRODOTTI */
$App->params->uploadPaths['prod'] = ADMIN_PATH_UPLOAD_DIR."warehouse/products/";
$App->params->uploadDirs['prod'] = UPLOAD_DIR."warehouse/products/";
$App->params->ordersType['prod'] = 'ASC';
Config::$DatabaseTablesFields['warehouse products']['users_id']['defValue'] = $App->userLoggedData->id;
$App->params->tables['prod'] = Config::$DatabaseTables['warehouse products'];
$App->params->fields['prod'] = Config::$DatabaseTablesFields['warehouse products'];

// tags
$App->params->ordersType['tags'] = 'ASC';
$App->params->tables['tags'] = Config::$DatabaseTables['warehouse tags'];
$App->params->fields['tags'] = Config::$DatabaseTablesFields['warehouse tags'];


// tipi attributi prodotto
$App->params->ordersType['proatypes'] = 'DESC';
$App->params->tables['proatypes'] = $App->params->tableRif.'products_attribute_types';
$App->params->fields['proatypes'] = array (
	'id'						=> array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>true,'primary'=>true),
	'value_type'				=> array('label'=>Config::$localStrings['valore'],'required'=>true,'searchTable'=>false,'type'=>'varchar|10'),
	'active'					=> array('label'=>Config::$localStrings['attivazione'],'required'=>false,'type'=>'int|1','validate'=>'int','defValue'=>'0')
);
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$App->params->fields['proatypes']['title_'.$lang] = array('label'=>Config::$localStrings['titolo'].' '.$lang,'searchTable'=>true,'required'=>$required,'type'=>'varchar|255');
}

// attributi prodotto
$App->params->tables['proa'] = $App->params->tableRif.'products_attributes';
$App->params->fields['proa'] = array (
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>true,'primary'=>true),
	'products_id'=>array('label'=>'ID Prodotto','required'=>false,'searchTable'=>false,'type'=>'int|8'),
	'products_attribute_types_id'=>array('label'=>'ID Tipo Attributo','required'=>false,'searchTable'=>false,'type'=>'int|8'),
	'code'=>array('label'=>Config::$localStrings['codice'],'required'=>false,'searchTable'=>false,'type'=>'varchar|100','defValue'=>''),
	'value_string'=>array('label'=>Config::$localStrings['valore stringa'],'required'=>false,'searchTable'=>true,'type'=>'varchar|100','defValue'=>''),
	'value_int'=>array('label'=>Config::$localStrings['valore intero'],'required'=>false,'searchTable'=>true,'type'=>'int|8','validate'=>'int','defValue'=>'0'),
	'value_float'=>array('label'=>Config::$localStrings['valore float'],'required'=>false,'searchTable'=>true,'type'=>'float|10,2','validate'=>'float','defValue'=>'0.00'),
	'value_type'=>array('label'=>Config::$localStrings['valore tipo'],'required'=>false,'searchTable'=>true,'type'=>'varchar|10','defValue'=>''),
	'quantity'=>array('label'=>Config::$localStrings['quantità'],'required'=>false,'searchTable'=>true,'type'=>'int|8','validate'=>'int','defValue'=>'0'),
	'active'=>array('label'=>Config::$localStrings['attivazione'],'required'=>false,'type'=>'int|1','validate'=>'int','defValue'=>'0')
);
?>