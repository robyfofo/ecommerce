<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/slides-home-rev/layers.php v.1.0.0. 20/03/2021
*/

//Core::setDebugMode(1);

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

if (Core::$request->method == 'listLaye' && $App->id > 0) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'slide_id',$App->id);

if (isset($_POST['slide_id']) && isset($_MY_SESSION_VARS[$App->sessionName]['slide_id']) && $_MY_SESSION_VARS[$App->sessionName]['slide_id'] != $_POST['slide_id']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'slide_id',$_POST['slide_id']);
$App->slide_id = (isset($_MY_SESSION_VARS[$App->sessionName]['slide_id']) ? $_MY_SESSION_VARS[$App->sessionName]['slide_id'] : 0);

//echo 'slide_id : '.$App->slide_id;
if ($App->slide_id > 0) {
	Sql::initQuery($App->params->tables['item'],array('*'),array($App->slide_id),'active = 1 AND id = ?');
	Sql::setOptions(array('fieldTokeyObj'=>'id'));
	$App->ownerData = Sql::getRecord();
	if (Core::$resultOp->error > 0) {echo Core::$resultOp->message; die;}
}
//ToolsStrings::dump($App->ownerData);

if (!isset($App->ownerData->id) || (isset($App->ownerData->id) && $App->ownerData->id == 0)) {
	$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['Devi attivare la %ITEM% padre!']));
	ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
}
	
$App->pageSubTitle = Config::$localStrings['voce'].' '.$App->ownerData->title.': ';

switch(Core::$request->method) {
	case 'moreOrderingLaye':
		Utilities::increaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['laye'],'orderingType'=>$App->params->ordersType['laye'],'parent'=>1,'parentField'=>'slide_id','label'=>ucfirst(Config::$localStrings['layer']).' '.Config::$localStrings['spostato']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');
	break;
	case 'lessOrderingLaye':
		Utilities::decreaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['laye'],'orderingType'=>$App->params->ordersType['laye'],'parent'=>1,'parentField'=>'slide_id','label'=>ucfirst(Config::$localStrings['layer']).' '.Config::$localStrings['spostato']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');
	break;

	case 'activeLaye':
	case 'disactiveLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['laye'],$App->id,array('label'=>Config::$localStrings['layer'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');	
	break;
	
	case 'deleteLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
			
			$App->itemOld = new stdClass;
			Sql::initQuery($App->params->tables['laye'],array('filename'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

			Sql::initQuery($App->params->tables['laye'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

			if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['laye'].$App->itemOld->filename)) {
				@unlink($App->params->uploadPaths['laye'].$App->itemOld->filename);			
			}

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['layer'],Config::$localStrings['%ITEM% cancellato'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;
	
	case 'newLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;	
		$App->item->created = Config::$nowDateTime;	
		$App->item->active = 1;
		$App->item->ordering = Sql::getMaxValueOfField($App->params->tables['laye'],'ordering','') + 1;		
		$App->item->filenameRequired = false;
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['laye']);
		$App->pageSubTitle .= preg_replace('/%ITEM%/',Config::$localStrings['layer'],Config::$localStrings['nuovo %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertLaye';
	break;
	
	case 'insertLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($_POST) {	
			//Sql::setDebugMode(1);

			if (!isset($_POST['active'])) $_POST['active'] = 0;
			if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == '')) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['laye'],'ordering','slide_id = '.$App->slide_id) + 1;

			// preleva il filename dal form
			ToolsUpload::setFilenameFormat($globalSettings['image type available']);
		   	ToolsUpload::getFilenameFromForm();
		   	$_POST['filename'] = ToolsUpload::getFilenameMd5();
		   	$_POST['org_filename'] = ToolsUpload::getOrgFilename();
			if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newLaye');
	   		}
			
			//ToolsStrings::dump($_POST);
			
			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['laye'],Config::$localStrings,array());
			if (Core::$resultOp->error > 0) { 
				echo $_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newLaye');
			}
			
			//ToolsStrings::dump($_POST);
			
			Sql::insertRawlyPost($App->params->fields['laye'],$App->params->tables['laye']);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			//die('fatto');

			// sposto il file
			if ($_POST['filename'] != '') {
				move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['laye'].$_POST['filename']) or die('Errore caricamento file');
			}	
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['layer'],Config::$localStrings['%ITEM% inserito']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');
		
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}

	break;

	case 'modifyLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['laye'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();		
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['laye']);
		$App->item->filenameRequired = (isset($App->item->filename) && $App->item->filename != '' ? false : false);
		$App->pageSubTitle .= preg_replace('/%ITEM%/',Config::$localStrings['layer'],Config::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateLaye';
		$App->viewMethod = 'form';
	break;
	
	case 'updateLaye':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($_POST) {	
			
			if (!isset($App->itemOld)) $App->itemOld = new stdClass;
			if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
			if (!isset($_POST['active'])) $_POST['active'] = 0;			
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == '')) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['laye'],'ordering','slide_id = '.$App->slide_id) + 1;

			// preleva filename vecchio
			Sql::initQuery($App->params->tables['laye'],array('filename','org_filename'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			// preleva il filename dal form
			ToolsUpload::setFilenameFormat($globalSettings['image type available']);	
			ToolsUpload::getFilenameFromForm();
			if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyLaye');
	   		}
	   		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		   	$_POST['org_filename'] = ToolsUpload::getOrgFilename(); 		   		   	
			$uploadFilename = $_POST['filename'];
			
			// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
			if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;
			if ($_POST['org_filename'] == '' && $App->itemOld->org_filename != '') $_POST['org_filename'] = $App->itemOld->org_filename;

			// opzione cancella immagine
		   	if (isset($_POST['deleteFile']) && $_POST['deleteFile'] == 1) {
		   		if (file_exists($App->params->uploadPaths['laye'].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths['laye'].$App->itemOld->filename);	
				}	
				$_POST['filename'] = '';
		   		$_POST['org_filename'] = ''; 	
		   	}	   	

			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['laye'],Config::$localStrings,array());
			if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newLaye');
			}

			Sql::updateRawlyPost($App->params->fields['laye'],$App->params->tables['laye'],'id',$App->id);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			if ($uploadFilename != '') {
   				move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['laye'].$uploadFilename) or die('Errore caricamento file');   			
   				// cancella l'immagine vecchia 
				if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['laye'].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths['laye'].$App->itemOld->filename);			
				}	   			
   			}

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['layer'],Config::$localStrings['%ITEM% modificato']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');
				
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;
	
	case 'pageLaye':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listLaye');
	break;

	case 'listLaye':
	default;
		$App->items = new stdClass;
		$App->item = new stdClass;		
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);				
		$qryFields = array();
		$qryFields[] = 'ite.*';		
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		$and = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['laye'],'');
		}	
			
		if ($App->slide_id > 0) {
			$clause .= "slide_id = ?";
			$qryFieldsValues[] = $App->slide_id;
			$and = ' AND ';
		}		

		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
		}
		Sql::initQuery($App->params->tables['laye']." AS ite",$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('ite.ordering '.$App->params->ordersTypes['laye']);
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$field = 'content_'.Config::$localStrings['user'];
				if (isset($value->$field)) { $value->content = $value->$field; } else { $value->content = ''; }

				$arr[] = $value;
				}
			}
		$App->items = $arr;
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle .=  preg_replace('/%ITEM%/',Config::$localStrings['layers'],Config::$localStrings['lista degli %ITEM%']);	
		$App->viewMethod = 'list';	
	break;	
}

switch((string)$App->viewMethod) {
	case 'form':
		$App->templateApp = 'formLayer.html';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formLayer.js"></script>';
	break;
	
	case 'list':
	default:
		$App->templateApp = 'listLayers.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listLayers.js"></script>';
	break;
}	
?>