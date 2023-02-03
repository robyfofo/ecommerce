<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/users/users.php v.1.0.0. 17/03/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);



switch(Core::$request->method) 
{

	case 'activeItem':
	case 'disactiveItem':
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Core::$localStrings['utente'],'attivata'=>Core::$localStrings['attivato'],'disattivata'=>Core::$localStrings['disattivato']));		
		$App->viewMethod = 'list';		
	break;
	
	case 'deleteItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['utente'],Core::$localStrings['%ITEM% cancellato'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	
	case 'newItem':			
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$_SESSION[$App->sessionName]['formTabActive'] = 1;
		//Core::setDebugMode(1);
		
		$App->province = new stdClass;
		Sql::initQuery($App->params->tables['province'],array('*'),array(),'active = 1','nome ASC');
		$App->province = Sql::getRecords();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		$App->nations = new stdClass;
		Sql::initQuery($App->params->tables['nations'],array('*'),array(),'active = 1','title_'.Core::$localStrings['user'].' ASC');
		$App->nations = Sql::getRecords();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		$App->item = new stdClass;	
		$App->item->created = $App->nowDateTime;		
		$App->item->active = 1;
		$App->item->id_level = 0;		
		$App->templatesAvaiable = $Module->getUserTemplatesArray();
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);

		$App->comune = new stdClass;
		$App->comune->selected = new stdClass;
		$App->comune->selected->id = 0;

		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['utente'],Core::$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertItem';
		$App->viewMethod = 'form';	
	break;
	
	case 'insertItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		//Core::setDebugMode(1);

		$_POST['is_root'] = 0;

		if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
		if (!isset($_POST['active'])) $_POST['active'] = 0;	
		
		$_POST['is_admin'] = 1;	

		// toglie spazi a username
		$_POST['username'] = preg_replace('/\s+/', '', $_POST['username']);
		
		if (Form::validateVariableUsername($_POST['username']) == false) 
		{
			$_SESSION['message'] .= '1|'.Core::$localStrings['Username in formato errato!'];
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
		}
		
		if (!isset($_POST['location_comuni_id']) || (isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] == '')) $_POST['location_comuni_id'] = 0;
		if (!isset($_POST['location_province_id']) || (isset($_POST['location_province_id']) && $_POST['location_province_id'] == '')) $_POST['location_province_id'] = 0;
		if (!isset($_POST['location_nations_id']) || (isset($_POST['location_nations_id']) && $_POST['location_nations_id'] == '')) $_POST['location_nations_id'] = 0;

		if ( isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] > 0 ) $_POST['comune_alt'] = '';
		if ( isset($_POST['location_province_id']) && $_POST['location_province_id'] > 0 ) $_POST['provincia_alt'] = '';

		/* recupero dati avatar */
		list($_POST['avatar'],$_POST['avatar_info']) = $Module->getAvatarData(0,Core::$localStrings);
		if ($Module->errorType > 0) 
		{
			Core::$resultOp->messages[] = $Module->message;
			Core::$resultOp->type =  $Module->errorType;
			Core::$resultOp->error =  $Module->error;
			$_SESSION[$App->sessionName]['formTabActive'] = 4;
		}
		
		/* controllo password */
		$_POST['password'] = $Module->checkPassword(0,Core::$localStrings);
		if ($Module->error > 0) 
		{
			Core::$resultOp->messages[] = $Module->message;
			Core::$resultOp->type =  $Module->errorType;
			Core::$resultOp->error =  $Module->error;
			$_SESSION[$App->sessionName]['formTabActive'] = 1;
		}

		/* controllo nome utente */
		$_POST['username'] = $Module->checkUsername(0,Core::$localStrings);
		if ($Module->error > 0) 
		{
			Core::$resultOp->messages[] = $Module->message;
			Core::$resultOp->type =  $Module->errorType;
			Core::$resultOp->error =  $Module->error;
			$_SESSION[$App->sessionName]['formTabActive'] = 1;
		}

		/* controllo email univoca */
		$_POST['email'] = $Module->checkEmail(0,Core::$localStrings);
		if ($Module->error > 0) 
		{
			Core::$resultOp->messages[] = $Module->message;
			Core::$resultOp->type =  $Module->errorType;
			Core::$resultOp->error =  $Module->error;
			$_SESSION[$App->sessionName]['formTabActive'] = 1;
		}								

		if (Core::$resultOp->error == 0) {

			$_POST['hash'] = password_hash(SITE_CODE_KEY.$_POST['username'].$_POST['email'],PASSWORD_DEFAULT);
			$_POST['hash'] = SanitizeStrings::base64url_encode($_POST['hash']);

			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}

			ToolsStrings::dump($_POST);//die();

			Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
			if (Core::$resultOp->error > 0) {die('errore inserimento rcord');ToolsStrings::redirect(URL_SITE_ADMIN.'error/db');}

			$App->id = Sql::getLastInsertedIdVar();

			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['utente'],Core::$localStrings['%ITEM% inserito'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');

		} else {
			$_SESSION['message'] = Core::$resultOp->type.'|'.implode('<br>', Core::$resultOp->messages);
			if (isset(Core::$resultOp->message) && Core::$resultOp->message != '') $_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem/'.$App->id);
		}
	break;

	case 'modifyItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		$_SESSION[$App->sessionName]['formTabActive'] = 1;
		//Core::setDebugMode(1);
		
		$App->province = new stdClass;
		Sql::initQuery($App->params->tables['province'],array('*'),array(),'active = 1','nome ASC');
		$App->province = Sql::getRecords();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		$App->nations = new stdClass;
		Sql::initQuery($App->params->tables['nations'],array('*'),array(),'active = 1','title_'.Core::$localStrings['user'].' ASC');
		$App->nations = Sql::getRecords();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		$App->item = new stdClass;
		$App->templatesAvaiable = $Module->getUserTemplatesArray();
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);

		$App->comune = new stdClass;
		$App->comune->selected = new stdClass;	
		$App->comune->selected->id = 0;
		if (isset($App->item->location_comuni_id) && $App->item->location_comuni_id > 0) {
			$App->comune->selected->id = $App->item->location_comuni_id;
		}
	
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['utente'],Core::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateItem';
		$App->viewMethod = 'form';
	break;
	
	case 'updateItem':
		//Core::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }

		if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
		if (!isset($_POST['active'])) $_POST['active'] = 0;
		
		$_POST['is_admin'] = 1;

		// toglie spazi a username
		$_POST['username'] = preg_replace('/\s+/', '', $_POST['username']);
		
		if (Form::validateVariableUsername($_POST['username']) == false) 
		{
			$_SESSION['message'] .= '1|'.Core::$localStrings['Username in formato errato!'];
			ToolsStrings::redirect(URL_SITE.Core::$request->action.'/newItem');
		}
		
		if (!isset($_POST['location_comuni_id']) || (isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] == '')) $_POST['location_comuni_id'] = 0;
		if (!isset($_POST['location_province_id']) || (isset($_POST['location_province_id']) && $_POST['location_province_id'] == '')) $_POST['location_province_id'] = 0;
		if (!isset($_POST['location_nations_id']) || (isset($_POST['location_nations_id']) && $_POST['location_nations_id'] == '')) $_POST['location_nations_id'] = 0;

		if ( isset($_POST['location_comuni_id']) && $_POST['location_comuni_id'] > 0 ) $_POST['comune_alt'] = '';
		if ( isset($_POST['location_province_id']) && $_POST['location_province_id'] > 0 ) $_POST['provincia_alt'] = '';

		/* requpero i vecchi dati */
		$App->oldItem = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->oldItem = Sql::getRecord();
		if (Core::$resultOp->error > 0) { die();ToolsStrings::redirect(URL_SITE.'error/db'); }

		if (Core::$resultOp->error == 0) {	
		
			/* recupero dati avatar */
			list($_POST['avatar'],$_POST['avatar_info']) = $Module->getAvatarData($_POST['id'],Core::$localStrings);
			if ($Module->errorType > 0) 
			{
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$_SESSION[$App->sessionName]['formTabActive'] = 4;
			}
			
			/* controllo password */
			$_POST['password'] = $Module->checkPassword($App->id,Core::$localStrings);
			if ($Module->error > 0) 
			{
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$_SESSION[$App->sessionName]['formTabActive'] = 1;
			}

			/* controllo nome utente */
			if ($_POST['username'] != $App->oldItem->username) 
			{
				$_POST['username'] = $Module->checkUsername($App->id,Core::$localStrings);
				if ($Module->error > 0) 
				{
					Core::$resultOp->messages[] = $Module->message;
					Core::$resultOp->type =  $Module->errorType;
					Core::$resultOp->error =  $Module->error;
					$_SESSION[$App->sessionName]['formTabActive'] = 1;
				}
			}

			/* controllo email univoca */
			if ($_POST['email'] != $App->oldItem->email) 
			{
				$_POST['email'] = $Module->checkEmail($_POST['id'],Core::$localStrings);
				if ($Module->error > 0) 
				{
					Core::$resultOp->messages[] = $Module->message;
					Core::$resultOp->type =  $Module->errorType;
					Core::$resultOp->error =  $Module->error;
					$_SESSION[$App->sessionName]['formTabActive'] = 1;
				}
			}
						
			if (Core::$resultOp->error == 0) {		

				$_POST['hash'] = password_hash(SITE_CODE_KEY.$_POST['username'].$_POST['email'],PASSWORD_DEFAULT);
				$_POST['hash'] = SanitizeStrings::base64url_encode($_POST['hash']);

				// parsa i post in base ai campi
				Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
				if (Core::$resultOp->error > 0) {
					$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
				}

				//ToolsStrings::dump($_POST);die();

				Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
				if (Core::$resultOp->error > 0) { die('errore modifica record');ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

				if (Core::$resultOp->error == 0) {
					$_POST['hash'] = md5(SITE_CODE_KEY.$_POST['username'].$_POST['email'].$_POST['password']);
					Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
					if(Core::$resultOp->error == 0) {					   						
					}	
				}

				$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['utente'],Core::$localStrings['%ITEM% modificato'])).'!';
				if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
				} else {
					ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
				}

			} else {
				$_SESSION['message'] = Core::$resultOp->type.'|'.implode('<br>', Core::$resultOp->messages);
				if (isset(Core::$resultOp->message) && Core::$resultOp->message != '') $_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			}
		
		} else {					
			$_SESSION['message'] = Core::$resultOp->type.'|'.implode('<br>', Core::$resultOp->messages);
			if (isset(Core::$resultOp->message) && Core::$resultOp->message != '') $_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		}
	break;
	
	case 'checkUserAjaxItem':
		$count = $Module->checkUsernameAjax($_POST['id'],$_POST['username']);
		if($count > 0) {
			echo '<span style="color:red;">'.preg_replace('/%USERNAME%/',$_POST['username'],Core::$localStrings['Username %USERNAME% risulta già presente nel nostro database!']).'</span>';
			} else {
				echo '<span style="color:green;">'.preg_replace('/%USERNAME%/',$_POST['username'],Core::$localStrings['Username %USERNAME% è disponibile!']).'</span>';
				}
		$renderTpl = false;
		die();
	break;
	
	case 'checkEmailAjaxItem':
		$count = $Module->checkEmailAjax($_POST['id'],$_POST['email']);
		if($count > 0) {
			echo '<span style="color:red;">'.preg_replace('/%EMAIL%/',$_POST['email'],Core::$localStrings['Indirizzo email %EMAIL% risulta già presente nel nostro database!']).'</span>';
			} else {
				echo '<span style="color:green;">'.preg_replace('/%EMAIL%/',$_POST['email'],Core::$localStrings['Indirizzo email %EMAIL% è disponibile!']).'</span>';
				}
		$renderTpl = false;
		die();
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;

	case 'listItem':
	default;	
		$_SESSION[$App->sessionName]['formTabActive'] = 2;
		$App->viewMethod = 'list';	
	break;

}


switch((string)$App->viewMethod) 
{
	case 'form':
		$App->templateApp = 'formUser.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';	
	break;

	case 'list':
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = 'is_root <> 1';
		$and = ' AND ';
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
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				$value->levellabel = Permissions::getUserLevelLabel($value->id_level);	
				$arr[] = $value;
				}
			}
		$App->items = $arr;
		//print_r($App->items );
		//print_r($App->user_levels);		
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Core::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['utenti'],Core::$localStrings['lista degli %ITEM%']);
		$App->templateApp = 'listUsers.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listItems.js"></script>';		
	break;
	
	default:
	break;
	
}	
?>