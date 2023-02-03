<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/pages/blocks.php v.1.0.0. 23/03/2021
*/

if (isset($_POST['itemsforpage'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);
if (isset($_POST['id_owner'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'id_owner',$_POST['id_owner']);

if (Core::$request->method == 'listIblo' && $App->id > 0) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'id_owner',$App->id);

/* gestione sessione -> id_owner */	
$App->id_owner = (isset($_MY_SESSION_VARS[$App->sessionName]['id_owner']) ? $_MY_SESSION_VARS[$App->sessionName]['id_owner'] : 0);

//echo $App->id_owner;

if ($App->id_owner > 0) {
	Sql::initQuery($App->params->tables['item'],array('*'),array($App->id_owner),'id = ?');
	Sql::setOptions(array('fieldTokeyObj'=>'id'));
	$App->ownerData = Sql::getRecord();
	if (Core::$resultOp->error > 0) {echo Core::$resultOp->message; die;}
	$field = 'title_'.$localStrings['user'];	
	$App->ownerData->title = $App->ownerData->$field;
	}
	
//print_r($App->ownerData);

if (Core::$resultOp->error > 0) {echo Core::$resultOp->message; die;}
if (!isset($App->ownerData->id) || (isset($App->ownerData->id) && $App->ownerData->id == 0)) {
	//ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/messageItem/2/'.urlencode($localStrings['Devi creare o attivare almeno una pagina!']));
	//die();
	}

switch(Core::$request->method) {
	
	case 'moreOrderingIblo':
		Utilities::increaseFieldOrdering($App->id,$localStrings,array('table'=>$App->params->tables['iblo'],'orderingType'=>$App->params->ordersType['iblo'],'parent'=>1,'parentField'=>'id_owner','label'=>ucfirst($localStrings['voce-b']).' '.$localStrings['spostato']));
		$_SESSION['message'] =  Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');
	break;
	case 'lessOrderingIblo':
		Utilities::decreaseFieldOrdering($App->id,$localStrings,array('table'=>$App->params->tables['iblo'],'orderingType'=>$App->params->ordersType['iblo'],'parent'=>1,'parentField'=>'id_owner','label'=>ucfirst($localStrings['voce-b']).' '.$localStrings['spostato']));
		$_SESSION['message'] =  Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');
	break;

	case 'activeIblo':
	case 'disactiveIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['iblo'],$App->id,array('label'=>$localStrings['voce-b'],'attivata'=>$localStrings['attivato'],'disattivata'=>$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');	
	break;
		
	case 'deleteIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
		$delete = true;					
		if ($delete == true && Core::$resultOp->error == 0) {					
			$App->itemOld = new stdClass;
			Sql::initQuery($App->params->tables['iblo'],array('filename'),array($App->id),'id = ?');
			$App->itemOld = Sql::getRecord();
			if (Core::$resultOp->error == 0) {
				Sql::initQuery($App->params->tables['iblo'],array('id'),array($App->id),'id = ?');
				Sql::deleteRecord();
				if (Core::$resultOp->error == 0) {
					$_SESSION['message'] = '0|'. ucfirst(preg_replace('/%ITEM%/',$localStrings['voce-b'],$localStrings['%ITEM% cancellato'])).'!';
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');		
					}
				}
			}
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newIblo':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		$App->item = new stdClass;	
		$App->item->active = 1;
		$App->item->ordering = 0;
		$App->item->filenameRequired = false;
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['iblo']);
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['voce-b'],$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertIblo';	
		$App->viewMethod = 'form';	
	break;
	
	case 'insertIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
	   	if ($_POST) {
			/* gestione automatica dell'ordering de in input = 0 */
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && intval($_POST['ordering'])  == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['iblo'],'ordering','id_owner = '.$App->id_owner) + 1;
		
			//  parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['iblo'],$localStrings,array());
			//ToolsStrings::dump($_POST);die();
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newIblo');		
			}

			Sql::insertRawlyPost($App->params->fields['iblo'],$App->params->tables['iblo']);
			if (Core::$resultOp->error > 0) { die();ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['voce-b'],$localStrings['%ITEM% inserito'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');				
			

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}		
	break;

	case 'modifyIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		$App->item = new stdClass;	
		Sql::initQuery($App->params->tables['iblo'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['iblo']);
		$App->item->filenameRequired = (isset($App->item->filename) && $App->item->filename != '' ? false : false);
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['voce-b'],$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateIblo';
		$App->viewMethod = 'form';	
	break;

	case 'updateIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($_POST) {

			$App->itemOld = new stdClass;
			
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == '')) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['iblo'],'ordering','id_owner = '.$App->id_owner) + 1;

	   		// requpero i vecchi dati
			$App->oldItem = new stdClass;
			Sql::initQuery($App->params->tables['iblo'],array('*'),array($App->id),'id = ?');
			$App->oldItem = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }

			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['iblo'],$localStrings,array());
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyIblo/'.$App->id);			
			}
			
			// aggiorna record nel db 
			Sql::updateRawlyPost($App->params->fields['iblo'],$App->params->tables['iblo'],'id',$App->id);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['voce-b'],$localStrings['%ITEM% modificato'])).'!';
			if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyIblo/'.$App->id);
			} else {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');
			}		

		} else {					
				ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}		
	break;
	
	case 'pageIblo':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listIblo');
	break;
	
	case 'downloadIblo':
		if($App->id > 0) {	
			$renderTpl = false;		
			ToolsUpload::downloadFileFromDB($App->params->uploadPaths['iblo'],$App->params->tables['iblo'],$App->id,'filename','org_filename','','');	
			die();
			}
		$App->viewMethod = 'list';
	break;
	
	case 'messageIblo':
		Core::$resultOp->error = $App->id;
		Core::$resultOp->message = urldecode(Core::$request->params[0]);
		$App->viewMethod = 'list';
	break;
	
	case 'listIblo':
	default;	
		$App->items = new stdClass;
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields[] = 'ite.*';	

		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['iblo'],'');
			}	
		if ($App->id_owner > 0) {
			$clause .= "id_owner = ?";
			$qryFieldsValues[] = $App->id_owner;
			$and = ' AND ';
			}	
		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
			}
		Sql::initQuery($App->params->tables['iblo']." AS ite",$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);
		Sql::setOrder('ordering '.$App->params->ordersType['iblo']);	
		Sql::setResultPaged(true);
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		
		/* sistemo i dati */
		$arr = array();
		if (isset($obj) && is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$field = 'title_'.$localStrings['user'];	
				$value->title = $value->$field;
				$field = 'content_'.$localStrings['user'];	
				$value->content = $value->$field;
				$arr[] = $value;
			}
		}
		$App->items = $arr;

		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);	
		$App->paginationTitle = $localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);
		
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['voci-b'],$localStrings['lista %ITEM%']);		


		$App->viewMethod = 'list';
	break;		
	}

switch((string)$App->viewMethod){
	
	case 'form':
		$App->templateApp = 'formIblo.html';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formIblo.js" type="text/javascript"></script>';
	break;
	
	case 'list':
	default;
		$App->templateApp = 'listIblo.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listIblo.js" type="text/javascript"></script>';
	break;
	}
?>