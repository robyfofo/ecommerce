<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/product-attributes-types.php v.1.0.0. 25/02/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {
	case 'activePrat':
	case 'disactivePrat':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {	
			Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['proatypes'],$App->id,array('label'=>$localStrings['tipo attributo'],'attivata'=>$localStrings['attivato'],'disattivata'=>$localStrings['disattivato']));
			$_SESSION['message'] = '0|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listPrat');	
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'deletePrat':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {				
			
			// cancello il record
			Sql::initQuery($App->params->tables['proatypes'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['tipo attributo'],$localStrings['%ITEM% cancellato'])).'!';	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listPrat');			
						
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newPrat':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;
		$App->item->active = 1;				
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['attributo'].' '.$localStrings['prodotto'],$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertPrat';
		$App->viewMethod = 'form';
	break;
	
	case 'insertPrat':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($_POST) {			
			// parsa i post in base ai campi
	   		Form::parsePostByFields($App->params->fields['proatypes'],$localStrings,array());
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newPrat');
			}
		
			// inserisci record
			Sql::insertRawlyPost($App->params->fields['proatypes'],$App->params->tables['proatypes']);
	   		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
	   		
			// redirect
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['tipo attributo'].' '.$localStrings['prodotto'],$localStrings['%ITEM% inserito'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listPrat');				
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;
	
	case 'modifyPrat':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['proatypes'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }			
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['tipo attributo'].' '.$localStrings['prodotto'],$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updatePrat';
		$App->viewMethod = 'form';
	break;
	
	case 'updatePrat':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($_POST) {
			//Core::setDebugMode(1);			
			// parsa i post in base ai campi
	   		Form::parsePostByFields($App->params->fields['proatypes'],$localStrings,array());
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyPrat/'.$App->id);
			}
		
			// aggiorna record
			Sql::updateRawlyPost($App->params->fields['proatypes'],$App->params->tables['proatypes'],'id',$App->id);
	   		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
	   		
			// redirect
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['tipo attributo'].' '.$localStrings['prodotto'],$localStrings['%ITEM% modificato'])).'!';
			if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyPrat/'.$App->id);
			} else {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listPrat');
			}		
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
		die();
	break;

	case 'listPrat':
	default;
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);

		$queryVars = Applications::resetDataTableArrayVars();
		$queryVars['table'] = $App->params->tables['proatypes']." AS ite";
		$queryVars['fields'][] = 'ite.*';
		$queryVars['fieldsValue'] = array();
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['proatypes'],'');
		}	
		if (isset($sessClause) && $sessClause != '') $queryVars['where'] .= $queryVars['and'].'('.$sessClause.')';
		if (isset($qryFieldsValuesClause) && is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$queryVars['fieldsValue'] = array_merge($queryVars['fieldsValue'],$qryFieldsValuesClause);	
		}

		Sql::initQuery($queryVars['table'],$queryVars['fields'],$queryVars['fieldsValue'],$queryVars['where']);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('title_'.$localStrings['user'].' '.$App->params->ordersType['proatypes']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();
	
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = ucfirst($localStrings['mostra da %START% a %END% di %ITEM% elementi']);
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);	
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['tipi attributo'].' '.$localStrings['prodotto'],$localStrings['lista dei %ITEM%']);		
		$App->viewMethod = 'list';	
	break;	
}

switch((string)$App->viewMethod) {	
	case 'form':
		$App->templateApp = 'formProductAttributesTypes.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formProductAttributesTypes.js"></script>';
	break;

	case 'list':
	default:
		$App->templateApp = 'listProductAttributesTypes.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listProductAttributesTypes.js"></script>';
	break;

}	
?>