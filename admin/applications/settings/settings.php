<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 *	admin/contacts/configuration.php v.1.0.0. 29/06/2021 
*/

// carica la cofigurazione del modulo
$App->config = new stdClass();
Sql::initQuery(Config::$dbTablePrefix.'settings',array('*'),array(),'');
$pdoObject = Sql::getPdoObjRecords();	
if (Core::$resultOp->error > 0) { die('errore lettura settings'); }
while ($row = $pdoObject->fetch()) {
	$App->setting[$row->keyword] = $row->value;
	/*
	if ( $row->comment != '') {
		$row->comment = utf8_encode($row->comment);
		$App->contact_help[$row->keyword] = json_decode($row->comment) or die('formato json errato');
	} else {
		$App->contact_help[$row->keyword] = array();
	}
	*/
}

//ToolsStrings::dump($App->contact_config);
//ToolsStrings::dump($App->contact_help);
//die();


if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch (Core::$request->method) {
	case 'updateItem':
		//if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		//ToolsStrings::dump($_POST);
		//Config::$debugMode = 1;
		$keyword = (isset($_POST['keyword']) ? $_POST['keyword'] : '');
		$value = (isset($_POST['value']) ? $_POST['value'] : '');
		Sql::initQuery(
			$App->params->tables['settings'],
			array('value'),
			array($value,$keyword),
			'keyword = ?'
		);
		Sql::updateRecord();
		if (Core::$resultOp->error > 0) { 
			$result = array(
				'error' => 1,
				'message' => 'Errore form'
			);
			echo json_encode($result);
		} else {
			$result = array(
				'error' => 0,
				'message' => 'Form inviato'
			);
			echo json_encode($result);
		}
		die();
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,Core::$request->action,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;

	default:
		$App->item = new stdClass;
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		Config::initQueryParams();
		Config::$queryParams['tables'] = Config::$DatabaseTables['settings'];
		Config::$queryParams['fields'] = array('*');
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause, $qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'], Config::$DatabaseTablesFields['settings'], '');
		}
		if (isset($sessClause) && $sessClause != '') {
			Config::$queryParams['where'] .= Config::$queryParams['and'] . '(' . $sessClause . ')';
		}
		if (isset($qryFieldsValuesClause) && is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			Config::$queryParams['fieldsVal'] = array_merge(Config::$queryParams['fieldsVal'], $qryFieldsValuesClause);
		}
		Sql::initQuery(Config::$queryParams['tables'],Config::$queryParams['fields'], Config::$queryParams['fieldsVal'], Config::$queryParams['where']);
		Sql::setItemsForPage($App->itemsForPage);
		Sql::setPage($App->page);
		Sql::setResultPaged(true);
		Sql::setOrder('id '.$App->params->ordersType['settings']);
		if (Core::$resultOp->error <> 1) $App->items = Sql::getRecords();
		$App->pagination = Utilities::getPagination($App->page, Sql::getTotalsItems(), $App->itemsForPage);
		$App->paginationTitle = Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/', $App->pagination->firstPartItem, $App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/', $App->pagination->lastPartItem, $App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/', $App->pagination->itemsTotal, $App->paginationTitle);
		$App->pageSubTitle = preg_replace('/%ITEM%/', Config::$localStrings['configurazione'], Config::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateConf';
		$App->viewMethod = '';
	break;
}

switch((string)$App->viewMethod) {
	default:
		$App->templateApp = 'listSettings.html';
		$App->jscript[] = '<script src="' . URL_SITE_ADMIN . $App->pathApplications . Core::$request->action . '/templates/' . $App->templateUser . '/js/listSettings.js"></script>';
	break;
}
?>