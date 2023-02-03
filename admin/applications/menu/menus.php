<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/menu/menus.php v.1.0.0. 22/03/2021
*/

if (!isset($_MY_SESSION_VARS[$App->sessionName]['page'])) $_MY_SESSION_VARS = $my_session->addSessionsModuleVars($_MY_SESSION_VARS,$App->sessionName,array('page'=>1,'ifp'=>'10','srcTab'=>''));

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {
	case 'ajaxGetSectionModuleLinkInfoItem':
		$menutypevars = (isset($_POST['menutypevars']) && $_POST['menutypevars'] != '') ? $_POST['menutypevars'] : '';
		if ($menutypevars != '') {
			if (isset(Config::$localStrings['menu-type-vars'][$menutypevars]['info'])) echo Config::$localStrings['menu-type-vars'][$menutypevars]['info'];
		} 
		die();
	break;
	
	case 'moreOrderingItem':
		Utilities::increaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Config::$localStrings['voce']).' '.Config::$localStrings['spostato']));
		$_SESSION['message'] =  Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	case 'lessOrderingItem':
		Utilities::decreaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Config::$localStrings['voce']).' '.Config::$localStrings['spostato']));
		$_SESSION['message'] =  Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;

	case 'activeItem':
	case 'disactiveItem':
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Config::$localStrings['voce'],'attivata'=>Config::$localStrings['attivato'],'disattivata'=>Config::$localStrings['disattivato']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');	
	break;
	
	case 'deleteItem':
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
		// select per parent
		$App->subMenu = new stdClass();				
		Menu::$optLevelString = '-->';
		$App->subMenu = Menu::getObjFromMenus(array());
		//print_r($App->subMenu);die();
				
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->ordering = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','') + 1;		
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['inserisci %ITEM%']);
		$App->viewMethod = 'form';
		$App->methodForm = 'insertItem';
	break;
	
	case 'modifyItem':
		$App->subMenu = new stdClass();				
		Menu::$optHideId = 1;
		Menu::$optHideSons = 1;
		Menu::$optRifId = 'id';
		Menu::$optRifIdValue = $App->id;	
		Menu::$optLevelString = '-->';			
		$App->subMenu = Menu::getObjFromMenus(array());
		//print_r($App->subMenu);die();

		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['modifica %ITEM%']).'!';
		$App->viewMethod = 'form';
		$App->methodForm = 'updateItem';	
	break;

	
	case 'insertItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }

		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','') + 1;

		// imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias(0,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);
		
		/* imposta tipo se presente */
		switch($_POST['type']) {
			case 'module-link':
				if ($_POST['module'] != '') {
					$_POST['url'] = $_POST['module'];
					$_POST['alias'] = $_POST['module'];
				}
			break;
			case 'module-menu':
				if ($_POST['menutypevars'] != '' && isset(Config::$localStrings['menu-type-vars'][$_POST['menutypevars']])) {
					$_POST['url'] = Config::$localStrings['menu-type-vars'][$_POST['menutypevars']]['varreplace'];
					$_POST['alias'] = Config::$localStrings['menu-type-vars'][$_POST['menutypevars']]['varreplace'];
				}
			break;
			default:
			break;
		}
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) {
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');		
		}	

		Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);				
		if (Core::$resultOp->error > 0) { die('Errore inserimento record!');ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }				
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% inserito'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');				
	break;
	
	case 'updateItem':
		$App->itemOld = new stdClass;
		
		if ($_POST) {
			if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','') + 1;	
			
			// requpero i vecchi dati
			$App->oldItem = new stdClass;
			Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
			$App->oldItem = Sql::getRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }
			
			// imposta alias
			if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);

			/* imposta tipo se presente */
			switch($_POST['type']) {
				case 'module-link':
					if ($_POST['module'] != '') {
						$_POST['url'] = $_POST['module'];
						$_POST['alias'] = $_POST['module'];
					}
				break;
				case 'module-menu':
					if ($_POST['menutypevars'] != '' && isset(Config::$localStrings['menu-type-vars'][$_POST['menutypevars']])) {
						$_POST['url'] = Config::$localStrings['menu-type-vars'][$_POST['menutypevars']]['varreplace'];
						$_POST['alias'] = Config::$localStrings['menu-type-vars'][$_POST['menutypevars']]['varreplace'];
					}
				break;
				default:
				break;
			}			
			
			// parsa i post in base ai campi
			Form::parsePostByFields($App->params->fields['item'],Config::$localStrings,array());
			if (Core::$resultOp->error > 0) {
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);			
			}
			
			// aggiorna record nel db 
			Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); die(); }
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['voce'],Config::$localStrings['%ITEM% modificato'])).'!';
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
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
				
	case 'listItem':
	default:
		//Core::setDebugMode(1);
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 10);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);				
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		
		$App->renderSub = 1;
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			$qryFields = array('ite.*');
			$qryFieldsValues = array();
			$qryFieldsValuesClause = array();
			$clause = '';
			$and = '';			
			
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['item'],'');
	
			if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
			if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
				$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
			}
			Sql::initQuery($App->params->tables['item']." AS ite",$qryFields,$qryFieldsValues,$clause);		
			Sql::setItemsForPage($App->itemsForPage);	
			Sql::setPage($App->page);		
			Sql::setResultPaged(true);
			Sql::setOrder('ite.ordering '.$App->params->ordersType['item']);
			if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
			/* sistemo i dati */
			$arr = array();
			if (is_array($obj) && is_array($obj) && count($obj) > 0) {
				foreach ($obj AS $value) {	
					$field = 'title_'.Config::$localStrings['user'];	
					$value->title = $value->$field;
					$arr[] = $value;
				}
			}
			$App->items = $arr;
			
		} else {			
			$App->renderSub = 0;
			Menu::$optOrdering = 'm.ordering '.$App->params->ordersType['item'];
			$App->items = Menu::getObjFromMenus();
		}
				
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
		$App->templateApp = 'formMenu.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications. Core::$request->action.'/templates/'.$App->templateUser.'/js/formMenu.js"></script>';
	break;
	
	default:
	case 'list':
		$App->templateApp = 'listMenus.html';	
		$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.css" rel="stylesheet">';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.cookie/jquery.cookie.js"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.min.js"></script>';
		//$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.bootstrap3.js"></script>';		
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listMenus.js"></script>';		

	break;
}	
?>