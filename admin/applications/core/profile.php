<?php
/* wscms/core/profile.php v.3.5.4. 31/07/2019 */

//Sql::setDebugMode(1);

include_once(PATH.$App->pathApplicationsCore."class.module.php");
$Module = new Module(Config::$dbTablePrefix."users");

/* variabili ambiente */
$App->codeVersion = ' 3.5.4.';
$App->pageTitle = ucfirst(Core::$localStrings['profilo']);
$App->pageSubTitle = preg_replace('/%ITEM%/', Core::$localStrings['profilo'], Core::$localStrings['modifica il %ITEM%']);
$App->breadcrumb[] = '<li class="active"><i class="icon-user"></i> '.Core::$localStrings['profilo'].'</li>';
$App->templateApp = Core::$request->action.'.html';
$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);
$App->coreModule = true;

$App->params = new stdClass();

$App->params->tables['item'] = Config::$dbTablePrefix.'users';
$App->params->fields['item'] = array(
	'id'						=> array('label'=>'ID','required'=>false,'type'=>'autoinc','primary'=>true),
	'name'						=> array('label'=>Core::$localStrings['nome'],'searchTable'=>true,'required'=>false,'type'=>'varchar'),
	'surname'					=> array('label'=>Core::$localStrings['cognome'],'searchTable'=>true,'required'=>false,'type'=>'varchar'),
	'street'					=> array('label'=>Core::$localStrings['via'],'searchTable'=>false,'required'=>false,'type'=>'varchar'),
	'location_comuni_id'		=> array('label'=>Core::$localStrings['comune'],'searchTable'=>false,'required'=>false,'type'=>'int|10','defValue'=>0),
	'comune_alt'				=> array('label'=>Core::$localStrings['altro comune'],'searchTable'=>false,'required'=>false,'type'=>'varchar|150'),
	'zip_code'					=> array('label'=>Core::$localStrings['c.a.p.'],'searchTable'=>false,'required'=>false,'type'=>'varchar'),
	'location_province_id'		=> array('label'=>Core::$localStrings['provincia'],'searchTable'=>false,'required'=>false,'type'=>'int|10','defValue'=>0),
	'provincia_alt'				=> array('label'=>Core::$localStrings['altra provincia'],'searchTable'=>true,'required'=>false,'type'=>'varchar|150','defValue'=>''),
	'location_nations_id'		=> array('label'=>Core::$localStrings['nazione'],'searchTable'=>false,'required'=>false,'type'=>'int|10','defValue'=>0),
	'telephone'					=> array('label'=>Core::$localStrings['telefono'],'searchTable'=>false,'required'=>false,'type'=>'varchar'),
	'email'						=> array('label'=>Core::$localStrings['email'],'searchTable'=>true,'required'=>true,'type'=>'varchar'),
	'mobile'					=> array('label'=>Core::$localStrings['mobile'],'searchTable'=>true,'required'=>false,'type'=>'varchar'),
	'fax'						=> array('label'=>Core::$localStrings['fax'],'searchTable'=>true,'required'=>false,'type'=>'varchar'),
	'skype'						=> array('label'=>Core::$localStrings['skype'],'searchTable'=>true,'type'=>'varchar'),
	'avatar'					=> array('label'=>Core::$localStrings['avatar'],'searchTable'=>false,'type'=>'blob'),
	'avatar_info'				=> array('label'=>Core::$localStrings['avatar info'],'searchTable'=>false,'type'=>'varchar'),
	'template'					=> array('label'=>'template','searchTable'=>false,'type'=>'varchar|100'),
);
$App->params->tables['nations'] = Config::$dbTablePrefix.'location_nations';
$App->params->tables['province'] = Config::$dbTablePrefix.'location_province';
$App->params->tables['comuni'] = Config::$dbTablePrefix.'location_comuni';

$App->province = new stdClass;
Sql::initQuery($App->params->tables['province'],array('*'),array(),'active = 1','nome ASC');
$App->province = Sql::getRecords();
if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

$App->nations = new stdClass;
Sql::initQuery($App->params->tables['nations'],array('*'),array(),'active = 1','title_'.Core::$localStrings['user'].' ASC');
$App->nations = Sql::getRecords();
if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

                                 
switch(Core::$request->method) {
	case 'renderAvatarDB':
		$id = intval(Core::$request->param);
		$App->item = new stdClass;
	   if ($id > 0) {	
			Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),"id = ?");
			$App->item = Sql::getRecord();	
			if (Core::$resultOp->error == 0) {	
				if (isset($App->item->avatar)) {
					$array_avatarInfo = unserialize($App->item->avatar_info);					
					$img = $App->item->avatar;
					@header ("Content-type: ".$array_avatarInfo['type']);
					echo $img;				
				}
			}
		}
		die();
	break;
	
	default;
		$App->item = new stdClass;
		if ($App->id > 0) {

			if ($_POST) {

				//Core::setDebugMode(1);

				if (!isset($_POST['location_comuni_id']) || (isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] == '')) $_POST['location_comuni_id'] = 0;
				if (!isset($_POST['location_province_id']) || (isset($_POST['location_province_id']) && $_POST['location_province_id'] == '')) $_POST['location_province_id'] = 0;
				if (!isset($_POST['location_nations_id']) || (isset($_POST['location_nations_id']) && $_POST['location_nations_id'] == '')) $_POST['location_nations_id'] = 0;
	
				if ( isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] > 0 ) $_POST['comune_alt'] = '';
				if ( isset($_POST['location_province_id']) && $_POST['location_province_id'] > 0 ) $_POST['provincia_alt'] = '';	

				if (!isset($_POST['active'])) $_POST['active'] = 1;								
				/* recupero dati avatar */
				list($_POST['avatar'],$_POST['avatar_info']) = $Module->getAvatarData($App->id,Core::$localStrings);
				if ($Module->message != '') Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				if (Core::$resultOp->error == 0) {

					// parsa i post in base ai campi
					Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
					if (Core::$resultOp->error > 0) {
						$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
						ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
					}

					Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
					if (Core::$resultOp->error > 0) { die('errore modifica record');ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

					Core::$resultOp->message = Core::$localStrings['Account modificato correttamente! Per rendere effettive le modifiche devi uscire dal sistema e loggarti nuovamente.'];

				} else {
					Core::$resultOp->error = 1;
				}	
			} 
								
			/* recupera i dati memorizzati */
			/* (tabella,campi(array),valori campi(array),where clause, limit, order, option , pagination(default false)) */
			Sql::initQuery(Config::$dbTablePrefix.'users',array('*'),array($App->id),"id = ?");
			$App->item = Sql::getRecord();			
			$App->templatesAvaiable = $Module->getUserTemplatesArray();
			if($Module->error == 1) {	
				Core::$resultOp->error = 1;
				Core::$resultOp->message = $Module->message;
			}

			$App->comune = new stdClass;
			$App->comune->selected = new stdClass;	
			$App->comune->selected->id = 0;
			if (isset($App->item->location_comuni_id) && $App->item->location_comuni_id > 0) {
				$App->comune->selected->id = $App->item->location_comuni_id;
			}

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN."home");
			die();						
		}
	break;	
}

$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">';
$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>';

$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/css/ajax-bootstrap-select.min.css" rel="stylesheet">';

$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/js/ajax-bootstrap-select.min.js"></script>';
$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/ajax-bootstrap-select/js/locale/ajax-bootstrap-select.'.Core::$localStrings['charset'].'.min.js"></script>';

$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplicationsCore.'templates/'.$App->templateUser.'/js/profile.js"></script>';
?>