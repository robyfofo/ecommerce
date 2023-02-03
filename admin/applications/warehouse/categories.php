<?php
/**
 * Framework Siti HTML-PHP-MySQL
 * PHP Version 7
 * @author Roberto Mantovani (<me@robertomantovani.vr.it>
 * @copyright 2009 Roberto Mantovani
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * admin/warehouse/subcategories.php v.1.0.0. 24/02/2021
*/

if (isset($_POST['itemsforpage']) && isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) && $_MY_SESSION_VARS[$App->sessionName]['ifp'] != $_POST['itemsforpage']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'ifp',$_POST['itemsforpage']);
if (isset($_POST['searchFromTable']) && isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != $_POST['searchFromTable']) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'srcTab',$_POST['searchFromTable']);

if (Core::$request->method == 'listCate' && $App->id > 0) $_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'id_cat',$App->id);

// GESTIONE TAG
$App->tags = new stdClass;	
Sql::initQuery($App->params->tables['tags'],array('*'),array(),'');
Sql::setOptions(array('fieldTokeyObj'=>'id'));
Sql::setOrder('ordering ASC');
$obj = Sql::getRecords();
if (Core::$resultOp->error > 0) {echo Core::$resultOp->message; die;}
// sistemo i dati
$arr = array();
if (is_array($obj) && is_array($obj) && count($obj) > 0) {
	foreach ($obj AS $key=>$value) {	
		$field = 'title_'.Config::$localStrings['user'];	
		$value->title = $value->$field;
		$arr[$key] = $value;
	}
}
$App->tags = $arr;

switch(Core::$request->method) {

	case 'moreOrderingCate':
		if ($App->id > 0) {
			Utilities::increaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['cate'],'orderingType'=>$App->params->ordersType['cate'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Config::$localStrings['categoria']).' '.Config::$localStrings['spostata']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;
	case 'lessOrderingCate':
		if ($App->id > 0) {
			Utilities::decreaseFieldOrdering($App->id,Config::$localStrings,array('table'=>$App->params->tables['cate'],'orderingType'=>$App->params->ordersType['cate'],'parent'=>1,'parentField'=>'parent','label'=>ucfirst(Config::$localStrings['categoria']).' '.Config::$localStrings['spostata']));
			Core::$resultOp->error = Core::$resultOp->type;
			$_SESSION['message'] = Core::$resultOp->error.'|'.Core::$resultOp->message;
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		} else {
			ToolsStrings::redirect(URL_SITE.'error/404');
		}
	break;

	case 'activeCate':
	case 'disactiveCate':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) {	ToolsStrings::redirect(URL_SITE.'error/404'); }	
		Sql::manageFieldActive(substr(Core::$request->method,0,-4),$App->params->tables['cate'],$App->id,array('label'=>Config::$localStrings['categoria'],'attivata'=>Config::$localStrings['attivata'],'disattivata'=>Config::$localStrings['disattivata']));
		$_SESSION['message'] = '0|'.Core::$resultOp->message;
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
	break;
	
	case 'deleteCate':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) {	ToolsStrings::redirect(URL_SITE.'error/404'); }	
			
		Sql::initQuery($App->params->tables['cate'],array('id'),array($App->id),'parent = ?');
		$count = Sql::countRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		if ($count > 0) {
			$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['categorie'],Config::$localStrings['Ci sono ancora %ITEM% associate!']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		}
				
		// controlla se ha prodotti associati
		Sql::initQuery($App->params->tables['prod'],array('id'),array($App->id),'categories_id = ?');
		$count = Sql::countRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		if ($count > 0) {
			$_SESSION['message'] = '2|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['prodotti'],Config::$localStrings['Ci sono ancora %ITEM% associati!']));
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		}
		
		// prendo i vecchi dati
		$App->itemOld = new stdClass;
		Sql::initQuery($App->params->tables['cate'],array('filename'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }
		
		// cancello il record
		Sql::initQuery($App->params->tables['cate'],array('id'),array($App->id),'id = ?');
		Sql::deleteRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE.'error/db'); }

		// cancello il file associato
		if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['cate'].$App->itemOld->filename)) {
			@unlink($App->params->uploadPaths['cate'].$App->itemOld->filename);			
		}	
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['%ITEM% cancellata'])).'!';	
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
	break;
	
	case 'newCate':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }		
		$App->item = new stdClass;		
		$App->item->active = 1;
		$App->item->ordering = 0;
		$App->itemTags = array();
		// select per parent
		$App->categories = new stdClass();	
		Subcategories::$optLevelString = '-->';	
		$App->categories = Subcategories::getObjFromSubCategories();
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['inserisci %ITEM%']);
		$App->methodForm = 'insertCate';
		$App->viewMethod = 'form';
	break;
		
	case 'insertCate':
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }
		
		//Core::setDebugMode(1);
		//ToolsStrings::dump($_POST);

		// gestione automatica dell'ordering de in input = 0
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['cate'],'ordering','parent = '.intval($_POST['parent'])) + 1;
		
		// imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);
		
		// set seo tag default
		foreach ($globalSettings['languages'] AS $lang){
			$_POST['title_seo_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
			$_POST['meta_title_'.$lang] = SanitizeStrings::cleanTitleUrl($_POST['title_'.$lang]);
		}	

		// tagsId 		
		if (isset($_POST['id_tags']) && is_array($_POST['id_tags'])) {
			$_POST['id_tags'] = implode(',',$_POST['id_tags']);
		} else {
			$_POST['id_tags'] = '';
		}

		// preleva il filename dal form  
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);
		ToolsUpload::getFilenameFromForm();
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newCate');
		}
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename();
		
		

		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['cate'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/newCate');
		}

		//ToolsStrings::dump($_POST);

		Sql::insertRawlyPost($App->params->fields['cate'],$App->params->tables['cate']);
		if (Core::$resultOp->error > 0) { die('error insert db'); ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }

		//die();
		
		$App->id = Sql::getLastInsertedIdVar(); /* preleva l'id della pagina */	 
		// sposto il file
		if ($_POST['filename'] != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['cate'].$_POST['filename']) or die('Errore caricamento file');
		}	
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['%ITEM% inserita'])).'!';
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
	break;

	case 'modifyCate':	
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;	
		// select per parent
		$App->subCategories = new stdClass();		
		Subcategories::$optHideId = 1;
		Subcategories::$optHideSons = 1;
		Subcategories::$optRifId = 'id';
		Subcategories::$optRifIdValue = $App->id;
		Subcategories::$optLevelString = '-->';
		$App->categories = Subcategories::getObjFromSubCategories();
		
		Sql::initQuery($App->params->tables['cate'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }
		
		$App->itemTags = array();
		if ($App->item->id_tags != '') $App->itemTags = explode(',',$App->item->id_tags);	
				
		if (Core::$resultOp->error == 1) Utilities::setItemDataObjWithPost($App->item,$App->params->fields['cate']);			
		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['modifica %ITEM%']).'!';
		$App->methodForm = 'updateCate';
		$App->viewMethod = 'form';
	break;
	
	case 'updateCate':
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }
			
		// preleva dati vecchio
		Sql::initQuery($App->params->tables['cate'],array('*'),array($App->id),'id = ?');
		$App->itemOld = Sql::getRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
			
		// gestione automatica dell'ordering de in input = 0
		if (!isset($_POST['ordering']) || (isset($_POST['ordering']) && $_POST['ordering'] == 0)) $_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['cate'],'ordering','parent = '.intval($_POST['parent'])) + 1;
		// se cambia parent aggiorna l'ordering
		if ($_POST['parent'] != $App->itemOld->parent) {
			$_POST['ordering'] = Sql::getMaxValueOfField($App->params->tables['cate'],'ordering','parent = '.intval($_POST['parent'])) + 1;  
		} 	

		// imposta alias
		if ($_POST['alias'] == '') $_POST['alias'] = $Module->getAlias($App->id,$_POST['alias'],$_POST['title_'.Config::$localStrings['user']]);	
			
		// tagsId 			
		if (isset($_POST['id_tags']) && is_array($_POST['id_tags'])) {
			$_POST['id_tags'] = implode(',',$_POST['id_tags']);
		} else {
			$_POST['id_tags'] = '';
		}	
						
		//preleva il filename dal form
		ToolsUpload::setFilenameFormat($globalSettings['image type available']);	
		ToolsUpload::getFilenameFromForm();
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyCate/'.$App->id);
		}
		$_POST['filename'] = ToolsUpload::getFilenameMd5();
		$_POST['org_filename'] = ToolsUpload::getOrgFilename(); 		   		   	
		$uploadFilename = $_POST['filename'];
		// imposta il nomefile precedente se non si è caricata un file (serve per far passare il controllo campo file presente)
		if ($_POST['filename'] == '' && $App->itemOld->filename != '') $_POST['filename'] = $App->itemOld->filename;
		if ($_POST['org_filename'] == '' && $App->itemOld->org_filename != '') $_POST['org_filename'] = $App->itemOld->org_filename;	 
		// opzione cancella immagine
		if (isset($_POST['deleteFilename']) && $_POST['deleteFilename'] == 1) {
			if (file_exists($App->params->uploadPaths['cate'].$App->itemOld->filename)) {			
				@unlink($App->params->uploadPaths['cate'].$App->itemOld->filename);	
			}	
			$_POST['filename'] = '';
			$_POST['org_filename'] = ''; 	
		}
			
		// parsa i post in base ai campi
		Form::parsePostByFields($App->params->fields['cate'],Config::$localStrings,array());
		if (Core::$resultOp->error > 0) { 
			$_SESSION['message'] = '1|'.implode('<br>', Core::$resultOp->messages);
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyCate/'.$App->id);
		}

		Sql::updateRawlyPost($App->params->fields['cate'],$App->params->tables['cate'],'id',$App->id);
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		if ($uploadFilename != '') {
			move_uploaded_file(ToolsUpload::getTempFilename(),$App->params->uploadPaths['cate'].$uploadFilename) or die('Errore caricamento file');   			
			// cancella l'immagine vecchia
			if (isset($App->itemOld->filename) && file_exists($App->params->uploadPaths['cate'].$App->itemOld->filename)) {			
				@unlink($App->params->uploadPaths['cate'].$App->itemOld->filename);			
			}
		}
			
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['categoria'],Config::$localStrings['%ITEM% modificata'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifyCate/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		}										
	break;
	
	case 'modifySeoCate':	
		
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		$App->item = new stdClass;
		Sql::initQuery($App->params->tables['cate'],array('*'),array($App->id),'id = ?');
		$App->item = Sql::getRecord();
		if (!isset($App->item->id) || (isset($App->item->id) && $App->item->id < 1)) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/404'); }			
		$App->pageSubTitle = Config::$localStrings['modifica'].' '.Config::$localStrings['Tag SEO'].' '.Config::$localStrings['categoria'];
		$App->methodForm = 'updateSeoCate';
		$App->viewMethod = 'formSeo';
	break;

	case 'updateSeoCate':
		if ($App->params->moduleAccessWrite == 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/nopm'); }
		if ($App->id == 0) { ToolsStrings::redirect(URL_SITE.'error/404'); }	
		if (!$_POST) { ToolsStrings::redirect(URL_SITE.'error/404'); }

		//Core::setDebugMode(1);
		//ToolsStrings::dump($_POST);die();

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
		Sql::initQuery($App->params->tables['cate'],$fields,$fieldsVal,'id = ?','');
		Sql::updateRecord();
		if (Core::$resultOp->error > 0) { ToolsStrings::redirect(URL_SITE_ADMIN.'error/db'); }
		
		$_SESSION['message'] = '0|'.ucfirst(preg_replace('/%ITEM%/',Config::$localStrings['Tag SEO'],Config::$localStrings['%ITEM% modificati'])).'!';
		if (isset($_POST['applyForm']) && $_POST['applyForm'] == 'apply') {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/modifySeoCate/'.$App->id);
		} else {
			ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
		}
	break;

	case 'pageCate':
		$_MY_SESSION_VARS = $my_session->addSessionsModuleSingleVar($_MY_SESSION_VARS,$App->sessionName,'page',$App->id);
		ToolsStrings::redirect(URL_SITE_ADMIN.Core::$request->action.'/listCate');
	break;

	case 'listCate':
	default;	
		Core::setDebugMode(1);
		$App->itemsForPage = (isset($_MY_SESSION_VARS[$App->sessionName]['ifp']) ? $_MY_SESSION_VARS[$App->sessionName]['ifp'] : 10);
		$App->page = (isset($_MY_SESSION_VARS[$App->sessionName]['page']) ? $_MY_SESSION_VARS[$App->sessionName]['page'] : 1);				

		Sql::setItemsForPage($App->itemsForPage);	
		Sql::setPage($App->page);		
		Sql::setResultPaged(true);
		
		$App->renderSub = 1;
		
		if (isset($_MY_SESSION_VARS[$App->sessionName]['srcTab']) && $_MY_SESSION_VARS[$App->sessionName]['srcTab'] != '') {
			$qryFields = array('cat.*');
			$qryFields[] = "(SELECT COUNT(ite.id) FROM ".$App->params->tables['prod']." AS ite WHERE ite.categories_id = cat.id) AS items";	
			$qryFieldsValues = array();
			$qryFieldsValuesClause = array();
			$clause = '';
			$and = '';			
			
			list($sessClause,$qryFieldsValuesClause) = Sql::getClauseVarsFromAppSession($_MY_SESSION_VARS[$App->sessionName]['srcTab'],$App->params->fields['cate'],'');
	
			if (isset($sessClause) && $sessClause != '') $clause .= $and.'('.$sessClause.')';
			if (is_array($qryFieldsValuesClause) && count($qryFieldsValuesClause) > 0) {
				$qryFieldsValues = array_merge($qryFieldsValues,$qryFieldsValuesClause);	
			}
			Sql::initQuery($App->params->tables['cate']." AS cat",$qryFields,$qryFieldsValues,$clause);		
			Sql::setItemsForPage($App->itemsForPage);	
			Sql::setPage($App->page);		
			Sql::setResultPaged(true);
			Sql::setOrder('ordering '.$App->params->ordersType['cate']);
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
			Subcategories::$optOrdering = 'c.ordering '.$App->params->ordersType['cate'];
			$App->items = Subcategories::getObjFromSubCategories();	
		}
				
		$App->pagination = Utilities::getPagination($App->page,Sql::getTotalsItems(),$App->itemsForPage);
		$App->paginationTitle = ucfirst(Config::$localStrings['mostra da %START% a %END% di %ITEM% elementi']);
		$App->paginationTitle = preg_replace('/%START%/',$App->pagination->firstPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%END%/',$App->pagination->lastPartItem,$App->paginationTitle);
		$App->paginationTitle = preg_replace('/%ITEM%/',$App->pagination->itemsTotal,$App->paginationTitle);

		$App->pageSubTitle = preg_replace('/%ITEM%/',Config::$localStrings['categorie'],Config::$localStrings['lista delle %ITEM%']);
		$App->viewMethod = 'list';	
	break;	
}

switch((string)$App->viewMethod) {
	
	case 'formSeo':
		$App->templateApp = 'formSeoCategory.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formSeoCategory.js"></script>';
	break;
	
	case 'form':
		$App->templateApp = 'formCategory.html';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/formCategory.js"></script>';
	break;

	case 'list':
	default:
		$App->templateApp = 'listCategories.html';	
		$App->css[] = '<link href="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.css" rel="stylesheet">';	
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.cookie/jquery.cookie.js" type="text/javascript"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.'templates/'.$App->templateUser.'/plugins/jquery.treegrid/jquery.treegrid.min.js" type="text/javascript"></script>';
		$App->jscript[] = '<script src="'.URL_SITE_ADMIN.$App->pathApplications.Core::$request->action.'/templates/'.$App->templateUser.'/js/listCategories.js"></script>';
	break;	

}	
?>