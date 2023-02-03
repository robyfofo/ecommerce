<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/brands/brands.php v.1.0.0. 15/10/2021
*/

//Core::setDebugMode(1);

if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {	

	case 'activelistItem':
	case 'disactivelistItem':
		if (Core::$request->method == 'activelistItem') {
			$act = 'activeItem';
			$mess =  preg_replace('/%ITEM%/',Config::$localStrings['voci'],Config::$localStrings['%ITEM% attivati']).'!';
		} else {
			$act = 'disactiveItem';
			$mess =  preg_replace('/%ITEM%/',Config::$localStrings['voci'],Config::$localStrings['%ITEM% disattivati']).'!';
		}
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		foreach ($_SESSION[$App->sessionName]['checklist'] AS $value) {
			Sql::manageFieldActive(substr($act,0,-4),$App->params->tables['item'],$value,array('label'=>Config::$localStrings['voce'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		}
		$_SESSION[$App->sessionName]['checklist'] = array();
		$_SESSION['message'] = '0|'.ucfirst($mess);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
		
	case 'deletelistItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		foreach ($_SESSION[$App->sessionName]['checklist'] AS $value) {
			if (intval($value) > 0) {
				Sql::initQuery($App->params->tables['item'],array('id'),array(intval($value)),'id = ?');
				Sql::deleteRecord();
				if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			}
		}
		$_SESSION[$App->sessionName]['checklist'] = array();
		$mess =  preg_replace('/%ITEM%/',Config::$localStrings['voci'],Config::$localStrings['%ITEM% cancellati']).'!';
		$_SESSION['message'] = '0|'.ucfirst($mess);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');

	break;

	case 'activeItem':
	case 'disactiveItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Config::$localStrings['voce'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'deleteItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {
			Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% cancellato'])).'!';	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');		
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newItem':		
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->in_footer = 0;	
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertItem';
	break;
	
	case 'modifyItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }			
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['modifica %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'updateItem';	
	break;

	case 'insertItem':
		Core::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($_POST) {	
			
			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
			if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}
			
			Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% inserito']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');				

		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;
	
	case 'updateItem':
		//Core::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($_POST) {
			
			// requpero i vecchi dati
			$App->oldItem = new stdClass;
			Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
			$App->oldItem = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
			if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			}

			Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
			if (Core::$resultOp->error > 0) {ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% modificato']));
			if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			} else {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
			}								
								
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,Core::$request->action,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
				
	case 'listItem':
	default:
		$App->items = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);

		$queryVars = Applications::resetDataTableArrayVars();
		$queryVars['table'] = $App->params->tables['item']." AS ite";
		$queryVars['fields'][] = 'ite.*';
		$queryVars['fieldsValue'] = array();
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['item'],'');
		}	
		if (isset($sessClause) && $sessClause != '') $queryVars['where'] .= $queryVars['and'].'('.$sessClause.')';
		if (isset($qryFieldsValuesClause) && is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$queryVars['fieldsValue'] = array_merge($queryVars['fieldsValue'],$qryFieldsValuesClause);	
		}

		Sql::initQuery($queryVars['table'],$queryVars['fields'],$queryVars['fieldsValue'],$queryVars['where']);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('title_'.Config::$localStrings['user'].' '.$App->params->ordersType['item']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();
	
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);	
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voci'],Config::$localStrings['lista %ITEM%']);		
		$App->viewMethod = 'list';	
	break;
}

switch((string)$App->viewMethod) {
	case 'form':	
		$App->templateApp = 'formBrand.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/formBrand.js"></script>';
	break;
	
	default:
	case 'list':
		$App->templateApp = 'listBrands.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/listBrands.js"></script>';
	break;
}	
?>