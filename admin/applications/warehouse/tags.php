<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/tags.php v.1.0.0. 30/03/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {	
	case 'moreOrderingTags':
		if ($App->id > 0) {
			Utilities::increaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['tags'],'orderingType'=>$App->params->ordersType['tags'],'parent'=>0,'parentField'=>'','label'=>ucfirst(Config::$localStrings['tag']).' '.Config::$localStrings['spostato']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;

	case 'lessOrderingTags':
		if ($App->id > 0) {
			Utilities::decreaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['tags'],'orderingType'=>$App->params->ordersType['tags'],'parent'=>0,'parentField'=>'','label'=>ucfirst(Config::$localStrings['tag']).' '.Config::$localStrings['spostato']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;

	case 'activeTags':
	case 'disactiveTags':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['tags'],$App->id,array('label'=>Config::$localStrings['tag'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');		
	break;
	
	case 'deleteTags':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		Sql::initQuery($App->params->tables['tags'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['tag'],Config::$localStrings['%ITEM% cancellato'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');
	break;
	
	case 'newTags':		
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->ordering = 0;
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['tag'],Config::$localStrings['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertTags';
	break;
	
	case 'insertTags':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }

		// gestione automatica dell'ordering se in input = 0	
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['tags'],'ordering','') + 1;
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['tags'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);die();
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newTags');
		}

		Sql::insertRawlyPost($App->params->fields['tags'],$App->params->tables['tags']);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['tag'],Config::$localStrings['%ITEM% inserito'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');				

	break;
	
	case 'modifyTags':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }			
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['tags'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['tag'],Config::$localStrings['modifica %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'updateTags';	
	break;
		
	case 'updateTags':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }
			
		// gestione automatica dell'ordering se in input = 0
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['tags'],'ordering','') + 1;	
		
		// requpero i vecchi dati
		$App->oldTags = new stdClass;
		Sql::initQuery($App->params->tables['tags'],array('*'),array($App->id),'id = ?');
		$App->oldTags = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		Form::parsePostByFields($App->params->fields['tags'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyTags/'.$App->id);
		}

		Sql::updateRawlyPost($App->params->fields['tags'],$App->params->tables['tags'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['tag'],Config::$localStrings['%ITEM% modificato'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyTags/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');
		}

	break;

	case 'pageTags':	
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listTags');
		die();
	break;
				
	case 'listTags':
	default:
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields = array('ite.*');
			
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		$and = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['tags'],'');
			}	
		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
			}
		Sql::initQuery($App->params->tables['tags']." AS ite",$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('ordering '.$App->params->ordersType['tags']);
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$field = 'title_'.Config::$localStrings['user'];	
				$value->title = $value->$field;	
				$arr[] = $value;
			}
		}
		$App->items = $arr;
		
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = ucfirst(Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi']);
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);	
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['tags'],Config::$localStrings['lista %ITEM%']);		
		$App->viewMethod = 'list';	
	break;
}

switch((string)$App->viewMethod) {
	case 'form':	
		$App->templateApp = 'formTag.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/formTag.js"></script>';
	break;
	
	default:
	case 'list':
		$App->templateApp = 'listTags.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/listTags.js"></script>';
	break;
}	
?>