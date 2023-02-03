<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *	admin/contacts/contacts.php v.1.0.0. 23/06/2021 
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch (Core::$request->method) {

	case 'deleteCont':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		Sql::initQuery($App->params->tables['contacts'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['contatto'],Config::$localStrings['%ITEM% cancellato'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCont');		
	break;

	case 'newCont':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }			
		$App->item = new stdClass;		
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['contacts']);
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['contatto'],Config::$localStrings['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertCont';
	break;

	case 'insertCont':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['contacts'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newCont');
		}
		
		Sql::insertRawlyPost($App->params->fields['contacts'],$App->params->tables['contacts']);
		if (Core::$resultOp->error > 0) {ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');}
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% inserita']));
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCont');	
	break;
	
	case 'modifyCont':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }		
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['contacts'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['contacts']);
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['contatto'],Config::$localStrings['modifica %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'updateCont';	
	break;

	case 'updateCont':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		
		// requpero i vecchi dati
		$App->oldItem = new stdClass;
		Sql::initQuery($App->params->tables['contacts'],array('*'),array($App->id),'id = ?');
		$App->oldItem = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }	
		
		Form::parsePostByFields($App->params->fields['contacts'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyCont/'.$App->id);
		}

		Sql::updateRawlyPost($App->params->fields['contacts'],$App->params->tables['contacts'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }	
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['contatto'],Config::$localStrings['%ITEM% modificato'])).'!';

		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyConf/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listConf');
		}	
						
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,Core::$request->action,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCont');
	break;

	default;
		$App->item = new stdClass;
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		$and = '';
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause, $qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'], $App->params->fields['contacts'], '');
		}
		if (isset($sessClause) && $sessClause != '') $clause .= $and . '(' . $sessClause . ')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues, $qryFieldsValuesClause);
		}
		Sql::initQuery($App->params->tables['contacts'], $qryFields, $qryFieldsValues, $clause);
		Sql::setItemsForPage($App->itemsForPage);
		Sql::setPage($App->page);
		Sql::setResultPaged(true);
		Sql::setOrder('created '.$App->params->ordersType['contacts']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();

		$App->pagination = Utilities::getPagination($App->page, Sql::getTotalsItems(), $App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/', $App->pagination->firstPartItem, $App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/', $App->pagination->lastPartItem, $App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/', $App->pagination->itemsTotal, $App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/', Config::$localStrings['contatti'], Config::$localStrings['lista %ITEM%']);
		$App->viewMethod = 'list';
		break;
}


/* SEZIONE SWITCH VISUALIZZAZIONE TEMPLATE (LIST, FORM, ECC) */

switch ((string)$App->viewMethod) {
	case 'form':
		$App->templateApp = 'formContact.html';
		$App->jscript[] = '<script src="' . URL_SITE_ADMIN . $App->pathApplications . Core::$request->action . '/templates/' . $App->templateUser . '/js/formContact.js"></script>';
	
		$App->defaultJavascript = "
		messages['Devi inserire un indirizzo email valido!'] = '".preg_replace('/%ITEM%/', Config::$localStrings['indirizzo email valido'], Config::$localStrings['Devi inserire un %ITEM%!'])."';
		messages['Devi inserire un numero di telefono valido!'] = '".preg_replace('/%ITEM%/', Config::$localStrings['numero di telefono valido'], Config::$localStrings['Devi inserire un %ITEM%!'])."';
		messages['Devi inserire un contenuto!'] = '".preg_replace('/%ITEM%/', Config::$localStrings['messaggio'], Config::$localStrings['Devi inserire un %ITEM%!'])."';
		";
	break;

	default:
	case 'list':
		$App->templateApp = 'listContacts.html';
		$App->jscript[] = '<script src="' . URL_SITE_ADMIN . $App->pathApplications . Core::$request->action . '/templates/' . $App->templateUser . '/js/listContacts.js"></script>';
	break;
}
