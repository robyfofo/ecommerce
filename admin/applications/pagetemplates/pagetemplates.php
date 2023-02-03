<?php
/* admin/pagetemplates/items.php v.1.0.0. 16/02/2021 */

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {
	
	case 'moreOrderingItem':
		Utilities::increaseFieldOrdering($App->id,Core::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>0,'parentField'=>'','label'=>ucfirst(Core::$localStrings['voce']).' '.Core::$localStrings['spostato']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	case 'lessOrderingItem':
		Utilities::decreaseFieldOrdering($App->id,Core::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>0,'parentField'=>'','label'=>ucfirst(Core::$localStrings['voce']).' '.Core::$localStrings['spostato']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;

	case 'activeItem':
	case 'disactiveItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Core::$localStrings['voce'],'attivata'=>Core::$localStrings['attivato'],'disattivata'=>Core::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'deleteItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
			if (!isset($App->itemOld)) $App->itemOld = new stdClass;
			Sql::initQuery($App->params->tables['item'],array('filename'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
			if (Core::$resultOp->error == 0) {
				Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
				Sql::deleteRecord();
				if (Core::$resultOp->error == 0) {
					/* cancella il file vero e proprio */
					if (file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {			
						@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);			
						}
					Core::$resultOp->message = ucfirst(Core::$localStrings['voce cancellata']).'!';			
					}
				}
			}			
		$App->viewMethod = 'list';
	break;
	
	case 'predefinitoItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
			Sql::initQuery($App->params->tables['item'],array('predefinito'),array('0'));
			Sql::updateRecord();
			if (Core::$resultOp->error == 0) {
				Sql::initQuery($App->params->tables['item'],array('predefinito'),array('1',$App->id),'id = ?');
				Sql::updateRecord();
				if (Core::$resultOp->error == 0) {
					$_SESSION['message'] = '0|'.ucfirst(Core::$localStrings['voce predefinita']).'!';
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');			
				}
			}
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}

	break;
			
	case 'newItem':
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['inserisci %ITEM%'],Core::$localStrings['voce']);
		$App->viewMethod = 'formNew';
	break;
	
	case 'insertItem':
		if ($_POST) {
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','') + 1;
			/* preleva il filename dal form */	   	
	   	ToolsUpload::getFilenameFromForm();	   	
	   	$_POST['filename'] = ToolsUpload::getFilename();	
			/* parsa i post in base ai campi */
			Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
			if (Core::$resultOp->error == 0) {					
	   		/* sposto il file */
	   		if ($_POST['filename'] != '') {
	   			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['item'].$_POST['filename']) or die('Errore caricamento file');
	   			}		  	   			
	   		/* memorizza nel db */
	   		Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
	   		if (Core::$resultOp->error == 0) {
			   	}
				}	
			} else {	
				Core::$resultOp->error = 1;
				}			
		list($id,$App->viewMethod,$App->pageSubTitle,Core::$resultOp->message) = Form::getInsertRecordFromPostResults(0,Core::$resultOp,Core::$localStrings,array());
	break;
	
	case 'modifyItem':	
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['modifica %ITEM%'],Core::$localStrings['voce']);
		$App->viewMethod = 'formMod';
	break;
	
	case 'updateItem':
		if ($_POST) {
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','') + 1;
			if (!isset($App->itemOld)) $App->itemOld = new stdClass;
	   	Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
	   	/* preleva il filename dal form */	   	
	   	ToolsUpload::getFilenameFromForm();	   	
	   	$_POST['filename'] = ToolsUpload::getFilenameMd5();
	   	$uploadFilename = $_POST['filename'];	   	
	   	/* imposta il nomefile precedente se non si è caricata un immagine (serve per far passare il controolo campo immagine presente)*/	   	
	   	if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;	   	
			/* opzione cancella immagine */
	   	if (isset($_POST['deleteFile']) && $_POST['deleteFile'] == 1) {
	   		if (file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);
					$_POST['filename'] = '';
	   			$_POST['org_filename'] = ''; 		
					}	
	   		}	   	
			/* parsa i post in base ai campi */
			Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
			if (Core::$resultOp->error == 0) {					
	   		if ($uploadFilename != '') {
	   			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['item'].$uploadFilename) or die('Errore caricamento file');   			
	   			/* cancella l'immagine vecchia */
					if (file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {			
						@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);			
						}	   			
		   		}
	   		/* memorizza nel db */
	   		Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
	   		if (Core::$resultOp->error == 0) {		
					}		
				}		
			} else {					
				Core::$resultOp->error = 1;
				}
		list($id,$App->viewMethod,$App->pageSubTitle,Core::$resultOp->message) = Form::getUpdateRecordFromPostResults($App->id,Core::$resultOp,Core::$localStrings,array());	
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,Core::$request->action,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	
	case 'listItem':
	default;	
		$App->items = new stdClass;			
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);		
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		$and = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['item'],'');
			}		
		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
			}
		Sql::initQuery($App->params->tables['item'],$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);
		Sql::setResultPaged(true);	
		Sql::setOrder('ordering '.$App->params->ordersType['item']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();

		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Core::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);
		
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['voci'],Core::$localStrings['lista %ITEM%']);
		$App->viewMethod = 'list';		
	break;	
}

switch((string)$App->viewMethod) {
	case 'formNew':
		$App->item = new stdClass;	
		$App->item->created = $App->nowDateTime;
		$App->item->active = 1;
		$App->item->predefinito = 0;
		$App->item->filenameRequired = false;
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->templateApp = 'formPagetemplate.html';
		$App->methodForm = 'insertItem';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';	
	break;
	
	case 'formMod':
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->item->filenameRequired = (isset($App->item->filename) && $App->item->filename != '' ? false : false);
		$App->templateApp = 'formPagetemplate.html';
		$App->methodForm = 'updateItem';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';	
	break;

	default;			
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listItem.js"></script>';
		$App->templateApp = 'listPagetemplates.html';			
	break;	
	}
?>
