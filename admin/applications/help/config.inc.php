<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/help/config.inc.php v.4.5.1. 20/03/2020
*/

$App->params = new stdClass();
$App->params->label = 'Aiuto';
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('label','help_small','help'),array('help'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj; 

$App->params->codeVersion = ' 4.5.1.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPathDirs = array();
$App->params->uploadDirs = array();
$App->params->ordersType = array();

/* ITEM */
$App->params->ordersType['item'] = 'DESC';
$App->params->tables['item'] = Config::$dbTablePrefix.'help';
$App->params->fields['item'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'ordering'=>array('label'=>'Ordine','searchTable'=>false,'required'=>false,'type'=>'int|8'),
	'created'=>array('label'=>$localStrings['creazione'],'searchTable'=>false,'required'=>false,'type'=>'datatime','defValue'=>$App->nowDateTime,'validate'=>'datatimeiso'),
	'active'=>array('label'=>'Attiva','required'=>false,'type'=>'int|1','validate'=>'int','defValue'=>'0')
	);		
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == 'it' ? true : false);
	$App->params->fields['item']['title_'.$lang] = array('label'=>'Titolo '.$lang,'searchTable'=>true,'required'=>$required,'type'=>'text');
	$App->params->fields['item']['content_'.$lang] = array('label'=>$localStrings['contenuto'].'  '.$lang,'searchTable'=>true,'required'=>false,'type'=>'mediumtext');
	}
?>