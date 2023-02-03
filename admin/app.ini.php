<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/app.init.php v.1.0.0. 05/07/2021
*/

// modules item resources
$globalSettings['modules item resources ordering'] = 'ASC';
$globalSettings['modules item resources table'] = Config::$dbTablePrefix.'modules_resources';
$globalSettings['modules item resources fields'] = array (
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>true,'primary'=>true),
	'id_owner'=>array('label'=>'IDOwner','required'=>true,'searchTable'=>false,'type'=>'int|8'),
	'resource_type'=>array('label'=>'Type resource','required'=>true,'searchTable'=>false,'type'=>'int|8'),
	'filename'=>array('label'=>'File','searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
	'org_filename'=>array('label'=>'Nome Originale','searchTable'=>false,'required'=>false,'type'=>'varchar|255'),
	'extension'=>array('label'=>'Ext','searchTable'=>false,'required'=>false,'type'=>'varchar|40','defValue'=>''),
	'code'=>array('label'=>'Code','searchTable'=>false,'required'=>false,'type'=>'text','defValue'=>''),
	'size_file'=>array('label'=>'Dimensione','searchTable'=>false,'required'=>false,'type'=>'varchar|20','defValue'=>''),
	'size_image'=>array('label'=>'Dimensione','searchTable'=>false,'required'=>false,'type'=>'varchar|40','defValue'=>''),
	'type'=>array('label'=>'Tipo','searchTable'=>true,'required'=>false,'type'=>'varchar|100','defValue'=>''),
	'url_code'=>array('label'=>'Codice Url','searchTable'=>false,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'url_image'=>array('label'=>'Image Url','searchTable'=>false,'required'=>false,'type'=>'varchar|255','defValue'=>''),
	'module_name'=>array('label'=>'Modulo','searchTable'=>true,'required'=>false,'type'=>'varchar|255'),
	'module_table'=>array('label'=>'Tabella','searchTable'=>true,'required'=>false,'type'=>'varchar|255'),
	'ordering'=>array('label'=>Config::$localStrings['ordinamento'],'required'=>false,'type'=>'int|8','validate'=>'int'),
	
	'created'					=> array('label'=>Config::$localStrings['creazione'],'searchTable'=>false,'required'=>false,'type'=>'datatime','defValue'=>Config::$nowDateTimeIso,'validate'=>'datetimeiso'),
	'active'					=> array('label'=>Config::$localStrings['attiva'],'required'=>false,'type'=>'int','validate'=>'int|1','defValue'=>'0')

);
foreach($globalSettings['languages'] AS $lang) {
	$searchTable = true;
	$required = ($lang == Config::$localStrings['user'] ? true : false);
	$globalSettings['modules item resources fields']['title_'.$lang] = array('label'=>'Titolo '.$lang,'searchTable'=>$searchTable,'required'=>$required,'type'=>'varchar|255');
	$globalSettings['modules item resources fields']['content_'.$lang] = array('label'=>Config::$localStrings['contenuto'].'  '.$lang,'searchTable'=>true,'required'=>false,'type'=>'text','defValue'=>'');	
}


$App->breadcrumbHtml = '<li class="breadcrumb-item"><a href="'.URL_SITE.'">Home</a></li>';

$App->breadcrumb = array();
$App->breadcrumb[] = array('name'=>'home','level'=>0);
?>