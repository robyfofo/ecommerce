<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/pages/pages.php v.1.0.0. 01/06/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

switch(Core::$request->method) {
	case 'moreOrderingItem':
		Utilities::increaseFieldOrdering($App->id,Core::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Core::$localStrings['voce']).' '.Core::$localStrings['spostata']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	case 'lessOrderingItem':
		Utilities::decreaseFieldOrdering($App->id,Core::$localStrings,array('table'=>$App->params->tables['item'],'orderingType'=>$App->params->ordersType['item'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Core::$localStrings['voce']).' '.Core::$localStrings['spostata']));
		$_SESSION['message'] = Core::$resultOp->type.'|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;
	
	case 'activeItem':
	case 'disactiveItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['item'],$App->id,array('label'=>Core::$localStrings['voce'],'attivata'=>Core::$localStrings['attivata'],'disattivata'=>Core::$localStrings['disattivata']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;

	case 'deleteItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }	
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		// controlla se ha blocchi associati	
		Sql::initQuery($App->params->tables['iblo'],array('id'),array($App->id),'id_owner = ?');
		$count = Sql::countRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		if ($count > 0) {
			$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['blocchi'],Core::$localStrings['Ci sono ancora %ITEM% associati!']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}
				
		/* controlla se ha pagine figlie associate */			
		Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'parent = ?');
		$count = Sql::countRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		if ($count > 0) {
			$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['pagine'],Core::$localStrings['Ci sono ancora %ITEM% associate!']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}

		// prendo i vecchi dati
		$App->itemOld = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('filename'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		
		// cancello il record
		Sql::initQuery($App->params->tables['item'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
			
		// cancello il file associato
		if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {
			@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);			
		}
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['voce'],Core::$localStrings['%ITEM% cancellata'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');

	break;
	
	case 'newItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		//Core::setDebugMode(1);
		$App->item = new stdClass();
		$App->item->created = Config::$nowDateTime;
		$App->templateItem = new stdClass();
		$App->parents = new stdClass();	
		
		// select per parent 
		Pages::$optLevelString = '-->';
		$App->parents = Pages::getObjFromPages();
				
		// carica i dati del template
		$App->templateItem = $Module->getTemplatePredefinito(0);		
		if (!isset($App->templateItem->id) || (isset($App->templateItem->id) && (int)$App->templateItem->id == 0)) {	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/message/1/'.urlencode('Devi creare od attivare almeno un template!'));			
		}
							
		// select per i template
		$App->templatesItem = $Module->getTemplatesPage();	
				
		// altri campi 
		$App->item->active = 1;
		$App->item->menu = 1;
		$App->item->is_label = 1;
		$App->item->alias = "";
		$App->item->parent = 0;	
		$App->item->ordering = 0;
		$App->item->filenameRequired = false;	
		$App->item->filename1Required = false;
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['voce'],Core::$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertItem';
		$App->viewMethod = 'form';	
	break;
	
	case 'modifyItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }	
		//Core::setDebugMode(1);
		$App->item = new stdClass();
		$App->templateItem = new stdClass();
		$App->parents = new stdClass();	
			
		Pages::$optHideId = 1;
		Pages::$optHideSons = 1;
		Pages::$optRifId = 'id';
		Pages::$optRifIdValue = $App->id;
		Pages::$optLevelString = '-->';
		$App->parents = Pages::getObjFromPages();
		
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (Core::$resultOp->error > 0) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);

		// carica i dati del template
		$App->templateItem = $Module->getTemplatePredefinito($App->item->id_template);		
		if (!isset($App->templateItem->id) || (isset($App->templateItem->id) && (int)$App->templateItem->id == 0)) {	
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/message/1/'.urlencode('Devi creare od attivare almeno un template!'));			
		}
		
		// select per i template
		$App->templatesItem = $Module->getTemplatesPage();	
		
		$App->item->filenameRequired = (isset($App->item->filename) && $App->item->filename != '' ? false : false);
		$App->item->filename1Required = (isset($App->item->filename1) && $App->item->filename1 != '' ? false : false);
		
		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['pagina'],Core::$localStrings['modifica %ITEM%']);
		$App->methodForm = 'updateItem';
		$App->viewMethod = 'form';	
	break;		
	
	case 'insertItem':
		//Core::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		
		// set seo tag default
		foreach ($globalSettings['languages'] AS $lang){
			$_POST['title_seo_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
			$_POST['meta_title_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
		}
		
		// gestione automatica dell'ordering de in input = 0
		$_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','parent = '.intval($_POST['parent'])) + 1;

		// imposta alias */
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias(0,$_POST['alias'],$_POST['title_'.Core::$localStrings['user']]);	
		
		// preleva il filename dal form
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);   	
		ToolsUpload::getFilenameFromForm();	
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
		}   	
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
		$tempFilename = ToolsUpload::getTempFilename();
		
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newItem');
		}

		Sql::insertRawlyPost($App->params->fields['item'],$App->params->tables['item']);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		$App->id = Sql::getLastInsertedIdVar();
		
		// sposto il file
		if ($_POST['filename'] != '') {
			move_uploaded_file($tempFilename,$App->params->uploadPaths['item'].$_POST['filename']) or die('Errore caricamento file');
		}	
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['pagina'],Core::$localStrings['%ITEM% inserita'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listProd');				
		
	break;
		
	case 'updateItem':
		//Core::setDebugMode(1);
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); 	}
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }

		$App->templateItem = new stdClass;
		$App->itemOld = new stdClass;		

		// preleva dati vecchio
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		// se cambia parent aggiorna l'ordering
		if ($_POST['parent'] != $App->itemOld->parent) {
			$_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['item'],'ordering','parent = '.intval($_POST['parent'])) + 1;  
		} 	

		//imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Core::$localStrings['user']]);
		
		// preleva il filename dal form
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);	   	
		ToolsUpload::getFilenameFromForm($App->id);	   			
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		}
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
		$tempFilename = ToolsUpload::getTempFilename();
		$uploadFilename = $_POST['filename'];
		
		// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
		if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;
		if ($_POST['org_filename'] == '' && $App->itemOld->org_filename != '') $_POST['org_filename'] = $App->itemOld->org_filename; 	
			
		if (isset($_POST['deleteFilename']) && $_POST['deleteFilename'] == 1) {
			if (file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {			
					@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);	
				}	
			$_POST['filename'] = '';
			$_POST['org_filename'] = ''; 	
		}	   	    	
			
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['item'],Core::$localStrings,array());			
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyProd/'.$App->id);
		}
		
		Sql::updateRawlyPost($App->params->fields['item'],$App->params->tables['item'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		// sistema i parent se ne è stata selezionato uno diverso
		if ($_POST['parent'] != $_POST['bk_parent']) $Module->manageParentField();	
		
		if ($uploadFilename != '') {
			move_uploaded_file($tempFilename,$App->params->uploadPaths['item'].$uploadFilename) or die('Errore caricamento file');   			
			// cancella l'immagine vecchia
			if (file_exists($App->params->uploadPaths['item'].$App->itemOld->filename)) {			
				@unlink($App->params->uploadPaths['item'].$App->itemOld->filename);			
			}	   			
		}

		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['pagina'],Core::$localStrings['%ITEM% modificata'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyItem/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}	

	break;
	case 'modifySeoItem':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }			
		$App->pageSubTitle = Core::$localStrings['modifica'].' '.Core::$localStrings['Tag SEO'].' '.Core::$localStrings['voce'];
		$App->viewMethod = 'formSeoMod';
	break;

	case 'updateSeoItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		if (!$_POST) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		$fields = array();
		$fieldsVal = array();
		foreach($globalSettings['languages'] AS $lang) {
			$fields[] = 'meta_title_'.$lang;	
			$fieldsVal[] = (isset($_POST['meta_title_'.$lang]) ? $_POST['meta_title_'.$lang] : '');									
			$fields[] = 'meta_description_'.$lang;
			$fieldsVal[] = (isset($_POST['meta_description_'.$lang]) ? $_POST['meta_description_'.$lang] : '');					
			$fields[] = 'meta_keyword_'.$lang;
			$fieldsVal[] = (isset($_POST['meta_keyword_'.$lang]) ? $_POST['meta_keyword_'.$lang] : '');					
			$fields[] = 'title_seo_'.$lang;
			$fieldsVal[] = (isset($_POST['title_seo_'.$lang]) ? $_POST['title_seo_'.$lang] : '');
		}					
		$fieldsVal[] = $App->id;
		Sql::initQuery($App->params->tables['item'],$fields,$fieldsVal,'id = ?','');
		Sql::updateRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Core::$localStrings['Tag SEO'],Core::$localStrings['%ITEM% modificati'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifySeoItem/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
		}
	break;

	
	case 'pageItem':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listItem');
	break;


	case 'listItem':
	default;
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
					$field = 'title_'.Core::$localStrings['user'];	
					$value->title = $value->$field;
					$arr[] = $value;
				}
			}
			$App->items = $arr;
			
		} else {			
			$App->renderSub = 0;
			Pages::$optOrdering = 'p.ordering '.$App->params->ordersType['item'];
			$App->items = Pages::getObjFromPages();
		}
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = Core::$localStrings['mostra da %START% a %END% di %ITEM% elementi'];
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/',Core::$localStrings['voci'],Core::$localStrings['lista %ITEM%']);
		$App->viewMethod = 'list';		
	break;


	case 'ajaxLoadTemplateDataItem':
		$template = $Module->getTemplatePredefinito(0);		
		if (isset($template->id) && (int)$template->id > 0) {	
			include_once(PATH.$App->pathApplications.Core::$request->action."/templates/".$App->templateUser."/formTemplatesData.tpl.php");
			}
		$renderTpl = false;
		die();		
	break;
	
	case 'ajaxReloadTemplateDataItem':
		if ($App->id > 0) {
			$template = $Module->getTemplatePredefinito($App->id);
			if (isset($template->id) && (int)$template->id > 0) {	
				include_once(PATH.$App->pathApplications.Core::$request->action."/templates/".$App->templateUser."/formTemplatesData.tpl.php");
				}
			}
		$renderTpl = false;
		die();	
	break;
	}

switch((string)$App->viewMethod) {
	case 'form':
		$App->templateApp = 'formPage.html';
		$App->defaultJavascript = "var moduleName = '".Core::$request->action."';";
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formPage.js" type="text/javascript"></script>';

	break;

	case 'formSeoMod':
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['item'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();		
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['item']);
		$App->templateApp = 'formSeoPage.html';
		$App->methodForm = 'updateSeoItem';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formSeoPage.js"></script>';
	break;

	case 'list':				
		$App->templateApp = 'listPages.html';
		$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.css" rel="stylesheet">';
		$App->css[] = '<link href="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/css/listPages.css" rel="stylesheet">';		
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.cookie/jquery.cookie.js" type="text/javascript"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.min.js" type="text/javascript"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listPages.js" type="text/javascript"></script>';		
	default:
	break;

}
?>
