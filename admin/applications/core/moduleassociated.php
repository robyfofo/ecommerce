<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/core/moduleassociated.php v.1.0.0. 03/03/2021
*/

//Sql::setDebugMode(1);

if (isset(Core::$request->method) && Core::$request->method == 'from') {
	$_SESSION['associatedModule']['ownerId'] = (isset(Core::$request->param) ? intval(Core::$request->param) : '');
	$_SESSION['associatedModule']['type'] = (isset(Core::$request->params[0]) ? intval(Core::$request->params[0]) : 0);
}

/*
echo '<br>module: '.$_SESSION['associatedModule']['module'];
echo '<br>returnmethod: '.$_SESSION['associatedModule']['returnMethod'];
echo '<br>rifDatabase: '.$_SESSION['associatedModule']['rifDatabase'];
echo '<br>ownerId: '.$_SESSION['associatedModule']['ownerId'];
echo '<br>type: '.$_SESSION['associatedModule']['type'];
*/

ToolsStrings::dump($_SESSION['associatedModule']);


if (!isset($_SESSION['associatedModule']['ownerId']) || (isset($_SESSION['associatedModule']['ownerId']) && $_SESSION['associatedModule']['ownerId'] == 0)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); die(); }

include_once(PATH.$App->pathApplicationsCore."class.module.php");
$Module = new Module(Config::$dbTablePrefix."module_associated");

// carica lingua e configurazione modulo
include_once(PATH.$App->pathApplications.$_SESSION['associatedModule']['module']."/lang/".$localStrings['user'].".inc.php");
include_once(PATH.$App->pathApplications.$_SESSION['associatedModule']['module']."/config.inc.php");
//ToolsStrings::dump($App->params);die();

$App->associatedTypefile = '';
if ($_SESSION['associatedModule']['type'] == 1) {
	$App->associatedTypefile = $localStrings['immagine'];
	$App->associatedTypefiles = $localStrings['immagini'];
}
if ($_SESSION['associatedModule']['type'] == 2) {
	$App->associatedTypefile = $localStrings['file'];
	$App->associatedTypefiles = $localStrings['files'];
}

//ToolsStrings::dump($_SESSION['associatedModule']);

/* variabili ambiente */
$App->codeVersion = ' 1.0.0.';
$App->pageTitle = 'Associazioni modulo';
$App->pageSubTitle = 'Associazioni: ';
$App->breadcrumb[] = '<li class="active"><i class="icon-user"></i> '.$App->pageTitle.'</li>';
$App->templateApp = Core::$request->action.'.html';
$App->id = intval(Core::$request->param);
if (isset($_POST['id'])) $App->id = intval($_POST['id']);
$App->coreModule = true;
$App->sessionName = 'moduleassociated';

//if (!isset($App->ownerData->id) || (isset($App->ownerData->id) && $App->ownerData->id == 0)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); die(); }

if ( isset($_POST['itemsforpage']) && 
	( !isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) || 
		( isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage'] )
	)
) 
{
	$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
}

if ( isset($_POST['searchFromTable']) && 
	( !isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) || 
		( isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable'] )
	)
) 
{
	$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);
}

if ($_SESSION['associatedModule']['ownerId'] > 0) {
	Sql::initQuery($App->params->tables[ $_SESSION['associatedModule']['rifParams'] ],array('*'),array($_SESSION['associatedModule']['ownerId']),'active = 1 AND id = ?');
	Sql::setOptions(array('fieldTokeyObj'=>'id'));
	$App->ownerData = Sql::getRecord();
	if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'/404'); die; }
	$field = 'title_'.$localStrings['user'];	
	$App->ownerData->title = $App->ownerData->$field;
	//ToolsStrings::dump($App->ownerData);
}

switch(Core::$request->method) {
	case 'moreOrderingItem':
		if ($App->id > 0) {
			Utilities::increaseFieldOrdering($App->id,$localStrings,array('table'=>$globalSettings['modules item resources table'],'orderingType'=>$globalSettings['modules item resources ordering'],'parent'=>1,'parentField'=>'module_table','label'=>ucfirst($App->associatedTypefile).' '.$localStrings['spostato/a']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	case 'lessOrderingItem':
		if ($App->id > 0) {
			Utilities::decreaseFieldOrdering($App->id,$localStrings,array('table'=>$globalSettings['modules item resources table'],'orderingType'=>$globalSettings['modules item resources ordering'],'parent'=>1,'parentField'=>'module_table','label'=>ucfirst($App->associatedTypefile).' '.$localStrings['spostato/a']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;

	case 'activeItem':
	case 'disactiveItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {		
			Sql::manageFieldActive(substr(Core::$request->method,0,-4),$globalSettings['modules item resources table'],$App->id,array('label'=>$App->associatedTypefile,'attivata'=>$localStrings['attivato/a'],'disattivata'=>$localStrings['disattivato/a']));
			$_SESSION['message'] = '0|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newItem':
		$App->item = new stdClass;	
		$App->item->active = 1;
		$App->pageSubTitle = preg_replace('/%ITEM%/',$App->associatedTypefile,$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertItem';
		$App->viewMethod = 'form';
	break;

	case 'insertItem':
		if ($_POST) 
		{
			//Sql::setDebugMode(1);
			$_POST['id_owner'] = $_SESSION['associatedModule']['ownerId'];
			$_POST['resource_type'] = $_SESSION['associatedModule']['type'];
			$_POST['module_name'] = $_SESSION['associatedModule']['module'];
			$_POST['module_table'] = $App->params->tables[ $_SESSION['associatedModule']['rifParams'] ];

			// gestione automatica dell'ordering de in input = 0
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) 
			{
				$_POST['ordering'] = Sql::getMaxValueOfField
				(
					$globalSettings['modules item resources table'],
					'id',
					"id_owner = ".intval($_POST['id_owner'])." AND module_table = '".$App->params->tables[ $_SESSION['associatedModule']['rifParams'] ]."'"
				) + 1;
			}

			ToolsUpload::setFilenameFormat($globalSettings['image type available']);
	   		ToolsUpload::getFilenameFromForm();
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}
			$_POST['filename'] = ToolsUpload::getFilenameMd5();
	   		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
			if ($_POST['filename'] != '') 
			{
				$_POST['size_file'] = ToolsUpload::getFileSize();
				$_POST['size_image'] = ToolsUpload::getImageSize();
				$_POST['extension'] = ToolsUpload::getFileExtension();
				$_POST['type'] = ToolsUpload::getFileType();  		
			}  

			// parsa i post in base ai campi
			Form::parsePostByFields($globalSettings['modules item resources fields'],$localStrings,array());
			if (Core::$resultOp->error > 0) { 
			 	$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				//die('errore form');
			 	ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}

			Sql::insertRawlyPost($globalSettings['modules item resources fields'],$globalSettings['modules item resources table']);
	   		if (Core::$resultOp->error > 0) 
			{ 
				die('errore inserimento record nel database');
				ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); 
			}
	   		
			$App->id = Sql::getLastInsertedIdVar(); /* preleva l'id della pagina */	 
			// sposto il file
			if ($_POST['filename'] != '') {
				move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths[ $_SESSION['associatedModule']['rifParams'] ].$_POST['filename']) or die('Errore caricamento file');
			}	
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$App->associatedTypefile,$localStrings['%ITEM% inserito/a'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
		die();
	break;

	case 'modifyItem':
		Sql::setDebugMode(1);
		$App->item = new stdClass;
		Sql::initQuery($globalSettings['modules item resources table'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }	

		$App->pageSubTitle = preg_replace('/%ITEM%/',$App->associatedTypefile,$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateItem';
		$App->viewMethod = 'form';
	break;

	case 'updateItem':
		if ($_POST) {
			Sql::setDebugMode(1);

			// preleva dati vecchio
			Sql::initQuery($globalSettings['modules item resources table'],array('*'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

			$_POST['id_owner'] = $_SESSION['associatedModule']['ownerId'];
			$_POST['resource_type'] = $_SESSION['associatedModule']['type'];
			$_POST['module_name'] = $_SESSION['associatedModule']['module'];
			$_POST['module_table'] = $App->params->tables[ $_SESSION['associatedModule']['rifParams'] ];

			// gestione automatica dell'ordering de in input = 0
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) 
			{
				$_POST['ordering'] = Sql::getMaxValueOfField
				(
					$globalSettings['modules item resources table'],
					'id',
					"id_owner = ".intval($_POST['id_owner'])." AND module_table = '".$App->params->tables[ $_SESSION['associatedModule']['rifParams'] ]."'"
				) + 1;
			}

			//preleva il filename dal form
			ToolsUpload::setFilenameFormat($globalSettings['image type available']);	
			ToolsUpload::getFilenameFromForm();
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			}
			$_POST['filename'] = ToolsUpload::getFilenameMd5();
	   		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
			if ($_POST['filename'] != '') 
			{
				$_POST['size_file'] = ToolsUpload::getFileSize();
				$_POST['size_image'] = ToolsUpload::getImageSize();
				$_POST['extension'] = ToolsUpload::getFileExtension();
				$_POST['type'] = ToolsUpload::getFileType();  		
			}	   		   	
			$uploadFilename = $_POST['filename'];
			// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
			if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;
			if ($_POST['org_filename'] == '' && $App->itemOld->org_filename != '') $_POST['org_filename'] = $App->itemOld->org_filename;	 
			// opzione cancella immagine
			if (isset($_POST['deleteFile']) && $_POST['deleteFile'] == 1) {
			   if (file_exists($App->params->uploadPaths['prod'].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths['prod'].$App->itemOld->filename);	
				}	
				$_POST['filename'] = '';
			   	$_POST['org_filename'] = ''; 
				$_POST['size_file'] = '';
				$_POST['size_image'] = '';
				$_POST['extension'] = '';
				$_POST['type'] = '';
			}
			  
			// parsa i post in base ai campi
			Form::parsePostByFields($globalSettings['modules item resources fields'],$localStrings,array());
			if (Core::$resultOp->error > 0) { 
			 	$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				//die('errore form');
			 	ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifiItem');
			}

			Sql::updateRawlyPost($globalSettings['modules item resources fields'],$globalSettings['modules item resources table'],'id',$App->id);
			if (Core::$resultOp->error > 0) 
			{ 
				die('errore modifica record nel database');
				ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); 
			}

			if ($uploadFilename != '') {
				move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths[ $_SESSION['associatedModule']['rifParams'] ].$uploadFilename) or die('Errore caricamento file');   			
			   	// cancella l'immagine vecchia
				if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths[ $_SESSION['associatedModule']['rifParams'] ].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths[ $_SESSION['associatedModule']['rifParams'] ].$App->itemOld->filename);			
				}
			}

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$App->associatedTypefile,$localStrings['%ITEM% modificato/a'])).'!';
			if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			} else {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/lisItem');
			}		

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	default;	
		//Sql::setDebugMode(1);
		$App->items = new stdClass;
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = 'resource_type = 1';
		$and = ' AND ';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$globalSettings['modules_item_resources fields'],'');
		}	
		if ($_SESSION['associatedModule']['ownerId'] > 0) {
			$clause .= $and."id_owner = ?";
			$qryFieldsValues[] = $_SESSION['associatedModule']['ownerId'];
			$and = ' AND ';
		}	
		if ($_SESSION['associatedModule']['module'] != '') {
			$clause .= $and."module_table = ?";
			$qryFieldsValues[] = $App->params->tables[ $_SESSION['associatedModule']['rifParams'] ];
			$and = ' AND ';
		}	
		//ToolsStrings::dump($qryFieldsValues);

		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
		}
		Sql::initQuery($globalSettings['modules item resources table'],$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('ordering '.$globalSettings['modules item resources ordering']);
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$field = 'title_'.$localStrings['user'];	
				$value->title = $value->$field;
				$arr[] = $value;
			}
		}
		$App->items = $arr;
		
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = ucfirst($localStrings['mostra da %START% a %END% di %ITEM% elementi']);
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = $App->pageSubTitle .= preg_replace('/%ITEM%/',$App->associatedTypefiles,$localStrings['lista delle %ITEM%']);;
		$App->viewMethod = 'list';
	break;		
}

switch((string)$App->viewMethod) {
	case 'form':
		$App->templateApp = 'moduleassociated-form.html';
	break;
	default;	
	case 'list':
		$App->templateApp = 'moduleassociated-list.html';	
	break;		

}
?>