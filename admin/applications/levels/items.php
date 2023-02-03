<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/levels/items.php v.4.5.1. 19/03/2020
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {

	case 'activeItem':
	case 'disactiveItem':
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>$_lang['voce'],'attivata'=>$_lang['attivato'],'disattivata'=>$_lang['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'deleteItem':
		if ($App->id > 0) {
			Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$_lang['voce'],$_lang['%ITEM% cancellato'])).'!';	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');		
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newItem':					
		$App->item = new stdClass;		
		$App->item->active = 1;
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->level_modules = array();
		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['voce'],$_lang['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertItem';		
	break;
	
	case 'modifyItem':
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);		
		$App->level_modules = Permissions::getLevelModulesRights($App->id);
		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['modifica %ITEM%'],$_lang['modulo']);
		$App->viewMethod = 'form';
		$App->methodForm = 'updateItem';	
	break;
	
	case 'insertItem':
		if ($_POST) {
			
			// forzo il modulo home se non è settato
			$_POST['modules_read'][$App->module_home_id] = 1;
			
			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['item'],$_lang,array());
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
			}


			Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);				
			if (Core::$resultOp->error > 0) { die();ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
			// prende ultimo id
			$App->id = Sql::getLastInsertedIdVar();
			// asserra i record con lo stesso livello
			Sql::initQuery($App->params->tables['ass-item'],array(),array($App->id),'levels_id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db');die(); }	
			
			// memorizzo associazioni
			foreach($App->modules AS $sectionKey=>$sectionModules) {
				foreach($sectionModules AS $module) {					
					$accessread = (isset($_POST['modules_read'][$module->id]) ? $_POST['modules_read'][$module->id] : 0);
					$accesswrite = (isset($_POST['modules_write'][$module->id]) ? $_POST['modules_write'][$module->id] : 0);
					
					Sql::initQuery($App->params->tables['ass-item'],array('modules_id','users_id','levels_id','read_access','write_access'),array($module->id,'0',$App->id,$accessread,$accesswrite),'');
					Sql::insertRecord();
					if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db');die(); }
				}
			}

						
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$_lang['voce'],$_lang['%ITEM% inserito']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');				
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;

	case 'updateItem':
		$App->itemOld = new stdClass;
		if ($_POST) {
			
			// forzo il modulo home se non è settato
			$_POST['modules_read'][$App->module_home_id] = 1;
						
			// asserra i record con lo stesso livello
			Sql::initQuery($App->params->tables['ass-item'],array(),array($App->id),'levels_id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db');die(); }	
			
			// memorizzo associazioni
			foreach($App->modules AS $sectionKey=>$sectionModules) {
				foreach($sectionModules AS $module) {					
					$accessread = (isset($_POST['modules_read'][$module->id]) ? $_POST['modules_read'][$module->id] : 0);
					$accesswrite = (isset($_POST['modules_write'][$module->id]) ? $_POST['modules_write'][$module->id] : 0);
					
					Sql::initQuery($App->params->tables['ass-item'],array('modules_id','users_id','levels_id','read_access','write_access'),array($module->id,'0',$App->id,$accessread,$accesswrite),'');
					Sql::insertRecord();
					if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db');die(); }
				}
			}
						
			Form::parsePostByFields($App->params->fields['item'],$_lang,array());
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
			}
			
			Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db');die(); }	
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$_lang['voce'],$_lang['%ITEM% modificato']));
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
				
	case 'listItem':
	default:
		$App->item = new stdClass;						
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
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		$arr = array();
		if (is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
				
				$App->level_modules = Permissions::getLevelModulesRights($value->id);
				$modules = array();
				foreach ($App->level_modules AS $k1=>$v1) {	
					if ($v1->read_access == 1 || $v1->write_access == 1) {
    					$modules[] = $k1;   	
    				}
										
				}
				$value->modules = implode(', ',$modules);
				$arr[] = $value;
			}
		}
		$App->items = $arr;

		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = $_lang['Mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);	
		
		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['voci'],$_lang['lista %ITEM%']);
		$App->viewMethod = 'list';	
	break;

}


/* SEZIONE SWITCH VISUALIZZAZIONE TEMPLATE (LIST, FORM, ECC) */

switch((string)$App->viewMethod) {
	
	case 'form':
		$App->templateApp = 'formItem.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';
	break;

	case 'list':
	default:
		$App->templateApp = 'listItems.html';		
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/listItems.js"></script>';

	break;
}	
?>