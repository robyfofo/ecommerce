<?php
// app/location/config.inc.php v.1.3.0. 18/09/2020

$App->params = new stdClass();
/* prende i dati del modulo */
Sql::initQuery(Config::$dbTablePrefix.'modules',array('section','name','label','help_small','help'),array('location'),'name = ?');
$obj = Sql::getRecord();
if (Core::$resultOp->error == 0 && isset($obj) && count((array)$obj) > 1) $App->params = $obj;
if (!isset($App->params->label) || (isset($App->params->label) && $App->params->label == '')) die('Error reading module settings!');

$App->params->tables = array();
$App->params->fields = array();
$App->params->uploadPaths = array();
$App->params->uploadDirs = array();
$App->params->orderTypes = array();

$App->params->codeVersion = ' 1.3.0.';
$App->params->pageTitle = $App->params->label;
$App->params->breadcrumb = '<li class="breadcrumb-item active"><strong>'.$App->params->label.'</strong></li>';

$App->params->tableRif =  Config::$dbTablePrefix.'location';

// nazioni
$App->params->orderTypes['nations'] = 'ASC';
$App->params->tables['nations'] = $App->params->tableRif.'_nations';
$App->params->fields['nations'] = array(
	'id'			=> array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'targa'			=> array('label'=>$localStrings['targa'],'searchTable'=>true,'required'=>true,'type'=>'varchar|8','defValue'=>''),
	'active'		=> array('label'=>$localStrings['attiva'],'required'=>false,'type'=>'int|1','defValue'=>0)
);		
foreach($globalSettings['languages'] AS $lang) {
	$required = ($lang == $localStrings['user'] ? true : false);
	$App->params->fields['nations']['title_'.$lang] = array('label'=>$localStrings['titolo'].' '.$lang,'searchTable'=>true,'required'=>$required,'type'=>'varchar');
}

// province
$App->params->orderTypes['province'] = 'ASC';
$App->params->tables['province'] = $App->params->tableRif.'_province';
$App->params->fields['province'] = array(
	'id'			=> array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'nome'			=> array('label'=>$localStrings['nome'],'searchTable'=>true,'required'=>true,'type'=>'varchar|255','defValue'=>''),
	'targa'			=> array('label'=>$localStrings['targa'],'searchTable'=>true,'required'=>true,'type'=>'varchar|50','defValue'=>''),
	'active'		=> array('label'=>$localStrings['attiva'],'required'=>false,'type'=>'int|1','defValue'=>0)
);

// comuni
$App->params->orderTypes['comuni'] = 'ASC';
$App->params->tables['comuni'] = $App->params->tableRif.'_comuni';
$App->params->fields['comuni'] = array(
	'id'					=> array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'nome'					=> array('label'=>$localStrings['nome'],'searchTable'=>true,'required'=>true,'type'=>'varchar|255','defValue'=>''),
	'cap'					=> array('label'=>$localStrings['cap'],'searchTable'=>true,'required'=>true,'type'=>'varchar|50','defValue'=>''),

	'provincia'				=> array('label'=>$localStrings['provincia'],'searchTable'=>false,'required'=>false,'type'=>'varchar|150','defValue'=>''),
	'location_province_id'	=> array('label'=>$localStrings['provincia'],'searchTable'=>false,'required'=>false,'type'=>'int|10','defValue'=>0),

	'nation'				=> array('label'=>$localStrings['nazione'],'searchTable'=>true,'required'=>false,'type'=>'varchar|150','defValue'=>''),
	'location_nations_id'	=> array('label'=>$localStrings['nazione'],'searchTable'=>false,'required'=>false,'type'=>'int|10','defValue'=>0),

	'active'		=> array('label'=>$localStrings['attiva'],'required'=>false,'type'=>'int|1','defValue'=>0)
);
?>