<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/levels/config.inc.php v.4.5.1. 06/05/2020
*/

//Core::setDebugMode(1);

$App->params = new stdClass();
$App->params->label = "Livelli utente";
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('label','help_small','help'),array('levels'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPathDirs = array();
$App->params->uploadDirs = array();
$App->params->ordersType = array();

// prende id dell home
$App->module_home_id = 3;


/* variabili ambiente */
$App->params->codeVersion = ' 4.5.1.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="active"><i class="icon-user"></i> '.$App->params->label.'</li>';
$App->params->tables['item'] = Config::$dbTablePrefix.'levels';
$App->params->fields['item'] = array(
	'id'=>array('label'=>'ID','required'=>false,'type'=>'int|8','autoinc'=>true,'primary'=>true),
	'title'=>array('label'=>Core::$localStrings['titolo'],'searchTable'=>true,'required'=>true,'type'=>'varchar|100'),
	'active'=>array('label'=>Core::$localStrings['attiva'],'required'=>false,'type'=>'int|8','defValue'=>0)
);

$App->params->tables['ass-item'] = Config::$dbTablePrefix.'modules_levels_access';
?>