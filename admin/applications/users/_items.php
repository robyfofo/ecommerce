<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/users/items.php v.4.5.1. 19/03/2020
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {
	case 'activeItem':
	case 'disactiveItem':
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>$_lang['utente'],'attivata'=>$_lang['attivato'],'disattivata'=>$_lang['disattivato']));		
		$App->viewMethod = 'list';		
	break;
	
	case 'deleteItem':
		if ($App->id > 0) {
			Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error == 0) {
				Core::$resultOp->message = ucfirst(preg_replace('/%ITEM%/',$_lang['utente'],$_lang['%ITEM% cancellato'])).'!';	
			}
		}		
		$App->viewMethod = 'list';
	break;
	
	case 'newItem':			
		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['utente'],$_lang['inserisci %ITEM%']);
		$App->viewMethod = 'formNew';	
	break;
	
	case 'insertItem':
		if ($_POST) {	
			$_POST['is_root'] = 0;
			if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
			if (!isset($_POST['active'])) $_POST['active'] = 0;	
			
			$_POST['is_admin'] = 1;					
			
			/* recupero dati avatar */
			list($_POST['avatar'],$_POST['avatar_info']) = $Module->getAvatarData(0,$_lang);
			if ($Module->errorType > 0) {
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$App->formTabActive = 4;
			}
			
			/* controllo password */
			$_POST['password'] = $Module->checkPassword(0,$_lang);
			if ($Module->error > 0) {
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$App->formTabActive = 1;
			}

			/* controllo nome utente */
			$_POST['username'] = $Module->checkUsername(0,$_lang);
			if ($Module->error > 0) {
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$App->formTabActive = 1;
			}


			/* controllo email univoca */
			$_POST['email'] = $Module->checkEmail(0,$_lang);
			if ($Module->error > 0) {
				Core::$resultOp->messages[] = $Module->message;
				Core::$resultOp->type =  $Module->errorType;
				Core::$resultOp->error =  $Module->error;
				$App->formTabActive = 1;
			}								

			if (Core::$resultOp->error == 0) {
				/* parsa i post in base ai campi */
				Form::parsePostByFields($App->params->fields['item'],$_lang,array());
				if (Core::$resultOp->error == 0) {					
					$_POST['hash'] = md5(SITE_CODE_KEY.$_POST['username'].$_POST['email'].$_POST['password']);
					Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
					if (Core::$resultOp->error == 0) {
						$App->id = Sql::getLastInsertedIdVar(); /* preleva l'id della pagina */	 			
		   			}			
				}
			}
			
		} else {
			Core::$resultOp->error = 1;
		}			
		list($id,$App->viewMethod,$App->pageSubTitle,Core::$resultOp->message) = Form::getInsertRecordFromPostResults(
			0,Core::$resultOp,'',
			array(		
			'label inserted'=>preg_replace('/%ITEM%/',$_lang['utente'],$_lang['%ITEM% inserito']),
			'label insert'=>preg_replace('/%ITEM%/',$_lang['utente'],$_lang['inserisci %ITEM%'])	
			)
		);
	break;

	case 'modifyItem':				
		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['utente'],$_lang['modifica %ITEM%']);
		$App->viewMethod = 'formMod';
	break;
	
	case 'updateItem':
		if ($_POST) {
			$_POST['is_root'] = 0;
			if (!isset($_POST['created'])) $_POST['created'] = $App->nowDateTime;
			if (!isset($_POST['active'])) $_POST['active'] = 0;
			
			$_POST['is_admin'] = 1;
			
			/* requpero i vecchi dati */
			$App->oldItem = new stdClass;
			Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
			$App->oldItem = Sql::getRecord();
			if (Core::$resultOp->error == 0) {	
			
				/* recupero dati avatar */
				list($_POST['avatar'],$_POST['avatar_info']) = $Module->getAvatarData($_POST['id'],$_lang);
				if ($Module->errorType > 0) {
					Core::$resultOp->messages[] = $Module->message;
					Core::$resultOp->type =  $Module->errorType;
					Core::$resultOp->error =  $Module->error;
					$App->formTabActive = 4;
					}
				
				/* controllo password */
				$_POST['password'] = $Module->checkPassword($App->id,$_lang);
				if ($Module->error > 0) {
					Core::$resultOp->messages[] = $Module->message;
					Core::$resultOp->type =  $Module->errorType;
					Core::$resultOp->error =  $Module->error;
					$App->formTabActive = 1;
					}

				/* controllo nome utente */
				if ($_POST['username'] != $App->oldItem->username) {
					$_POST['username'] = $Module->checkUsername($App->id,$_lang);
					if ($Module->error > 0) {
						Core::$resultOp->messages[] = $Module->message;
						Core::$resultOp->type =  $Module->errorType;
						Core::$resultOp->error =  $Module->error;
						$App->formTabActive = 1;
						}
					}
	
				/* controllo email univoca */
				if ($_POST['email'] != $App->oldItem->email) {
					$_POST['email'] = $Module->checkEmail($_POST['id'],$_lang);
					if ($Module->error > 0) {
						Core::$resultOp->messages[] = $Module->message;
						Core::$resultOp->type =  $Module->errorType;
						Core::$resultOp->error =  $Module->error;
						$App->formTabActive = 1;
						}
					}
							
				if (Core::$resultOp->error == 0) {				
					/* parsa i post in base ai campi */
					Form::parsePostByFields($App->params->fields['item'],$_lang,array());
					if (Core::$resultOp->error == 0) {
						$_POST['hash'] = md5(SITE_CODE_KEY.$_POST['username'].$_POST['email'].$_POST['password']);
						Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
						if(Core::$resultOp->error == 0) {					   						
						}	
					}
				}
			}
			
		} else {					
			Core::$resultOp->error = 1;
		}
		list($id,$App->viewMethod,$App->pageSubTitle,Core::$resultOp->message) = Form::getUpdateRecordFromPostResults(
			$App->id,Core::$resultOp,'',
			array(
			'label modified'=>preg_replace('/%ITEM%/',$_lang['utente'],$_lang['%ITEM% modificato']),
			'label modify'=>preg_replace('/%ITEM%/',$_lang['utente'],$_lang['modifica %ITEM%']),
			'label insert'=>preg_replace('/%ITEM%/',$_lang['utente'],$_lang['inserisci %ITEM%']),
			'label modify applied'=>$_lang['modifiche applicate'],
			)
		);
	break;
	
	case 'checkUserAjaxItem':
		$count = $Module->checkUsernameAjax($_POST['id'],$_POST['username']);
		if($count > 0) {
			echo '<span style="color:red;">'.preg_replace('/%USERNAME%/',$_POST['username'],$_lang['Username %USERNAME% risulta già presente nel nostro database!']).'</span>';
			} else {
				echo '<span style="color:green;">'.preg_replace('/%USERNAME%/',$_POST['username'],$_lang['Username %USERNAME% è disponibile!']).'</span>';
				}
		$renderTpl = false;
		die();
	break;
	
	case 'checkEmailAjaxItem':
		$count = $Module->checkEmailAjax($_POST['id'],$_POST['email']);
		if($count > 0) {
			echo '<span style="color:red;">'.preg_replace('/%EMAIL%/',$_POST['email'],$_lang['Indirizzo email %EMAIL% risulta già presente nel nostro database!']).'</span>';
			} else {
				echo '<span style="color:green;">'.preg_replace('/%EMAIL%/',$_POST['email'],$_lang['Indirizzo email %EMAIL% è disponibile!']).'</span>';
				}
		$renderTpl = false;
		die();
	break;

	case 'pageItem':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		$App->viewMethod = 'list';	
	break;

	case 'messageItem':
		Core::$resultOp->error = $App->id;
		Core::$resultOp->message = urldecode(Core::$request->params[0]);
		$App->viewMethod = 'list';		
	break;

	case 'listItem':
		$App->viewMethod = 'list';		
	break;

	default;	
		$App->viewMethod = 'list';	
	break;	
	}


/* SEZIONE SWITCH VISUALIZZAZIONE TEMPLATE (LIST, FORM, ECC) */

switch((string)$App->viewMethod) {
	case 'formNew':
		$App->item = new stdClass;	
		$App->item->created = $App->nowDateTime;		
		$App->item->active = 1;
		$App->item->id_level = 0;		
		$App->templatesAvaiable = $Module->getUserTemplatesArray();
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->templateApp = 'formItem.html';
		$App->methodForm = 'insertItem';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';	
	break;
	
	case 'formMod':
		$App->item = new stdClass;
		$App->templatesAvaiable = $Module->getUserTemplatesArray();
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->templateApp = 'formItem.html';
		$App->methodForm = 'updateItem';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formItem.js"></script>';	
	break;

	case 'list':
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);
		$qryFields = array('*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = 'is_root = 0';
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
				$value->levellabel = Permissions::getUserLevelLabel($App->user_levels,$value->id_level);	
				$arr[] = $value;
				}
			}
		$App->items = $arr;
		//print_r($App->items );
		//print_r($App->user_levels);		
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = $_lang['Mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/',$_lang['utenti'],$_lang['lista degli %ITEM%']);
		$App->templateApp = 'listItems.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listItems.js"></script>';		
	break;
	
	default:
	break;
	}	
?>