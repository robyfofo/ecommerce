<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/product-attributes.php v.1.0.0. 25/02/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

if (Core::$request->method == 'listProa' && $App->id > 0) $_SESSION[$App->sessionName]['products_id'] = $App->id;
if (isset($_POST['products_id']) && isset($_SESSION[$App->sessionName]['products_id']) && $_SESSION[$App->sessionName]['products_id'] != $_POST['products_id']) $_SESSION[$App->sessionName]['products_id'] = $_POST['products_id'];

if (isset($_POST['products_attribute_types_id']) && isset($_SESSION[$App->sessionName]['products_attribute_types_id']) && $_SESSION[$App->sessionName]['products_attribute_types_id'] != $_POST['products_attribute_types_id']) { $_SESSION[$App->sessionName]['products_attribute_types_id'] = $_POST['products_attribute_types_id']; }

/* gestione sessione -> products_id */	
$App->products_id = (isset($_SESSION[$App->sessionName]['products_id']) ? $_SESSION[$App->sessionName]['products_id'] : 0);

/* gestione sessione -> products_id */	
$App->products_attribute_types_id = (isset($_SESSION[$App->sessionName]['products_attribute_types_id']) ? $_SESSION[$App->sessionName]['products_attribute_types_id'] : 0);

/*
echo 'App->products_id: '.$App->products_id;
echo 'App->products_attribute_types_id: '.$App->products_attribute_types_id;
*/

if ($App->products_id > 0) {
	Sql::initQuery($App->params->tables['prod'],array('*'),array($App->products_id),'id = ?');
	Sql::setOptions(array('fieldTokeyObj'=>'id'));
	$App->ownerData = Sql::getRecord();
	if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
	$field = 'title_'.$localStrings['user'];	
	$App->ownerData->title = $App->ownerData->$field;
}

if (!isset($App->ownerData->id) || (isset($App->ownerData->id) && $App->ownerData->id == 0)) {
	$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['prodotto'],$localStrings['Devi creare o attivare almeno uno %ITEM%!']));
	ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');
}

// gestione attributi
$App->attrs = new stdClass;	
Sql::initQuery($App->params->tables['proatypes'],array('*'),array(),'');
Sql::setOptions(array('fieldTokeyObj'=>'id'));
Sql::setOrder('title_'.$localStrings['user'].' ASC');
$obj = Sql::getRecords();
if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
// sistemo i dati
$arr = array();
if (is_array($obj) && is_array($obj) && count($obj) > 0) {
	foreach ($obj AS $key=>$value) {	
		$field = 'title_'.$localStrings['user'];	
		$value->title = $value->$field;
		$arr[$key] = $value;
	}
}
$App->attrs = $arr;

// gestione dettaglio tipo attributo
$App->attributeData =  new stdClass;
if ($App->products_attribute_types_id  > 0) {
	Sql::initQuery($App->params->tables['proatypes'],array('*'),array($App->products_attribute_types_id),'id = ?');
	Sql::setOptions(array('fieldTokeyObj'=>'id'));
	$App->attributeData = Sql::getRecord();
	if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
}
//print_r($App->attributeData);

$App->moduleRedirectSuffix = (isset($_SESSION[Core::$request->action]['moduleRedirectSuffix']) ? $_SESSION[Core::$request->action]['moduleRedirectSuffix'] : 'listItemaaaa');


switch(Core::$request->method) {
	case 'activeProa':
	case 'disactiveProa':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {	
			Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['proa'],$App->id,array('label'=>$localStrings['attributo'],'attivata'=>$localStrings['attivato'],'disattivata'=>$localStrings['disattivato']));
			$_SESSION['message'] = '0|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProa');	
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'deleteProa':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id > 0) {				
			
			// cancello il record
			Sql::initQuery($App->params->tables['proa'],array('id'),array($App->id),'id = ?');
			Sql::deleteRecord();
			if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['attributo'],$localStrings['%ITEM% cancellato'])).'!';	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProa');			
						
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	
	case 'newProa':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->value = '';
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['attributo'].' '.$localStrings['prodotto'],$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertProa';
		$App->viewMethod = 'form';
	break;
	
	case 'insertProa':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($_POST) {
			
			// aggiunge post			
			$_POST['value_string'] = $_POST['value'];
			$_POST['value_int'] = intval($_POST['value']);
			$_POST['value_float'] = floatval($_POST['value']);
			$_POST['value_type'] = (isset($App->attrs[$_POST['products_attribute_types_id']]->value_type) ? $App->attrs[$_POST['products_attribute_types_id']]->value_type : '');
			
			// parsa i post in base ai campi
	   		Form::parsePostByFields($App->params->fields['proa'],$localStrings,array());
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newProa');
			}
		
			// inserisci record
			Sql::insertRawlyPost($App->params->fields['proa'],$App->params->tables['proa']);
	   		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
	   		
			// redirect
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['attributo'].' '.$localStrings['prodotto'],$localStrings['%ITEM% inserito'])).'!';
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProa');				
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;
	
	case 'modifyProa':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['proa'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }	
		
		$value_type = (isset($App->attrs[$App->item->products_attribute_types_id]->value_type) ? $App->attrs[$App->item->products_attribute_types_id]->value_type : '');
		switch($value->value_type) {
			case 'int':
				$App->item->value = $App->item->value_int;
			break;
			case 'float':
				$App->item->value = $App->item->value_float;
			break;
			default:
				$App->item->value = $App->item->value_string;
			break;
		}	
					
		$App->pageSubTitle = preg_replace('/%ITEM%/',$localStrings['attributo'].' '.$localStrings['prodotto'],$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateProa';
		$App->viewMethod = 'form';
	break;
	
	case 'updateProa':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($_POST) {
			Core::setDebugMode(1);
			// aggiunge post			
			$_POST['value_string'] = $_POST['value'];
			$_POST['value_int'] = intval($_POST['value']);
			$_POST['value_float'] = floatval($_POST['value']);
			$_POST['value_type'] = (isset($App->attrs[$_POST['products_attribute_types_id']]->value_type) ? $App->attrs[$_POST['products_attribute_types_id']]->value_type : '');
			
			// parsa i post in base ai campi
	   		Form::parsePostByFields($App->params->fields['proa'],$localStrings,array());
	   		if (Core::$resultOp->error > 0) { 
				$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProa/'.$App->id);
			}
		
			// aggiorna record
			Sql::updateRawlyPost($App->params->fields['proa'],$App->params->tables['proa'],'id',$App->id);
	   		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
	   		
			// redirect
			$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',$localStrings['attributo'].' '.$localStrings['prodotto'],$localStrings['%ITEM% modificato'])).'!';
			if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProa/'.$App->id);
			} else {
				ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProa');
			}		
			
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.'error/404');
		}
	break;

	case 'listProa':
	default;
		$App->items = new stdClass;
		$App->item = new stdClass;						
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 5);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);				
		$qryFields = array('att.*');
		$qryFieldsValues = array();
		$qryFieldsValuesClause = array();
		$clause = '';
		$and = '';
		
		if ($App->products_id > 0) {
			$clause .= $and."products_id = ?";
			$qryFieldsValues[] = $App->products_id;
			$and = ' AND ';
		}	
		if ($App->products_attribute_types_id > 0) {
			$clause .= $and."products_attribute_types_id = ?";
			$qryFieldsValues[] =  $App->products_attribute_types_id;
			$and = ' AND ';
		}	
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['proa'],'');
		}	
		if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
		if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
			$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
		}
		Sql::initQuery($App->params->tables['proa']." AS att",$qryFields,$qryFieldsValues,$clause);
		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		Sql::setOrder('code ASC');
		if (Core::$resultOp->error <> 1) $obj = Sql::getRecords();
		/* sistemo i dati */
		
		$arr = array();
		if (is_array($obj) && is_array($obj) && count($obj) > 0) {
			foreach ($obj AS $value) {	
			
				$field = 'title_'.$localStrings['user'];			
				$value->attribute_type = (isset($App->attrs[$value->products_attribute_types_id]->$field) ? $App->attrs[$value->products_attribute_types_id]->$field : '');
				switch($value->value_type) {
					default:
						$value->value = $value->value_string;
					break;
				}
				$arr[] = $value;
			}
		}
		$App->items = $arr;
		
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = $localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = ucfirst(preg_replace('/%ITEM%/',$localStrings['attributi'].' '.$localStrings['prodotto'],$localStrings['lista dei %ITEM%']));
		$App->viewMethod = 'list';	
	break;	
}

switch((string)$App->viewMethod) {	
	case 'form':
		$App->templateApp = 'formProductAttributes.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formProductAttributes.js"></script>';
	break;

	case 'list':
	default:
		$App->templateApp = 'listProductAttributes.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listProductAttributes.js"></script>';
	break;	
}	
?>