<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/pages/config.inc.php v.1.0.0. 01/07/2021
*/

/* prende i dati del modulo template dal file conf */
include_once(PATH.$App->pathApplications."pagetemplates/config.inc.php");
$paramsuploadPaths = $App->params->uploadPaths['item'];
$paramsuploadDirs = $App->params->uploadDirs['item'];

$App->params = new stdClass();
$App->params->label = "Pagine";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('name','label','help_small','help'),array('pages'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

$App->params->template['uploadpathdir'] = $paramsuploadPaths;
$App->params->template['defuploaddir'] = $paramsuploadDirs;

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

$App->params->tableRif =  Config::$dbTablePrefix.'pages';

/* ITEMS */
$App->params->ordersType['item'] = 'ASC';
$App->params->uploadPaths['item'] = ADMIN_PATH_UPLOAD_DIR."pages/";
$App->params->uploadDirs['item'] = UPLOAD_DIR."pages/";
$App->params->tables['item'] = $App->params->tableRif;
$App->params->fields['item'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'id_user'=>array('label'=>Core::$localStrings['proprietario'],'searchTable'=>false,'required'=>false,'type'=>'int','defValue'=>$App->userLoggedData->id),
	'parent'=>array('label'=>'Parent','searchTable'=>false,'required'=>false,'type'=>'varchar','defValue'=>0),
	'id_template'=>array('label'=>Core::$localStrings['template'],'searchTable'=>false,'required'=>false,'type'=>'int'),
	'ordering'						=> array(
				'label'				=> Core::$localStrings['ordinamento'],
				'required'			=> false,
				'type'				=> 'int|8',
				'defValue'			=> 1
	),

	'menu'							=> array(
				'label'				=>'In menu',
				'searchTable'		=> false,
				'required'			=> false,
				'type'				=> 'int',
				'defValue'			=> 0
	),

	'is_label'						=> array(
				'label'				=> Core::$localStrings['etichetta'],
				'searchTable'		=> false,
				'required'			=> false,
				'type'				=>	'int',
				'defValue'			=> 0
	),

	'alias'=>array('label'=>'Alias','searchTable'=>true,'required'=>true,'type'=>'varchar'),
	'url'=>array('label'=>Core::$localStrings['url'],'searchTable'=>true,'required'=>false,'type'=>'varchar|255'),
	'target'=>array('label'=>Core::$localStrings['target'],'searchTable'=>true,'required'=>false,'type'=>'varchar|20'),
	'jscript_init_code'=>array('label'=>'Codice Javascript inizio BODY','required'=>false,'type'=>'varchar','defValue'=>''),
	'filename'=>array('label'=>'File','searchTable'=>false,'required'=>false,'type'=>'varchar'),
	'org_filename'=>array('label'=>'Nome Originale','searchTable'=>true,'required'=>false,'type'=>'varchar','defValue'=>''),
	'filename1'=>array('label'=>Core::$localStrings['immagine bottom'],'searchTable'=>false,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'org_filename1'=>array('label'=>Core::$localStrings['immagine bottom'],'searchTable'=>true,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'access_read'=>array('label'=>Core::$localStrings['accesso lettura'],'searchTable'=>false,'required'=>false,'type'=>'text','defValue'=>'none'),
	'access_write'=>array('label'=>Core::$localStrings['accesso scrittura'],'searchTable'=>false,'required'=>false,'type'=>'text','defValue'=>'none'),
	'created'						=> array(
				'label'				=>Core::$localStrings['creazione'],
				'searchTable'		=>false,
				'required'			=>false,
				'type'				=>'datatime',
				'defValue'			=>Core::$nowDateTime,
				'validate'			=>'datetimeiso'
	),
	'active'						=> array(
				'label'				=> Core::$localStrings['attivazione'],
				'required'			=> false,
				'type'				=> 'int|1',
				'defValue'			=> '0'
	)
	);	
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == Core::$localStrings['user'] ? true : false);
	
	$App->params->fields['item']['meta_title_'.$lang] = array('label'=>'Titolo META '.$lang,'searchTable'=>true,'required'=>false,'type'=>'varchar|255');
	$App->params->fields['item']['meta_description_'.$lang] = array('label'=>'Descrizione META '.$lang,'searchTable'=>true,'required'=>false,'type'=>'varchar|300');
	$App->params->fields['item']['meta_keyword_'.$lang] = array('label'=>'Keyword META '.$lang,'searchTable'=>true,'required'=>false,'type'=>'varchar|255');
	$App->params->fields['item']['title_seo_'.$lang] = array('label'=>'Titolo SEO '.$lang,'searchTable'=>true,'required'=>false,'type'=>'varchar|255');

	$App->params->fields['item']['title_'.$lang] = array('label'=>'Titolo '.$lang,'searchTable'=>true,'required'=>$required,'type'=>'varchar');
	}
	
/* BLOCKS */
$App->params->ordersType['iblo'] = 'ASC';
$App->params->uploadPaths['iblo'] = ADMIN_PATH_UPLOAD_DIR."pages/blocks/";
$App->params->uploadDirs['iblo'] = UPLOAD_DIR."pages/blocks/";
$App->params->ordersType['iblo'] = 'ASC';
$App->params->tables['iblo'] = $App->params->tableRif.'_blocks';
$App->params->fields['iblo'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'id_owner'=>array('label'=>'IDOwner','required'=>false,'searchTable'=>false,'type'=>'int'),
	'filename'=>array('label'=>'File','searchTable'=>false,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'org_filename'=>array('label'=>'Nome Originale','searchTable'=>true,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'url'					=> array('label'=>Core::$localStrings['url'],'searchTable'=>true,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'target'=>array('label'=>'Target','searchTable'=>true,'required'=>false,'type'=>'varchar|20','defValue'=>''),
	'ordering'=>array('label'=>Core::$localStrings['ordinamento'],'required'=>false,'type'=>'int|8','validate'=>'int','defValue'=>1),
	'created'=>array('label'=>Core::$localStrings['creazione'],'searchTable'=>false,'required'=>false,'type'=>'datatime','defValue'=>Core::$nowDateTime,'validate'=>'datetimeiso'),
	'active'=>array('label'=>'Attiva','required'=>false,'type'=>'int','validate'=>'int','defValue'=>'0')
);	
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == Core::$localStrings['user'] ? true : false);
	$App->params->fields['iblo']['title_'.$lang] = array('label'=>Core::$localStrings['titolo'].' '.$lang,'searchTable'=>true,'required'=>false,'type'=>'varchar');
	$App->params->fields['iblo']['content_'.$lang] = array('label'=>Core::$localStrings['contenuto'].' '.$lang,'searchTable'=>false,'required'=>false,'type'=>'longtext');
	$App->params->fields['iblo']['url_text_'.$lang] = array('label'=>Core::$localStrings['testo url'].' '.$lang,'searchTable'=>false,'required'=>false,'type'=>'varchar|255');
}
?>